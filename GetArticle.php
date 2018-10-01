<?php
	//sql連線
	$DBNAME = "ithome";
	$DBUSER = "root";
	$DBPASSWD = "1234";
	$DBHOST = "localhost";
	 
	$conn = mysqli_connect( $DBHOST, $DBUSER, $DBPASSWD);

	if (empty($conn)){
	  print mysqli_error($conn);
	  die ("無法連結資料庫");
	  exit;
	}

	if( !mysqli_select_db($conn, $DBNAME)) {
	  die ("無法選擇資料庫");
	}

	// 設定連線編碼
	mysqli_query( $conn, "SET NAMES 'utf8'");

	//curl初始化
	$ch = curl_init();

	//page
	for ( $k=0 ; $k<10 ; $k++ ) {

		//id count
		$sql_search = " SELECT id 
				 		FROM article 
			   		  ";
		// echo $sql_search;
		$result_search = mysqli_query($conn, $sql_search);
		
		$search_count = mysqli_num_rows($result_search);

		if ( $k > 0 ) {
			
			//設置欲獲取之URL地址
			// curl_setopt($ch , CURLOPT_URL , "https://www.ithome.com.tw/latest?page=2");
			curl_setopt($ch , CURLOPT_URL , "https://www.ithome.com.tw/latest/635/1306?page=".$k);

		}
		else {
			
			//設置欲獲取之URL地址
			curl_setopt($ch , CURLOPT_URL , "https://www.ithome.com.tw/latest");

		}

		//取消輸出http header
		curl_setopt($ch , CURLOPT_HEADER,0);
		//抓取结果直接返回（如果为0，则直接输出内容到页面）
		curl_setopt($ch , CURLOPT_RETURNTRANSFER,1);

		//取出字串
		$list = curl_exec($ch);
	 

		$key_prefix = '<p class="title"><a href="';  
		$key_suffix = '">'; 
		if ( preg_match_all("/$key_prefix(.*?)$key_suffix/i" , $list , $matches) ) { 

            $key = $matches[1];            

            for ( $j=0 ; $j<count($key) ; $j++ ) {

            	$sql_check_url = " SELECT id
            					   FROM article
            					   Where url = '".$key[$j]."'
            					 ";
				// echo $sql_check_url;
            	$result_check = mysqli_query($conn, $sql_check_url);

            	$check_url = mysqli_num_rows($result_check);

            	if ( $check_url < 1 ) {
					//curl初始化
					$article = curl_init();


					// 設置欲獲取之URL地址
					curl_setopt($article , CURLOPT_URL , "https://www.ithome.com.tw".$key[$j]);

					//取消輸出http header
					curl_setopt($article , CURLOPT_HEADER,0);
					//抓取结果直接返回（如果为0，则直接输出内容到页面）
					curl_setopt($article , CURLOPT_RETURNTRANSFER,1);

					//取出字串
					$art = curl_exec($article);
					
					//去除换行
					$art = str_replace("\n","",$art);

					//url
					$art_url = $key[$j];

					//title
					$art_title_start = mb_strpos($art , '<h1 class="page-header">' , 0 , 'UTF-8') + 24;

					$art_title_end = mb_strpos($art , '</h1>' , 0 , 'UTF-8');

					$art_title = mb_substr($art , $art_title_start , $art_title_end - $art_title_start , 'UTF-8');

					if ( $art_title_start <= 24 ) {
						if ( preg_match('/<meta property="og:title" content="(.*?)" \/>/' , $art , $title) ){
							$art_title = $title[1];
							
						}
					}

					//picture
					$pic_prefix = '<div class="img-wrapper">.*?hidden">.*?<div class="field-item even"><img src="';  
					$pic_suffix = '" width='; 
					$art_pic = '';
					if ( preg_match("/$pic_prefix(.*?)$pic_suffix/i" , $art , $pic) ) { 
							$art_pic = $pic[1];
					}

					//author
					if ( preg_match('/<span class="author"><a\shref="\/.*?\/.*?">(.*?)<\/a>/i' , $art , $author) ){
						$art_auther = $author[1];
					}
					
					//day
					if ( preg_match('/<span class="created">(.*?)<\/span><span class="pub-wording">/' , $art , $day) ){
						$art_day = $day[1];
					}	

					//detail
					$detail_prefix = '<div class="field field-name-body field-type-text-with-summary field-label-hidden">';

					$detail_suffix = '<section id="block-block-45" class="block block-block clearfix">'; 				
					if ( preg_match("/$detail_prefix\s?(.*)\s?$detail_suffix/",$art,$detail) ){
						$art_detail = preg_replace('/<(.*?)>/','',$detail[1]);					
					}

					$detail_suffix = '<article id="node-125741" class="node node-news node-promoted  clearfix">'; 
					if ( preg_match("/$detail_prefix\s?(.*)\s?$detail_suffix/",$art,$detail) ){
						$art_detail = preg_replace('/<(.*?)>/','',$detail[1]);	
					}

					//去除單引號
					$art_detail = str_replace("'","",$art_detail);


					//like
					$fb_url = "https://www.facebook.com/plugins/like.php?action=like&app_id=161989317205664&channel=https%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter%2Fr%2FkO5a7GzG6AF.js%3Fversion%3D42%23cb%3Df2b785b0cd14f9%26domain%3Dwww.ithome.com.tw%26origin%3Dhttps%253A%252F%252Fwww.ithome.com.tw%252Ff1e9c0199dea55c%26relation%3Dparent.parent&container_width=0&href=https%3A%2F%2Fwww.ithome.com.tw%2Fnews%2F".substr($key[$j] , -6)."&layout=button_count&locale=zh_TW&sdk=joey&share=true&show_faces=false";

					//curl初始化
					$fb_art = curl_init();

	 				// 設置欲獲取之URL地址
	 				curl_setopt($fb_art , CURLOPT_URL , $fb_url);

	 				//不需要页面的HTTP頭
	 				curl_setopt($fb_art , CURLOPT_HEADER,0);
	 				//抓取结果直接返回（如果为0，则直接输出内容到页面）
	 				curl_setopt($fb_art , CURLOPT_RETURNTRANSFER,1);

	 				//模擬user-agent
	 				$UserAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13';
	 				curl_setopt($fb_art, CURLOPT_USERAGENT, $UserAgent);

					//取出字串
					$fb_like = curl_exec($fb_art);

					if ( preg_match('/<span class="_5n6h _2pih" id="u_0_3">(.*)<\/span><\/div><\/button><input type="hidden" autocomplete="off" name="action" value="like" \/>/' , $fb_like , $like) ){
						$art_like = $like[1];
					}

					// echo $j.'<br>'."picture=".$art_pic.'<br>'."title=".$art_title.'--auther='.$art_auther.'--day='.$art_day.'<br>detail='.$art_detail.'<br>like='.$art_like.'<br>';

					$sql_insert = "INSERT INTO article (   id
														  ,title
														  ,auther
														  ,day
														  ,fb_like
														  ,picture
														  ,detail
														  ,url
													    )
												VALUES (  '".($search_count + $j + 1)."'
														 ,'".$art_title."'
														 ,'".$art_auther."'
														 ,'".$art_day."'
														 ,'".$art_like."'
														 ,'".$art_pic."'
														 ,'".$art_detail."'	
														 ,'".$art_url."'														
													   )";
					// echo $sql_insert.'<br>';			
					$result = mysqli_query($conn, $sql_insert);
				}
            }
			
		}


	}		

	echo "ok";
?>
