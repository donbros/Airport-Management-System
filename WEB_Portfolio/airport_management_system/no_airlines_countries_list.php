<!DOCTYPE html> 

<?php
// proc_airport_management.php parodoma išsamesnė pasirinktų skelbimų informacija 

header('Content-Type: text/html; charset=utf-8'); // LIETUVIŲ KALBOS AKTYVAVIMAS 

?>

<? 
$a=array(); 	
?>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Oro linijų sąrašas</title> 
        <link href="include/styles.css" rel="stylesheet" type="text/css" media="all" >
    </head>
    <body>
        <table class="center" ><tr><td>
            <center><img src="include/top.png"></center>
        </td></tr><tr><td> 
			<!--<center><font size="5">Dabar yra tokia registruotų vartotojų lentelė</font></center><br>-->
		<form name="vartotojai" action="proc_airlines_management.php" method="post"> 
 <?php
		include("include/meniu.php"); //įterpiamas meniu pagal vartotojo rolę
 			$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
			
			$sql_airlines = "SELECT ID,Name,ID_ISO "
					. "FROM " . TBL_AIRLINES . " ORDER BY Name";
			$result_airlines = mysqli_query($db, $sql_airlines);
			if (!$result_airlines || (mysqli_num_rows($result_airlines) < 1)) 
				{echo "Klaida skaitant lentelę airlines"; exit;} 
			
			$sql_countries = "SELECT ID_ISO,Name "
				   . "FROM " . TBL_COUNTRIES . " ORDER BY Name ASC"; 
			$result_countries = mysqli_query($db, $sql_countries);
			if (!$result_countries || (mysqli_num_rows($result_countries) < 1)) 
				{echo "Klaida skaitant lentelę airports"; exit;}				
				
 ?> 
		</table>
    <table class="center" border="1" cellspacing="0" cellpadding="12">
	
		<tr>
		<th><b>Nr.</b></th>
		<th><b>Šalis</b></th>
		</tr>
<?php
	
	$i=0; 
	// spausdinamas žinučių sąrašas 
    while($row_countries = mysqli_fetch_assoc($result_countries)) 
	{	 
		$Name=$row_countries['Name']; 
		$ID_ISO=$row_countries['ID_ISO']; 
		
		$haveAirline = false;
		$result_airlines = mysqli_query($db, $sql_airlines); // restartinam $result_3 
		while($row_airlines = mysqli_fetch_assoc($result_airlines))
		{
			if($row_airlines['ID_ISO']==$ID_ISO)
			{
				$haveAirline = true;
			}
		}
		
		echo "<tr>";
		
		if(!$haveAirline)
		{
			echo "<td>" . $i . "</td>";
			echo "<td>"; 
			echo $Name. "</td>";
			
			$i++; 
		}
		echo "</tr>";
	}
	
 ?> 
 
 <!-- Užžymėti visus <br/> -->
		
        </table>
        <br> 
        </form>
    </body></html> 
