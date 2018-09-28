<?php
	
	//連資料庫，取值
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
		
		$sql ="select * from article ";
		// echo $sql;
		$result = mysqli_query($conn, $sql);
		

		while ( $row = mysqli_fetch_array($result) ) {
		 	echo $row['id']."1".'<br>';
		}



	//php array轉json（內容含中文）範例
		$data_array = array();	
		$data_array = [
						$data_array['id'] = "台北",
						$data_array['title'] = "台中",
					  ];

		// print_r($data_array);	
		// echo '<br>';
		// 先利用urlencode讓陣列中沒有中文
		foreach($data_array as $key => $value){
			// echo urlencode($key).'<br>';
			// echo urlencode($value).'<br>';
			$new_data_array[urlencode($key)] = urlencode($value);
		}


		// 利用json_encode將資料轉成JSON格式
		$data_json_url = json_encode($new_data_array);
		// echo "data_json_url==".$data_json_url.'<br>';
		
		// 利用urldecode將資料轉回中文

		$data_json = urldecode($data_json_url);
		echo "data_json==".$data_json.'<br>';	

	//php class 防呆練習
		class demo{

		// public $name = "tina"; 
		private $six = "girl";

			function init(){
				echo $this -> name = "tina";
				if ( method_exists($this,xxx) ) {
					$this -> six();
				}

				// if( method_exists($this,xx) ) {
				// 	echo "out";
				// }
			}

			function six(){
				echo $this -> six;
			}

			function text(){

			}
		}

		$demo =  new demo;
		$demo -> init();


?>