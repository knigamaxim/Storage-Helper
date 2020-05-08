<?php



class StorageHelper
{

    private $fileName;

    public function saveUploadedFile($file)
    {
        $newname = $this->preparePath($file); 
        $oldname = $this->getStoragePath().$file;
        if ($newname && $this->saveAs($oldname, $newname)) {
            return $this->fileName;
        }
    }


    protected function getFilename($file)
    {
       
        $hash = sha1_file($this->getStoragePath().$file);

        $name = substr_replace($hash, '/', 2, 0);
        $name = substr_replace($name, '/', 5, 0); 

        return $name . '.' . $this->getFileExt($file);

    }

}