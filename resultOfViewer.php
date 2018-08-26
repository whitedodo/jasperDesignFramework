<?php

/*
 * Created Date: 2018-08-24
 * Subject: resultOfViewer
 * FileName: resultOfViewer.php
 * Version: 0.1
 * Author: Dodo(rabbit.white at daum dot net)
 * Description:
 * 2018-08-24 / Dodo / 
 */

require_once './controller/FileModule/FileController.php';

$multiUpload = new MultiUpload();

$multiUpload->setUploadDir("/phpStreaming/upload/");
$multiUpload->upload(4, 'drawing');

?>
