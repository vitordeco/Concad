$( function (){
	
	$(document).on("keyup", ".loadSearch", loadSearch);
	$(document).on("click", ".loadClick", loadClick);
	$(document).on("click", ".loadDelete", loadDelete);
	
});

function loadSearch()
{
	var search = $(this).val();
	
	if( search.length < 2 ) return false;
	
	$.ajax({
		type: 'POST',
		data: {search: search, method: 'load'},
		async: true,
		cache: false,
		dataType: "json",
		success: function($returndata)
		{
			if( $returndata.error )
			{
				message($returndata.error, 'error');
			}
			
			if( $returndata.html )
			{
				$("#loadGrp .loadTarget").html($returndata.html);
			}
		}
	});
}

function loadSelected()
{
	var ids = $("#loadGrp .loadSelected").data("ids");
	
	$.ajax({
		type: 'POST',
		data: {ids: ids, method: 'load'},
		async: true,
		cache: false,
		dataType: "json",
		success: function($returndata)
		{
			if( $returndata.error )
			{
				message($returndata.error, 'error');
			}
			
			if( $returndata.html )
			{
				$("#loadGrp .loadSelected").html($returndata.html);
			}
		}
	});
}

function loadClick()
{
	var value = $(this).data("id");
	var target = $("#loadGrp .loadSelected");
	
	var ids = target.data("ids");
	if( ids != undefined )
	{
		ids += "," + value;
	} else {
		ids = value;
	}
	
	target.data("ids", ids);
	//console.log(ids);
	loadSelected();
	
	$(this).parents('tr').remove();
	
	//remover tabela caso não houver mais registros
	var table = $("#loadGrp .loadTarget table");
	if( table.find("tbody tr").length == 0 )
	{
		table.remove();
	}
}

function loadDelete()
{
	$(this).parents('tr').remove();
	
	//remover tabela caso não houver mais registros
	var table = $("#loadGrp .loadSelected table");
	if( table.find("tbody tr").length == 0 )
	{
		table.remove();
	}
	
	//remover o id removido
	ids = [];
	$("#loadGrp .loadSelected input").each( function(i, e){
		ids.push($(e).val());
	});
	ids = ids.join(",");
	$("#loadGrp .loadSelected").attr("data-ids", ids);
}