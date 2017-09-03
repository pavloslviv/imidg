<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 1:48
 * To change this template use File | Settings | File Templates.
 */
class DBCollection
{
    public $table;
    public $fields;
    public $data = array();
    public $pagesCount;
    public $currentPage;
    public $lang;

    public function __construct($table, $fields = null,$getFields=false, $lang=null)
    {
        $this->table = $table;
        $this->fields = $fields;
        $this->lang = $lang ? $lang : Lang::$current;
        if (!$this->fields && $getFields) {
            $this->fields=array();
            $db = Core::getDB();
            $fields = $db->select('show columns from ?#', $table);
            foreach ($fields as $field) {
                array_push($this->fields, $field['Field']);
            }
        }
    }

    /*public function set($values)
    {
        if (!is_array($values)) return false;
        foreach ($values as $key => $value) {
            $this->_attributes[$key] = $value;
        }
        return true;
    }

    public function get($key)
    {
        return $this->_attributes[$key];
    }*/

    public function fetch($where = '', $orderBy = null, $limit = null)
    {
        if (!$this->table) return false;
        $db = Core::getDB();
        if($this->fields){
            if (!in_array('id', $this->fields)) array_push($this->fields, 'id');
        }
        $where = $where == '' ? '1=1' : $where;
        $suffix = $orderBy ? ' order by ' . $orderBy : ' ';
        $suffix .= $limit ? ' limit ' . $limit : ' ';
        if($this->fields){
            $data = $db->select('SELECT ?# FROM ' . $this->table . ' WHERE ' . $where . $suffix, $this->_preparedFields());
        } else {
            $data = $db->select('SELECT * FROM ' . $this->table . ' WHERE ' . $where . $suffix);
            if(is_array($data) && count($data)){
                $this->fields = array_keys(array_shift(array_values($data)));
            }
        }
        if (is_array($data) && count($data)) {
            if($this->lang==Lang::$default){
                foreach ($data as $row) {
                    $this->data[$row['id']] = $row;
                }
            } else {
                foreach ($data as $row) {
                    $translatedRow = array();
                    foreach($row as $field=>$value){
                        $lang_code = array_shift(explode('_',$field));
                        if(in_array($lang_code,Lang::$languages)){
                            continue;
                        }
                        if(Lang::$fields[$this->table] && in_array($field,Lang::$fields[$this->table]) && $row[$this->lang.'_'.$field]){
                            $translatedRow[$field]=$row[$this->lang.'_'.$field];
                        } else {
                            $translatedRow[$field]=$value;
                        }
                    }
                    $this->data[$row['id']] = $translatedRow;
                }
            }
        }
        return true;
    }

    public function fetchPage($pageNum, $where = '', $orderBy = null, $itemPerPage = 10)
    {
        if (!$this->table) return false;
        if (!$pageNum) $pageNum = 1;
        $db = Core::getDB();
        if($this->fields){
            if (!in_array('id', $this->fields)) array_push($this->fields, 'id');
        }
        $where = $where == '' ? '1=1' : $where;
        $suffix = $orderBy ? ' order by ' . $orderBy : ' ';
        $totalRows = 0;
        $offset = $itemPerPage * ($pageNum - 1);
        if($this->fields){
            $data = $db->selectPage($totalRows, 'SELECT ?# FROM ' . $this->table . ' WHERE ' . $where . $suffix . ' LIMIT ?d, ?d', $this->_preparedFields(), $offset, $itemPerPage);
        } else {
            $data = $db->select('SELECT * FROM ' . $this->table . ' WHERE ' . $where . $suffix . ' LIMIT ?d, ?d', $offset, $itemPerPage);
            if(count($data)){
                $this->fields = array_keys(array_shift(array_values($data)));
            }
        }
        $this->pagesCount = ceil($totalRows / $itemPerPage);
        $this->currentPage = $pageNum;

        if (is_array($data)) {
            if($this->lang==Lang::$default){
                foreach ($data as $row) {
                    $this->data[$row['id']] = $row;
                }
            } else {
                foreach ($data as $row) {
                    $translatedRow = array();
                    foreach($row as $field=>$value){
                        $lang_code = array_shift(explode('_',$field));
                        if(in_array($lang_code,Lang::$languages)){
                            continue;
                        }
                        if(Lang::$fields[$this->table] && in_array($field,Lang::$fields[$this->table]) && $row[$this->lang.'_'.$field]){
                            $translatedRow[$field]=$row[$this->lang.'_'.$field];
                        } else {
                            $translatedRow[$field]=$value;
                        }
                    }
                    $this->data[$row['id']] = $translatedRow;
                }
            }
        }
        return true;
    }

    private function _preparedFields(){
        $result = array();
        foreach ($this->fields as $field){
            //Add default lang variant in case selected language not filled
            if($this->lang!=Lang::$default){
                $result[]=$field;
            }
            $result[]=Lang::prepareField($field,$this->table,$this->lang);
        }
        return $result;
    }
}
