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
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<form name = 'myform' method = "POST">

<table cellspacing="10">
<tr>
<th>Category Type</th><th> Match Date</th><th> Sort by</th> <th></th>
</tr>
<tr>
<td>
<select name = 'categ'>
<?php	$sql = "select * from categ order by id";
	$result = mysql_query($sql) or die  ("GG".mysql_error());
	while($row = mysql_fetch_assoc($result)){
		echo "<option value = '".$row['id']."'> ".$row['id']." ".$row['categ_name']."</option>";
	}
?>
</select>
</td><td>
<select name = 'date'>
<?php	$sql = "select * from date_list";
	$result = mysql_query($sql) or die ("GG".mysql_error());
	while($row = mysql_fetch_assoc($result)){
		echo "<option value = '".$row['record_time']."'>".$row['record_time']."</option>";
	}
?>
</select>
</td>
<td>
	<select name = 'sort'>
		<option value='tf'>TF</optino>
		<option value='idf'>IDF</option>
		<option value="tf_idf">TF_IDF</option>
	</select>
</td>

<td><input type="submit"></input>
</td>
</tr>

</table>



</form>


<?php	
	if(isset($_POST["categ"])){
	#	print_r($_POST);
		
		#$date_str = date('Y-m-d');
		$wordlist_table_name = "wordlist_categ".$_POST["categ"]."_".$_POST["date"];
		$tfidf_table_name = "tfidf_categ".$_POST["categ"]."_".$_POST["date"];
		echo "<h1> Matching ".$_POST["categ"]." on date ".$_POST["date"]." Sort by ".$_POST["sort"]."</h1>";
		echo "<table cellspacing = \"10\">";
		

		#echo $wordlist_table_name;
		#echo $tfidf_table_name;
		$sql = "select distinct(b.name), a.tf_idf , b.idf , a.tf_idf/b.idf as tf from `".$tfidf_table_name."` a , `".$wordlist_table_name."` b where a.word_index = b.id order by ".$_POST['sort']." desc limit 50";
		print $sql;
		$result = mysql_query($sql) or die("GG".mysql_error());
		echo "<tr><th>Word</th><th>TF_IDF</th><th>TF</th><th>IDF</th></tr>";
		while($row = mysql_fetch_assoc($result)){
			echo "<tr>";
			echo "<td class=\"product_name\" ><a href = 'word_detail.php?categ={$_POST['categ']}&word={$row['name']}"."'>".$row['name']."</a></td>";
			echo "<td> ".$row['tf_idf']."</td>";
			echo "<td>".$row['tf']."</td>";
			echo "<td>".$row['idf']."</td>";#."<br />";
			
			echo "</tr>";
		}
		echo "</table>";
	}
	
	mysql_close($db);
?>


</body>
</html>
