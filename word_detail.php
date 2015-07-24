<?php
	$db =mysql_connect("127.0.0.1", "root","6a5a4a");
	$conn = mysql_select_db("gmarket",$db);
	date_default_timezone_set('Asia/pacific');
	mysql_query("set character_set_results='utf8'");
	mysql_query("set character_set_client='utf8'");
	mysql_query("set names utf8");
	
	$sql = "select categ_name from categ where id = ".$_GET['categ'];
	$result = mysql_query($sql) or die("GG".mysql_error());
	$row = mysql_fetch_assoc($result);
	$categ_name = $row['categ_name'];

?>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<button></button>
<h1>Tracing Word: <?php echo $_GET['word']; ?> in Category : <?php echo $_GET['categ']." ".$categ_name;  ?> </h1>
<form name = 'myform' method = "GET">
<table cellspacing="10">
<tr>
<th>Start Date</th><th> End Date</th> 
</tr>
<tr>
<td>

<select name = 'start_date'>
<?php	$sql = "select * from date_list";
	$result = mysql_query($sql) or die ("GG".mysql_error());
	while($row = mysql_fetch_assoc($result)){
		echo "<option value = '".$row['record_time']."'>".$row['record_time']."</option>";
	}
?>
</select>
</td>

<td>
<select name = 'end_date'>
<?php	$sql = "select * from date_list";
	$result = mysql_query($sql) or die ("GG".mysql_error());
	while($row = mysql_fetch_assoc($result)){
		echo "<option value = '".$row['record_time']."'>".$row['record_time']."</option>";
	}
?>
</select>
</td>

<td><input type="submit"></input>

</td>

</tr>

</table>

<input type="hidden" name="word" value=<?php echo "'{$_GET['word']}'"; ?> />
<input type="hidden" name="categ" value=<?php echo "'{$_GET['categ']}'"; ?> />

</form>

<table cellspacing="10">
<tr><th>Product Name </th><th> URL </th> <th> Record Date </th></tr>
<?php	
	$sql = "select * from `{$categ_name}` where product_name like '%".$_GET['word']."%'";
	echo $sql;
	$result = mysql_query($sql) or die ("GG".mysql_error());

	while($row = mysql_fetch_assoc($result)){
		echo "<tr>";
		echo "<td class=\"product_name\" >".$row['product_name']."</td>";
		echo "<td> <a target='_blank' href='{$row['url']}'>".$row['url']."</a></td>";
		echo "<td>".$row['record_time']."</td>";
		echo "</tr>";
	}
	
	mysql_close($db);
?>
</table>

</body>
</html>
