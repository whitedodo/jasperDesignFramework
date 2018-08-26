<?php
/*
 * Created Date: 2018-08-26
 * Subject: streamingVideo
 * FileName: streamingVideo.php
 * Version: 0.1
 * Author: Dodo(rabbit.white at daum dot net)
 * Description:
 * 2018-08-24 / Dodo / 
 */
 
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<script>

	var context;
	var video;

	// Put event listeners into place
	window.addEventListener("DOMContentLoaded", function() {
    	// Grab elements, create settings, etc.
        var canvas = document.getElementById('canvas');
        context = canvas.getContext('2d');
        video = document.getElementById('video');
        var mediaConfig =  { video: true };
        var errBack = function(e) {
        	console.log('An error has occurred!', e)
    };

		// Put video listeners into place
        if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia(mediaConfig).then(function(stream) {
                video.src = window.URL.createObjectURL(stream);
                video.play();
            });
        }

  	/* Legacy code below! */
 	 else if(navigator.getUserMedia) { // Standard
			navigator.getUserMedia(mediaConfig, function(stream) {
				video.src = stream;
				video.play();
			}, errBack);
		} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
			navigator.webkitGetUserMedia(mediaConfig, function(stream){
				video.src = window.webkitURL.createObjectURL(stream);
				video.play();
			}, errBack);
		} else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
			navigator.mozGetUserMedia(mediaConfig, function(stream){
				video.src = window.URL.createObjectURL(stream);
				video.play();
			}, errBack);
		}

	}, false);

    function captureSnap(path, method){
		uploadEx(path, method);            
    }

	function uploadEx(path, method) {

		context.drawImage(video, 0, 0, 640, 480);
		
        var canvas = document.getElementById("canvas");
        var dataURL = canvas.toDataURL("image/png");
        document.getElementById('drawing').value = dataURL;
        var fd = new FormData(document.forms["form1"]);

        var xhr = new XMLHttpRequest();
        xhr.open(method, path, true);

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                var percentComplete = (e.loaded / e.total) * 100;
                console.log(percentComplete + '% uploaded');
                alert('Succesfully uploaded');
            }
        };

        xhr.onload = function() {};
        
        xhr.send(fd);
    };

    
    function post_to_url(path, params, method) {
    
        method = method || "post"; // Set method to post by default, if not specified.
        // The rest of this code assumes you are not using a library.
    
        // It can be made less wordy if you use one.
        var form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);
    
        for(var key in params) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);
            form.appendChild(hiddenField);
        }
    
        document.body.appendChild(form);
        form.submit();
    
    }
</script>
</head>
<body>
  <h1>Camera Test(Not Streaming Camera)</h1>
  
<div>
<form name="form1" accept-charset="utf-8" action="resultOfDrawing.php" method="POST">

	<input type="hidden" name="drawing" id="drawing">
	<video id="video" width="640" height="480" autoplay></video>
	<a href="javascript:captureSnap('resultOfDrawing.php', 'POST');">Snap Button</a>
	<input type="submit" value="전송" />
	<canvas id="canvas" width="640" height="480"></canvas>
	
</form>
</div>
</body>
</html>