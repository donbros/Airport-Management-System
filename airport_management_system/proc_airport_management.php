
<link href="include/styles_extra.css" rel="stylesheet" type="text/css" > 

<?php
	
    session_start();
	
	// cia sesijos kontrole - neišmeta vartotojo, jei jis buvo viename iš puslapių 
	if (!isset($_SESSION['prev']) || (($_SESSION['prev'] != "posterslist.php") 
		&& ($_SESSION['prev'] != "proc_airport_management.php") 
	&& ($_SESSION['prev'] != "index.php")  
	&& ($_SESSION['prev'] != "register.php") 
	&& ($_SESSION['prev'] != "operacija3.php")
	&& ($_SESSION['prev'] != "operacija4.php") 
	&& ($_SESSION['prev'] != "zinute.php")
	)
	   )
	{ 
	    header("Location: logout.php");exit;
	}
    
	// print_r($_POST); 
	// echo " : "; 
	// print_r($_SESSION['showlist']); 
	
	$PREV = $_SESSION['prev']; 
	
	$_SESSION['prev'] = "proc_airport_management.php"; // kazkoks sesijos susiejiklis 

	$skaitomoszinutes = $_POST['skaitomoszinutes']; 
	
	// 1. apsauga nuo pakartotinio duomenų rodymo (po paspaudimo "Vykdyti" (jis atstovauja kaip koks žymeklis) duomenys overwritinami); 
	// 2. priskiriame rezultatus sesijai, kad paspaudę grįžti atgal matytume tuos pačius rezultatus !!!!!!!!!!!!!!!!!!!!!!!!!!!!!! 
	// $_SESSION['showlist'] - kintmasis po šio if'o atsakingas už pasirinktų skelbimų rodymą 
	if($_POST!=null && isset($_POST['Vykdyti'])) { unset($_POST['Vykdyti']); $_SESSION['showlist']=$_POST; }
	// echo "good|".$_POST['placiau_'.$numberrr]."|"; 
	// po patikros unsetina mygtuko vardu "Vykdyti" reikšmę 
	
	$user = $_SESSION['user']; 

	include("include/nustatymai.php");
	include("include/functions.php");

	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 

	$sql = "SELECT posterid,rubric,expiration_date,topic,description,fk_userid "
        . "FROM " . TBL_POSTERS . " ORDER BY posterid";
	$result = mysqli_query($db, $sql);
	if (!$result || (mysqli_num_rows($result) < 1)) 
		{echo "Klaida skaitant lentelę posters"; exit;}

	$sql_users = "SELECT userid,realname,surname,username,userlevel,post,email,timestamp "
            . "FROM " . TBL_USERS . " ORDER BY realname";
	$result_users = mysqli_query($db, $sql_users);
	if (!$result_users || (mysqli_num_rows($result_users) < 1)) 
	{echo "Klaida skaitant lentelę users"; exit;} 	
	 
	 $sql_views = "SELECT fk_posterid, fk_userid "
            . "FROM " . TBL_VIEWS . " ORDER BY fk_posterid"; 
	$result_views = mysqli_query($db, $sql_views); 
	//if (!$result_views || (mysqli_num_rows($result_views) < 1)) 
	//{echo "Klaida skaitant lentelę views"; exit;} 	
	
	// IDOMUS FAKTAS: jei i echo idedi pilna $_POST arba $_SESSION ir t.t. ji parodo kaip kintamaji 
	
?>

<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Pasirinktas (-i) skelbimas (-ai)</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
    </head>
    <body>
        <table class="center" ><tr><td>
            <center><img src="include/top.png"></center>
			</td></tr><tr><td><center><font size="5">Skelbimo (-ų) apžiūra</font></center></td></tr></table> <br>
		<!-- čia galima padėti formą ir bandyti daryti daugybinimą trynimą --> 
		<table class="meniu" style="width:47%; border-width: 2px; border-style: solid; margin-left:auto; margin-right:auto;"><tr><td width="50%" >

		   <a href=<?php 
		   
		   if ($skaitomoszinutes == "1" && $_POST['bendrosgautoszinutes'] != "1" && $_POST['bendrossiustoszinutes'] != "1") { echo "posterslist.php"; } 
		   
		   if($skaitomoszinutes != "1" && $_POST['bendrosgautoszinutes'] != "1" && $_POST['bendrossiustoszinutes'] != "1") 
		   { 
			?> <?php 
				if ($PREV == "posterslist.php" || $PREV == "operacija3.php" || $PREV == "operacija4.php" ) 
				{ 
					echo $PREV; 
				} 
			   else { echo "index.php"; } ?>
			   
			   <?php 
		   } 
		   
		   if ($_POST['bendrosgautoszinutes'] == "1" && $_POST['bendrossiustoszinutes'] != "1") 
		   {
			    echo "operacija4.php";     
		   }
		   
		   if ($_POST['bendrossiustoszinutes'] == "1" && $_POST['bendrosgautoszinutes'] != "1") 
		   {
			    echo "operacija3.php";     
		   }
		   
		   ?> ><input type="button" class="v" value="Atgal į pradžią"></a> <br>  </td> </tr> </table> 
<?php
		$parode=false;   // ar pasirinkti skelbimai 
		$i = 0; 
		// nunulinam j (gal nebūtina) 
		$j = 0; 
		
        while($row = mysqli_fetch_assoc($result)) 
	{	 
		
		$fk_username = ""; // pasiziureti veliau 
		
		$result_users = mysqli_query($db, $sql_users); // restartinam $result_2 
		while($row_users = mysqli_fetch_assoc($result_users))
		{
				if($row_users['userid'] == $row['fk_userid'])
				{
					$fk_username = $row_users['username']; 
				}
		}
		

		
		$rubric=$row['rubric']; 
		$expiration_date=$row['expiration_date']; 
		$topic=$row['topic'];
		$description=$row['description'];
	  	$posterid= $row['posterid'];
		$fk_userid=$row['fk_userid']; 

		// $rodyti=(isset($_POST['placiau_'.$posterid]));
		$rodyti=(isset($_POST['placiau_'.$posterid])); 
		//$trinti=(isset($_POST['naikinti_'.$posterid]));
		//$trynimai_[]=$naikinti; 
		if ($rodyti) 
		{ 	
			?>		
    		<table class="center" border="1" cellspacing="0" cellpadding="3">
			<form name="vartotojai" action="zinute.php" method="post" <?php if(($_SESSION['ulevel'] == $user_roles["Kontrolierius"]) || ($user == $fk_username)) { echo "onsubmit=\"return confirm('Ar tikrai norite ištrinti šį skelbimą?')\""; } ?>><?php
			$parode = true; 
			
			//$keisti[]=$user;                    // cia isiminti kuriuos keiciam, ka keiciam bus irasyta i $pakeitimai
      		
			$expiration = "nebegalioja"; 
			if($expiration_date > date("Y-m-d"))
				$expiration = "galioja"; 
			
			// kiekvieną reikšmę saugome kaip masyvą 
			// echo "<input type=\"text\" name=\"ownerid[$i]\" value=$fk_userid>"; 
			// echo "<input type=\"text\" name=\"ownername[$i]\" value=$fk_username>"; 
			// echo "<input type=\"text\" name=\"posterid[$i]\" value=$posterid>"; 
				
			$result_views = mysqli_query($db, $sql_views); // restartinam $result_views 
			$insert=true; 
			
			$useridsession = $_SESSION['userid']; 
				
		    // jei neregistruotas vartotojas saugos tik jo ip adresą 
			if ($useridsession == '0')
			{ 
				$useridsession = md5($_SERVER['REMOTE_ADDR']); 
			}
			
			// jei vartotojas dar nematė skelbimo pridėsime į views lentelę 
			while($row_views = mysqli_fetch_assoc($result_views))
			{ 
				if ($useridsession == $row_views['fk_userid'] && $row['posterid']==$row_views['fk_posterid']) 
				{
					$insert=false; 
				} 
			} 
			 
			if($insert) 
			{ 
				$sql_views_ = "INSERT INTO " .TBL_VIEWS. " (fk_posterid, fk_userid)
				VALUES ('$posterid', '$useridsession')";
					// Tikriname, ar gerai prireigstruota į duomenų bazę 
				if (mysqli_query($db, $sql_views_)) 
					 {$_SESSION['message']="Registracija sėkminga";}
				else {$_SESSION['message']="DB registracijos klaida:" . $sql_views_ . "<br>" . mysqli_error($db);}
			} 
						
			// SELECT'ina TBL_VIEWS lentelę (po atnaujinimo) 
			$sql_views = "SELECT fk_posterid, fk_userid "
					. "FROM " . TBL_VIEWS . " ORDER BY fk_posterid"; 
			$result_views = mysqli_query($db, $sql_views); 
			if (!$result_views || (mysqli_num_rows($result_views) < 1)) 
			{echo "Klaida skaitant lentelę users"; exit;} 	
			
			// skaičiuoja kiek skirtingų vartotojų peržiūrėjo konkretų skelbimą 
			$result_views = mysqli_query($db, $sql_views); // restartinam $result_views 
			$j=0; 
			while($row_views = mysqli_fetch_assoc($result_views))
			{
					// atrenka skaičių kiek vartotojų peržiūrėjo skelbimą 
					if($row_views['fk_posterid'] == $row['posterid'])
					{
						$j++; 
						// $fk_username = $row_users['username']; 
					}
			} 
			
			echo "<input type=\"hidden\" name=\"ownerid\" value=$fk_userid>"; 
			echo "<input type=\"hidden\" name=\"ownername\" value=$fk_username>"; 
			echo "<input type=\"hidden\" name=\"posterid\" value=$posterid>"; 
			echo "<input type=\"hidden\" name=\"posterrubric\" value=$rubric>"; 
			// \"\" leidzia ikelti sakinius su tarpo simboliu (" ") 
			echo "<input type=\"hidden\" name=\"postertopic\" value=\"$topic\">"; 

		 	echo "<tr><td><b>Rubrika</b></td>   <td>".$rubric. "</td>";    // rodyti sia eilute patvirtinimui
			echo "<tr><td><b>Skelbimo galiojimo laikas</b></td>   <td>".$expiration_date. "</td>";    
			echo "<tr><td><b>Skelbimo statusas</b></td>   <td>".$expiration. "</td>";   
			echo "<tr><td><b>Skelbimo savininkas</b></td>   <td>".$fk_username. "</td>"; 
			echo "<tr><td><b>Tema</b></td>   <td>".$topic. "</td>";  echo "<tr><td><b>Tema</b><td>  
			<textarea id=\"zinute\" class=\"text\" cols=\"56\" rows =\"15\" name=\"zinute\" readonly>";
		    	echo $description."
				</textarea>
				</td>
				</tr>"; 
			
			if ((($user == $fk_username) && ($_SESSION['ulevel'] == $user_roles["Reg_vartotojas"])) || ($_SESSION['ulevel'] == $user_roles["Kontrolierius"])) 
			{
				echo "<input type=\"hidden\" name=\"posterrubric\" value=$rubric><tr><td><b>Peržiūrų skaičius skirtingų vartotojų</b></td>   <td>".$j. "</td>"; 
			}
			
			// $guest = "guest";
			// žinutę galima rašyti tuo atveju, jeigu tai nėra tavo paties skelbimas ir parašyti žinutę nori registruotas vartotojas 
			if (($user != $fk_username) && ($_SESSION['ulevel'] == $user_roles["Reg_vartotojas"])) 
			{
				$bendrossiustoszinutes = $_POST['bendrossiustoszinutes']; 
				
				$result_2 = mysqli_query($db, $sql); 
				while($row_extra = mysqli_fetch_assoc($result_2)) 
				{ 
				
				$j = $row_extra['posterid']; 
							
				// perrašom sesijos sąrašą "showlist" 
				$postersarray = $_POST['placiau_'.$j]; 
							
				if ($postersarray != null) 
				{ 
					echo "
					<input type=\"hidden\" name=\"placiau_".$j."\" value=$postersarray>"; 
				}
						   
				} 
				    
				echo "<tr><td><b>Rašyti žinutę</b></td>   <td>
				<input type=\"hidden\" name=\"mygtukas\" value=\"rasyti\">
				<input type=\"hidden\" name=\"bendrossiustoszinutes\" value=$bendrossiustoszinutes>
				
				<input type=\"submit\" class=\"v\" value=\"rašyti\">
				</td>"; 
			// echo "<tr><td><b>Rašyti žinutę</b></td>   <td><input type=\"submit\" name=click value=$i></td>"; 
			}
			?> 
			
			<?php 
			if(($_SESSION['ulevel'] == $user_roles["Kontrolierius"]) || ($user == $fk_username))
			{
				
				$result_2 = mysqli_query($db, $sql); 
				while($row_extra = mysqli_fetch_assoc($result_2)) 
				{ 
				
				$j = $row_extra['posterid']; 
							
				// perrašom sesijos sąrašą "showlist" 
				$postersarray = $_POST['placiau_'.$j]; 
							
				if ($postersarray != null) 
				{ 
					echo "
					<input type=\"hidden\" name=\"placiau_".$j."\" value=$postersarray>"; 
				}
						   
				} 
				    
				echo "<tr><td><b>Trinti skelbimą</b></td>   <td><input type=\"submit\" name=\"mygtukas\" class=\"v\" value=\"trinti\"></td>"; 
			} 
			
			$i++; 
			
			//echo "</td></tr>";
			//echo "<tr><td>_______________________</td></tr>";
			// form - parodo, kurie duomenis priskiarimi formai, ji tarsi objektinio tipo kintamasis, 
			// kuris stovi ima informacija is mygtuko prie kurio yra (tiksliau is paskutinio matomo formos dalyje) 
			?></form>
			
			
			 
			<form name="vartotojai" action="operacija4.php" method="post" ><?php 
			
			// REIKTŲ PADARYTI, JOG ŽINUTĘ GALĖTŲ RAŠYTI (VISŲ PIRMA ČIA - MATYTI) TIK USERIAI ! ŠIAIP YRA PADARYTA PAPRASTA VARTOTOJŲ KONTROLĖ, jeigu kokiu nors būdų
			// kokia nors role pvz kontroleirius pradetu rasyti iznute tiesiog ji ismestu is paskyros ir tiek :) 
			
			if (($user == $fk_username) && ($_SESSION['ulevel'] == $user_roles["Reg_vartotojas"])) 
			{
				
				$bendrosgautoszinutes = $_POST['bendrosgautoszinutes']; 
				
				echo "<tr><td><b>Skaityti žinutes</b></td>   <td>
				<input type=\"hidden\" name=\"mygtukas\" value=\"skaityti\">
				<input type=\"hidden\" name=\"bendrosgautoszinutes\" value=$bendrosgautoszinutes>
				<input type=\"hidden\" name=\"posterid\" value=$posterid>
				<input type=\"submit\" class=\"v\" value=\"skaityti\"></td>"; 
			// echo "<tr><td><b>Rašyti žinutę</b></td>   <td><input type=\"submit\" name=click value=$i></td>"; 
			}
			
			// pasirinktų skelbimų perdavimas per $_POST 
			
			$result_extra = mysqli_query($db, $sql); // restartinam $result_views 
			while($row_extra = mysqli_fetch_assoc($result_extra)) 
			{ 
			
			$n = $row_extra['posterid']; 
			
			// perrašom sesijos sąrašą "showlist" 
			$postersarray = $_POST['placiau_'.$n]; 
		    
			if ($postersarray != null) 
			{ 
				echo "
				<input type=\"hidden\" name=\"placiau_".$n."\" value=$postersarray>"; 
			}
			
			} 
		    ?> 
		    
			</form> 
			
			
			  </table><br><br><br><br>
    		<?php
		}

  }
  // čia pratesiama forma galėtų būti ta ilgoji 
  // jei nieko neužymėjai išmes šį pranešimą 
  if (!$parode) {
					?>
					<table class="center" style=" border-width: 2px; border-style: solid; background-color: #FFF7B7"><td style=" background-color: #996633; color: #F8E9FC; border-radius:3px 3px 3px 3px; padding: 5px 11px; display: inline-block; font-size: 12px; margin: 4px 2px; " >Nieko nepasirinkote arba jūsų pasirinktas sąrašas tuščias.</td> </table> 
					<?php 
				}
					
// pakeitimus irasysim i sesija 
//	if (empty($keisti)){header("Location:index.php");exit;}  //nieko nekeicia
		
//   $_SESSION['ka_keisti']=$keisti; $_SESSION['pakeitimai']=$pakeitimai; $_SESSION['pakeitimai_']=$pakeitimai_; 
//		$_SESSION['trynimai_']=$trynimai_; // Per čia galima daryti perkėlimą  
?>

  </body></html>
