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
	<title>Add arts</title>
</head>
<body>

<?php 
	include "header.php";
	include "nav.php";
	include "funct.inc.php";
	
?>

<div class='main'>

<?php
	
	$fileData = file_get_contents("wokpapers.php");
	$filedata = str_replace("'", "&apos;", $fileData);
	$fileData = str_replace("<br>",";",$fileData);
	$newprop = strip_tags($fileData,"<td>");	
	$newprop = str_replace('td valign="top"','td',$newprop);
    $newarray = explode("<td>",$newprop);
	   
    $counter = 0;
    $hit = 0;
	   
    while($counter < count($newarray)){
	        
        // authors 
        if(trim(str_replace("</td>","",$newarray[$counter])) == "AF")
            $finalArray[$hit]['AF'] = trim(str_replace("</td>","",$newarray[$counter+1]));
	                
        // title
        if(trim(str_replace("</td>","",$newarray[$counter])) == "TI")
            $finalArray[$hit]['TI'] = trim(str_replace("</td>","",$newarray[$counter+1]));
       
        // journal
        if(trim(str_replace("</td>","",$newarray[$counter])) == "SO")
            $finalArray[$hit]['SO'] = trim(str_replace("</td>","",$newarray[$counter+1]));
	        
        // abstract
        if(trim(str_replace("</td>","",$newarray[$counter])) == "AB")
            $finalArray[$hit]['AB'] = trim(str_replace("</td>","",$newarray[$counter+1]));
        	
        // publication year
        if(trim(str_replace("</td>","",$newarray[$counter])) == "PY")
            $finalArray[$hit]['PY'] = trim(str_replace("</td>","",$newarray[$counter+1])); 
   
        // volume
        if(trim(str_replace("</td>","",$newarray[$counter])) == "VL")
            $finalArray[$hit]['VL'] = trim(str_replace("</td>","",$newarray[$counter+1]));
        
        // issue
        if(trim(str_replace("</td>","",$newarray[$counter])) == "IS")
            $finalArray[$hit]['IS'] = trim(str_replace("</td>","",$newarray[$counter+1]));
	        
        // first page
        if(trim(str_replace("</td>","",$newarray[$counter])) == "BP")
            $finalArray[$hit]['BP'] = trim(str_replace("</td>","",$newarray[$counter+1]));
	        
        // last page
        if(trim(str_replace("</td>","",$newarray[$counter])) == "EP")
            $finalArray[$hit]['EP'] = trim(str_replace("</td>","",$newarray[$counter+1]));
	                
        // DOI
        if(trim(str_replace("</td>","",$newarray[$counter])) == "DI"){
            $finalArray[$hit]['DI'] = trim(str_replace("</td>","",$newarray[$counter+1]));
            $hit++;
        }
	            
        $counter++;
    }
	   
	
	$ID = findHighestID($db);  
    
	// find what's already in the database
	$search = "SELECT * FROM citation";
	$error = "Could not get list";	
	if($result = $db->query($search)) {
		while($row = $result->fetchArray()) {
			$dbarray[] = $row;
		}
	}else{
		die($error);
	}

	// prepare variables for the database
	foreach($finalArray as $item){
        $TI = str_replace("'", "&apos;", $item['TI']);
        $AF = str_replace("'", "&apos;", $item['AF']);
        $SO = str_replace("'", "&apos;", $item['SO']);
        $PY = str_replace("'", "&apos;", $item['PY']);
        $VL = str_replace("'", "&apos;", $item['VL']);
        $IS = str_replace("'", "&apos;", $item['IS']);
        $BP = str_replace("'", "&apos;", $item['BP']);
        $EP = str_replace("'", "&apos;", $item['EP']);
        if(isset($item['AB']))
			$AB = str_replace("'", "&apos;", $item['AB']);
		else
			$AB = "";
	    $DI = str_replace("'", "&apos;", $item['DI']);
		
		// look for duplicates
		$key = array_search($PY, $dbarray);
		if($DI == $dbarray[$key]['doi']) 
			echo "already got it";
		
		//insert into database
		$sql = "INSERT INTO citation (ID,title,authors,journal,year,volume,issue,pages,lastPage,abstract,doi,user,hits) 
	        VALUES ('$ID','$TI','$AF','$SO','$PY','$VL','$IS','$BP','$EP','$AB','$DI','','')";
		//$db->exec($sql);
		$ID++;
	}

	// probably don't need this
	$query = 'SELECT * FROM citation'; 	
		
	if($result = $db->query($query)) {
	  while($row = $result->fetchArray()) {
	    	print("ID: {$row['ID']} - Name: {$row['authors']} <br />");
		}
	} else {
	  die($error);
	}



	
	    
?>
</div>
</body>
</html>