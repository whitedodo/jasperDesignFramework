<?php 
/*
 * Created Date: 2018-08-24
 * Subject: audioStreaming
 * FileName: audioStreaming.php
 * Version: 0.1
 * Author: Dodo(rabbit.white at daum dot net)
 * Description:
 * 2018-08-24 / Dodo / 
 */
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta charset="utf-8">
<title>Audio Recorder(Realtime Based WebAudio)</title>

<script src="./js/jquery/1.7/jquery.js"></script>
<script src="./js/blob.js"></script>
<script src="./js/audio/audiodisplay.js"></script>
<script src="./js/audio/recorder.js"></script>
<script src="./js/audio/main.js"></script>
<link rel="stylesheet" type="text/css" href="./css/audio/myAudio.css">

</head>
<body>
<form name="form1" enctype="multipart/form-data" accept-charset="utf-8" 
	  action="upload.php" method="POST">
	<input id="form_audio_filelink" type="hidden" name="form_audio_filelink" value="">
	<input id="form_audio_filename" type="hidden" name="form_audio_filename" value="">
	
<table class="tg_myAudio">
	<tr>
		<td>
    		<div id="visualInterfaceAudio">
        		<canvas id="analyser" width="1024" height="300"></canvas>
        		<canvas id="wavedisplay" width="1024" height="300"></canvas>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div id="blob_link"></div>
		</td>
	</tr>
	<tr>
		<td>
    		<a id="record" onclick="toggleRecording('audio', this);">녹음</a>
    		<a href="javascript:saveAudio( 'audio', 'download', null );">저장</a>
    		<a href="javascript:saveAudio( 'audio', 'upload.php', 'POST' )">폼 전송</a>
    		<a href="javascript:convertToBlobNormal('audio')">Blob저장</a>
		</td>
	</tr>
</table>
</form>

</body>
</html>