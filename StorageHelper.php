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

}