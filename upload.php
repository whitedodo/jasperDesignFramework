<?php
/*
 * Created Date: 2018-08-24
 * Subject: upload(audioStreaming)
 * FileName: upload.php
 * Version: 0.1
 * Author: Dodo(rabbit.white at daum dot net)
 * Description:
 * 2018-08-24 / Dodo / http://php.net/manual/kr/features.file-upload.multiple.php
 */

require_once './controller/FileModule/FileController.php';

$multiUpload = new MultiUpload();

// Audio Streaming
$multiUpload->setUploadDir("/phpStreaming/upload/");
$multiUpload->upload(3, 'data');

?>