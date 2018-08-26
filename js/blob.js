/*
 * Created Date: 2018-08-24
 * Subject: Blob
 * FileName: blob.js
 * Version: 0.1
 * Author: Dodo(rabbit.white at daum dot net)
 * Description:
 * 2018-08-24 / Dodo / 
*/

function convertToBlobNormal(vID){
	
	alert('버그(Bug)');
	
	var blob = document.getElementById( 'form_' + vID + '_filelink' );
	var blobUrl = blob.value;

	var xhr = new XMLHttpRequest;
	xhr.responseType = 'blob';

	xhr.onload = function() {
	   var recoveredBlob = xhr.response;

	   var reader = new FileReader;

	   reader.onload = function() {
	     var blobAsDataUrl = reader.result;
	     window.location = blobAsDataUrl;
	   };

	   reader.readAsDataURL(recoveredBlob);
	};

	xhr.open('GET', blobUrl);
	xhr.send();
}
