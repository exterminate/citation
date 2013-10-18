<?php  
	class MyDB extends SQLite3 {
	    function __construct() {
	        $this->open('myDatabase.db');
	    }
	}
	$db = new MyDB();
	$db->exec('CREATE TABLE if not exists citation (ID INT PRIMARY KEY NOT NULL,authors TEXT NOT NULL,journal TEXT NOT NULL,	year  TEXT NOT NULL,volume TEXT NOT NULL,issue TEXT NOT NULL,lastPage TEXT NOT NULL,user TEXT NOT NULL,hits TEXT NOT NULL,title TEXT NOT NULL,pages TEXT NOT NULL,abstract TEXT NOT NULL,doi TEXT NOT NULL)');
	
	if(!isset($_POST['abstract']))
		$postAbstract = "";
	else 	
		$postAbstract = $_POST['abstract'];

	if(!isset($_POST['title']))
		$postTitle = "";
	else 	
		$postTitle = $_POST['title'];
	
?>

<!DOCTYPE html>
<html>
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="js/searchResults.js"></script>
	<title>Search</title>
	<?php include "head.php"; ?>
</head>
<body>


<?php 
	include "header.php";
	include "nav.php";
	include "funct.inc.php";
	
?>

<div class='main'>
	<div id='output'></div>
	<form action='search.php' method='POST' class='searchForm'>
		<label>Title</label><br>
		<input type='text' name='title' value="<?php echo $postTitle; ?>"><br>
		<label>Abstract</label><br>
		<textarea name='abstract'><?php echo $postAbstract; ?></textarea><br>

		<div class="buttonDiv">
			<input type='submit' value='Search' name='search' class='positionLeft'>

			<?php
			if(!empty($postTitle) || !empty($postAbstract))
				echo "<button id='copy' class='positionRight'>Copy</button>";
			?>
			
		</div>

		

	</form>
	
<?php


$query = 'SELECT * FROM citation'; 	
$error = "The array was not populated";		
if($result = $db->query($query)) {
	while($row = $result->fetchArray()) {
		$dbarray[] = $row;
	}
}else{
	die($error);
}

if(isset($_POST['search'])) {
	$title = $_POST['title'];
	if(isset($_POST['abstract']))
		$descr = $_POST['abstract'];
	echo searchMatch($dbarray,$title,$descr);
}
?>

</div>
</body>
</html>