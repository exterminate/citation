<?php
	// functions
	
	function findHighestID($db)	 {  //get highest ID
		$checkHighestID = $db->query("SELECT MAX(ID) FROM citation");
		$rowHighestID = $checkHighestID->fetchArray();
		if(isset($rowHighestID['MAX(ID)']))
			return $rowHighestID['MAX(ID)'] + 1;		else
			return 0;
	}	
	
?>