<?php

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

	session_start(); 
	// cia sesijos kontrole (pasiziureti veliau ar ji reikalinga)
	//if (!isset($_SESSION['prev']) || ($_SESSION['prev'] != "operacija2.php" && $_SESSION['prev'] != "register.php"))
	//{ 
	//	header("Location: logout.php");exit;
	//}

	$_SESSION['prev'] = "proc_airport_registration.php";

	include("include/nustatymai.php");
	include("include/functions.php");
 
	$_SESSION['ID_ISO_error']=""; 
	$_SESSION['Name_error']=""; 
	$_SESSION['Location_error']=""; 
	
	// galime tikrinimą pagal Name daryti, nes vis tiek jį visada įvedinėja 
	//if(isset($_POST['Name']))
	//{
		$ID_ISO=$_POST['ID_ISO'];
		//$_SESSION['name_login']=$user;
		$Name=$_POST['Name'];//$_SESSION['surname_login']=$surname;     // naujas !!!!!!!! 
		$Location=$_POST['Location'];//$_SESSION['realname_login']=$realname; // naujas !!!!!!!! 
		//$expiration_date=$_POST['expiration_date'];//$_SESSION['realname_login']=$realname; // naujas !!!!!!!! 
		
		$_SESSION['ID_ISO_login']=$_POST['ID_ISO'];  
		$_SESSION['Name_login']=$_POST['Name']; 
		$_SESSION['Location_login']=$_POST['Location']; 
		//$_SESSION['expiration_date_login']=$_POST['expiration_date']; 
	//}
	//else
	{//
	//	$ID_ISO=$_SESSION['ID_ISO_login'];
	//	$Name=$_SESSION['Name_login'];
	//	$Location=$_SESSION['Location_login']; 
		//$expiration_date=$_SESSION['expiration_date_login'];
	//}
	
	// prisijungiame prie duomenų bazės 
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 

	// ********************************************************************
	// čia prasideda įvestų duomenų manipuliacija, sutvarkyti pagal reikalą
	// ********************************************************************

	// // išsireiškiame TBL_AIRPORTS
	// $sql_airports = "SELECT ID,Name,ID_ISO,Location "
            // . "FROM " . TBL_AIRPORTS . " ORDER BY Name";
	// $result_airports = mysqli_query($db, $sql_airports);
	// if (!$result_airports || (mysqli_num_rows($result_airports) < 1)) 
		// {echo "Klaida skaitant lentelę users"; exit;} 	
		 
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
	//{
		
		// įkeliame naują oro uostą (trūksta dar avialinijų užpildymo
		$sql = "INSERT INTO " . TBL_AIRPORTS. " (Name, ID_ISO, Location)
		VALUES ('$Name', '$ID_ISO', '$Location')";

		// Tikriname, ar gerai prireigstruota į duomenų bazę 
		if (mysqli_query($db, $sql))
			 {$_SESSION['message']="Registracija sėkminga";}
		else {$_SESSION['message']="DB registracijos klaida:" . $sql . "<br>" . mysqli_error($db);}

		// nuoroda į index.php puslapį 
		//header("Location:index.php");
		//exit; 
	//}
	//$_SESSION['message']="Yra klaidų duomenų įvedime"; 

	// išsireiškiame TBL_USERS 
	// $sql_users = "SELECT userid,realname,surname,username,userlevel,post,email,timestamp "
            // . "FROM " . TBL_USERS . " ORDER BY realname";
	// $result_users = mysqli_query($db, $sql_users);
	// if (!$result_users || (mysqli_num_rows($result_users) < 1)) 
		// {echo "Klaida skaitant lentelę users"; exit;} 	
		 
	// // inicijuoti kintamieji 
	// $user_found = ''; 
	// $found = false; 

	// ieškome userid 
	// $result_users = mysqli_query($db, $sql_users); // restartinam $result_2 
	// while($row_users = mysqli_fetch_assoc($result_users))
	// {
		// if($row_users['username'] == $user)
		// {
			// $found = true; 
			// $user_found = $row_users['userid']; 
		// }
	// }

	// kintamieji
	// $earliest_date = date('Y-m-d',strtotime(date("Y-m-d") . "+1 days")); 

	// // Įkeliame TBL_POSTERS (yra AUTOINCREMENT, tad nereikia rūpintis ID) 
	// // if($found) labiau formalumas, labai retas atvejis, kad jo nerastų duomenų bazėje 
	// if($found)
	// {
		// // jeigu bent kažkas įrašyta 
		// // if(($Name != '') && ($description != '') && ($expiration_date >= $earliest_date))
		// if(checkTopic($Name) && checkdescription($Location))  
		// {
		
			// // įkeliame naują oro uostą (trūksta dar avialinijų užpildymo
			// $sql = "INSERT INTO " . TBL_POSTERS. " (Name, ID_ISO, Location)
			// VALUES ('$Name', '$ID_ISO', '$Location')";

			// // Tikriname, ar gerai prireigstruota į duomenų bazę 
			// if (mysqli_query($db, $sql)) 
				 // {$_SESSION['message']="Registracija sėkminga";}
			// else {$_SESSION['message']="DB registracijos klaida:" . $sql . "<br>" . mysqli_error($db);}

			// // nuoroda į index.php puslapį 
			// header("Location:index.php");
			// exit; 
		// }
		// $_SESSION['message']="Yra klaidų duomenų įvedime"; 
	// }
	
	// nuoroda į index.php puslapį 
	 header("Location:airport_registration.php");
	 exit;
     ?>