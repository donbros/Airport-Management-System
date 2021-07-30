<!DOCTYPE html>

<?php
// meniu.php  rodomas meniu pagal vartotojo rolę

if (!isset($_SESSION)) { header("Location: logout.php");exit;}
include("include/nustatymai.php");
include("include/styles.php"); 
// kintamųjų pakopijavimas iš sesijos (vardas ir rolė (kodu)) 

?>

<html>
<header>

<!-- Atsakingi už css įtraukimą prie meniu --> 
<link href="include/styles_extra.css" rel="stylesheet" type="text/css" > 
<link href="include/styles.css" rel="stylesheet" type="text/css" > 

</header> 
</html>

<?php 

$realname = $_SESSION['realname']; // functions.php ir proclogin.php priskirti sesijai reikia realname 
// $surname = $_SESSION['surname']; 
$user=$_SESSION['user'];
$userlevel=$_SESSION['ulevel'];
$role="";
{foreach($user_roles as $x=>$x_value)
			      {if ($x_value == $userlevel) $role=$x;}
} 

	// nereikalingų sesijai duomenų trynimas (šita vieta gan gera, nes meniu daug kur kartojasi) 
	// PASTABA: neimplementinti meniu procposterslist.php ir zinute.php 
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	if(isset($_SESSION['showlist']))
		unset($_SESSION['showlist']); 

	// prisijungiame prie duomenų bazės 
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	//$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 

	// išsireiškiame TBL_USERS 
	/*$sql_users = "SELECT userid,realname,surname,username,userlevel,post,email,timestamp "
            . "FROM " . TBL_USERS . " ORDER BY realname";
	 $result_users = mysqli_query($db, $sql_users);
	if (!$result_users || (mysqli_num_rows($result_users) < 1)) 
		{echo "Klaida skaitant lentelę users"; exit;} 	

		$result_users = mysqli_query($db, $sql_users); // restartinam $result_2 
		while($row_users = mysqli_fetch_assoc($result_users))
		{
			if($row_users['username'] == $user)
			{
					$post = $row_users['post']; 
			}
		} 

     	echo "<table width=100% border=\"0\" cellspacing=\"1\" cellpadding=\"3\" class=\"meniu\">";
        echo "<tr><td>";
        echo "Prisijungęs vartotojas: <b>".$user."</b>     Rolė: <b>".$role."".$realname."</b> <br>";
        echo "</td></tr><tr><td>"; 
        echo "<a href=\"posterslist.php\"><input type=\"button\" value=\"Skelbimų sąrašas\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
        if (($userlevel == $user_roles["Reg_vartotojas"]) || ($userlevel == $user_roles[DEFAULT_LEVEL]) ) 
		{ 
			if ($post == '1') 
				echo "<a href=\"operacija2.php\"><input type=\"button\" value=\"Įdėti skelbimą\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
        	echo "<a href=\"operacija3.php\"><input type=\"button\" value=\"Siųstos žinutės\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
			echo "<a href=\"operacija4.php\"><input type=\"button\" value=\"Gautos žinutės\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
		}
		// jei tai ne svecias tada leidziama redaguoti paskyra 
        if ($_SESSION['user'] != "guest") echo "<a href=\"useredit.php\"><input type=\"button\" value=\"Redaguoti paskyrą\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
     //Trečia operacija tik rodoma pasirinktu kategoriju vartotojams, pvz.:
        //if (($userlevel == $user_roles["Kontrolierius"]) || ($userlevel == $user_roles[ADMIN_LEVEL] )) {
        //    echo "[<a href=\"operacija3.php\">Demo operacija3</a>] &nbsp;&nbsp;";
       	//	}   
        //Administratoriaus sąsaja rodoma tik administratoriui
        if (($userlevel == $user_roles["Administratorius"]) || ($userlevel == $user_roles[ADMIN_LEVEL]) ) {
            echo "<a href=\"admin.php\"><input type=\"button\" value=\"Administratoriaus sąsaja\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
        }
        echo "<a href=\"logout.php\"><input type=\"button\" value=\"Atsijungti\" class=\"v_menu\" ></a>";
		*/
      echo "</td></tr></table>";
?>       
    
 