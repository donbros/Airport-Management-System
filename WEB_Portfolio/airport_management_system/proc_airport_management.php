<?php

	// DESCRIPTION
	// Pasirinktas oro uostas. Oro uostą galima pilnai ir patogiai redaguoti. Atlikus redagavimą reikiama
	// spausti "atnaujinti informaciją" ir duomenys bus atnaujinti ir parodomi

	// REVISIT (jei kada grįžčiau apsižiūrėti kodo): 
	// 1. paruošiau pavyzdį kaip HTML konvertuoti php aplinkoje
    // 2. yra pavaizduotas alternatyvus HTML separatorius (" "), kuris yra - &#160
	
	session_start();
	?> 
	<link href="include/styles_extra.css" rel="stylesheet" type="text/css" > 
	<?php
	$PREV = $_SESSION['prev']; 
	
	$_SESSION['prev'] = "proc_airport_management.php"; // kazkoks sesijos susiejiklis 
	
	// 1. apsauga nuo pakartotinio duomenų rodymo (po paspaudimo "Vykdyti" (jis atstovauja kaip koks žymeklis) duomenys overwritinami); 
	// 2. priskiriame rezultatus sesijai, kad paspaudę grįžti atgal matytume tuos pačius rezultatus !!!!!!!!!!!!!!!!!!!!!!!!!!!!!! 
	// $_SESSION['showlist'] - kintmasis po šio if'o atsakingas už pasirinktų skelbimų rodymą 
	if($_POST!=null && isset($_POST['Vykdyti'])) { unset($_POST['Vykdyti']); $_SESSION['showlist']=$_POST; }
	
	// įtraukiami standartiniai php operaciniai puslapiai
	include("include/settingsdb.php");
	include("include/functions.php");

	// Duomenų bazių paruošimo dalis
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 

	$sql_airports = "SELECT ID,Name,ID_ISO,Location "
			. "FROM " . TBL_AIRPORTS . " ORDER BY ID ASC"; 
	$result_airports = mysqli_query($db, $sql_airports);
	if (!$result_airports || (mysqli_num_rows($result_airports) < 1)) 
		{echo "Klaida skaitant lentelę airports"; exit;}
			
	$sql_countries = "SELECT ID_ISO,Name "
           . "FROM " . TBL_COUNTRIES . " ORDER BY Name ASC"; 
	$result_countries = mysqli_query($db, $sql_countries);
	if (!$result_countries || (mysqli_num_rows($result_countries) < 1)) 
		{echo "Klaida skaitant lentelę airports"; exit;}
				
	$sql_airports_airlines_relations = "SELECT ID_Airports,ID_Airlines "
			. "FROM " . TBL_AIRPORTS_AIRLINES_RELATIONS . " ORDER BY ID_Airports";
	$result_airports_airlines_relations = mysqli_query($db, $sql_airports_airlines_relations);
	if (!$result_airports_airlines_relations || (mysqli_num_rows($result_airports_airlines_relations) < 1)) 
		{echo "Klaida skaitant lentelę airlines"; exit;} 
			
	$sql_airlines = "SELECT ID,Name,ID_ISO "
			. "FROM " . TBL_AIRLINES . " ORDER BY Name";
	$result_airlines = mysqli_query($db, $sql_airlines);
	if (!$result_airlines || (mysqli_num_rows($result_airlines) < 1)) 
		{echo "Klaida skaitant lentelę airlines"; exit;} 
?>

<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Redaguojamas Pasirinktas Oro Uostas (-ai)</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
		<!-- pridedami reikalingi scrptai kitiems scriptams -->
		<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <!-- Google žemėlapio script'as -->
	<script>
				var map;

				//var name='<?php echo $name; ?>';

				function initialize() {
					var mapOptions = {
						zoom: 12,
						center: new google.maps.LatLng(document.getElementById('latMap_1').value, document.getElementById('lngMap_1').value),
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					map = new google.maps.Map(document.getElementById('map_canvas'),
						mapOptions
					);
					google.maps.event.addListener(map, 'click', function(event) {
						document.getElementById('latMap').value = event.latLng.lat();
						document.getElementById('lngMap').value = event.latLng.lng();
					});
				}
				function mapDivClicked (event) {
					var target = document.getElementById('map_canvas'),
						posx = event.pageX - target.offsetLeft,
						posy = event.pageY - target.offsetTop,
						bounds = map.getBounds(),
						neLatlng = bounds.getNorthEast(),
						swLatlng = bounds.getSouthWest(),
						startLat = neLatlng.lat(),
						endLng = neLatlng.lng(),
						endLat = swLatlng.lat(),
						startLng = swLatlng.lng();

					document.getElementById('posX').value = posx;
					document.getElementById('posY').value = posy;
					// suapvalinimui skirti kitamieji
					var lat = startLat + ((posy/350) * (endLat - startLat));
					var lng = startLng + ((posx/500) * (endLng - startLng));
					document.getElementById('lat').value = lat.toFixed(4);
					document.getElementById('lng').value = lng.toFixed(4);
				}
				google.maps.event.addDomListener(window, 'load', initialize);
		</script>	
	</head>
    <body>
        <table class="center" ><tr><td>
            <center><img src="include/top.png"></center>
			</td></tr><tr><td><center><font size="5">PASIRINKTO ORO UOSTO REDAGAVIMAS</font></center></td></tr></table> <br>
		<!-- čia galima padėti formą ir bandyti daryti daugybinimą trynimą --> 
		
		<table class="meniu" style="width:47%; border-width: 2px; border-style: solid; margin-left:auto; margin-right:auto;"><tr><td width="50%" >

		   <center> <a href=<?php 
		   
		    echo "airport_management.php";
		   
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
		
        while($row_airports = mysqli_fetch_assoc($result_airports)) 
		{	 
			
			$ID= $row_airports['ID'];
			$ID_ISO=$row_airports['ID_ISO']; 
			$Location=$row_airports['Location']; 
			$Name=$row_airports['Name'];
			
			// --------------- TESTING -----------------
			// echo $Name . " - " . $_POST['naikinti_'.$ID] . ";  ";
			// --------------- TESTING -------------------
		
			//$trinti=(isset($_POST['naikinti_'.$posterid]));
			//$trynimai_[]=$naikinti; 
			
			if ($ifThereIsIDs=(isset($_POST['placiau_'.$ID]))) // vėliau paiminėsime tik vieną ID
			{ 	
				?>		
				<form action="proc_airport_management_2.php" method="POST" class="login" name="add_name" id="add_name" >
				<table class="center" border="1" cellspacing="0" cellpadding="3">
				<?php // veiksmą suredaguoti čia ?>
				<?php
				
				$parode = true; 
				
				$insert=true; 
				
				$NameToInsert=explode(" ",$Name); $NameToInsert2=implode("&#160",$NameToInsert); // sutvarkome kad idėtų pilną pavadinimą 
				echo "<input type=\"hidden\" name=\"ID\" value=".$ID.">";
				echo "<tr><td><b>Pavadinimas</b></td>   <td><input maxlength=\"32\" type=\"text\" name=\"Name\" value=" . $NameToInsert2. "></td>";
				echo "<tr><td><b>Šalys</b></td><td>";
				//echo "<select name=\"ID_ISO".$Name."\">";
				echo "<select name=\"ID_ISO\">";
				for($i=0; $i<count($row_countries_id); $i++)
				{	
					echo "<option ";
					if ($row_countries_id[$i] == $row_airports['ID_ISO']) {
						echo "selected ";
				}
				echo "value=\"".$row_countries_id[$i]."\" ";
				echo ">".$row_countries_name[$i]."</option>";
				} echo "</select></td>
				";
				
				$LocationSeparate = explode(" ", $Location); // išskiria į masyvą pagal tarpo simbolį
				
				echo "<input type=\"hidden\" value= " . $LocationSeparate[0] . " id=\"latMap_1\" name=\"latMap_1\" />";
				
				echo "<input type=\"hidden\" value= " . $LocationSeparate[1] . " id=\"lngMap_1\" />";
				
				echo"";
				
				echo "<tr><td><b>Lokacija žemėlapyje</b><td>  
				<div id=\"map_canvas\" onclick=\"mapDivClicked(event);\" style=\"height: 350px; width: 500px;  margin:0 auto;\"></div>
				<input type=\"hidden\" id=\"posX\" /><input type=\"hidden\" id=\"posY\" />
				platuma: <input value= " . $LocationSeparate[0] . " id=\"lat\" name=\"lat\" placeholder=\"platumos koordinatės\"/>		
				Ilguma: <input value= " . $LocationSeparate[1] . " id=\"lng\" name=\"lng\" placeholder=\"ilgumos koordinates\"/><div/>
				<input type=\"hidden\" id=\"latMap\" />
				<input type=\"hidden\" id=\"lngMap\" />
				";
				echo $description."
				</textarea>
				</td>
				</tr> "; 
				
				// ORO LINIJOS
				// RELOADINAME, nes kitaip cikle neveikia (arba sugalvoti kokį apėjimą nes dabar du kartus kreipiasi į duombazę)
				$result_airports_airlines_relations = mysqli_query($db, $sql_airports_airlines_relations); if (!$result_airports_airlines_relations || (mysqli_num_rows($result_airports_airlines_relations) < 1)) {echo "Klaida skaitant lentelę airlines"; exit;} 
				$airlines_array = array(); // masyvas / dinaminis valdiklis nurodantis kokios prikauso avialinijos
				$counter_airlines_array_ID=0;
				while($row_3 = mysqli_fetch_assoc($result_airports_airlines_relations))
				{
					if($row_3['ID_Airports'] == $ID)
					{
						$airlines_array[$counter_airlines_array_ID++] = $row_3['ID_Airlines'];
					}
				}	
				
				$airlines_name_array = array();
				$result_airlines = mysqli_query($db, $sql_airlines); // reloadiname duombazę
				$length=count($airlines_array);
				
				$counter_airlines_name_array=0;
				while($row_4 = mysqli_fetch_assoc($result_airlines))
				{
					for($w = 0; $w < $length; $w++)
					{
						if($airlines_array[$w]==$row_4['ID'])
						{
							$airlines_name_array[$counter_airlines_name_array++] =$row_4['Name'];
						}
					}
				}
				
				$result_airlines = mysqli_query($db, $sql_airlines); // reloadiname duombazę
				
				// --------------- TESTING -----------------
				// echo "1-as array ";
				// for($i=0; $i<$length; $i++)
				// {
					// echo $airlines_array[$i] . " | ";
				// }
				// echo count($airlines_array) . " \\\ ";
				// ------------- TESTING END ----------------
				
				// dynamic_field - table klasės id, jis yra susietas su jQuery script'u, kad veiktų pridėjimas
				echo "<tr><td><b>Oro Linijos</b></td>";
				?> <td><table class="table table-bordered" id="dynamic_field"> <?php
						echo "<tr>";
						//echo "<select name=\"airline[]\" id=\"airline\">";
						while($row=mysqli_fetch_array($result_airlines))
						{
							$emparray[] = $row;
							for($i=0; $i<count($airlines_array); $i++)
								if($airlines_array[$i]==$row['ID'])
									$airlines_array_aar[] = $row;
						}; 
						//echo "</select>";
				echo "</td>"; ?>
				<td><button type="button" id="add" class="btn btn-success">Pridėti</td> <?php
				echo "</tr></table></td></tr>";
				
				// --------------- TESTING -----------------
				//echo "<tr><td>Information regarding airlines</td><td>masyvo dydis (likęs): " . count($airlines_array) . "; pasirinktas id pavaizduoti: " . $chosen_id. ";</td></tr>";    // rodyti sia eilute patvirtinimui
				// ------------- TESTING END ----------------
				
				// --------------- TESTING -----------------
				// echo "likęs array ";
				// for($i=0; $i<$length; $i++)
					// echo $airlines_array[$i] . " | ";
				// ------------- TESTING END ----------------
				
				?>
				
				<script>
				// šis jQuery script išspausdina reikiamą kiekį parinkčių (angl. option) ir parenka tas parintis, kurias konkretus objektas turi
				// konkrečiau - jis išspausdina reikiama kiekį oro linijų (angl. airlines) ir parenkta tas oro linijas vaizduoti pirma, kurios priklauso oro uostuia
				$(document).ready(function(){
					var i = 0;
					
					// [išsireiškiame masyvą 'jquery' priimtinu būdu]
					var allAirlines = <?php echo json_encode($emparray); ?>;
					var foundAirlines = <?php 
					if(isset($airlines_array_aar))
						echo json_encode($airlines_array_aar); 
					else
						echo json_encode(0);
					?>;
					
					// jeigu ne tuščias
					if(foundAirlines.length!==0)
					{
						$.map(foundAirlines, function(keyy,valuee){ 
							i++;
							$('#dynamic_field').append(
							'<tr id="row'+i+'"><td>'+
							'<select name="airline[]" id="aairline" >'+
							'<option value="-1">---</option>'+
							$.map(allAirlines, function(key,value){
								if(key[0] === keyy[0])
								{
									return ('<option ' +
												' selected ' +
												'value=' + key[0] +'>'+key[1]+'</option>' );
								}
								else
								{
									return ('<option ' +
											'value=' + key[0] +'>'+key[1]+'</option>' );
								}
								})+
							'</select></td>'+
							'<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>'
							)
						});
					}
					// pridėjimo galimybė
					$('#add').click(function(){
						i++;
						var allAirlines2 = <?php echo json_encode($emparray); ?>;
						
						// dinamiškai pridedamos oro linijos (airlines)
						$('#dynamic_field').append(
						'<tr id="row'+i+'"><td>'+
						'<select name="airline[]" id="aairline" >'+
						'<option value="-1">---</option>'+
						$.map(allAirlines2, function(key,value){ return '<option value=' + key[0] +'>'+key[1]+'</option>' })+
						'</select></td>'+
						'<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>'
						);
					});
					// trynimo galimybė
					$(document).on('click', '.btn_remove', function(){
						var button_id = $(this).attr("id");
						$('#row'+button_id+'').remove();
					});
					// siuntimo/ikelimo funkcija
					$('#submit').click(function(){
						$.ajax({
						url:"proc_airport_management_2.php",
						method:"POST",
						data:$('#add_name').serialize(),
						success:function(data)
						{
							// lentelė print
							// alert(data);
							// refreshinu, kad laukeliai suveiktų
							// čia reik reloado nes palieka senuosius neteisingus duomenis įrašytus matyt dėl session, tai jei nenoriu tvarkyti ju refresh greit stuvarko
							location.reload();
							$('#add_name')[0].reset();
						}
						});
					});
				});
				</script>
				<?php
				
				// --------------- TESTING -----------------
				// echo "<tr><td>Airlines</td><td>" . implode(", ",$airlines_name_array) . "</td></tr>";    // rodyti sia eilute patvirtinimui
				// ------------- TESTING END ----------------
				
				echo "<tr><td><b>Atnaujinti</b></td>";
				?>
				<td><input type="button" name="submit" id="submit" class="v" value="atnaujinti informaciją"></td> 
				
				<?php
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
				
				/* $keisti[]=$user;                    // cia isiminti kuriuos keiciam, ka keiciam bus irasyta i $pakeitimai
				
				echo "<tr><td>".$user. "</td><td>";    // rodyti sia eilute patvirtinimui
				
				// spausdins "Užblokuotas" arba atitinkamą buvusią rolę 
				if ($level == UZBLOKUOTAS) 
				{
					echo "Užblokuotas";
				}
				else
				{
					foreach($user_roles as $x=>$x_value)
					{
						if ($x_value == $level) 
							echo $x;
					}
				}
			 
				echo "</td><td>";

				// jei nusprendeme trinti vartotoja (primenybe trynimui) 
				if ($naikinti)
				{      
					$trynimai_[]=$naikinti; 
					echo "<font color=red>PAŠALINTI</color>";
					//$pakeitimai[]=-1; // ir isiminti  kad salinam
					// $pakeitimai[]=$nlevel;    // isiminti i kokia role
					$naikpoz=true;
				}		 
				else 
				{      
					$pakeitimai[]=$nlevel;    // isiminti i kokia role
					if ($nlevel == UZBLOKUOTAS) echo "UŽBLOKUOTAS";
					else
					{
						foreach($user_roles as $x=>$x_value)
						{
							if ($x_value == $nlevel) echo $x;
						}
					}
				}

				if($npost!='1')
					$npost='0'; 
				
				echo "</td><td>";
				echo $post; // testavimas ********************** 
				echo "</td><td>";
				echo $npost; // testavimas ********************** 
				$pakeitimai_[]=$npost;
				echo "</td></tr>";*/
				
			}
	}
  
	if($trinti)
	{
	  ?>
	  <table class="center" border="1" cellspacing="0" cellpadding="12">
	  <?php $tarpas = ""; ?>
		<tr><th><b>Nr.</b></th>
		<th><b>Oro linijos pavadinimas</b></th>
		<th><b>Ištrintų susietų oro linijų ID</b></th>
		</tr>
		<?php
		
		$i = 0; 
		foreach ($trynimaiID_ as $IDD)
		{
			$sql_airports_airlines_relations = "SELECT ID_Airports,ID_Airlines "
				. "FROM " . TBL_AIRPORTS_AIRLINES_RELATIONS . " ORDER BY ID_Airports";
			$result_airports_airlines_relations = mysqli_query($db, $sql_airports_airlines_relations);
			if (!$result_airports_airlines_relations || (mysqli_num_rows($result_airports_airlines_relations) < 1)) 
				{echo "Klaida skaitant lentelę airlines"; exit;} 
			
			echo "<tr>";
			
			echo "<td>" . $i . "</td>"; 
			echo "<td>" . $trynimaiName_[$i] . "</td>"; 
			echo "<td>";
			// jei netyčia nefetchintu reikia retartinti sita
			while($row_aar = mysqli_fetch_assoc($result_airports_airlines_relations))
				{
					if($row_aar['ID_Airports'] == $IDD)
					{
						echo $row_aar['ID_Airlines'] . "; ";
						
						// ----------------- Testavimas --------------------	
						// echo " " . $IDD . " - " . $row_aar['ID_Airports'] ."\\";
						// ----------------- Testavimas --------------------	
						
						$sql__ = "DELETE FROM ". TBL_AIRPORTS_AIRLINES_RELATIONS. "  WHERE  ID_Airports='$IDD'";
						if (!mysqli_query($db, $sql__)) {
							echo " DB klaida šalinant vartotoją: " . $sql__ . "<br>" . mysqli_error($db);
						exit;}
					}
				}	
			echo "</td>"; 
			echo " </tr>"; 
			
			// ----------------- Testavimas --------------------	
			// echo " NAUJAS: ";
			// echo $trynimaiIDasd[$i++] . "| ";
			// echo $trynimaiName_[$i++] . "; ";
			// ----------------- Testavimas --------------------
			
			$sql_Airports = "DELETE FROM ". TBL_AIRPORTS. "  WHERE  ID='$IDD'";
			if (!mysqli_query($db, $sql_Airports)) {
				echo " DB klaida šalinant vartotoją: " . $sql_Airports . "<br>" . mysqli_error($db);
			exit;}
			$i++;
		}
	   ?> </table> <?php				
	}
  
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
