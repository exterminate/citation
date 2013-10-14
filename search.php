<?php
	class MyDB extends SQLite3 {
	    function __construct() {
	        $this->open('myDatabase.db');
	    }
	}
	$db = new MyDB();
	$db->exec('CREATE TABLE if not exists citation (ID INT PRIMARY KEY NOT NULL,authors TEXT NOT NULL,journal TEXT NOT NULL,	year  TEXT NOT NULL,volume TEXT NOT NULL,issue TEXT NOT NULL,lastPage TEXT NOT NULL,user TEXT NOT NULL,hits TEXT NOT NULL,title TEXT NOT NULL,pages TEXT NOT NULL,abstract TEXT NOT NULL,doi TEXT NOT NULL)');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Search</title>
	<link rel="stylesheet" href="citationstyle.css">
</head>
<body>

<?php 
	include "header.php";
	include "nav.php";
	include "funct.inc.php";
	
?>

<div class='main'>
	<form action='search.php' method='POST' class='searchForm'>
		<label>Title</label><br>
		<input type='text' name='title'><br>
		<label>Abstract</label><br>
		<textarea name='abstract'></textarea><br>
		<input type='submit' value='Search' name='search'>
	</form>

<?php
/*
$dbarray = array(
	array(
		'title' => 'batteries doing stuff with iron',
		'descr' => 'iron is used to model and capacitator, and energy',
		'hit' => ''
	),
	array(
		'title' => 'energy and power',
		'descr' => 'power, energy and lead awesome.',
		'hit' => ''
	),
	array(
		'title' => 'batteries with lead',
		'descr' => 'lead is used to model and capacitator, and energy',
		'hit' => ''
	)
);
*/


$query = 'SELECT * FROM citation'; 	
$error = "The array was not populated";		
if($result = $db->query($query)) {
	while($row = $result->fetchArray()) {
		$dbarray[] = $row;
	}
	//$dbarray = $result->fetchArray();
	echo "<pre>";
	//print_r($dbarray);
	echo "</pre>";
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