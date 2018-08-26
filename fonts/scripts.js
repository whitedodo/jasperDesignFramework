WebFontConfig = {
custom: {
families: ['Nanum Gothic'],
urls: ['http://whitedodo.github.io/fonts/earlyaccess/nanumgothic.css']
		}
};
(function() {
		var wf = document.createElement('script');
	wf.src = ('https:' == document.location.protocol ? 'https' : 'http') + 
    'http://whitedodo.github.io/fonts/earlyaccess/webfont/1.4.10/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
    
})();