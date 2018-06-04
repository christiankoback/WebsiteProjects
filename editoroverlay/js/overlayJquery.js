$(function() { 
	$("#frame1").css("opacity", $("#UOpacity").val()  );
	$("#frame2").css("opacity", $("#fOpacity").val() );
	$modalClose = $(this).add("#modalClose");
	$modalClose.click(function(event){		//close modals
		var modal = document.getElementById('colorPicker');
		var modal2 = document.getElementById('fileSaveWarning');
		if (event.target.id =="modalClose"){
			modal.style.display = "none";
		}else{
			
			if (event.target == modal) {
				modal.style.display = "none";
			}else if (event.target == modal2) {
				modal2.style.display = "none";
			}else{}
		}
	});
	
	
	//$(".inputfile + label").css("background-color","yellow");
	$("#fileLabel").text("Browse Text File To Edit");
	
	//init color picker values
	var red = Number( $("#redRange").val() );
	var blue = Number( $("#blueRange").val() );
	var green = Number( $("#greenRange").val() );
	$("#redValue").val(red);
	$("#blueValue").val(blue);
	$("#greenValue").val(green );
	
	var finalHex = "#" + red.toString(16) + green.toString(16) + blue.toString(16);
	$("#frame2").css("color",finalHex);
	
	//checkbox events
	$("#urlEdit,#fileEdit").on("change", function(event){
		var urlEdit = "urlEdit";
		if (event.target.checked == true){
			$("#invisibleFrame").css("z-index","-10");
			if (event.target.id == urlEdit){
				$("#fileEdit").prop("checked",false);
				$("#frame1").css("z-index","0");
				$("#frame2").css("z-index","-1");
				
			}else{
				$("#urlEdit").prop("checked",false);
				$("#frame1").css("z-index","-1");
				$("#frame2").css("z-index","0");
			}
		}else{
			$("#invisibleFrame").css("z-index","1");
		}
	});
	$("#fileInput").change(function(event){
		var fileName ="";

		if( event.target.value ){
			fileName = event.target.value.split( '\\' ).pop();
			$("fileLabel").empty();
			$("#fileLabel").text(fileName);
		
		
			//Set the extension for the file 
		        var fileExtension = /text.*/; 
		        var fileExtensions = ["txt","php"];
		        //Get the file object 
		        var fileTobeRead = $("#fileInput")[0].files[0];
		        //Check of the extension match 
		        var isvalid = 0;
		        for (var fileExtIndex = 0; fileExtIndex < fileExtensions.length; fileExtIndex++){	
		        	if (fileTobeRead.name.substr(fileTobeRead.name.length - 3) == fileExtensions[fileExtIndex]){
		        		isvalid = 1;
		        		break;
		        	}
		        }
		        if (fileTobeRead.type.match(fileExtension) || isvalid == 1){
				//Initialize the FileReader object to read the 2file 
				var fileReader = new FileReader(); 
				fileReader.onload = function () { 
					//var lines = (fileReader.result).split(/\r\n/g); // tolerate both Windows and Unix linebreaks
					//var sepLines="";
					//for (var lineIndex=0;lineIndex < lines.length;lineIndex++){
					//	sepLines += lines[lineIndex].split(/\r|\n/g)
					//}
					//fileReader.result
					$("#frame2").text( fileReader.result ) ;
				} 
				fileReader.readAsText(fileTobeRead); 
				//fileReader.readAsDataURL(fileTobeRead);
				//fileReader.readAsArrayBuffer(fileTobeRead);
				//fileReader.readAsBinaryString(fileTobeRead);
				
		        }else { 
		            alert("Please select a text or php file."); 
		         }
	         }else{}	
	});
	// Firefox bug fix
	$("#fileInput").on( 'focus', function(){ $("#fileInput").addClass( 'has-focus' ); })
	$("#fileInput").on( 'blur', function(){ $("#fileInput").removeClass( 'has-focus' ); });
	
	$("#fOpacity, #UOpacity").on("change", function(event){
		var value= parseFloat( event.target.value );
		var decimals = 1;
		if (isNaN(value) ){
			value = 0 ;
		}
		if (value > 1){
			value = 1;
		}
		var eventID=  event.target.id;
		var roundedValue = Number( Math.round( value +'e'+ decimals ) +'e-'+ decimals );
		$("#"+ eventID).val(roundedValue);
		
		if ( eventID == "UOpacity"){
			$("#frame1").css("opacity",roundedValue.toString());
		}else if ( eventID  == "fOpacity"){
			$("#frame2").css("opacity",roundedValue.toString());
		}else{}
	
	});

	$("#filesave").click(function(event){ 
		event.preventDefault();
		if ( (jQuery.trim($("#fileInput").val() ).length > 0) && $("#fileEdit").prop("checked")  ){
			$("#saveFile").val("");
			$("#fileSaveWarning").css("display","block");
		}
	});

	$("#colorDisplay").click(function(event){
		event.preventDefault();
		$("#colorPicker").css("display","block");
	});
	$("#redRange,#blueRange,#greenRange").on("input",function(event){
		var red = Number( $("#redRange").val() );
		var blue = Number($("#blueRange").val() );
		var green = Number($("#greenRange").val() );
		$("#redValue").val(red);
		$("#blueValue").val(blue);
		$("#greenValue").val(green );
		var finalHex = "#" + red.toString(16) + green.toString(16) + blue.toString(16);
		$("#frame2").css("color",finalHex);
	});
	
	
	$("#saveWarningYes,#saveWarningNo").click(function(event){
		$("#fileSaveWarning").css("display", "none");
		if (event.target.id == "saveWarningYes" ){
			$("#saveFile").val("saveFile");
		}else{
			$("#saveFile").val("noSaveFile");
		}
		var file = $('input[type=file]').val().replace(/C:\\fakepath\\/i, '');
		alert("file name: "+ file);
		var filename = file.replace(/C:\\fakepath\\/i, '');
		
		var text = $("#frame2").html().replace(/<br>/g, "\r\n").replace(/<\/div>/g, "\r\n").replace(/<div>/, "\r\n").replace(/<div>/g, '');
		alert(text);
		//text = text.replace(/<\/div><\/div>/g, '\n');   \//
		//text = text.replace(/<\/div>/g, "\r\n")
		//text = text.replace(/<div>/g, '');
		//text = formattedText.replace(/<div>|<\//div>/g, '');
		var blob = new Blob([text], {type: "text/plain;charset=utf-8"});
		saveAs(blob, filename);		
	});
});