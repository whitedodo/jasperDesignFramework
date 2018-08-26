<?php
    $title = "Unknown Drawing";
?>
<!DOCTYPE html>
<html>
<head>
<title>Handwriting(Digital Pad)</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >

<link rel="stylesheet" type="text/css" href="./css/drawing/drawingStyle.css">

<script type="text/javascript">

    var ctx, color = "#000";
    document.addEventListener( "DOMContentLoaded", function(){
    	// setup a new canvas for drawing wait for device init
        setTimeout(function(){
    	   newCanvas();
        }, 1000);
    
    }, false );
    
    // function to setup a new canvas for drawing
    function newCanvas(){
    	//define and resize canvas
        document.getElementById("content").style.height = window.innerHeight - 90;
        var canvas = '<canvas id="canvas" width="'+ (window.innerWidth) +'" height="' + (window.innerHeight - 90)+'"></canvas>';
    	document.getElementById("content").innerHTML = canvas;
        
        // setup canvas
    	ctx=document.getElementById("canvas").getContext("2d");
    	ctx.strokeStyle = color;
    	ctx.lineWidth = 3;	
    	
    	// setup to trigger drawing on mouse or touch
        drawTouch();
        drawPointer();
    	drawMouse();
    }


	// 연필심 키우기
	function scaleCanvas(){
		ctx.lineWidth = ctx.lineWidth + 1;
	}
	
	// 연필심 줄이기
    function reduceCanvas(){
    	ctx.lineWidth = ctx.lineWidth - 1;
    }
    
    // 저장 캔버스
    function saveCanvas(path, params, method){
		uploadEx(path, method);
    }

    // 팔렛트 색상 바꾸기
    function selectColor(el){
        
        for(var i=0; i < document.getElementsByClassName("palette").length;i++){
            document.getElementsByClassName("palette")[i].style.borderColor = "#777";
            document.getElementsByClassName("palette")[i].style.borderStyle = "solid";
        }
        el.style.borderColor = "#fff";
        el.style.borderStyle = "dashed";
        color = window.getComputedStyle(el).backgroundColor;
        ctx.beginPath();
        ctx.strokeStyle = color;
        
    }
    
    // prototype to	start drawing on touch using canvas moveTo and lineTo
    var drawTouch = function() {
    	var start = function(e) {
    		ctx.beginPath();
    		x = e.changedTouches[0].pageX;
    		y = e.changedTouches[0].pageY - 44;
    		ctx.moveTo(x,y);
    	};
    	var move = function(e) {
    		e.preventDefault();
    		x = e.changedTouches[0].pageX;
    		y = e.changedTouches[0].pageY - 44;
    		ctx.lineTo(x,y);
    		ctx.stroke();
    	};
        document.getElementById("canvas").addEventListener("touchstart", start, false);
    	document.getElementById("canvas").addEventListener("touchmove", move, false);
    }; 
        
    // prototype to	start drawing on pointer(microsoft ie) using canvas moveTo and lineTo
    var drawPointer = function() {
    	var start = function(e) {
            e = e.originalEvent;
    		ctx.beginPath();
    		x = e.pageX;
    		y = e.pageY - 44;
    		ctx.moveTo(x,y);
    	};
    	var move = function(e) {
    		e.preventDefault();
            e = e.originalEvent;
    		x = e.pageX;
    		y = e.pageY - 44;
    		ctx.lineTo(x,y);
    		ctx.stroke();
        };
        document.getElementById("canvas").addEventListener("MSPointerDown", start, false);
    	document.getElementById("canvas").addEventListener("MSPointerMove", move, false);
    };        
    
    // prototype to	start drawing on mouse using canvas moveTo and lineTo
    var drawMouse = function() {
    	var clicked = 0;
    	var start = function(e) {
    		clicked = 1;
    		ctx.beginPath();
    		x = e.pageX;
    		y = e.pageY - 66;	// 66 - 보정값
    		ctx.moveTo(x,y);
    	};
    	var move = function(e) {
    		if(clicked){
    			x = e.pageX;
    			y = e.pageY - 66;	// 66 - 보정값
    			ctx.lineTo(x,y);
    			ctx.stroke();
    		}
    	};
    	
    	var stop = function(e) {
    		clicked = 0;
    	};
    	
        document.getElementById("canvas").addEventListener("mousedown", start, false);
    	document.getElementById("canvas").addEventListener("mousemove", move, false);
    	document.addEventListener("mouseup", stop, false);
    };

    function uploadEx(path, method) {
        
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
<link rel="stylesheet" type="text/css" href="./css/drawing/mystyle.css">
<script src="./fonts/scripts.js"></script>
</head>

<body>
<!-- ExCanvas -->
<form name="form1" accept-charset="utf-8" action="resultOfViewer.php" method="POST">
	<input type="hidden" name="drawing" id="drawing">
    <table style="width:600px; height:600px;">
    	<tr>
    		<td style="width:200px; height:200px">
    		
                <div id="page">
                	<div class="header">
                		<a id="new" class="navbtn" onclick="newCanvas()">새로만들기</a>
            			<a id="save" class="navbtn" onclick="saveCanvas('resultOfDrawing.php' , {'drawing':'img'}, 'POST')">저장</a>
            			<a id="scale" class="navbtn" onclick="scaleCanvas()">연필 심 키우기</a>
            			<a id="reduce" class="navbtn" onclick="reduceCanvas()">연필 심 줄이기</a>
                        <div class="title">Handwriting(Digital Pad)</div>
        			</div>
                    <div class="header_subject">
                        <div class="body_title"><?php echo $title; ?></div>
                    </div>
                    <div id="content"><p style="text-align:center">캔버스 불러오는 중...</p></div>
                    <div class="footer">
                		<div class="palette-case">
                			<div class="palette-box">
                				<div class="palette white" onclick="selectColor(this)"></div>
                			</div>	
                			<div class="palette-box">
                				<div class="palette red" onclick="selectColor(this)"></div>
                			</div>
                			<div class="palette-box">
                				<div class="palette blue" onclick="selectColor(this)"></div>
                			</div>
                			<div class="palette-box">
                				<div class="palette green" onclick="selectColor(this)"></div>
                			</div>
                			<div class="palette-box">
                				<div class="palette black" onclick="selectColor(this)"></div>
                			</div>		
                			<div class="palette-box">
                				<div class="palette yellow" onclick="selectColor(this)"></div>
                			</div>		
                			<div style="clear:both"></div>
                		</div>
                    </div>
                </div> 
    		</td>
    	</tr>
    </table>
</form>
<!-- ExCanvas 종료 -->

</body>
</html>

