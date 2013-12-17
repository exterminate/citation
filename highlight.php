<?php
	include "funct.inc.php";
	
	if(file_exists("highlights.inc.php"))
	{
		$highlighted_articles = json_decode(file_get_contents("highlights.inc.php"),true);
	}else
		$highlighted_articles = array();	
	
	class MyDB extends SQLite3 { function __construct() { $this->open('myDatabase.db'); } }
	$db = new MyDB();
			
	$query = 'SELECT * FROM citation'; 	
	$error = "The array was not populated";		
	if($result = $db->query($query)) {
		while($row = $result->fetchArray()) {
			$dbarray[] = $row;
		}
	}else{
		die($error);
	}

	// Delete a gighlighted article
	
	if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['num']))
	{	
		unset($highlighted_articles[$_GET['num']]);
		$json_encoded = json_encode($highlighted_articles);
		file_put_contents("highlights.inc.php", $json_encoded);
		header("Location: highlight.php");
		exit;
	}else {
		$_GET['action'] = "";
		$_GET['num'] = "";
	}
	
	if(isset($_POST['submit']) && $_POST['submit'] == "Highlight")
	{
		$journal = $_POST['journal'];
		$page = if_integer(intval($_POST['page']));
		$vol = if_integer(intval($_POST['vol']));
		
		// check if they're already highlighted OR not in the database
		// INCOMPLETE -  STARTED
		foreach($highlighted_articles as $art)
		{
			if($vol == $art['vol'] && $page == $art['page'])
			{
				die("<p>This entry has already been included</p>");
			}else
				continue;	
		}
		
		foreach($dbarray as $key => $item) // articles from database
		{
			if($vol == $item['volume'] && $page == $item['pages'])
			{
				
			
		
		
		// <-
		
		
		$highlighted_articles[strtotime("now")]['journal'] = $journal;
		$highlighted_articles[strtotime("now")]['vol'] = $vol;
		$highlighted_articles[strtotime("now")]['page'] = $page;
		
		$json_encoded = json_encode($highlighted_articles);
		file_put_contents("highlights.inc.php", $json_encoded);
		$statement = "Saved!";
		
			}else
				continue;
		}				
		unset($vol);
		unset($journal);
		unset($page);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<?php include "head.php"; ?>
	<title>Home</title>
</head>
<body>

<?php 
	include "header.php";
	include "nav.php";
	
	if(isset($statement))
	{
		?>
		
		<script>
			alert("<?php echo $statement; ?>");
		</script>
		
		<?php
	}
	
?>
<div class='main'>

<div class='records'>

<h2>Add an article to highlighted list</h2>

<form method='POST' action='highlight.php'>
	<table>
		<tr>
			<td><label>Journal:&nbsp;</label></td>
			<td><select name='journal'><option value='ENERGY & ENVIRONMENTAL SCIENCE'>ENERGY & ENVIRONMENTAL SCIENCE</option></select></td>
		</tr>	
		<tr>
			<td><label>Volume:&nbsp;</label></td>
			<td><input type='text' name='vol' value=''></td>
		</tr>
		<tr>
			<td><label>Page:&nbsp;</label></td>
			<td><input type='text' name='page' value=''></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='submit' name='submit' value='Highlight'></td>
		</tr>
	</table>
</form>


<div class='highlighed_articles'>
	<h2>Highlighted articles</h2>	
	<?php
		
				
		// find details that match from highlight file
		
				
		foreach($highlighted_articles as $it => $art) //highlighted articles
		{
			foreach($dbarray as $key => $item) // articles from database
			{ 
				if ($art['page'] == $item['pages'] && $art['vol'] == $item['volume'] && substr($art['journal'],0,5) == substr($item['journal'],0,5)) {
					echo "<div class='highlightSection'><p><b>".$item['title']."</b> - <a href='highlight.php?action=delete&num=".$it."'>delete</a><br>".author_tidy($item['authors'])."<br>".$item['journal'].", <b>". $item['volume']."</b>, ".$item['pages']."</p><p class='small'>".$item['abstract']."</p></div><br>";
				}
			}
		}
		
		
	?>

</div>
</div>

</div>
</body>

</html>