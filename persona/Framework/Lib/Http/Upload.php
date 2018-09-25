<?php
namespace Framework\Lib\Http;

use Framework\Lib\Interfaces\UploadedFileInterface;


class Upload 
{
    protected $path;
    protected $format;
    public function __construct($path = null){
        if($path){
            $this->path = $path;
        }
    }
    public function upload(UploadedFileInterface $file):string{
        $targetpath = $this->addSuffix($this->path.DIRECTORY_SEPARATOR.$file->getClientFilename());
        $file->moveTo($targetpath);
        return pathinfo($targetpath)['basename'];
    }

    private function addSuffix(string $path):string{
        if(file_exists($path)){
            $info = pathinfo($path);
            $path = $info['dirname'].DIRECTORY_SEPARATOR.$info['filename'].'_copy.'.$info['extension'];
            return $this->addSuffix($path);
        }
        return $path;
    }
}
