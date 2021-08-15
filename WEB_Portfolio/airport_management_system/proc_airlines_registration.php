<?php
ob_start();
?>
<?php

session_start(); 
	
// REIKIA ISSIREIKSTI AVIALINIJAS!
// Oro uosto pavadinimas: tikrinam ar ivesta; netikrinam vienodumo
// Šalis: view dalyje turi būti PATEIKTAS iš kurio turėtumėte rinktis šalį; pasirinkus ją, o tiksliau jos kodą, tą kodą perkeliame prie įvestos informacijos;
// Lokacija: tikrinam ar ivesta; netikrinam vienodumo; turim kažkaip "paimti" informaciją ir ją perkelti į duomenų bazę (kol kas ilguma ir platuma atskirtos, reiktų įkelti kartu su separatorium, turbūt " ");
// Avialinijos: prisaikdiname atskirame table - viskas ten daroma paieškos forma. Reikia visą tą sarašiuką sumesti į atskirą table ir prisaikdinti tos šalies ID t.y. pirmą mums reikia ją kažkaip gauti. Reiškia pirma ikeliam be nieko ir tada iškart darome paiešką gal;

// procregister.php tikrina registracijos reikšmes
// įvedimo laukų reikšmes issaugo $_SESSION['xxxx_login'], xxxx-name, pass, mail
// jei randa klaidų jas sužymi $_SESSION['xxxx_error']
// jei vardas, slaptažodis ir email tinka, įraso naują vartotoja į DB, nukreipia į index.php
// po klaidų- vel į register.php 

	// cia sesijos kontrole (pasiziureti veliau ar ji reikalinga)
	// if (!isset($_SESSION['prev']) || ($_SESSION['prev'] != "airlines_registration.php")
	// { 
		// header("Location: index.php");exit;
	// }

	// pažymime kad šitas puslapis yra "buvęs puslapis"
	$_SESSION['prev'] = "proc_airlines_registration.php";

	include("include/settingsdb.php");
	include("include/functions.php");
 
	$_SESSION['ID_ISO_error']=""; 
	$_SESSION['Name_error']=""; 
	
	if(isset($_POST['ID_ISO']))
	{
		$ID_ISO=$_POST['ID_ISO'];
		$Name=$_POST['Name'];
		
		$_SESSION['ID_ISO_login']=$_POST['ID_ISO'];  
		$_SESSION['Name_login']=$_POST['Name'];
	}
	else
	{
		$ID_ISO=$_SESSION['ID_ISO_login'];
		$Name=$_SESSION['Name_login'];
		$Location=$_SESSION['Location_login']; 
	}
	
	// prisijungiame prie duomenų bazės 
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 

	// pasitikriname ar prisijungta prie duomenų bazės (NEREIKALINGAS)
	//echo $db ? 'connected; ' : 'not connected; ';
	
	// tikriname ar paimami kintamieji
	//echo 'Įkeltieji duomenys: vardas - ' . $Name . '| ISO - '. $ID_ISO . '| Koordinatės - '  . $Location . "| baigėsi įkeltieji duomenys| ";
	// tikriname ar sesijos paimami kintamieji
	//echo $_SESSION['ID_ISO_login'] . '; ' . $_SESSION['Name_login'] . '; ' . $_SESSION['Location_login'];
	
	// ********************************************************************
	// čia prasideda įvestų duomenų manipuliacija, sutvarkyti pagal reikalą
	// ********************************************************************

	// išsireiškiame TBL_AIRLINES
	$sql_airlines = "SELECT ID,Name,ID_ISO "
            . "FROM " . TBL_AIRLINES . " ORDER BY Name";
	$result_airlines = mysqli_query($db, $sql_airlines);
	if (!$result_airlines || (mysqli_num_rows($result_airlines) < 1)) 
		{echo "Klaida skaitant lentelę airlines"; exit;} 	
		 
	// // inicijuoti kintamieji 
	// $airport_found = ''; 
	// $found = false; 

	// $result_airports = mysqli_query($db, $sql_airports); // restartinam $result_2 
		// while($row_airports = mysqli_fetch_assoc($result_airports))
		// {
			// if($row_airports['Name'] == $airport)
			// {
				// $found = true; 
				// $airport_found = $row_airports['ID']; 
			// }
		// }

	// Įkeliame TBL_POSTERS (yra AUTOINCREMENT, tad nereikia rūpintis ID) 
	//if(checkTopic($Name) && checkdescription($Location))  
	if(checknaming($Name) && checkISO($ID_ISO))
	{
		
		// įkeliame naują oro uostą (trūksta dar avialinijų užpildymo
		$sql = "INSERT INTO " . TBL_AIRLINES. " (Name, ID_ISO)
		VALUES ('$Name', '$ID_ISO')";
		
		$last_id = -1;
		
		if (mysqli_query($db, $sql))
		{
				// last id uzfiksuoja
				$last_id = mysqli_insert_id($db);
				// echo "New record created successfully. Last inserted ID is: " . $last_id;
				$_SESSION['message']="Registracija sėkminga ";
		}
		else {$_SESSION['message']="DB registracijos klaida: " . $sql . "<br>" . mysqli_error($db);}
		?><?php
		// nuoroda į index.php puslapį 
		header("Location:https://registravimosistema.000webhostapp.com/index.php");
		exit;?><?php
	}
	
	// nuoroda į index.php puslapį 
	 header("Location:https://registravimosistema.000webhostapp.com/airlines_registration.php");
	 exit;
	 ob_end_flush();
	 ?>