<?php
/*
 *  Subject: resultOfDrawing.php
 *  Created Date: 2018-08-25
 *  Author: Dodo (rabbit.white at daum dot net)
 *  Description:
 */

    $upload_dir = "./upload/";
    $img = $_POST["drawing"];

    // echo "<script>alert('$img');</script>";
    
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    
    $data = base64_decode($img);
    
    $file = $upload_dir . mktime() . ".png";
    $success = file_put_contents($file, $data);
    
    print $success ? $file : 'Unable to save the file.';

?>
