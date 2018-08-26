<?php
/*
 *  Subject: MultiUpload.php
 *  Created Date: 2018-08-25
 *  Author: Dodo (rabbit.white at daum dot net)
 *  Description:
 */

class MultiUpload{
    
    private $uploadDir;
    
    private $blobfileName;  // blob 파일 이름
    private $blobfileLink;  // blob 링크
    
    public function __construct(){
        $this->uploadDir = null;
    }
    
    public function __destruct(){
        
    }
    
    // 사용자 경로 가져오기
    public function getUploadDir(){
        return $this->uploadDir;
    }
    
    // 사용자 경로 설정하기
    public function setUploadDir($uploadDir){
        $this->uploadDir = $uploadDir;
    }
    
    // 멀티 Blob 지원 - 단, URL 사이트를 벗어나면 사라지는 현상이 있음.
    public function setBlob($fileName, $blobLink){
        $this->blobfileName = $fileName;
        $this->blobfileLink = $blobLink;
    }
    
    // Upload 기능별 구분 - Normal Type(1), Blob(2), Audio-Single(3), 
    public function upload( $type, $id ){
        
        $upload_dir = $this->getUploadDir();
        
        // 유형(Type)
        switch ( $type ){
            
            // Normal Type(일반 유형)
            case 1: 
                $multiUpload = $this->createMultiUpload( $id );    // 재사용성(Re-Use)
                $this->saveAsNormal( $upload_dir, $multiUpload );
                break;
            
            // Blob Type(Blob 유형)
            case 2:
                $multiUpload = $this->createMultiBlob( $this->blobfileName, $this->blobfileLink );  // 서버 업로드
                $this->saveAsBlob( $upload_dir, $multiUpload );
                break;
                
            // Single Type(Audio 유형)
            case 3:
                $fileInfo = $this->saveAsSingleAudio( $upload_dir, $id );    // 객체(Obj) FileObject사용
                break;
                
            case 4:
                $fileInfo = saveAsImageSingle( $upload_dir, $id );
                break;
                
        } // End of Switch
        
    }
    
    
    // 배열 구조 재 생성
    public function createMultiUpload( $valID ) {
        
        $fileInfoArr = array();
        
        for( $i = 0; $i <= count($_FILES[ $valID ][name]); $i++) {
            
            if( $_FILES[ $valID ][size][$i] && !$_FILES[ $valID ][error][$i] ) {
                
                $file_name      = $_FILES[ $valID ][name][$i];
                $file_tmp_name  = $_FILES[ $valID ][tmp_name][$i];
                $file_size      = $_FILES[ $valID ][size][$i];
                $file_ext = $this->getExtension( $file_name );
                $file_error = $_FILES[$valID]["error"][$i];
                
                $create_fileName  = time() . "." . $file_ext;         // 시간함수 형태로 파일명 생성
                
                $fileInfo = new FileInfo();
                $fileInfo->setFileName($file_name);
                $fileInfo->setFileTmpName($file_tmp_name);
                $fileInfo->setFileSize($file_size);
                $fileInfo->setFileExt($file_ext);
                $fileInfo->setFileError($file_error);
                $fileInfo->setCreateName($create_fileName);
                
                array_push( $fileInfoArr , $fileInfo );
                
            } // end of if
            
        } // end of for
        
        return $fileInfoArr;
    }
    
    // 배열 재구조 생성하기
    public function createMultiBlob( $blobFileName, $blobFileLink ){
        
        $fileInfoArr = array();
        $i = 0;
        
        foreach ( $blobFileName as $val){
            
            $file_name = $val;
            $file_ext = $this->getExtension( $file_name );
            $link = $blobFileLink[$i];
            $create_fileName  = time() . "." . $file_ext;         // 시간함수 형태로 파일명 생성
            
            $node = new BlobInfo();
            $node->setFileName( $file_name );
            $node->setFileLink( $link );
            $node->setCreateFileName( $create_fileName );
            
            array_push($fileInfoArr, $node);
            
            $i++;
        }
        
        return $fileInfoArr;
    }
    
    
    // 일반 저장 구현
    public function saveAsNormal( $savePath, $fileInfo ){
        
        $rootDir = $_SERVER["DOCUMENT_ROOT"];
        $uploadDir = $rootDir . $savePath;
        
        foreach ($fileInfo as $val){
        
            if (!file_exists( $uploadDir . $val->getFileName() )) {
                move_uploaded_file($val->getFileTmpName(), $uploadDir . $val->getFileName() ); // 원본 파일명으로 저장
            } else {
                move_uploaded_file($val->getFileTmpName(), $uploadDir . $val->getCreateName()); // 시간함수 파일명으로 저장
            } // end of if
            
        } // end of foreach
        
    }
    
    // Blob 저장 구현
    public function saveAsBlob( $savePath, $blobInfo ){
        
        $rootDir = $_SERVER["DOCUMENT_ROOT"];
        $uploadDir = $rootDir . $savePath;
        
        foreach ( $blobInfo as $val ){
           
            $fileName = $val->getFileName();    // 실 파일명
            $fileLink = $val->getLinkName();    // 시간함수 파일명
            $createFileName = $val->getCreateFileName();
            
            echo $fileLink;
            
            file_put_contents( $uploadDir . $createFileName, $fileLink);    // 저장 처리
        }
        
    }
    
    public function saveAsBlobSingleAudio( $savePath, $vID ){
        
        $rootDir = $_SERVER["DOCUMENT_ROOT"];
        $uploadDir = $rootDir . $savePath;
        
        $fileInfo = new FileInfo();
        
        $p_audio = $_POST[$vID];
        $p_audio_name = $_FILES[$vID]['name'];
        $p_audio_type = $_FILES[$vID]['type'];
        $p_audio_temp = $_FILES[$vID]['tmp_name'];
        $p_audio_size = $_FILES[$vID]['size'];
        $p_audio_error = $_FILES[$vID]["error"];
        
        $fileInfo->setFileName( $p_audio_name );
        $fileInfo->setFileExt( $p_audio_type );
        $fileInfo->setFileSize( $p_audio_size );
        $fileInfo->setFileTmpName( $p_audio_temp );
        $fileInfo->setFileError( $p_audio_error );
        
        $randomName = $this->getRandomName();
        
        //Conditionals
        if ($p_audio_type === "audio/wav" ||
            $p_audio_type === "audio/wave" ||
            $p_audio_type === "audio/x-wave" ||
            $p_audio_type === "audio/vnd.wave")
        {
            $p_audio_type = ".wav";
            move_uploaded_file($p_audio_temp, "$uploadDir". $randomName . $p_audio_type);
        }
        
        if ($p_audio_type === "audio/wav" ||
            $p_audio_type === "audio/wave" ||
            $p_audio_type === "audio/x-wave" ||
            $p_audio_type === "audio/vnd.wave"){
            $p_audio_type = ".wav";
            
            move_uploaded_file($p_audio_temp, "$uploadDir". $randomName . $p_audio_type);
        }
        
        return $fileInfo;
        
    }
    
    // Image Single 저장하기
    // file_put_contents 옵션을 사용할 수 있어야 함.
    public function saveAsImageSingle( $savePath, $vID ){
        
        $rootDir = $_SERVER["DOCUMENT_ROOT"];
        $uploadDir = $rootDir . $savePath;
        
        $img = $_POST[$vID];
        
        $img = str_replace( 'data:image/png;base64,', '', $img );
        $img = str_replace( ' ', '+', $img );
        $data = base64_decode( $img );
        $file = $uploadDir . mktime() . ".png";
        
        $success = file_put_contents( $file, $data );
        
        //properties of the uploaded file
        $file_name = $_FILES[$vID]["name"];
        $file_type = $_FILES[$vID]["type"];
        $file_size = $_FILES[$vID]["size"];
        $file_temp = $_FILES[$vID]["temp_name"];
        $file_error = $_FILES[$vID]["error"];
        
        $fileInfo = new FileInfo();
        $fileInfo->setFileName($file_name);
        $fileInfo->setFileExt($file_type);
        $fileInfo->setFileSize($file_size);
        $fileInfo->setFileTmpName($file_temp);
        $fileInfo->setFileError($file_error);
        
        //print $success ? $file : 'Unable to save the file.';
        return $fileInfo;
    }
    
    // 랜덤 이름 생성
    public function getRandomName(){
        
        $id1 = mt_rand(0, 9999999);
        $id2 = mt_rand(0, 9999999);
        $id3 = mt_rand(0, 9999999);
        $id4 = mt_rand(0, 9999999);
        $id5 = mt_rand(0, 9999999);
        $id6 = mt_rand(0, 9999999);
        $id7 = mt_rand(0, 9999999);
        $id8 = mt_rand(0, 9999999);
        $id9 = mt_rand(0, 9999999);
        $id10 = mt_rand(0, 9999999);
        $id11 = mt_rand(0, 9999999);
        
        $randomName = $id1.$id2.$id3.$id4.$id5.$id6.$id7.$id8.$id9.$id10.$id11;
        
        return $randomName;
    }
    
    // 확장자 추출
    public function getExtension($file_name){
        
        $tmp = strpos(strrev( $file_name ), '.');
        $temp = strlen( $file_name ) - $tmp;
        
        if ($tmp) {
            $strName = substr($file_name, 0, $temp-1);
            $strExt = substr($file_name, strlen($strName) + 1, strlen($file_name));
            
            // 확장자 미허용 처리(이전 방식)
            if (preg_match('/htm|php|inc|phtm|shtm|cgi|dot|asp|ztx|pl/i', $strExt)){
                $strExt .= '.txt';
            } else {
                $strName = $file_name;
            }
            
            $strExt = strtolower($strExt);
            
            return $strExt;
        }
        
    }
    
}

?>