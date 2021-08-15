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
		
	// function checktopic ($topic){   // Vartotojo vardo sintakse
	   // if (!$topic || strlen($topic = trim($topic)) == 0) 
			// {$_SESSION['topic_error']=
				 // "<font size=\"2\" color=\"#ff0000\">* Neįvėdėte skelbimo pavadinimo/temos. </font>";
			 // "";
			 // return false;}
            
	        // else return true;
    // }
    
	function checkzinute ($message){   // Vartotojo vardo sintakse
	   if (!$message || strlen($message = trim($message)) == 0) 
			{$_SESSION['description_error']=
				 "<font size=\"2\" color=\"#ff0000\">* Neįvėdėte teksto į tekstinį laukelį. </font>";
			 "";
			 return false;}
            
	        else return true;
	}

	function checkname ($username){   // Vartotojo vardo sintakse
	   if (!$username || strlen($username = trim($username)) == 0) 
			{$_SESSION['name_error']=
				 "<font size=\"2\" color=\"#ff0000\">* Neįvestas vartotojo vardas</font>";
			 "";
			 return false;}
            elseif (!preg_match("/^([0-9a-zA-ZZąčęėįšųūž])*$/", $username))  /* Check if username is not alphanumeric */ 
			{$_SESSION['name_error']=
				"<font size=\"2\" color=\"#ff0000\">* Vartotojo vardas gali būti sudarytas<br>
				&nbsp;&nbsp;tik iš raidžių ir skaičių</font>";
		     return false;}
	        else return true;
   }
    
	// nauja f-ja 
	function checkrealname ($realname){   // Vartotojo vardo sintakse
	   if (!$realname || strlen($realname = trim($realname)) == 0) 
			{$_SESSION['realname_error']=
				 "<font size=\"2\" color=\"#ff0000\">* Neįvestas tikras vardas</font>";
			 "";
			 return false;}
            elseif (!preg_match("/^([0-9a-zA-Ząčęėįšųūž])*$/", $realname))  /* Check if username is not alphanumeric */ 
			{$_SESSION['realname_error']=
				"<font size=\"2\" color=\"#ff0000\">* Tikras vardas gali būti sudarytas<br>
				&nbsp;&nbsp;tik iš raidžių (ir lietuviškų) ir skaičių</font>";
		     return false;}
	        else return true;
   }

	// nauja f-ja 
	function checksurname ($surname){   // Vartotojo vardo sintakse
	   if (!$surname || strlen($surname = trim($surname)) == 0) 
			{$_SESSION['surname_error']=
				 "<font size=\"2\" color=\"#ff0000\">* Neįvesta vartotojo pavardė</font>";
			 "";
			 return false;}
            elseif (!preg_match("/^([0-9a-zA-Ząčęėįšųūž])*$/", $surname))  /* Check if username is not alphanumeric */ 
			{$_SESSION['surname_error']=
				"<font size=\"2\" color=\"#ff0000\">* Vartotojo pavardė gali būti sudaryta<br>
				&nbsp;&nbsp;tik iš raidžių (ir lietuviškų) ir skaičių</font>";
		     return false;}
	        else return true;
   }

	function checkpass($pwd,$dbpwd) {     //  slaptazodzio tikrinimas (tik demo: min 4 raides ir/ar skaiciai) ir ar sutampa su DB esanciu
	   if (!$pwd || strlen($pwd = trim($pwd)) == 0) 
			{$_SESSION['pass_error']=
			  "<font size=\"2\" color=\"#ff0000\">* Neįvestas slaptažodis</font>";
			 return false;}
            elseif (!preg_match("/^([0-9a-zA-Z])*$/", $pwd))  /* Check if $pass is not alphanumeric */ 
			{$_SESSION['pass_error']="* Čia slaptažodis gali būti sudarytas<br>&nbsp;&nbsp;tik iš raidžių ir skaičių";
		     return false;}
            elseif (strlen($pwd)<4)  // per trumpas
			         {$_SESSION['pass_error']=
						  "<font size=\"2\" color=\"#ff0000\">* Slaptažodžio ilgis <4 simbolius</font>";
		              return false;}
	          elseif ($dbpwd != substr(hash( 'sha256', $pwd ),5,32))
               {$_SESSION['pass_error']=
				   "<font size=\"2\" color=\"#ff0000\">* Neteisingas slaptažodis</font>";
                return false;}
            else return true;
   }

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

	function checkmail($mail) {   // e-mail sintax error checking  
	   if (!$mail || strlen($mail = trim($mail)) == 0) 
			{$_SESSION['mail_error']=
				"<font size=\"2\" color=\"#ff0000\">* Neįvestas e-pašto adresas</font>";
			   return false;}
            elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) 
			      {$_SESSION['mail_error']=
					   "<font size=\"2\" color=\"#ff0000\">* Neteisingas e-pašto adreso formatas</font>";
		            return false;}
	        else return true;
   }
 ?>
 