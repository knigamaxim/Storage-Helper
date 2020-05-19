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

    protected function getFileExt($file)
    {
        return strtolower( (new \SplFileInfo($file))->getExtension() );
    }

    protected function getStoragePath()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
    }


    public function getFile(string $filename)
    {
        return  $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . '/uploads/' . 
                $filename;
    }

    public static function normalizePath($path, $ds = DIRECTORY_SEPARATOR)
    {
        $path = rtrim(strtr($path, '/\\', $ds . $ds), $ds);
        if (strpos($ds . $path, "{$ds}.") === false && strpos($path, "{$ds}{$ds}") === false) {
            return $path;
        }
        if (strpos($path, "{$ds}{$ds}") === 0 && $ds == '\\') {
            $parts = [$ds];
        } else {
            $parts = [];
        }
        foreach (explode($ds, $path) as $part) {
            if ($part === '..' && !empty($parts) && end($parts) !== '..') {
                array_pop($parts);
            } elseif ($part === '.' || $part === '' && !empty($parts)) {
                continue;
            } else {
                $parts[] = $part;
            }
        }
        $path = implode($ds, $parts);
        return $path === '' ? '.' : $path;
    }
 

    public static function createDirectory($path, $mode = 0775, $recursive = true)
    {
        if (is_dir($path)) {
            return true;
        }
        $parentDir = dirname($path);

        if ($recursive && !is_dir($parentDir) && $parentDir !== $path) {
            static::createDirectory($parentDir, $mode, true);
        }
       if (!mkdir($path, $mode)) {
                return false;
        }
        return chmod($path, $mode);
    }    


    public function saveAs($oldname, $newname)
    {
    	if(rename($oldname, $newname)) return true;
    	return false;
    }


    protected function preparePath($file)
    {
        $this->fileName = $this->getFileName($file);  
        //     0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg
        
        $path = $this->getStoragePath() . $this->fileName;  
        //     /var/www/project/frontend/web/uploads/0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg
        
        $path = self::normalizePath($path);
        if (self::createDirectory(dirname($path))) {
            return $path;
        }
    }



}