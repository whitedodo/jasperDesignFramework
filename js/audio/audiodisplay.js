/*
 * Created Date: 2018-08-26
 * Subject: audiodisplay
 * FileName: audiodisplay.js
 * Version: 0.1
 * Author: Dodo(rabbit.white at daum dot net)
 * Description:
 * 
 */

// YYYY-MM-DD / Unknown / 
// 2018-08-26 / Dodo / Formula 식으로 분리 (formula_amp, formula_type, formula_max)

function drawBuffer( width, height, context, data ) {
	
    var step = Math.ceil( data.length / width );
    var amp = height / 2;
    context.fillStyle = "silver";
    context.clearRect(0,0,width,height);
    
    for ( var i=0; i < width; i++ ){
    	
    	var min = 1.0;
        var max = -1.0;
        
        // 대소 비교로직 (Min, Max)
        for ( j = 0; j < step; j++) {
            
        	var datum = data[(i*step)+j];
        	
            if (datum < min)
                min = datum;
            
            if (datum > max)
                max = datum;
        }
        
        var formula_amp = (1+min) * amp;
        var formula_type = 1;
        var formula_max = Math.max(1,(max-min)*amp);
        
        context.fillRect(i, formula_amp, formula_type, formula_max );
    }
    
}
