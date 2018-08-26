/*License (MIT)

Copyright 2018 Dodo / rabbit.white at daum dot net
Copyright 2013 Matt Diamond

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated 
documentation files (the "Software"), to deal in the Software without restriction, including without limitation 
the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and 
to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of 
the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO 
THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF 
CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
DEALINGS IN THE SOFTWARE.
*/

(function(window){
	
	var WORKER_PATH = './js/recorderWorker.js';
	
	var Recorder = function(source, cfg){

		var config = cfg || {};
		var bufferLen = config.bufferLen || 4096;
		this.context = source.context;
    
		if(	!this.context.createScriptProcessor	){
			this.node = this.context.createJavaScriptNode(bufferLen, 2, 2);
		} else {
			this.node = this.context.createScriptProcessor(bufferLen, 2, 2);
		}
   
		var worker = new Worker(config.workerPath || WORKER_PATH);
		worker.postMessage({
			command: 'init',

			config: {
				sampleRate: this.context.sampleRate
			}
		});
    
		var recording = false,
		currCallback;

		this.node.onaudioprocess = function(e){
    	if (!recording) return;
    	worker.postMessage({
    		command: 'record',
    		buffer: [
    			e.inputBuffer.getChannelData(0),
    			e.inputBuffer.getChannelData(1)
    			]
    	});
    }

    this.configure = function(cfg){
    	for (var prop in cfg){
    		
    		if (cfg.hasOwnProperty(prop)){
    			config[prop] = cfg[prop];
    		}
    	}
    }

    this.record = function(){
    	recording = true;
    }

    this.stop = function(){
    	recording = false;
    }

    this.clear = function(){
    	worker.postMessage({ command: 'clear' });
    }

    this.getBuffers = function(cb) {    
    	currCallback = cb || config.callback;
    	worker.postMessage({ command: 'getBuffers' })
    }

    this.exportWAV = function(cb, type){
    	currCallback = cb || config.callback;
    	type = type || config.type || 'audio/wav';
      
    	if (!currCallback) throw new Error('Callback not set');
      
    	worker.postMessage({
    		command: 'exportWAV',
    		type: type      
    	});
    }

    this.exportMonoWAV = function(cb, type){
    	currCallback = cb || config.callback;
    	type = type || config.type || 'audio/wav';
      
    	if (!currCallback) throw new Error('Callback not set');
    	
    	worker.postMessage({
    		command: 'exportMonoWAV',
    		type: type
    	});
    }
    
    // OGG
    this.exportOGG = function(cb, type){
    	currCallback = cb || config.callback;
    	type = type || config.type || 'audio/ogg';
    	
    	if (!currCallback) throw new Error('Callback not set');
    	
    	worker.postMessage({
    		command: 'exportOGG',
    		type: type
    	});
    }

    worker.onmessage = function(e){      
    	var blob = e.data;
    	currCallback(blob);
    }

	source.connect(this.node);
	this.node.connect(this.context.destination);   // if the script node is not connected to an output the "onaudioprocess" event is not triggered in chrome.
  
  };

  
  // Obj.setupDownload(blob, filename)
  // YYYY-MM-DD / Matt Diamond / 
  // 2018-08-26 / Dodo / 타입 추가 Ajax (jquery) , Ajax(javascript) 
  Recorder.setupDownload = function(blob, filename){
	  
	  var url = (window.URL || window.webkitURL).createObjectURL(blob);
	  
	  var frm_link = document.getElementById("form_audio_filelink");
	  var frm_filename = document.getElementById("form_audio_filename");
	  var downlink = document.getElementById("download");
	  	
	  frm_link.value = url;
	  frm_filename.value = filename || 'output.wav';
	  
      var fd = new FormData();
      
      fd.append('fname', filename);
      fd.append('data', blob);
      
      /*// Ajax - 구현 - jquery 타입
      $.ajax({
    	  type: 'POST',
          url: 'upload.php',
          data: fd,
          processData: false,
          contentType: false,
      }).done(function(data) {
    	  console.log(data);
    	  alert(data);
      });
      */
      
      // Ajax  JavaScript 타입
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload.php', true);
      
      xhr.onload = function () {
          console.log(this.responseText);
          
      };
      
      xhr.send( fd );
  }

  window.Recorder = Recorder;


})(window);