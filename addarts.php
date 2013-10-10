<?php
	class MyDB extends SQLite3
	{
	    function __construct()
	    {
	        $this->open('myDatabase.db');
	    }
	}
		
	$db = new MyDB();


	$db->exec('
		CREATE TABLE if not exists users (
		ID INT PRIMARY KEY NOT NULL,
	 	authors TEXT NOT NULL,
	 	journal TEXT NOT NULL,
	 	year  TEXT NOT NULL,
	 	volume TEXT NOT NULL,
	 	issue TEXT NOT NULL,
	 	lastPage TEXT NOT NULL,
	 	user TEXT NOT NULL,
		hits TEXT NOT NULL,
		title TEXT NOT NULL,
		pages TEXT NOT NULL,
		abstract TEXT NOT NULL,
		doi TEXT NOT NULL
	 	)');
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Add arts</title>
</head>
<body>
<?php 
	include "header.php";
	include "nav.php";
	
	$query = 'SELECT * FROM users'; 	
	
	if($result = $db->query($query))
	{
	  while($row = $result->fetchArray())
	  {
	    print("ID: {$row['ID']} <br />" . "Name: {$row['authors']} <br />");
	  }
	}
	else
	{
	  die($error);
	}
	
	$fileData = file_get_contents("wokpapers.php");
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
	   
    echo "<hr><pre>";   
    //print_r($finalArray);
    echo "</pre>";
	    
    // add array to database - citation table
    // ID, authors, journal, year, volume, issue, lastPage, user, hits, title, pages, abstract    
	    

    foreach($finalArray as $item){
        $TI = $item['TI'];
        $AF = $item['AF'];
        $SO = $item['SO'];
        $PY = $item['PY'];
        $VL = $item['VL'];
        $IS = $item['IS'];
        $BP = $item['BP'];
        $EP = $item['EP'];
        $AB = $item['AB'];
        $DI = $item['DI'];

		$sql = "INSERT INTO citation (title,authors,journal,year,volume,issue,pages,lastPage,abstract,doi) 
	        VALUES ('$TI','$AF','$SO','$PY','$VL','$IS','$BP','$EP','$AB','$DI')";
		$db->exec($query);
	}
	
	$checkSecond = $db->query("SELECT year FROM users WHERE ID <= 30");
	while($rowCount = $checkSecond->fetchArray()){
		echo $rowCount['year']	. "!<br>";		
	}
	
	
	    
?>
</body>
</html>