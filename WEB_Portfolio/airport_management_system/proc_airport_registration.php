<?php
ob_start();
?>
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

	// pažymime kad šitas puslapis yra "buvęs puslapis"
	$_SESSION['prev'] = "proc_airport_registration.php";

	include("include/settingsdb.php");
	include("include/functions.php");
 
	$_SESSION['ID_ISO_error']=""; 
	$_SESSION['Name_error']=""; 
	$_SESSION['Location_error']=""; 
	
	// galime tikrinimą pagal ID_ISO daryti, nes vis tiek jį visada įvedinėja 
	if(isset($_POST['ID_ISO']))
	{
		$Bilekas = $_POST['container'];
		$ID_ISO=$_POST['ID_ISO'];
		//$_SESSION['name_login']=$user;
		$Name=$_POST['Name'];//$_SESSION['surname_login']=$surname;
		// jie norima apvalinti vėliau
		//$lat = number_format((float)$_POST['lat'], 2, '.', '');
		//$lng = number_format((float)$_POST['lng'], 2, '.', '');
		$Location=$_POST['lat'] . ' ' . $_POST['lng'];//$_SESSION['realname_login']=$realname; // naujas !!!!!!!! 
		//$expiration_date=$_POST['expiration_date'];//$_SESSION['realname_login']=$realname; // naujas !!!!!!!! 
		
		// pasiemimas I (retai trinamus) sesijos kintamuosius
		$_SESSION['ID_ISO_login']=$_POST['ID_ISO'];  
		$_SESSION['Name_login']=$_POST['Name']; 
		//$_SESSION['Location_login']=$_POST['Location']; 
		$_SESSION['Location_login']=$Location;
		//$_SESSION['expiration_date_login']=$_POST['expiration_date']; 
		//echo "pirmas; ";
	}
	else
	{
		// pasiemimas IS (retai trinamu) sesijos kintamuju
		$ID_ISO=$_SESSION['ID_ISO_login'];
		$Name=$_SESSION['Name_login'];
		$Location=$_SESSION['Location_login']; 
		//$expiration_date=$_SESSION['expiration_date_login'];
		//echo "antras; ";
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
	if(checknaming($Name) && checkISO($ID_ISO) && checkLocation($_POST['lat'], $_POST['lng']) )
	{
		
		// įkeliame naują oro uostą (trūksta dar avialinijų užpildymo
		$sql = "INSERT INTO " . TBL_AIRPORTS. " (Name, ID_ISO, Location)
		VALUES ('$Name', '$ID_ISO', '$Location')";
		
		$last_id = -1;
		
		if (mysqli_query($db, $sql))
		{
				// last id uzfiksuoja
				$last_id = mysqli_insert_id($db);
				// echo "New record created successfully. Last inserted ID is: " . $last_id;
				$_SESSION['message']="Registracija sėkminga ";
		}
		else {$_SESSION['message']="DB registracijos klaida: " . $sql . "<br>" . mysqli_error($db);}
		
		// šita reikalinga gan, gal netrint
		echo $_SESSION['message'];
		
		// žinutė kuri praneša ar pavyko duomenis įkelti į duomenų bazę;
		// echo 'Ar sėkmingai užregistruota: ' . $_SESSION['message'];
		
		// viskas pagal gidą --->--v
		// $number = count($_POST["airline"]);
		
		// echo "count: " . $number . "; ";
		// echo $last_id . "; ";
		// echo $_POST["airline"][0] . " ";
		
		// grieztas neleidimas nieko neivesti - veliau galima pakeisti i kazka svelnesnio:
		// 1. galima į vieną iš laukelių nieko neivesti, taciau bus fiksuojami tik ivesti (careless/lazy user approach)
		// 2. parasome anksciau kad butu uzpildyti visi laukai (normal user, bet tokiu budu neleistume tusciu oro uostu palikti, gal geriau tiesiog ispeti, kad neivesta, bet naudoti lazy user approacha)
		// off topic: beje  reikia neleisti ivedineti tu paciu avialiniju - sutvarkyta: padaryta unikali raktu pora todel ivedus papildomus duomenis nieko neivyksta
		$cancel = false;
		// for($i=0; $i<$number; $i++)
		// {
			// if($_POST["airline"][$i] == '-1')
				// $cancel = true;
		// }
		
		// tikrina ar įvesta bent viena "oro_linija"
		if((count($_POST["airline"]) > 0) and ($cancel==false))
		{
			// įkeliame naujus oro uostų ir oro linijų saitus
			for($i=0; $i<count($_POST["airline"]); $i++)
			{
				if(-1!=$_POST["airline"][$i])
				{
					if(trim($_POST["airline"][$i]) != '')
					{
						
						$sql = "INSERT INTO " . TBL_AIRPORTS_AIRLINES_RELATIONS . " (ID_Airports, ID_Airlines)
							VALUES ('$last_id', '" . mysqli_real_escape_string($db, $_POST["airline"][$i]) . "')";
						
						//VALUES ('$last_id', '$ID_Airlines')";
						if (mysqli_query($db, $sql))
						{
								$_SESSION['message']="Registracija sėkminga";
						}
						else {$_SESSION['message']="DB registracijos klaida:" . $sql . "<br>" . mysqli_error($db);}
					}
				}
			}
			echo 'Duomenys ką tik įterpti.';
		}
		else
		{
			echo "Neįvesta nei viena oro linija.";
		}

		// galima trinti šita
		// if (mysqli_query($db, $sql)) {
		// $last_id = mysqli_insert_id($db);
		// echo "New record created successfully. Last inserted ID is: " . $last_id;
		// } else {
			// echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		// }

		// susiejimas
		// $number = count($_POST["name"]);
		// if($number > 0)
		// {
			// for($i=0; $i<$number; $i++)
			// {
				// if(trim($_POST["name"][$i]) != '')
				// {
					// // pirma reik pasiimti id paskutinio ideto oro uosto, o airlines tureciau tureti id is tuomet kai reikejo rinktis
					// $sql = "INSERT INTO " . TBL_AIRPORTS_AIRLINES_RELATIONS. " (ID_Airports, ID_Airlines)
					// VALUES ('$last_id', '$ID_Airlines')";

					// // Tikriname, ar gerai prireigstruota į duomenų bazę 
					// /*if (mysqli_query($db, $sql))
						 // {$_SESSION['message']="Registracija sėkminga";}
					// else {$_SESSION['message']="DB registracijos klaida:" . $sql . "<br>" . mysqli_error($db);}
					// */
				// }
			// }
		// }
		// else
		// {
			// echo "tralala";
		// }

		// nuoroda į index.php puslapį 
		//header("Location:index.php");
		header('Location: airport_registration.php'); 
		exit; 
	}
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
			//header("Location:index.php");
			//exit; 
		// }
		// $_SESSION['message']="Yra klaidų duomenų įvedime"; 
	// }
	
	// nuoroda į index.php puslapį 
	 header('Location: airport_registration.php');
	 exit;
	 ob_end_flush();
     ?>