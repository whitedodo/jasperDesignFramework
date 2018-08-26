/* Copyright 2018 Dodo (rabbit.white at daum dot net)
   Copyright 2013 Chris Wilson

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

window.AudioContext = window.AudioContext || window.webkitAudioContext;

var audioContext = new AudioContext();
var audioInput = null,
    realAudioInput = null,
    inputPoint = null,
    audioRecorder = null;

var rafID = null;
var analyserContext = null;
var canvasWidth, canvasHeight;
var recIndex = 0;

// 오디오 저장 처리
function saveAudio( vID, path, method ){
	
	var link = document.getElementById( 'form_' + vID + '_filelink' );
	var filename = document.getElementById( 'form_' + vID + '_filename' );

	// Download 처리 - Blob(대용량 처리)
	if ( path.indexOf("download") == 0 ){
		var url = link.value;
		var a   = document.createElement('a');
		
		a.style = "display: none";
		a.href  = url;
		a.download = filename.value;		
		document.body.appendChild(a);
		a.click();
	
		setTimeout(function(){
		    document.body.removeChild(a);
		    URL.revokeObjectURL(url); // 메모리 해제
		},100);
		
	
	}else{
		// 미지원(Unsupported)
		// 개발이 안 되는 영역(실시간 처리가 안 됨.)
		alert('미지원(Unsupported)');
	}
	
}

// 프로그래시브 업로드(미구현)
// 2018-08-26 - Dodo (rabbit.white at daum dot net)
function uploadEx(vID, path, method) {

    var fd = new FormData(document.forms["form1"]);

	// Blob, Blob 원시 파일명(Blob with Raw Filename)
	var link = document.getElementById( 'form_' + vID + '_filelink' );
	var filename = document.getElementById( 'form_' + vID + '_filename' );

	var url = link.value;
	var a   = document.createElement('a');
	a.style = "display: none";
	a.href  = url;
	a.download = filename.value;
	
	fd.append("fname", a);
	
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

/* 
- [TODO]:
- 영어(English)
- 1. offer mono option
- 2. "Monitor input" switch
- 한글(Hangul)
- [할 일]:
- 1. 모노 옵션 제공
- 2. "모니터 입력" 스위치
*/
// gotBuffers( buffers );
function gotBuffers( buffers ) {
	
    var canvas = document.getElementById( "wavedisplay" );
    drawBuffer( canvas.width, canvas.height, canvas.getContext('2d'), buffers[0] );

    // getBuffers가 호출되는 유일한 시간은 새 기록이 완료된 직후입니다. 
    // 여기서 다운로드를 설정해야 합니다.
    audioRecorder.exportWAV( doneEncoding );
}

// 인코딩이 완료된 경우 -> 저장 기능
function doneEncoding( blob ) {
	
	var fileName = "myRecording" + ((recIndex<10)?"0":"") + recIndex + ".wav";
    Recorder.setupDownload( blob, fileName );
    
    recIndex++;
}

// 녹음과 중지 기능 -> 버퍼
// YYYY-MM-DD / Chris Wilson /
// 2018-08-24 / Dodo / 전역 e함수 사용하지 않으면 recorderWorker.js 기능을 지원하지 않음.
function toggleRecording( vID, e ) {
	
    if (e.classList.contains("recording")) {
    	
        // 녹음 중지(Stop Recording)
        audioRecorder.stop();
        
        e.classList.remove("recording");
        audioRecorder.getBuffers( gotBuffers );
        
    } else {
    	
        // 녹음 시작(Start recording)
        if (!audioRecorder)
            return;
        
        e.classList.add("recording");
        audioRecorder.clear();
        audioRecorder.record();
    }
    
}

// Mono로 변환
// YYYY-MM-DD / Chris Wilson /
function convertToMono( input ) {
	
    var splitter = audioContext.createChannelSplitter(2);
    var merger = audioContext.createChannelMerger(2);

    input.connect( splitter );
    splitter.connect( merger, 0, 0 );
    splitter.connect( merger, 0, 1 );
    
    return merger;
}

// AnalyserUpdates()
// 에니메이션프레임 Cancel(캔셀/취소) 하기
// YYYY-MM-DD / Chris Wilson /
function cancelAnalyserUpdates() {
    window.cancelAnimationFrame( rafID );
    rafID = null;
}

// updateAnalysers(시간)
// 2018-08-26 - 시간 분석에 관한 사항
// 그래프 형태로 함수 출력
// YYYY-MM-DD / Chris Wilson /
function updateAnalysers(time) {
	
    if (!analyserContext) {
        var canvas = document.getElementById("analyser");
        canvasWidth = canvas.width;
        canvasHeight = canvas.height;
        analyserContext = canvas.getContext('2d');
    }

    // analyzer draw code here
    {
        var SPACING = 3;
        var BAR_WIDTH = 1;
        var numBars = Math.round(canvasWidth / SPACING);
        var freqByteData = new Uint8Array(analyserNode.frequencyBinCount);

        analyserNode.getByteFrequencyData(freqByteData); 

        analyserContext.clearRect(0, 0, canvasWidth, canvasHeight);
        analyserContext.fillStyle = '#F6D565';
        analyserContext.lineCap = 'round';
        
        var multiplier = analyserNode.frequencyBinCount / numBars;

        // Draw rectangle for each frequency bin.
        for (var i = 0; i < numBars; ++i) {
        	
            var magnitude = 0;
            var offset = Math.floor( i * multiplier );
            
            // gotta sum / average the block, or we miss narrow-bandwidth spikes
            for (var j = 0; j< multiplier; j++)
                magnitude += freqByteData[offset + j];
            
            magnitude = magnitude / multiplier;
            var magnitude2 = freqByteData[i * multiplier];            

            analyserContext.fillStyle = "hsl( " + Math.round((i*360)/numBars) + ", 100%, 50%)";
            analyserContext.fillRect(i * SPACING, canvasHeight, BAR_WIDTH, -magnitude);
        }
    }
    
    rafID = window.requestAnimationFrame( updateAnalysers );
}


function toggleMono() {
	
    if (audioInput != realAudioInput) {
        audioInput.disconnect();
        realAudioInput.disconnect();
        audioInput = realAudioInput;
    } else {
        realAudioInput.disconnect();
        audioInput = convertToMono( realAudioInput );
    }

    audioInput.connect(inputPoint);
}

// gotStream( 스트림 )
// YYYY-MM-DD / Chris Wilson /
function gotStream(stream) {
	
    inputPoint = audioContext.createGain();

    // Create an AudioNode from the stream.
    realAudioInput = audioContext.createMediaStreamSource(stream);
    audioInput = realAudioInput;
    audioInput.connect(inputPoint);

//    audioInput = convertToMono( input );

    analyserNode = audioContext.createAnalyser();
    analyserNode.fftSize = 2048;
    inputPoint.connect( analyserNode );

    audioRecorder = new Recorder( inputPoint );

    zeroGain = audioContext.createGain();
    zeroGain.gain.value = 0.0;
    inputPoint.connect( zeroGain );
    zeroGain.connect( audioContext.destination );
    updateAnalysers();
}

// 초기 오디오 환경설정(Webkit)
// YYYY-MM-DD / Chris Wilson /
function initAudio() {

    if (!navigator.getUserMedia)
        navigator.getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    if (!navigator.cancelAnimationFrame)
        navigator.cancelAnimationFrame = navigator.webkitCancelAnimationFrame || navigator.mozCancelAnimationFrame;
    if (!navigator.requestAnimationFrame)
        navigator.requestAnimationFrame = navigator.webkitRequestAnimationFrame || navigator.mozRequestAnimationFrame;

    navigator.getUserMedia(
    {
    	"audio": {
	        "mandatory": {
	            "googEchoCancellation": "false",
	            "googAutoGainControl": "false",
	            "googNoiseSuppression": "false",
	            "googHighpassFilter": "false"
	        },
        "optional": []
        },
    }, gotStream, function(e) {
        alert('Error getting audio');
        console.log(e);
    });
}

window.addEventListener('load', initAudio );
