<!DOCTYPE html>

<?php
	include("include/settingsdb.php");
	include("include/styles.php"); 
?>

<html>
	<header>
		<link href="include/styles.css" rel="stylesheet" type="text/css" > 
	</header> 
</html>

<?php 
	
	// prisijungiame prie duomenų bazės (online db variantas arba alternatyvios)
	// $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	// $db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
	
	// MySQL duomenų bazės prisijungimas
	// prireikė php_sqlsrv_82_ts_x64 ir php_sqlsrv_82_nts_x64 (žr. pagal php versiją)
	$serverName = DB_SERVER_SQLSRV; //serverName\instanceName
	$connectionInfo = array( "Database"=>DB_NAME_SQLSRV, "UID"=>DB_USER_SQLSRV, "PWD"=>DB_PASS_SQLSRV);
	$db = sqlsrv_connect( $serverName, $connectionInfo);

	if( $db ) {
		 echo "";
	}else{
		 echo "Connection could not be established.<br />";
		 die( print_r( sqlsrv_errors(), true));
	} 
	
?>       
    
 