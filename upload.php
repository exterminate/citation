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
	<?php include "head.php"; ?>
	<title>Add articles</title>
	<link rel="stylesheet" type="text/css" href="dropzone.css">
	<script src="dropzone.js"></script>
</head>
<body>

<?php 
	include "header.php";
	include "nav.php";
	include "funct.inc.php";
	
?>

<div class='main'>
	<div class='searchForm'>
		<p>First, export from Web of Science and save as a .htm file then drop the file in the area below.</p>
	</div>
	<form action="addarts.php" class="dropzone">

</div>
</body>
</html>
