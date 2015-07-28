<?php
	$db =mysql_connect("127.0.0.1", "root","6a5a4a");
	$conn = mysql_select_db("gmarket",$db);
	date_default_timezone_set('Asia/Taipei');
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
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">
			function checkForm(){
				if(document.myform.start_date.value > document.myform.end_date.value ){
					alert("Start Date must not be later then end date!!");
					return false;
				}
				return true;
			}
			$( document ).ready(function() {
			  // Handler for .ready() called.
				$("#div1").before($("#div2"));
			});

		</script>
	
	</head>
	
	<body>
		<button onclick="window.location='index.php'">上一頁</button>
		
		<h1>Tracing Word: <?php echo $_GET['word']; ?> in Category : <?php echo $_GET['categ']." ".$categ_name;  ?> </h1>
		
		<form name = 'myform' method = "GET">
			
			<table cellspacing="10">
				<tr>
					<th>Start Date</th>
					<th>End Date</th> 
					
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
					<td>
						<input type="submit" onclick="return checkForm()"></input>
					</td>
					
				</tr>
			</table>

			<input type="hidden" name="word" value=<?php echo "'{$_GET['word']}'"; ?> />
			<input type="hidden" name="categ" value=<?php echo "'{$_GET['categ']}'"; ?> />

		</form>
<button onclick=<?php echo "\"window.location='cross_category.php?word=".$_GET['word']."'\"" ?> >Cross Category Analysis</button>
		<div id="div1">
			<table cellspacing="8" style="display: inline-block;" width="auto" >
				<tr>
					<th>Product Name</th><th>URL</th><th>Record Date</th>
				</tr>
				<?php	
					#$sql = "select product_name, url, record_time from `{$categ_name}` where product_name like '%".$_GET['word']."%'";
					#echo $sql;
					if(isset($_GET['start_date'])){
						$date = $_GET['start_date'];
						$count_array = array();
						$abc  =0 ;
						while($date <= $_GET['end_date']){
							#echo $date;
							$sql = "select * from `tfidf_categ{$_GET['categ']}_{$date}` a , `wordlist_categ{$_GET['categ']}_{$date}` b, `{$categ_name}` c where b.name like '{$_GET['word']}' and b.id= a.word_index and a.document_index = c.id";
							echo $sql."<br />";
							
							$result = mysql_query($sql) or die ("GG".mysql_error());
							$count_array[$date] = mysql_num_rows($result);
							
							while($row = mysql_fetch_assoc($result)){	
								if($abc % 2 )
									echo "<tr class='odd' >";
								else
									echo "<tr>";
								echo "<td class=\"product_name\" >".$row['product_name']."</td>";
								echo "<td> <a target='_blank' href='{$row['url']}'>".$row['url']."</a></td>";
								echo "<td>".$row['record_time']."</td>";
								echo "</tr>";
							}
							$abc = $abc +1;
							$date = date("Y-m-d", strtotime("+1 day",strtotime($date)));
						}
					}
							
					
				?>
			</table>
		</div>

		<div id="div2">	
			<table cellingspace="8" style="display: inline-block;" width="auto">
				<tr>
					<th>Date</th><th># of Matches</th><th># of Producs</th><th>%</th>
				</tr>
				<?php
					if(isset($_GET['start_date'])){
						foreach($count_array as $i => $value){
						echo "<tr>";
						echo "<td>{$i}</td>";
						echo "<td>{$value}</td>";
						
						$query = "select count(*) from `{$categ_name}` where record_time = '{$i}'";
						#echo $query;
						$result = mysql_query($query) or die ("GG".mysql_error());
						$row = mysql_fetch_array($result);
						echo "<td>{$row[0]}</td>";
						echo "<td>".($value/$row[0])."</td>";
						echo "</tr>";
						}
					}
					
				?>
			</table>
		</div>
	</body>
</html>
<?php
	mysql_close($db);
?>
