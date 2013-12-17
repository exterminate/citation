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

		$before = array(",",";",":","*","<",">","'","."); 
		$after = array("","","","","","","","");
		
		$stopwords = array(" about", " above", " across", " afterwards", " after", " against", " again", " all ", " almost", "  along", "already", " also", " although", " always", " amongst", " among",  " amoungst", " amount", " am ", " and ", " another", " anyhow", " anyone", " anything", " anyway", " anywhere", " any ", " are ", " around", " as ",  " at ", 
		" back"," be "," became", " because"," becomes"," becoming", " been", " beforehand", " before", " behind", " being", " below", " besides", " beside", " between", " beyond", " bill", " both", " bottom"," but ", " by ", " best ",
		" call", " cannot ", " can ", " cant ", " con ", " co ", " could", " couldnt", " cry ", 
		" describe", " detail", " done", " down ", " do ", " due", " during"," de ", " different ",
		" each "," eg ", " eight", " either", " eleven"," elsewhere", " else ", " empty", " enough", " etc.", " even", " everyone", " every", " everything", " everywhere", " ever ", " except ", " exceptionally ",
		" few", " fifteen", " fill", " find", " fire", " first", "five", " former", " formerly", " forty", " for ", " found", " four", " from", " front", " full", " further", 
		" get ", " go ", 
		" had ", " has ", " hasnt", " have",  " hence", " her", " hereafter", " hereby", " herein", " hereupon", " here ", " however"," how ", " hundred", 
		" ie. ", " if ",  " indeed", " interest", " into", " in ", " is ", " itself ", " its ", " it ", " including ",
		" keep", 
		" last", " latterly", " latter", " least", " less", " ltd", 
		" made", " many", " may",  " meanwhile", " me ", " might", " mill", " mine", " moreover", " more", " mostly", " most", " move", " much", " must", " my ", " myself", 
		" namely", " name ", " neither", " nevertheless", " new ", " never", " next", " nine",  " nobody", " none", " noone", " nor", " nothing", " not", " nowhere", " now", " no ", 
		" off ", " of ", " often", " on ", " once", " one ", " only ", " onto ", " or ", " others", " other ", " otherwise", " ours ", " our ", " ourselves", " out ", " over ", " own ",
		" part ", " per ", " perhaps", " please", " put ", " present ",
		" rather", " re ", 
		" same", " see ", " seemed", " seem ", " seeming", " seems", " serious", " several", " she ", " should", " show ", " side ", " since ", " sincere", " six ", " sixty", " so ", " some ", " somehow", " someone", " something", " sometimes", " sometime", " somewhere", " still", " such", " system", " study ",
		" take ", " ten ", " than ", " that ", " the ", " their ", " them ", " themselves", " then ", " thence", " there ", " thereafter", " thereby", " therefore", " therein", " thereupon", " these ", " they ", " thick ", " thin ", " third", " this ", " those ", " though ", " three ", " through ", " throughout ", " thru ", " thus ", " to ", " together", " too ", " top ", " towards", " toward", " twelve", " twenty", " two ", 
		" un ", " under ", " until ", " up ", " upon ", " us ", " using ",
		" very ", " via ", 
		" was ", " we ", " well ", " were ", " what ", " whatever ", " when ", " whence ", " whenever ", " where ", " whereafter ", " whereas", " whereby", " wherein", " whereupon", " wherever", " whether", " which", " while", " whither", " who ", " whoever", " whole", " whom", " whose",  " why ", " will ", " with ", " within", " without", " would",
		" yourselves", " yet ", " you ", " your ", " yours ", " yourself", " the ", " a "
		);
		
		$title = str_replace($stopwords," ",$title);
		$descr = str_replace($stopwords," ",$descr);
		
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
		$toReturn = "<div class='searchRecords'><h2>Search results</h2>";
		
		// put each record in a div
		foreach($dbarray as $key => $item) {
			if($item['hits'] > 0) {

				$authors = $item['authors'];
				$authorArray = explode(";",$authors);
				for ($a = 0; $a < count($authorArray); $a++) {
					$individualAuthor = explode(",",trim($authorArray[$a]));
					$indAuth = "";
					
					for ($b = 1; $b < count($individualAuthor); $b++) {
						$indAuth .= $individualAuthor[$b]." ";
					}
					$indAuth .= " ".$individualAuthor[0].", ";
					$authorArray[$a] = $indAuth;

				
				} 
				
				$authors = implode("",$authorArray);
				
				$toReturn .= "<div class='individualRecord' id='".$key."'>";
				$toReturn .= "	<div class='selectionArea'>
									<p class='title'>".$item['title']."</p>
									<p class='abstract' hidden>".$item['abstract']."</p>
									<p class='match'>".$item['hits']."</p>
									<div class='citation' hidden>
										<p class='authors'>".$authors."</p>
										<p class='journal'>".$item['journal']."</p>
										<p class='year'>".$item['year']."</p>
										<p class='volume'>".$item['volume']."</p>
										<p class='pages'>".$item['pages']."</p>
									</div>
									<input type='checkbox' class='chk' id='chk".$key."' hidden/>
									<div class='triangle' hidden></div>
									<img class='tick right' src='images/star.png' hidden/>
									
								</div>
								
								
								<button class='expand'>Show abstract >>></button>
								";
				$toReturn .= "</div>";
			}
		}
		$toReturn .= "</div>";
		return $toReturn;
	}
	

	function if_integer($num)
	{
		if($num == 0)
			die("<p>Only integers are allowed in the volume and page boxes. Go back and try again</p>");
		else
			return $num;
	}
	
	function author_tidy($authors)
	{
		$authorArray = explode(";",$authors);
		for ($a = 0; $a < count($authorArray); $a++) 
		{
			$individualAuthor = explode(",",trim($authorArray[$a]));
			$indAuth = "";
							
			for ($b = 1; $b < count($individualAuthor); $b++) 
			{
				$indAuth .= $individualAuthor[$b]." ";
			}
			$indAuth .= " ".$individualAuthor[0].", ";
			$authorArray[$a] = $indAuth;
		} 						
		$authors = implode("",$authorArray);
		return $authors;
	}
	
?>