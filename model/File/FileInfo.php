<?php
/*
 *  Subject: Fileinfo.php
 *  Created Date: 2018-08-25
 *  Author: Dodo (rabbit.white at daum dot net)
 *  Description:
 */

class FileInfo{
    
    private $fileName;
    private $fileTmpName;
    private $fileSize;
    private $fileExt;
    private $fileError;
    private $createName;
    
    // construct
    public function __construct(){
        $this->fileName = null;
        $this->fileTmpName = null;
        $this->fileSize = null;
        $this->fileExt = null;
        $this->fileError = null;
        $this->createName = null;
    }
    
    // destruct
    public function __destruct(){
        
    }
    
    // getter
    public function getFileName(){
        return $this->fileName;
    }
    
    public function getFileTmpName(){
        return $this->fileTmpName;
    }
    
    public function getFileSize(){
        return $this->fileSize;
    }
    
    public function getFileExt(){
        return $this->fileExt;
    }
    
    public function getFileError(){
        return $this->fileError;
    }
    
    public function getCreateName(){
        return $this->createName;
    }
    
    // Setter
    public function setFileName($fileName){
        $this->fileName = $fileName;
    }
    
    public function setFileTmpName($fileTmpName){
        $this->fileTmpName = $fileTmpName;
    }
    
    public function setFileSize($fileSize){
        $this->fileSize = $fileSize;
    }
    
    public function setFileExt($fileExt){
        $this->fileExt = $fileExt;
    }
    
    public function setFileError($error){
        $this->fileError = $error;
    }
    
    public function setCreateName($createName){
        $this->createName = $createName;
    }
    
}

?>