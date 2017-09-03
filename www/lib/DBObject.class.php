<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 1:48
 * To change this template use File | Settings | File Templates.
 */
class DBObject
{
    public $table;
    public $id;
    protected $_attributes = array();

    public function __construct($table, $id = null, $lang=null)
    {
        $this->table = $table;
        $this->id = $id;
        $this->lang = $lang ? $lang : Lang::$current;
        if ($this->table && $this->id) $this->fetch();
        //TODO: Check what fields have table
        /*$db = Core::getDB();
        $fields = $db->select('show columns from ?',$table);
        while(){

        }*/
    }

    public function set($values,$value=null)
    {
        if (!is_array($values) && $value===null) return false;
        //If 2 arguments then set single value
        if($value!==null){
            $this->_attributes[$values] = $value;
        } else {
            foreach ($values as $key => $value) {
                if ($key=='id') continue;
                $this->_attributes[$key] = $value;
            }
        }
        return true;
    }

    public function get($key)
    {
        if ($key=='id') return $this->id;
        else return $this->_attributes[$key];
    }

    public function getAll()
    {
        $result=$this->_attributes;
        $result['id']=$this->id;
        return $result;
    }

    public function fetch($where=null)
    {
        $result = array();
        if (!$this->table || !($this->id || $where)) return false;
        $db = Core::getDB();
        if(!$where){
            $row = $db->selectRow('SELECT * FROM `' . $this->table . '` WHERE id=?', $this->id);
        } else {
            $row = $db->selectRow('SELECT * FROM `' . $this->table . '` WHERE '.$where);
            if($row['id']){
                $this->id=$row['id'];
            }
        }

        if($this->lang!=Lang::$default){
            foreach($row as $field=>$value){
                $lang_code = array_shift(explode('_',$field));
                if(in_array($lang_code,Lang::$languages)){
                    continue;
                }
                if(Lang::$fields[$this->table] && in_array($field,Lang::$fields[$this->table]) && $row[$this->lang.'_'.$field]){
                    $result[$field]=$row[$this->lang.'_'.$field];
                } else {
                    $result[$field]=$value;
                }
            }
        } else {
            foreach($row as $field=>$value){
                $lang_code = array_shift(explode('_',$field));
                if(in_array($lang_code,Lang::$languages)){
                    continue;
                }
                $result[$field]=$value;
            }
        }

        if (!$row['id']) {
            $this->id=0;
            return false;
        }
        if ($this->set($result))
            return true;
    }

    public function save()
    {
        if (!$this->table) return false;
        $db = Core::getDB();
        if($this->lang==Lang::$default){
            $values = $this->_attributes;
        } else {
            $values = array();
            foreach($this->_attributes as $field=>$value){
                if(Lang::$fields[$this->table] && in_array($field,Lang::$fields[$this->table])){
                    $values[$this->lang.'_'.$field]=$value;
                } else {
                    $values[$field]=$value;
                }
            }
        }
        unset($values['id']);
        if (!$this->id) {
            $newId = $db->query('INSERT INTO `' . $this->table . '` (?#) VALUES(?a)', array_keys($values), array_values($values));
            if ($newId) {
                $this->id = $newId;
                return true;
            }

        } else {
            return $db->query('UPDATE `' . $this->table . '` SET ?a WHERE id=?', $values, $this->id);
        }
    }

    public function delete()
    {
        if (!$this->table || !$this->id) return false;
        $db = Core::getDB();
        $db->query('DELETE FROM `'.$this->table.'` WHERE id=?', $this->id);
        $this->_attributes = array();
        $this->id=null;
    }
}
