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
		<link href="include/styles.css" rel="stylesheet" type="text/css" >
    </head>
    <body>
        <table class="center" ><tr><td>
            <center><img src="include/top.png"></center>
        
		
		<?php include("include/meniu.php"); //įterpiamas meniu pagal vartotojo rolę ?>
			<?php
 			$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
			
			$sql_airlines = "SELECT ID,Name,ID_ISO "
					. "FROM " . TBL_AIRLINES . " ORDER BY Name";
			$result_4 = mysqli_query($db, $sql_airlines);
			if (!$result_4 || (mysqli_num_rows($result_4) < 1)) 
				{echo "Klaida skaitant lentelę airlines"; exit;} 
			
			$sql_countries = "SELECT ID_ISO,Name "
				   . "FROM " . TBL_COUNTRIES . " ORDER BY Name ASC"; 
			$result_countries = mysqli_query($db, $sql_countries);
			if (!$result_countries || (mysqli_num_rows($result_countries) < 1)) 
				{echo "Klaida skaitant lentelę airports"; exit;}				
			?> 
			
		</td></tr></table>
		
		<form name="selected_airlines" action="proc_selected_airlines.php" method="post">
			<table class="meniu" align="center">
			 <tr><td>
				<select name="ID_ISO" >
					<option value="-1">---</option> 
					<?php
					while($row=mysqli_fetch_array($result_countries))
					{
						echo "<option value='" . $row['ID_ISO'] . "'>" . $row['Name'] . "</option>"; 
					}; ?> 
				</select>
				
			</td></tr><tr><td><center><input type="submit" class="v" align="center" value="spausdinti sąrašą"></center></td></tr>
			</table>
		</form>
		
    </body>
</html> 
