<?php ob_start(); // turi but php prigludes?>
	<?php
	session_start(); 
	
	// pažymime kad šitas puslapis yra "buvęs puslapis"
	$_SESSION['prev'] = "proc_airlines_management_2.php";

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
		
		// pasiemimas I (retai trinamus) sesijos kintamuosius
		$_SESSION['ID_login']=$_POST['ID'];
		$_SESSION['ID_ISO_login']=$_POST['ID_ISO'];  
		$_SESSION['Name_login']=$_POST['Name']; 
	}
	else
	{
		// pasiemimas IS (retai trinamu) sesijos kintamuju
		$ID=$_SESSION['ID_login'];
		$ID_ISO=$_SESSION['ID_ISO_login'];
		$Name=$_SESSION['Name_login'];
		
	}
	
	// prisijungiame prie duomenų bazės 
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
	
	// ********************************************************************
	// čia prasideda įvestų duomenų manipuliacija, sutvarkyti pagal reikalą
	// ********************************************************************

	// išsireiškiame TBL_AIRLINES
	// $sql_airlines = "SELECT ID,Name,ID_ISO "
            // . "FROM " . TBL_AIRLINES . " ORDER BY Name";
	// $result_airlines = mysqli_query($db, $sql_airlines);
	// if (!$result_airlines || (mysqli_num_rows($result_airlines) < 1)) 
		// {echo "Klaida skaitant lentelę airlines"; exit;} 	
	
	// Įkeliame TBL_POSTERS (yra AUTOINCREMENT, tad nereikia rūpintis ID) 
	//if(checkTopic($Name) && checkdescription($Location))  
	if(checknaming($Name) && checkISO($ID_ISO))
	{
		
		// įkeliame naują oro uostą (trūksta dar avialinijų užpildymo
		// $sql = "INESRT INTO " . TBL_AIRPORTS. " SET (Name, ID_ISO, Location)
		// VALUES ('$Name', '$ID_ISO', '$Location')";
		
		// $sql = "UPDATE "
		// . TBL_AIRPORTS. 
		// " SET (Name, ID_ISO, Location) VALUES ('$Name', '$ID_ISO', '$Location') WHERE ID='$ID'";
		
		$sql = "UPDATE ". TBL_AIRLINES ." SET Name='$Name', ID_ISO='$ID_ISO' WHERE  ID='$ID'";
		
		$last_id = -1;
		
		if (mysqli_query($db, $sql))
		{
				// last id uzfiksuoja
				$last_id = mysqli_insert_id($db);
				// echo "New record created successfully. Last inserted ID is: " . $last_id;
				$_SESSION['message']="Atnaujinimas sėkmingas ";
		}
		else {$_SESSION['message']="DB atnaujinimo klaida: " . $sql . "<br>" . mysqli_error($db);}
		
		// echo " " . $_SESSION['message'];
		
		// nuoroda į index.php puslapį 
		// header('Location: proc_airlines_management.php');
		
		header('Location: airlines_management.php');
		exit; 
	}
	
	// nuoroda į index.php puslapį 
	 header('Location: airlines_management.php');
	 exit;
	 ob_end_flush();
     ?>