<?php
// procregister.php tikrina registracijos reikšmes
// įvedimo laukų reikšmes issaugo $_SESSION['xxxx_login'], xxxx-name, pass, mail
// jei randa klaidų jas sužymi $_SESSION['xxxx_error']
// jei vardas, slaptažodis ir email tinka, įraso naują vartotoja į DB, nukreipia į index.php
// po klaidų- vel į register.php 

session_start(); 
// cia sesijos kontrole
if (!isset($_SESSION['prev']) || ($_SESSION['prev'] != "operacija2.php" && $_SESSION['prev'] != "register.php"))
{ 
	header("Location: logout.php");exit;
}

$_SESSION['prev'] = "proc_airlines_registration.php";

  include("include/nustatymai.php");
  include("include/functions.php");
 
	$_SESSION['rubric_error']=""; 
	$_SESSION['expiration_date_error']=""; 
	$_SESSION['airport_name_error']=""; 
	$_SESSION['description_error']=""; 
  

  
	// tas, kuris įkėlinėja skelbimą 
	$user=$_SESSION['user']; 
	
	// galime tikrinimą pagal airport_name daryti, nes vis tiek jį visada įvedinėja 
	if(isset($_POST['airport_name']))
	{
		$rubric=$_POST['rubric'];
		//$_SESSION['name_login']=$user;
		$expiration_date=$_POST['expiration_date'];//$_SESSION['realname_login']=$realname; // naujas !!!!!!!! 
		$airport_name=$_POST['airport_name'];//$_SESSION['surname_login']=$surname;     // naujas !!!!!!!! 
		$description=$_POST['description'];//$_SESSION['realname_login']=$realname; // naujas !!!!!!!! 
		
		$_SESSION['rubric_login']=$_POST['rubric'];  
		$_SESSION['expiration_date_login']=$_POST['expiration_date']; 
		$_SESSION['airport_name_login']=$_POST['airport_name']; 
		$_SESSION['description_login']=$_POST['description']; 
	}
	else
	{
		$rubric=$_SESSION['rubric_login'];
		$expiration_date=$_SESSION['expiration_date_login'];
		$airport_name=$_SESSION['airport_name_login'];
		$description=$_SESSION['description_login']; 
	}
	
	// prisijungiame prie duomenų bazės 
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 

	// išsireiškiame TBL_USERS 
	$sql_users = "SELECT userid,realname,surname,username,userlevel,post,email,timestamp "
            . "FROM " . TBL_USERS . " ORDER BY realname";
	$result_users = mysqli_query($db, $sql_users);
	if (!$result_users || (mysqli_num_rows($result_users) < 1)) 
		{echo "Klaida skaitant lentelę users"; exit;} 	
		 
	// inicijuoti kintamieji 
	$user_found = ''; 
	$found = false; 

	// ieškome userid 
	$result_users = mysqli_query($db, $sql_users); // restartinam $result_2 
	while($row_users = mysqli_fetch_assoc($result_users))
	{
		if($row_users['username'] == $user)
		{
			$found = true; 
			$user_found = $row_users['userid']; 
		}
	}

	$earliest_date = date('Y-m-d',strtotime(date("Y-m-d") . "+1 days")); 

	// Įkeliame TBL_POSTERS (yra AUTOINCREMENT, tad nereikia rūpintis ID) 
	// if($found) labiau formalumas, labai retas atvejis, kad jo nerastų duomenų bazėje 
	if($found)
	{
		// jeigu bent kažkas įrašyta 
		// if(($airport_name != '') && ($description != '') && ($expiration_date >= $earliest_date))
		if(checkairport_name($airport_name) && checkdescription($description))  
		{
		
			// įkeliame naują skelbimą 
			$sql = "INSERT INTO " . TBL_POSTERS. " (rubric, expiration_date, airport_name, description, fk_userid)
			VALUES ('$rubric', '$expiration_date', '$airport_name', '$description', '$user_found')";

			// Tikriname, ar gerai prireigstruota į duomenų bazę 
			if (mysqli_query($db, $sql)) 
				 {$_SESSION['message']="Registracija sėkminga";}
			else {$_SESSION['message']="DB registracijos klaida:" . $sql . "<br>" . mysqli_error($db);}

			// nuoroda į index.php puslapį 
			header("Location:index.php");
			exit; 
		}
		$_SESSION['message']="Yra klaidų duomenų įvedime"; 
	}
	
	// nuoroda į index.php puslapį 
	 header("Location:operacija2.php");
	 exit; 

     ?> 
  
  