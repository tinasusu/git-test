<!DOCTYPE HTML>

<HTML>
	<HEAD>
		<script src="./includes/jquery-3.3.1.js"></script>
		<script>

			function getArticle(row) {

			 	$.ajax({
			     	url: 'AJarticleDetail.php',
			     	type: 'GET',
			     	dataType: 'json',			 
			 	    data : { 
                        end_row : row,                            
                    },
			 	    error: function(xhr) {
			 	     	alert('Ajax request 發生錯誤');
			 	    },
			 	    success: function(response) {
			 	    	// alert(response.length);
			 	    	// alert(response);
						for ( var i=0 ; i < response.length ; i++ ) {
							// alert("id="+response[$i]['id']+" ; title="+response[$i]['title']+" ; auther="+response[$i]['auther']+" ; day="+response[$i]['day']);
							
							if ( response[i]['picture'] != null && response[i]['picture'] != '' ){
								var pic_url = '<img src="' + response[i]['picture'] + '" width="300" height="150">';
							}else {
								var pic_url = '';
							}

							$("table").append(
						         '<tr>' + 
						            '<td width="5%" align="center" style="background-color:#77DDFF">' + "id" + '</td>' +
						            '<td width="95%" align="left" style="background-color:#77DDFF">' + response[i]['id'] + '</td>' +
						         '</tr>' +
						         '<tr>' +
						         	'<td width="5%" align="center">' + "title" + '</td>' +
						         	'<td width="95%" align="left">' + response[i]['title'] + '</td>' +
						         '<tr>' +	
						         '<tr>' +
						         	'<td width="5%" align="center">' + "auther" + '</td>' +
						         	'<td width="95%" align="left">' + response[i]['auther'] + '</td>' +
						         '<tr>' +
						         '<tr>' +
						         	'<td width="5%" align="center">' + "day" + '</td>' +
						         	'<td width="95%" align="left">' + response[i]['day'] + '</td>' +
						         '<tr>' +
						         '<tr>' +
						         	'<td width="5%" align="center">' + "fb_like" + '</td>' +
						         	'<td width="95%" align="left">' + response[i]['fb_like'] + '</td>' +
						         '<tr>' +
						         '<tr>' +
						         	'<td width="5%" align="center">' + "picture" + '</td>' +
						         	'<td width="95%" align="left">' + pic_url + '</td>' +
						         '<tr>' +
						         '<tr>' +
						         	'<td width="5%" align="center">' + "detail" + '</td>' +
						         	'<td width="95%" align="left">' + response[i]['detail'] + '</td>' +
						         '<tr>'
						      );
							
							if ( i == (response.length-1 ) ) {
								document.getElementById("end_row").value = response[i]['id'];
			 	       		}

						}
			 	       
			 	       
			     	}
			  	});

			}

			$(document).ready(function () {
				var row = document.getElementById("end_row").value;
				getArticle(row);

				$("#down_page").click(function(){
					var down_row = (document.getElementById("end_row").value<145)?document.getElementById("end_row").value:145;
					$("tr").remove();
					getArticle(down_row);
				});

				$("#up_page").click(function(){
					var up_row = ( (document.getElementById("end_row").value - 10) >= 0 )?(document.getElementById("end_row").value - 10):0;
					$("tr").remove();
					getArticle(up_row);
				});

				var art_count = 150;
				var page = art_count/5;
				for ( var i=1 ; i <= page ; i++ ) {	

					$("div").append(
						'<input type="button" size="20" id="page_' + i + '" value="' + i + '">'
					);	

					$('#page_' + i).click(function(){
						var page_row = ( ( $(this).val() ) * 5)-5;
						$("tr").remove();
						getArticle(page_row);						
					});
				}

					
			});

		</script>
	</HEAD>
	<BODY>
		<div>
			<input type="hidden" size="20" id="end_row" value="0">
			<input type="button" size="20" id="up_page" value="上一頁">
			<input type="button" size="20" id="down_page" value="下一頁">
		</div>
		<table width="95%" style="border:5px#cccccc;" cellpadding="10" border="1"></table>
	</BODY>
</HTML>