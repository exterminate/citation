<?php
	// functions
	
	function findHighestID($db)	 {  //get highest ID
		$checkHighestID = $db->query("SELECT MAX(ID) FROM citation");
		$rowHighestID = $checkHighestID->fetchArray();
		if(isset($rowHighestID['MAX(ID)'])) 
			return $rowHighestID['MAX(ID)'] + 1;		else
			return 0;
	}	
	
	
	function searchMatch($dbarray,$title,$descr) { // search database

		$before = array(",",";",":","*","<",">","'","."," and "," to "," the "," for "," it "," with ","-"); 
		$after = array("","","","","","","",""," "," "," "," "," "," "," ");


		$titArray = explode(" ", trim(str_replace($before,$after,$title)));
		$desArray = explode(" ", trim(str_replace($before,$after,$descr)));

		for($i = 0; $i < count($titArray); $i++){
			if(empty($titArray[$i]))
				unset($titArray[$i]);
		}

		for($j = 0; $j < count($desArray); $j++){
			if(empty($desArray[$j]))
				unset($desArray[$j]);
		}


		foreach($titArray as &$tA){
			foreach($dbarray as $key => &$arr){
				if(isset($tA)){
					if(strpos(strtolower($arr['title']),strtolower($tA)) !== false){
						$arr['hits']++;
					}
				}	
			}
		}
		foreach($desArray as &$dA){
			foreach($dbarray as $key2 => &$arr2){
				if(isset($dA)){
					if(strpos(strtolower($arr2['abstract']),strtolower($dA)) !== false){
						$arr2['hits']++;
					}
				}	
			}
		}


		foreach ($dbarray as $key => $row) {
			$hits[$key]  = $row['hits'];
		}

		array_multisort($hits,SORT_DESC,$dbarray);
		
		//results
		$toReturn = "<div class='records'>";
		
		// put each record in a div
		foreach($dbarray as $key => $item) {
			if($item['hits'] > 0) {
				$toReturn .= "<div id='".$key."'>";
				$toReturn .= "	<p class='title'>".$item['title']."</p>
								<p class='abstract'>".$item['abstract']."</p>
								<p class='match'>".$item['hits']."</p>";
				$toReturn .= "</div>";
			}
		}
		$toReturn .= "</div>";
		return $toReturn;
	}
	
	
	
?>