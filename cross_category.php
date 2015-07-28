<?php
	$db =mysql_connect("127.0.0.1", "root","6a5a4a");
	$conn = mysql_select_db("gmarket",$db);
	date_default_timezone_set('Asia/Taipei');
	mysql_query("set character_set_results='utf8'");
	mysql_query("set character_set_client='utf8'");
	mysql_query("set names utf8");
	
?>

<html>
<head>
	<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
</head>
<body>
<button onclick="window.history.go(-1); return false;"> 上一頁 </button>

<h1>Number of Exact Match of Word <?php echo $_GET['word']; ?> </h1>
<table>
<?php
	$sql = "select * from date_list";
	$result = mysql_query($sql) or die("GG".mysql_error());
	$date_list = array();
	
	while($row = mysql_fetch_assoc($result)){
		array_push($date_list,$row);
	}

	$sql = "select * from categ";
	
	$result = mysql_query($sql) or die("GG".mysql_error());
	
	$categ_list = array();
	while($row = mysql_fetch_assoc($result)){
		array_push($categ_list,$row);
	}
	echo "<tr><th>Date</th>";
		for($j = 0; $j <count($categ_list) ; $j++ ){
			echo "<th>".$categ_list[$j]['categ_name']."</th>";
		}
	echo "</tr>";
	for ($i =0  ; $i < count($date_list) ; $i++ ){
		echo "<tr>";
		echo "<td>".$date_list[$i]['record_time']."</td>";
		for($j = 0; $j <count($categ_list) ; $j++ ){
			#echo $date_list[$i]['record_time'];
			$table_name = "categ".$categ_list[$j]['id']."_".$date_list[$i]['record_time'];
			$word_list_table_name = "wordlist_".$table_name;
			$tfidf_table_name = "tfidf_".$table_name;
			#echo $word_list_table_name."<br />";
			#echo $tfidf_table_name."<br />";
			$sql = "select distinct(url) from `{$word_list_table_name}` a , `{$tfidf_table_name}` b , `{$categ_list[$j]['categ_name']}` c where a.name like '{$_GET['word']}' and a.id =b.word_index and b.document_index = c.id";
			#echo $sql; 
			$result = mysql_query($sql) or die ("gGG".mysql_error());
			$sql2 = "select count(*) from `{$categ_list[$j]['categ_name']}` where record_time = '{$date_list[$i]['record_time']}' ";
			$result2= mysql_query($sql2);

			echo "<td>".mysql_num_rows($result)/mysql_fetch_array($result2)[0]."</td>";
			
		}
		echo "</tr>";
		#break;
	}


?>
</table>

</body>
</html>


<?php

	mysql_close($db);
?>