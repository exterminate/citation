$(document).ready(function(){	

	var setText = "";
	
	$.get('files/AASetText.html', function(data){
		setText = data;
	});
	
	$('.expand').click(function(){	
			
		//expand the result on the list to show the abstract and citation	
		
		$(this).parent().find('.abstract').toggle(400);
		$(this).parent().find('.citation').toggle(400);
		
		var text = $(this).text();
		
		if(text == "Show abstract >>>"){
			$(this).text("Hide abstract <<<");
		} else {
			$(this).text("Show abstract >>>");
		}
		
	});
	
	$('.selectionArea').bind('click', function(){ 
		var $checkbox = $(this).parent().find(':checkbox');
		$checkbox[0].checked = !$checkbox[0].checked;
		$checkbox.trigger('change');
		$(this).parent().find('.tick').toggle(400);
	});
	
	$('#copy').click(function(){
		var checked = $(".chk:checked").size();
		var content = "";
		
		if(checked == 0){
			//if no suggestions are selected
			alert("No suggestions are selected!");
		} else{
			$('.chk').each(function(){
				if($(this).is(":checked")){
					content += $(this).parent().find('.title').text()+ "</br>";
					content += "\n";
					content += $(this).parent().find('.journal').text() + ", ";
					content += $(this).parent().find('.year').text() + ", ";
					content += $(this).parent().find('.volume').text() + ", ";
					content += $(this).parent().find('.pages').text() + ".</br>";
				}
			});
			
			$("#output").html("<h2>Copy this text:</h2>"+setText + content + "</br></br>");
		
		}
	
	});
});