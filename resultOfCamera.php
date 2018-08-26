<?php
/*
 * Created Date: 2018-08-26
 * Subject: Camera Module (Web)
 * FileName: resultOfCamera.php
 * Version: 0.1
 * Author: Dodo(rabbit.white at daum dot net)
 * Description:

 */

require_once './controller/FileModule/FileController.php';

$multiUpload = new MultiUpload();

$multiUpload->setUploadDir("/phpStreaming/upload/");
$multiUpload->upload(1, 'camera');
$multiUpload->upload(1, 'camcorder');

?>
