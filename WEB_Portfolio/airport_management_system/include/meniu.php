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
		<!-- <link href="include/styles_extra.css" rel="stylesheet" type="text/css" >  --> 
		<link href="include/styles.css" rel="stylesheet" type="text/css" > 
	</header> 
</html>

<!-- Meniu juostą renkantis ką veikti toliau -->
<!-- <table width=100% border="0" cellspacing="1" cellpadding="3" class="meniu"><tr>
<td><a href="airport_registration.php"><input type= "button" value= "Registruoti oro uostą" class="v_menu" ></a></td>
<td><a href="airlines_registration.php"><input type="button" value="Registruoti avialiniją" class="v_menu" ></a></td>
<td><a href="airport_management.php"><input type="button" value="Tvarkyti oro uostus" class="v_menu" ></a></td>
<td><a href="airlines_management.php"><input type="button" value="Tvarkyti avialinijas" class="v_menu" ></a></td>
</tr>
<tr>
<td><a href="no_airlines_countries_list.php"><input type="button" value="     Šalys NOL / P1    " class="v_menu" ></a></td>
<td><a href="no_airlines_airports_countries_list.php"><input type="button" value=" Šalys NOLOP / P2" class="v_menu" ></a></td>
<td><a href="selected_airlines.php"><input type="button" value="     Oro uostai P3     " class="v_menu" ></a></td> &nbsp;&nbsp;
-->
<?php 

	// nereikalingų sesijai duomenų trynimas (šita vieta gan gera, nes meniu daug kur kartojasi) 
	// PASTABA: neimplementinti meniu procposterslist.php ir zinute.php 
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	//if(isset($_SESSION['showlist']))
	//	unset($_SESSION['showlist']); 
	
	// MySQL duomenų bazės prisijungimas
	// prireikė php_sqlsrv_82_ts_x64 ir php_sqlsrv_82_nts_x64 (žr. pagal php versiją)
	// $serverName = "DOMANTAS\\SQLEXPRESS01"; //serverName\instanceName
	// $connectionInfo = array( "Database"=>"DonbrosWebsitePHP", "UID"=>"sa", "PWD"=>"x");
	// $db = sqlsrv_connect( $serverName, $connectionInfo);
	// if( $db ) {
		 // echo "Conn.<br />";
	// }else{
		 // echo "Connection could not be established.<br />";
		 // die( print_r( sqlsrv_errors(), true));
	// } 
	
	// php variantas
    echo "<table width=100% border=\"0\" cellspacing=\"1\" cellpadding=\"3\" class=\"meniu\"><tr>";
    echo "<td><a href=\"airport_registration.php\"><input type=\"button\" value=\"registruoti oro uostą\" class=\"v_menu\" ></a></td> &nbsp;&nbsp;";
    echo "<td><a href=\"airlines_registration.php\"><input type=\"button\" value=\"registruoti avialiniją\" class=\"v_menu\" ></a></td> &nbsp;&nbsp;";
    echo "<td><a href=\"airport_management.php\"><input type=\"button\" value=\"tvarkyti oro uostus\" class=\"v_menu\" ></a></td> &nbsp;&nbsp;";
	echo "<td><a href=\"airlines_management.php\"><input type=\"button\" value=\"tvarkyti avialinijas\" class=\"v_menu\" ></a></td> &nbsp;&nbsp;";
	echo "</tr>";
	// // papildomos
	echo "<tr>";
	echo "<td><a href=\"no_airlines_countries_list.php\"><input type=\"button\" value=\"     šalys nol / p1    \" class=\"v_menu\" ></a></td> &nbsp;&nbsp;";
	echo "<td><a href=\"no_airlines_airports_countries_list.php\"><input type=\"button\" value=\" šalys nolop / p2\" class=\"v_menu\" ></a></td> &nbsp;&nbsp;";
	echo "<td><a href=\"selected_airlines.php\"><input type=\"button\" value=\"     oro uostai p3     \" class=\"v_menu\" ></a></td> &nbsp;&nbsp;";
	echo "</tr></table>";
	
	// prisijungiame prie duomenų bazės (online db variantas arba alternatyvios)
	// $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	// $db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
	
?>       
    
 