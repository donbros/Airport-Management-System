<?php
?>
<link href="include/styles_extra.css" rel="stylesheet" type="text/css" > 
<?php
	
    session_start();
	
	$PREV = $_SESSION['prev']; 
	
	$_SESSION['prev'] = "proc_airlines_management.php"; // kazkoks sesijos susiejiklis 
	
	// 1. apsauga nuo pakartotinio duomenų rodymo (po paspaudimo "Vykdyti" (jis atstovauja kaip koks žymeklis) duomenys overwritinami); 
	// 2. priskiriame rezultatus sesijai, kad paspaudę grįžti atgal matytume tuos pačius rezultatus !!!!!!!!!!!!!!!!!!!!!!!!!!!!!! 
	// $_SESSION['showlist'] - kintmasis po šio if'o atsakingas už pasirinktų skelbimų rodymą 
	if($_POST!=null && isset($_POST['Vykdyti'])) { unset($_POST['Vykdyti']); $_SESSION['showlist']=$_POST; }
	// echo "good|".$_POST['placiau_'.$numberrr]."|"; 
	// po patikros unsetina mygtuko vardu "Vykdyti" reikšmę 
	
	include("include/settingsdb.php");
	include("include/functions.php");

	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
	
	
	$sql_countries = "SELECT ID_ISO,Name "
           . "FROM " . TBL_COUNTRIES . " ORDER BY Name ASC"; 
	$result_countries = mysqli_query($db, $sql_countries);
	if (!$result_countries || (mysqli_num_rows($result_countries) < 1)) 
		{echo "Klaida skaitant lentelę airports"; exit;}
		
	$sql_airlines = "SELECT ID,Name,ID_ISO "
			. "FROM " . TBL_AIRLINES . " ORDER BY Name";
	$result_airlines = mysqli_query($db, $sql_airlines);
	if (!$result_airlines || (mysqli_num_rows($result_airlines) < 1)) 
		{echo "Klaida skaitant lentelę airlines"; exit;} 
	
	
	// IDOMUS FAKTAS: jei i echo idedi pilna $_POST arba $_SESSION ir t.t. ji parodo kaip kintamaji 
	
?>

<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Redaguojama Pairinkta Oro Linija</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
    </head>
    <body>
        <table class="center" ><tr><td>
            <center><img src="include/top.png"></center>
			</td></tr><tr><td><center><font size="5">PASIRINKTOS ORO LINIJOS REDAGAVIMAS</font></center></td></tr></table> <br>
		<!-- čia galima padėti formą ir bandyti daryti daugybinimą trynimą --> 
		
		<table class="meniu" style="width:47%; border-width: 2px; border-style: solid; margin-left:auto; margin-right:auto;"><tr><td width="50%" >

		   <center> <a href=<?php 
		   
		    echo "airlines_management.php";
		   
		   ?> ><input type="button" class="v" value="Atgal į pradžią"></a></center> </td> </tr> </table> 
		<?php
		$parode=false;   // ar pasirinkti skelbimai 
		$i = 0; 
		// nunulinam j (gal nebūtina) 
		$j = 0; 
		
		// šalių sudėjimas į masyvus iš duomenų bazės, kad nereiktų išsikvietinėti
		$row_countries_id = array();
		$row_countries_name = array();
		$k=0;
		while($row_countries = mysqli_fetch_assoc($result_countries))
		{
			 $row_countries_id[$k] = $row_countries['ID_ISO'];
			 $row_countries_name[$k] = $row_countries['Name'];
			 $k++;
		}
		
		$trinti = false;
		
        while($row_airlines = mysqli_fetch_assoc($result_airlines)) 
		{	 
		
		$ID= $row_airlines['ID'];
		$ID_ISO=$row_airlines['ID_ISO']; 
		$Name=$row_airlines['Name'];
			if ($ifThereIsIDs=(isset($_POST['placiau_'.$ID]))) // vėliau paiminėsime tik vieną ID
			{ 	
				?>		
				<table class="center" border="1" cellspacing="0" cellpadding="3">
				<?php // veiksmą suredaguoti čia ?>
				
				<form action="proc_airlines_management_2.php" method="POST" class="login" name="add_name" id="add_name" <?php ?>><?php
				//if(($_SESSION['ulevel'] == $user_roles["Kontrolierius"]) || ($user == $fk_username)) { echo "onsubmit=\"return confirm('Ar tikrai norite ištrinti šį skelbimą?')\""; } 
				$parode = true; 
				
				//$keisti[]=$user;                    // cia isiminti kuriuos keiciam, ka keiciam bus irasyta i $pakeitimai
				
				//$expiration = "nebegalioja"; 
				//if($Location > date("Y-m-d"))
				//	$expiration = "galioja"; 
				
				// kiekvieną reikšmę saugome kaip masyvą 
				// echo "<input type=\"text\" name=\"ownerid[$i]\" value=$fk_userid>"; 
				// echo "<input type=\"text\" name=\"ownername[$i]\" value=$fk_username>"; 
				// echo "<input type=\"text\" name=\"posterid[$i]\" value=$posterid>"; 
					
				$insert=true; 
				
				//-------------------------------------------------------------------------
				// Lentelės Pildymas
				//-------------------------------------------------------------------------
				//echo $Name . " | ;";
				
				$NameToInsert=explode(" ",$Name); $NameToInsert2=implode("&#160",$NameToInsert); // sutvarkome kad idėtų pilną pavadinimą 
				echo "<input type=\"hidden\" name=\"ID\" value=".$ID.">";
				echo "<tr><td><b>Pavadinimas</b></td>   <td><input type=\"text\" name=\"Name\" value=" . $NameToInsert2. "></td>";
				echo "<tr><td><b>Šalys</b></td><td>";
				//echo "<select name=\"ID_ISO".$Name."\">";
				echo "<select name=\"ID_ISO\">";
				for($i=0; $i<count($row_countries_id); $i++)
				{	
					echo "<option ";
					if ($row_countries_id[$i] == $row_airlines['ID_ISO']) {
						echo "selected ";
				}
				echo "value=\"".$row_countries_id[$i]."\" ";
				echo ">".$row_countries_name[$i]."</option>";
				} echo "</select></td>
				";
				
				echo "<tr><td><b>Atnaujinti</b></td>";
				echo "<td><input type=\"submit\" name=\"submit\"
				id=\"submit\" class=\"v\" value=\"atnaujinti informaciją\"></td>"; 
				
				$i++; 
				
				// form - parodo, kurie duomenis priskiarimi formai, ji tarsi objektinio tipo kintamasis, 
				// kuris stovi ima informacija is mygtuko prie kurio yra (tiksliau is paskutinio matomo formos dalyje) 
				?>
				</form>
				</table><br><br><br><br>
				<?php
			}
			if($ifThereIsIDs=(isset($_POST['naikinti_'.$ID])))
			{
				
				$trinti = true;
				
				$trynimai_[] = $_POST['naikinti_'.$ID]; // nebutinas sitas tik checkbox boolean rodo
				$trynimaiID_[] = $ID;
				$trynimaiName_[] = $Name;
				$trynimaiIDasd[]=$ID;
				
				// $keisti[]=$user;                    // cia isiminti kuriuos keiciam, ka keiciam bus irasyta i $pakeitimai
				
				// echo "<tr><td>".$user. "</td><td>";    // rodyti sia eilute patvirtinimui
				
				// // spausdins "Užblokuotas" arba atitinkamą buvusią rolę 
				// if ($level == UZBLOKUOTAS) 
				// {
					// echo "Užblokuotas";
				// }
				// else
				// {
					// foreach($user_roles as $x=>$x_value)
					// {
						// if ($x_value == $level) 
							// echo $x;
					// }
				// }
			 
				// echo "</td><td>";

				// // jei nusprendeme trinti vartotoja (primenybe trynimui) 
				// if ($naikinti)
				// {      
					// $trynimai_[]=$naikinti; 
					// echo "<font color=red>PAŠALINTI</color>";
					// //$pakeitimai[]=-1; // ir isiminti  kad salinam
					// // $pakeitimai[]=$nlevel;    // isiminti i kokia role
					// $naikpoz=true;
				// }		 
				// else 
				// {      
					// $pakeitimai[]=$nlevel;    // isiminti i kokia role
					// if ($nlevel == UZBLOKUOTAS) echo "UŽBLOKUOTAS";
					// else
					// {
						// foreach($user_roles as $x=>$x_value)
						// {
							// if ($x_value == $nlevel) echo $x;
						// }
					// }
				// }

				// if($npost!='1')
					// $npost='0'; 
				
				// echo "</td><td>";
				// echo $post; // testavimas ********************** 
				// echo "</td><td>";
				// echo $npost; // testavimas ********************** 

				// $pakeitimai_[]=$npost;
		
				// echo "</td></tr>";
			}
  }
  
  // jeigu kažkas turi priskirtą avialiniją neleisti jos ištrinti kol neišregistruos jos
  if($trinti)
  {?>
	  <table class="center" border="1" cellspacing="0" cellpadding="12">
	  <?php $tarpas = ""; 
		// echo "<tr><td>".$tarpas. "</td>";
		// echo "<td>" . $tarpas."</td>";
		// echo "</tr>"; 
		?>
		<tr><th><b>Nr.</b></th>
		<th><b>Oro linijos pavadinimas</b></th>
		<th><b>Informacija</b></th>
		<?php //if(($_SESSION['ulevel'] == $user_roles["Kontrolierius"])||($_SESSION['ulevel'] == $user_roles["Reg_vartotojas"])) { ?>
		<!-- <td><b>Naikinti</b></td> --> 
		<?php //} ?> 
		</tr>
		<?php
	  
		//echo $trynimaiid_[0];
		$i = 0; 
		// echo "TRINAMOS AVIALINIJOS (-A): \r\n";
		
		$sql_airlines = "SELECT ID,Name,ID_ISO "
				. "FROM " . TBL_AIRLINES . " ORDER BY Name";
		$result_airlines = mysqli_query($db, $sql_airlines);
		if (!$result_airlines || (mysqli_num_rows($result_airlines) < 1)) 
			{echo "Klaida skaitant lentelę airlines"; exit;} 
		
		
			foreach ($trynimaiID_ as $IDD)
			{	
			
			$sql_airports_airlines_relations = "SELECT ID_Airports,ID_Airlines "
				. "FROM " . TBL_AIRPORTS_AIRLINES_RELATIONS . " ORDER BY ID_Airports";
				$result_airports_airlines_relations = mysqli_query($db, $sql_airports_airlines_relations);
				if (!$result_airports_airlines_relations || (mysqli_num_rows($result_airports_airlines_relations) < 1)) 
					{echo "Klaida skaitant lentelę airlines"; exit;} 
			
				$trintiNegalima = false;
				while($row_aar = mysqli_fetch_assoc($result_airports_airlines_relations))
					{
						if($row_aar['ID_Airlines'] == $IDD)
						{
							// ----------------- Testavimas --------------------	
							// echo " " . $IDD . " - " . $row_aar['ID_Airports'] ."\\";
							// ----------------- Testavimas --------------------	
							
							$trintiNegalima = true;
							
							// $sql__ = "DELETE FROM ". TBL_AIRPORTS_AIRLINES_RELATIONS. "  WHERE  ID_Airports='$IDD'";
							// if (!mysqli_query($db, $sql__)) {
								// echo " DB klaida šalinant vartotoją: " . $sql__ . "<br>" . mysqli_error($db);
							// exit;}
						}
					}	
				echo "<tr>";
		
				echo "<td>" . $i. "</td>"; 
				echo "<td>" . $trynimaiName_[$i++] . "</td>"; 
				
				if ($trintiNegalima == false) // apsauga, kad nebūtu trinama tol kol yra susieta (kitaip sakant atsiejimas tik per oro uostą) 
				{
					$sql_airlines = "DELETE FROM ". TBL_AIRLINES. "  WHERE  ID='$IDD'";
					if (!mysqli_query($db, $sql_airlines)) {
						echo " DB klaida šalinant vartotoją: " . $sql_airlines . "<br>" . mysqli_error($db);
					exit;}
					echo "<td>" . "Ištrinta." . "</td>";
				}
				else
					echo "<td>" . "Trinti neleistina (oro linija priklauso bent 1 oro uostui)." . "</td>";
				
				echo" </tr>"; 
				}
			?> </table> <?php
					
  }
  
  // čia pratesiama forma galėtų būti ta ilgoji 
  // jei nieko neužymėjai išmes šį pranešimą 
  if (!$parode && !$trinti) {
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
