<!DOCTYPE html>

<?php
// meniu.php  rodomas meniu pagal vartotojo rolę

include("include/settingsdb.php");
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

<!-- Meniu juostą renkantis ką veikti toliau -->

<?php 

	// nereikalingų sesijai duomenų trynimas (šita vieta gan gera, nes meniu daug kur kartojasi) 
	// PASTABA: neimplementinti meniu procposterslist.php ir zinute.php 
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	//if(isset($_SESSION['showlist']))
	//	unset($_SESSION['showlist']); 

	// prisijungiame prie duomenų bazės 
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
	
    echo "<table width=100% border=\"0\" cellspacing=\"1\" cellpadding=\"3\" class=\"meniu\">";
    echo "<a href=\"airport_registration.php\"><input type=\"button\" value=\"Registruoti oro uostą\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
	echo "<a href=\"airport_management.php\"><input type=\"button\" value=\"Peržiūrėti ir tvarkyti oro uostus\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
    echo "<a href=\"airlines_registration.php\"><input type=\"button\" value=\"Registruoti avialiniją\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
	echo "<a href=\"airlines_management.php\"><input type=\"button\" value=\"Peržiūrėti ir tvarkyti avialinijas\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
	// Papildomos
	echo "<a href=\"no_airlines_countries_list.php\"><input type=\"button\" value=\"Šalių sąrašas NA\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
	echo "<a href=\"no_airlines_airports_countries_list.php\"><input type=\"button\" value=\"Šalių sąrašas NAO\" class=\"v_menu\" ></a> &nbsp;&nbsp;";
	echo "<a href=\"selected_airlines.php\"><input type=\"button\" value=\"Oro uostai į kuriuos skrenda tik pasirinktos šalies avialinijos\" class=\"v_menu\" ></a> &nbsp;&nbsp;";

    echo "</td></tr></table>";
?>       
    
 