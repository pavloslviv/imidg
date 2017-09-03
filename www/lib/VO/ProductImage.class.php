<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 25.12.11
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */
class ProductImage extends ImageObject
{
    protected $table = 'sr_shop_product';
    protected $flagField = 'image';
    protected $id;
    protected $directory = 'product';
    public $type = false;
    protected $copies = array('small'=>'140x140xS','medium'=>'400x400xS');

    public function __construct($id)
    {
        $db = Core::getDB();
        $row = $db->selectRow('SELECT id,'.$this->flagField.' FROM `' . $this->table . '` WHERE id=?', $id);
        if ($row['id']) {
            $this->id = $id;
            if($row[$this->flagField]){
                $this->type = $row[$this->flagField];
            }
        }
    }

    public function  getFullName($suffix = '')
    {
        if(!$this->type) return false;
        if ($suffix) $suffix = '_' . $suffix;
        if (file_exists(ROOT . '/media/' . $this->directory . '/' . $this->id . $suffix . '.' . $this->type))
            return ROOT . '/media/' . $this->directory . '/' . $this->id . $suffix . '.' . $this->type;
        else return false;
    }

    public function  getFullURL($suffix = '')
    {
        if(!$this->type) return false;
        if ($suffix) $suffix = '_' . $suffix;
        if (file_exists(ROOT . '/media/' . $this->directory . '/' . $this->id . $suffix . '.' . $this->type))
            return HTTP_ROOT . '/media/' . $this->directory . '/' . $this->id . $suffix . '.' . $this->type;
        else return false;
    }

    public function upload($varName)
    {
        if (!$this->id || !$_FILES[$varName]['name']) return false;
        $ext = pathinfo($_FILES[$varName]['name'], PATHINFO_EXTENSION);
        $this->type = time().'.'.strtolower($ext);

        move_uploaded_file($_FILES[$varName]['tmp_name'], ROOT . '/media/' . $this->directory . '/' . $this->id . '.' . $this->type);
        $db = Core::getDB();

        $db->query('UPDATE `' . $this->table . '` SET `' . $this->flagField . '`=? WHERE `id`=?', $this->type,$this->id);
        return true;
    }

    public function resize($width, $height)
    {
        $filename = $this->getFullName();
        if (!$filename) return false;
        try {
            $thumb = PhpThumbFactory::create($filename);
            $thumb->adaptiveResize((int)$width, (int)$height);
            $thumb->save($filename, $this->type);
        } catch (Exception $e) {
            echo $e;
        }
    }
    public function safeResize($width, $height)
    {
        $filename = $this->getFullName();
        if (!$filename) return false;
        try {
            $thumb = PhpThumbFactory::create($filename);
            $thumb->resize((int)$width, (int)$height);
            $thumb->save($filename, $this->type);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function makeThumbs()
    {
        $filename = $this->getFullName();
        if (!$filename) return false;
        foreach ($this->copies as $suffix => $size) {
            $size = explode('x', $size);
            try {
                $thumb = PhpThumbFactory::create($filename);
            } catch (Exception $e) {
                echo $e;
            }
            if ($size[2]=='S'){
                $thumb->resize($size[0], $size[1]);
            }
            else{
                $thumb->adaptiveResize($size[0], $size[1]);
            }
            $thumb->save(ROOT . '/media/' . $this->directory . '/' . $this->id . '_' . $suffix . '.' . $this->type);
        }
    }

    public function delete()
    {
        if ($filename = $this->getFullName()) unlink($filename);
        foreach ($this->copies as $suffix => $value) {
            if ($filename = $this->getFullName($suffix)) unlink($filename);
        }
        $db = Core::getDB();
        $db->query('UPDATE `' . $this->table . '` SET `' . $this->flagField . '`=NULL WHERE `id`=?', $this->id);

    }

}
