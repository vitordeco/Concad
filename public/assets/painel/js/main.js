$(function (){
	
	//input file
	$(".custom-file-input").on("change", function() {
		var fileName = $(this).val().split("\\").pop();
		$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	})
	
	//hamburger menu click
	$('header a.hamburguer').bind('click', function(){

		$('nav.nav-menu, section.content-panel').toggleClass('active')
		$('.filter form').removeClass('active')
		$('header a.mobile').attr('href', 'javascript:buttonBack()')
		
	})
	
	//dropdown menu nav
	$('nav.nav-menu ul li.menu-link[data-dropdown="true"] > a').bind('click', function(){
		
		var myLi = $(this).parent()
		
		if ( myLi.hasClass('active') ){
			
			myLi.removeClass('active')
			myLi.find('ul').slideUp()
			
		} else {
			
			$('nav.nav-menu ul li.menu-link[data-dropdown="true"]').find('ul').slideUp()
			$('nav.nav-menu ul li.menu-link[data-dropdown="true"]').removeClass('active')
			myLi.addClass('active')
			myLi.find('ul').slideDown()
			
		}
		
	})
	
	//active menu nav
	$('nav.nav-menu ul li a').each(function(){

		var href = $(this).attr('href')
		var controller = $('body').attr('data-controller')
		var action = $('body').attr('data-action')
		var id = $('body').attr('data-id')
		var hrefAtual = controller+'/'+action
		
		href = href.replace('/painel/', '')
		
		if ( href == hrefAtual ){
			
			if ( $(this).parents('.menu-link').attr('data-dropdown') == 'true' ) {
				
				$(this).parents('.menu-link').find('ul').fadeIn(0)
				$(this).parents('.menu-link').addClass('active')
				
				if ( !id ) {
					
					$(this).addClass('active')
					
				} else {
					
					//$(this).parent('li').after('<li class="active"><a href="javascript:;">'+$(this).text().replace('Adicionar','Editar')+' #'+id+'</a></li>')
					
				}
				
			} else {
				
				$(this).parents('.menu-link').addClass('active')
				
			}
			
		}
		
	})
	
	//submit button
	$('.card-footer button[type="submit"]').bind('click', function(){
		
		console.log('submited')
		$(this).parents('.card').find('form').find('button[type="submit"]').trigger('click')
		
	})
	
	//reset button
	$('.card-footer button[type="reset"]').bind('click', function(){
		
		$(this).parents('.card').find('form').find('input, textarea, select').val('')
		
	})
	
	//form auto values
	$('.auto-values').each(function(){
		
		var form = $(this)
		var json = $(this).attr('data-values')
		
		if ( json ){
			
			json = JSON.parse(json)
			
			for( var name in json ) {
				
				form.find('input[type="text"][name="'+name+'"]').val( json[name] );
				form.find('input[type="date"][name="'+name+'"]').val( json[name] );
				form.find('input[type="tel"][name="'+name+'"]').val( json[name] );
				form.find('input[type="num"][name="'+name+'"]').val( json[name] );
				form.find('input[type="email"][name="'+name+'"]').val( json[name] );
				form.find('input[type="password"][name="'+name+'"]').val( json[name] );
		    	
	    		form.find('input[type="radio"][name="'+name+'"][value="'+json[name]+'"]').trigger('click')
	    		form.find('input[type="checkbox"][name="'+name+'"][value="'+json[name]+'"]').trigger('click')
	    		form.find('select[name="'+name+'"] option[value="'+json[name]+'"]').attr('selected','selected')
			    
			}
			
		}
		
	})
	
	//auto title page form
	$('.auto-fil-title').each(function(){
		
		var page = $('.breadcrumb-item:nth-child(2)').text()
		var action = $('.breadcrumb-item:nth-child(3)').text()
		
		$(this).html( '<strong>'+action+'</strong> '+page )
		
	})
	
	if ( $('.filter-page').length ){
	
		$('.filter-page').each(function(){
			
			var form = $(this)
			var json = $(this).find('form').attr('data-values')
			
			if ( json ){
				
				json = JSON.parse(json)
				
				for( var name in json ) {
					
					form.find('input[type="text"][name="'+name+'"]').attr( 'value', json[name] );
					form.find('input[type="date"][name="'+name+'"]').attr( 'value', json[name] );
					form.find('input[type="tel"][name="'+name+'"]').attr( 'value', json[name] );
					form.find('input[type="num"][name="'+name+'"]').attr( 'value', json[name] );
					form.find('input[type="email"][name="'+name+'"]').attr( 'value', json[name] );
			    	
		    		form.find('input[type="radio"][name="'+name+'"][value="'+json[name]+'"]').trigger('click')
		    		form.find('input[type="checkbox"][name="'+name+'"][value="'+json[name]+'"]').trigger('click')
		    		form.find('select[name="'+name+'"] option[value="'+json[name]+'"]').attr('selected','selected')
				    
				}
				
				var conteudoDiv = $(this).html()
				$('.filter > div').html(conteudoDiv)
				$(this).remove()
				
			}
			
		})
	
	} else {
		
		$('.filter').remove()
		
	}
	
	$('.filter-mobile').bind('click', function(){
		
		$(this).parents('.filter').find('form').toggleClass('active')
		$('header a.mobile').attr('href', 'javascript:buttonBack()')
		
	})
	
})

function buttonBack(){
		
	$('.filter form.active').removeClass('active')
	$('nav.nav-menu.active').removeClass('active')
	$('header a.mobile').attr('href', $('header a.mobile').attr('data-href'))
	
}