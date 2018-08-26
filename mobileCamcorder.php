<?php
/*
 * Created Date: 2018-08-24
 * Subject: Mobile Streaming Camcorder(Apple, Android, Web)
 * FileName: mobileCamcorder.php
 * Version: 0.1
 * Author: Dodo(rabbit.white at daum dot net)
 * Description:
 * 2018-08-24 / Dodo / http://php.net/manual/kr/features.file-upload.multiple.php 
 */
?>

<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta name="viewport" content="width=320; user-scalable=no" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <title>Camera(Camera, Camcorder)</title>
	<script src="./js/jquery/1.7/jquery.js"></script>
	<script src="./js/jquery/plugin/form/jquery.form.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/drawing/mystyle.css">
    
	<script>
        $(document).ready(function(){
            if (!('url' in window) && ('webkitURL' in window)) {
                window.URL = window.webkitURL;
            }
         
            $('#camcorder').change(function(e){
                $('#mov').attr('src', URL.createObjectURL(e.target.files[0]));
                // iOS, Safari에서는 autoplay가 동작하지 않을 때 알림 메시지 출력
                alert('동영상 재생 버튼을 누르시오');
            });
        });
	</script>
	
</head>
<body>

<!-- Mobile Streaming -->
<form method="post" enctype="multipart/form-data" action="resultOfCamera.php">
<table class="tg_general" style="width: 100%;">
	<tr>
		<th colspan="2" style="text-align: left">
			<h2>Web Design And Application (W3C/HTML5) - inputBox</h2>
		</th>
	</tr>
	<tr>
		<td>
			<h5>Camera</h5>
		</td>
		<td>
			<h5>Camcorder</h5>
		</td>
	</tr>
	<tr>
		<td rowspan="2">
            <input type="file" id="camera" name="camera[]" capture="camera" accept="image/*" multiple />
		</td>
		<!--  이동형 운영체제(Mobile Operation System) - Area to play the movie -->
		<td>
            <video id="mov"></video>
		</td>
	</tr>
	<tr>
		<td>
            <input type="file" id="camcorder" name="camcorder[]" capture="camcorder" accept="video/*"  multiple />
		</td>
	</tr>
	<tr>
		<td colspan="2">
	            <input type="submit" value="전송" />
		</td>
	</tr>
</table>
</form>

<!-- 프로그래스바(Progress Bar) -->
<div class="progress">
    <div class="bar"></div >
    <div class="percent">0%</div >
</div>

<div id="status"></div>

<script>
    (function() {
        
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');
       
    $('form').ajaxForm({
        beforeSend: function() {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
    		//console.log(percentVal, position, total);
        },
        success: function() {
            var percentVal = '100%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
    	complete: function(xhr) {
    		status.html(xhr.responseText);
    	}
    }); 
    
    })();       
</script>

<!-- Mobile Streaming -->

</body>
</html>