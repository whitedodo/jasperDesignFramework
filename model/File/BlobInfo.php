<?php
/*
 *  Subject: BlobInfo.php
 *  Created Date: 2018-08-25
 *  Author: Dodo (rabbit.white at daum dot net)
 *  Description:
 */

class BlobInfo{
    
    private $fileName;
    private $fileLink;
    private $createFileName;
    
    // construct
    public function __construct() {
        $this->fileName = null;
        $this->fileLink = null;
        $this->createFileName = null;
    }
    
    // destruct
    public function __destruct(){
        
    }
    
    // getter
    public function getFileName(){
        return $this->fileName;
    }
    
    public function getLinkName(){
        return $this->fileLink;
    }
    
    public function getCreateFileName(){
        return $this->createFileName;
    }
    
    // setter
    public function setFileName($fileName){
        $this->fileName = $fileName;
    }
    
    public function setFileLink($fileLink){
        $this->fileLink = $fileLink;
    }
    
    public function setCreateFileName($fileName){
        $this->createFileName = $fileName;
    }
    
}

?>