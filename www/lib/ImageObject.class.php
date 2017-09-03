<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 25.12.11
 * Time: 16:03
 * To change this template use File | Settings | File Templates.
 */
include_once 'phpthumb/ThumbLib.inc.php';
abstract class ImageObject
{
    protected $table;
    protected $flagField;
    protected $id;
    protected $directory;
    protected $type = 'jpg';
    protected $copies = array();

    public function __construct($id)
    {
        $db = Core::getDB();
        if ($db->query('SELECT id FROM `' . $this->table . '` WHERE id=?', $id)) {
            $this->id = $id;
        }
    }

    public function  getFullName($suffix = '')
    {
        if ($suffix) $suffix = '_' . $suffix;
        if (file_exists(ROOT . '/media/' . $this->directory . '/' . $this->id . $suffix . '.' . $this->type))
            return ROOT . '/media/' . $this->directory . '/' . $this->id . $suffix . '.' . $this->type;
        else return false;
    }

    public function  getFullURL($suffix = '')
    {
        if ($suffix) $suffix = '_' . $suffix;
        if (file_exists(ROOT . '/media/' . $this->directory . '/' . $this->id . $suffix . '.' . $this->type))
            return HTTP_ROOT . '/media/' . $this->directory . '/' . $this->id . $suffix . '.' . $this->type;
        else return false;
    }

    public function upload($varName)
    {
        if (!$this->id || !$_FILES[$varName]['name']) return false;
        move_uploaded_file($_FILES[$varName]['tmp_name'], ROOT . '/media/' . $this->directory . '/' . $this->id . '.' . $this->type);
        $db = Core::getDB();

        $db->query('UPDATE `' . $this->table . '` SET `' . $this->flagField . '`=1 WHERE `id`=?', $this->id);
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
            $thumb->save(ROOT . '/media/' . $this->directory . '/' . $this->id . '_' . $suffix . '.' . $this->type, $this->type);
        }
    }

    public function delete()
    {
        if ($filename = $this->getFullName()) unlink($filename);
        foreach ($this->copies as $suffix => $value) {
            if ($filename = $this->getFullName($suffix)) unlink($filename);
        }
        $db = Core::getDB();
        $db->query('UPDATE `' . $this->table . '` SET `' . $this->flagField . '`=0 WHERE `id`=?', $this->id);

    }

}
