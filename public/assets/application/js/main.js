/**
 * check if mobile
 * @returns {Boolean}
 */
function isMobile()
{
	return ($(window).width() <= 600);
}

$( function(){

	/* parallax */
	$('.parallax').each(function(){
		
	    var $obj = $(this)
	
	    $(window).scroll(function() {
	    	
	        var yPos = ($(window).scrollTop() / $obj.data('speed')) 
	
	        var bgpos = $obj.attr('data-float')+'  -'+ yPos + 'px'
	        
	        $obj.css('background-position', bgpos )
	
	    }) 
	    
	})
	
	/* animated */
	$('.animated').each(function(){
		
	    var $obj = $(this)
	
	    $(window).scroll(function() {
	    	
	    	if ( $obj.attr('data-animated') ){
	    	
		        var alturaTela = $(window).height()
		        var alturaObj = $obj.height() / 2
		        
		        var posObj = ($obj.offset().top + alturaObj)
		        var posObjFinal = $obj.offset().top + alturaTela
		        
		        var posScroll = $(window).scrollTop() + alturaTela 
	
		        var EfeitoIn = $obj.attr('data-animated')
		        var EfeitoOut = EfeitoIn.replace('In', 'Out')
		        
		        if ( posScroll >= posObj ) {
		        	
		        	$obj.removeClass(EfeitoOut).addClass(EfeitoIn)
		        	$obj.css('opacity','1').css('visibility','visible')
		        	
		        }
		        
		        if (  posScroll <= posObj ) {
		        	
		        	$obj.removeClass(EfeitoIn).addClass(EfeitoOut)
		        	
		        }
	        
	    	}
	
	    }) 
	    
	})
	
	/* modal */
	$(document).on('click', '.open-modal', function(){

		$(this).find("form").data(contValues)
		initFormDataValues();
		
		var cont = $(this).attr('data-content')
		var contWidth = $(this).attr('data-width')
		var contHtml = $('#'+cont).html()
		var contValues = $(this).attr('data-values')
		
		if ( contWidth ) {
			contWidth = 'width:'+contWidth+'px'
		} else {
			contWidth = 'width:700px'
		}

		var htmlModal  = '<div class="bg-modal animated fadeIn">'
			htmlModal += '<div class="animated fadeInDown box-animated">'
			htmlModal += '<div class="box-modal" style="'+contWidth+'">'
			htmlModal += '<a class="close"></a>'
			htmlModal += '<div class="content-modal">'
			htmlModal += contHtml
			htmlModal += '</div>'
			htmlModal += '</div>'
			htmlModal += '</div>'
			htmlModal += '</div>'

		$('#container').prepend( htmlModal )
		
		initMask();
	})
	
	$(document).on('click', '.bg-modal .box-modal .close', function(){

		$('.bg-modal .box-animated').removeClass('fadeInDown').addClass('fadeOutUp')

		setTimeout(function(){
			
			$('.bg-modal').removeClass('fadeIn').addClass('fadeOut')

			setTimeout(function(){
				$('.bg-modal').remove()
			}, 700)
			
		}, 200)
		
		
	})
	
	$(document).on('click', '.bg-modal', function(){
		
		$('.close').trigger('click')
		
	})
	
	$(document).on('click', '.bg-modal .box-modal', function( e ){
		
		e.stopPropagation()
		
	})
	
})