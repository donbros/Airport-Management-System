<?php
// funkcijos  include/functions.php

function inisession($arg) {   //valom sesijos kintamuosius
        if($arg =="full"){
            //$_SESSION['message']="";
            //$_SESSION['user']="";
			//$_SESSION['realname']="";
			//$_SESSION['surname']="";
	       	//$_SESSION['ulevel']=0;
			//$_SESSION['userid']=0;
			//$_SESSION['umail']=0;
        }			    	 
		// $_SESSION['name_login']="";
		// $_SESSION['pass_login']="";
		// $_SESSION['passn_login']=""; // ar ok ? 
		// $_SESSION['mail_login']="";
		// $_SESSION['realname_login']=""; //naujas
		// $_SESSION['surname_login']=""; //naujas
	
		// $_SESSION['name_error']="";
      	// $_SESSION['pass_error']="";
		// $_SESSION['passn_error']=""; // ar ok ? 
		// $_SESSION['mail_error']=""; 
		// $_SESSION['realname_error']=""; //naujas
      	// $_SESSION['surname_error']=""; //naujas
	
		// // reikšmės padaromas iniciacijoje, o po to gal pakeičiamos ? 
		// $_SESSION['rubric_login']=""; 
		// $_SESSION['expiration_date_login'] = date('Y-m-d',strtotime(date("Y-m-d") . "+1 days")); // standartinis, kai tik įjungi toks būna parodomas 
		// $_SESSION['topic_login']=""; 
		// $_SESSION['description_login']=""; 
	
		// $_SESSION['rubric_error']=""; 
		// $_SESSION['expiration_date_error']=""; 
		// $_SESSION['topic_error']=""; 
		// $_SESSION['description_error']=""; 
		
		$_SESSION['Name_login']="";
		$_SESSION['ID_ISO_login']="";
		$_SESSION['Location_login']="";
	
		$_SESSION['Name_error']="";
		$_SESSION['ID_ISO_error']="";
		$_SESSION['Location_error']="";
		
        }

	// function checkName ($airname){
		// if (!$airname || strlen($airname = trim($airname)) == 0) 
			// {$_SESSION['Name_error']=
				 // "<font size=\"2\" color=\"#ff0000\">* Neįvėdėte teksto į tekstinį laukelį. </font>";
			 // "";
			 // return false;}
            
	    // else return true;
	// }

	// --------------------------------------------------------------

	function checknaming ($description){   // Vartotojo vardo sintakse
	   if (!$description || strlen($description = trim($description)) == 0) 
			{$_SESSION['Name_error']=
				 "<font size=\"2\" color=\"#ff0000\">* Neįvėdėte teksto į tekstinį laukelį. </font>";
			 "";
			 return false;}
            
		else return true;
    }
		
	function checkISO ($id_iso){   // Vartotojo vardo sintakse
	   if (!$id_iso || $id_iso == -1) 
			{$_SESSION['ID_ISO_error']=
				 "<font size=\"2\" color=\"#ff0000\">* Neįvėdėte šalies. </font>";
			 "";
			 return false;}
            
	        else return true;
    }
	
	function checkLocation ($lat, $lng){   // Vartotojo vardo sintakse
	   if (!$lat || !$lng
	   || preg_match("/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/", $lat)==false 
	   || preg_match("/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/", $lng)==false
	   ) 
			{$_SESSION['Location_error']=
				 "<font size=\"2\" color=\"#ff0000\">* Neįvestos koordinatės arba klaidingas jų formatas. </font>";
			 return false;}
            
	        else 
			{
				return true;
			}
    }
	
	// nenaudojamas
	function checkdb($username) {  // iesko DB pagal varda, grazina {vardas,slaptazodis,lygis,id} ir nustato name_error
		 $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		 $db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
		 $sql = "SELECT * FROM " . TBL_USERS. " WHERE username = '$username'";
		 $result = mysqli_query($db, $sql);
	     $uname = $upass = $ulevel = $uid = $umail = null;
		 if (!$result || (mysqli_num_rows($result) != 1))   // jei >1 tai DB vardas kartojasi, netikrinu, imu pirma
	  	 {  // neradom vartotojo DB
		    $_SESSION['name_error']=
			 "<font size=\"2\" color=\"#ff0000\">* Tokio vartotojo nėra</font>";
         }
      else {  //vardas yra DB
           $row = mysqli_fetch_assoc($result); 
           $uname= $row["username"]; $upass= $row["password"]; 
           $ulevel=$row["userlevel"]; $uid= $row["userid"]; $umail = $row["email"];}
     return array($uname,$upass,$ulevel,$uid,$umail);
 }
	
 ?>
 