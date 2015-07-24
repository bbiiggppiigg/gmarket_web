<?php
	$db =mysql_connect("127.0.0.1", "root","6a5a4a");
	$conn = mysql_select_db("gmarket",$db);
	date_default_timezone_set('Asia/Taipei');
	mysql_query("set character_set_results='utf8'");
	mysql_query("set character_set_client='utf8'");


?>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<form name = 'myform' method = "POST">
<select name = 'categ'>
<?php	$sql = "select * from categ order by categ_name";
	$result = mysql_query($sql) or die  ("GG".mysql_error());
	while($row = mysql_fetch_assoc($result)){
		echo "<option value = '".$row['id']."'> ".$row['categ_name']."</option>";
	}
?>
</select>
<select name = 'date'>
<?php	$sql = "select * from date_list";
	$result = mysql_query($sql) or die ("GG".mysql_error());
	while($row = mysql_fetch_assoc($result)){
		echo "<option value = '".$row['record_time']."'>".$row['record_time']."</option>";
	}
?>
</select>

<input type="submit"></input>

</form>


<?php	
	if(isset($_POST["categ"])){
	#	print_r($_POST);
		#$date_str = date('Y-m-d');
		echo "<table>";
		$wordlist_table_name = "wordlist_categ".$_POST["categ"]."_".$_POST["date"];
		$tfidf_table_name = "tfidf_categ".$_POST["categ"]."_".$_POST["date"];
		#echo $wordlist_table_name;
		#echo $tfidf_table_name;
		$sql = "select distinct(b.name), a.tf_idf from `".$tfidf_table_name."` a , `".$wordlist_table_name."` b where a.word_index = b.id order by a.tf_idf desc limit 50";
		$result = mysql_query($sql) or die("GG".mysql_error());
		echo "<tr><td>Word</td><td>TF_IDF</td></tr>";
		while($row = mysql_fetch_assoc($result)){
			echo "<tr>";
			echo "<td>".$row['name']."</td><td> ".$row['tf_idf']."</td>";#."<br />";
			echo "</tr>";
		}
		echo "</table>";
	}
	
?>


</body>
</html>
