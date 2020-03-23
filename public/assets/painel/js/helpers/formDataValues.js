function initFormDataValues()
{
	//loop nos formularios
	$("form").each( function(){
		
		var form = $(this);
		
		//verificar se existe data values
		if( form.data("values") )
		{
			//definir values
			var values = form.data("values");
			//console.log(values);
			
			//loop nos inputs
			form.find("input[type='text'], input[type='tel'], input[type='hidden']").each( function(){
				
				var input = $(this);
				var name = input.attr("name");

				//verificar se existe o valor do input
				if( (values[name] != undefined) && (input.val() == "") )
				{
					input.val(values[name]);
				}
				
				if( input.hasClass("trigger-change") )
				{
					input.trigger("change");
				}
				
			});
			
			//loop nos selects
			form.find("select").each( function(){
				
				var select = $(this);
				var name = select.attr("name");
				
				//verificar se existe o valor do select
				if( values[name] != undefined )
				{
					var option = select.find('option[value="' + values[name] + '"]');
					option.attr("selected", "selected");
				}
				
			});
			
			//loop nos checkboxs
			form.find("input[type='checkbox']").each( function(){
				
				var input = $(this);
				var name = input.attr("name");
				
				//verificar se existe o valor do checkbox
				if( values[name] == input.val() )
				{
					input.attr("checked", "checked");
				}
				
			});
			
			//loop nos radios
			form.find("input[type='radio']").each( function(){
				
				var input = $(this);
				var name = input.attr("name");
				
				//verificar se existe o valor do radio
				if( values[name] == input.val() )
				{
					input.attr("checked", "checked");
				}
				
			});
			
			//loop nos textareas
			form.find("textarea").each( function(){
				
				var textarea = $(this);
				var name = textarea.attr("name");
				
				//verificar se existe o valor do textarea
				if( values[name] != undefined )
				{
					textarea.val(values[name]);
				}
				
			});
		}
		
	});
}

$( function(){
	
	initFormDataValues();
	
});