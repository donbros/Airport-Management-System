<?php ob_start(); ?>
	<?php
	session_start(); 
	
	$_SESSION['prev'] = "proc_airport_management_2.php"; // pažymime kad šitas puslapis yra "buvęs puslapis"

	include("include/settingsdb.php");
	include("include/functions.php");
 
	$_SESSION['ID_ISO_error']=""; 
	$_SESSION['Name_error']=""; 
	$_SESSION['Location_error']=""; 
	
	// galime tikrinimą pagal ID_ISO daryti, nes vis tiek jį visada įvedinėja 
	if(isset($_POST['Name']))
	{
		$ID=$_POST['ID'];
		$ID_ISO=$_POST['ID_ISO'];
		$Name=$_POST['Name'];//$_SESSION['surname_login']=$surname;
		$Location=$_POST['lat'] . ' ' . $_POST['lng'];//$_SESSION['realname_login']=$realname; // naujas !!!!!!!! 
		
		// pasiemimas I (retai trinamus) sesijos kintamuosius
		$_SESSION['ID_ISO_login']=$_POST['ID_ISO'];  
		$_SESSION['Name_login']=$_POST['Name']; 
		$_SESSION['Location_login']=$Location;
	}
	else
	{
		// pasiemimas IS (retai trinamu) sesijos kintamuju
		$ID=$_SESSION['ID_login'];
		$ID_ISO=$_SESSION['ID_ISO_login'];
		$Name=$_SESSION['Name_login'];
		$Location=$_SESSION['Location_login']; 
	}
	
	// prisijungiame prie duomenų bazės 
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
	
	if(checknaming($Name) && checkISO($ID_ISO) && checkLocation($_POST['lat'], $_POST['lng']))
	{
		
		// įkeliame naują oro uostą (trūksta dar avialinijų užpildymo
		// $sql = "INESRT INTO " . TBL_AIRPORTS. " SET (Name, ID_ISO, Location)
		// VALUES ('$Name', '$ID_ISO', '$Location')";
		
		// $sql = "UPDATE "
		// . TBL_AIRPORTS. 
		// " SET (Name, ID_ISO, Location) VALUES ('$Name', '$ID_ISO', '$Location') WHERE ID='$ID'";
		
		$sql = "UPDATE ". TBL_AIRPORTS ." SET Name='$Name', Location='$Location', ID_ISO='$ID_ISO' WHERE  ID='$ID'";
		
		if (mysqli_query($db, $sql))
		{
				$_SESSION['message']="Atnaujinimas sėkmingas ";
		}
		else {$_SESSION['message']="DB atnaujinimo klaida: " . $sql . "<br>" . mysqli_error($db);}
		
		// AIRLINES ATNAUJINIMAS
		$number = count($_POST["airline"]);
		
		// --------------- TESTING -----------------
		// echo "count: " . $number . "; ";
		// ------------- TESTING END ----------------
		
		// dabar naujinsime relations
		
		// tikrina ar įvesta bent viena "oro_linija"
			// ištriname visus susijusius
			for($i=0; $i<$number; $i++)
			{
				$index = $_POST["airline"][$i];
				if(-1!=$_POST["airline"][$i])
				{
					// --------------- TESTING -----------------
					// echo "IDAIRPORT = " . $ID . " IDAIRLINE = " . $_POST["airline"][$i] ."| ";
					// ------------- TESTING END ----------------
					
					$sql_airports_airlines_relations = "SELECT ID_Airports,ID_Airlines "
							. "FROM " . TBL_AIRPORTS_AIRLINES_RELATIONS . " ORDER BY ID_Airports";
					$result_airports_airlines_relations = mysqli_query($db, $sql_airports_airlines_relations);
					if (!$result_airports_airlines_relations || (mysqli_num_rows($result_airports_airlines_relations) < 1)) 
						{echo "Klaida skaitant lentelę airlines"; exit;} 
					
					while($row_3 = mysqli_fetch_assoc($result_airports_airlines_relations))
					{
						// --------------- TESTING -----------------
						// echo " IDAIRPORT = " . $row_3['ID_Airports'] . " IDAIRLINE = " . $row_3['ID_Airlines'] ." ";
						// ------------- TESTING END ----------------
						
						
						if(($row_3['ID_Airports'] == $ID)
							//&& ($row_3['ID_Airlines'] == $index)
						)
						{
							$sql__ = "DELETE FROM ". TBL_AIRPORTS_AIRLINES_RELATIONS. "  WHERE  ID_Airports='$ID'";
							if (!mysqli_query($db, $sql__)) {
								echo " DB klaida šalinant vartotoją: " . $sql__ . "<br>" . mysqli_error($db);
							exit;}
						}
					}	
				}
			}
			// pridedame visus naujus
			for($i=0; $i<$number; $i++)
			{
				$index = $_POST["airline"][$i];
				if(-1!=$_POST["airline"][$i])
				{
						// --------------- TESTING -----------------
						// echo " IDport=" . $ID . " - IDline" . $index ." ";
						// ------------- TESTING END ----------------
						
						// tada viska kas nesikartoja ikeliame
						$sql = "INSERT INTO " . TBL_AIRPORTS_AIRLINES_RELATIONS . " (ID_Airports, ID_Airlines)
							VALUES ('$ID', '$index')";
						
						if (mysqli_query($db, $sql))
						{
								$_SESSION['message']="Registracija sėkminga";
						}
						else {$_SESSION['message']="DB registracijos klaida:" . $sql . "<br>" . mysqli_error($db);}
				}
			}	
			echo 'Duomenys ką tik atnaujinti.';
		
		
		// nuoroda į index.php puslapį 
		// header("Location:index.php");
		header('Location: airport_management.php');
		exit; 
	}
	else
	{
		echo "klaida";
	}
	
	// nuoroda į index.php puslapį 
	 header('Location: proc_airport_management.php');
	 exit;
	 ob_end_flush();
     ?>