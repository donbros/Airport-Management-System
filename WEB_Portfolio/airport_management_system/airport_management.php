<!DOCTYPE html> 

<?php
// Description: Spausdinami visi oro uostai. Galima atlikti tam tikrus pakeitimus sąraše

header('Content-Type: text/html; charset=utf-8'); // LIETUVIŲ KALBOS AKTYVAVIMAS 

?>

<? 
$a=array(); 	
?>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Oro uostų sąrašas</title> 
        <link href="include/styles.css" rel="stylesheet" type="text/css" media="all" >

    </head>
    <body>
        <table class="center" ><tr><td>
            <center><img src="include/top.png"></center>
        </td></tr><tr><td> 
			<!--<center><font size="5">Dabar yra tokia registruotų vartotojų lentelė</font></center><br>-->
		<form name="vartotojai" action="proc_airport_management.php" method="post"> 
 <?php
			include("include/connection.php"); 
			include("include/meniu.php"); //įterpiamas meniu pagal vartotojo rolę
 			
			// Procedūros
			$sql_airports = "SELECT ID,Name,ID_ISO,Location "
            . "FROM " . TBL_AIRPORTS . " ORDER BY Name DESC"; 
			$sql_countries = "SELECT ID_ISO,Name "
				   . "FROM " . TBL_COUNTRIES . " ORDER BY Name ASC"; 
			$sql_airports_airlines_relations = "SELECT ID_Airports,ID_Airlines "
					. "FROM " . TBL_AIRPORTS_AIRLINES_RELATIONS . " ORDER BY ID_Airports";
			$sql_airlines = "SELECT ID,Name,ID_ISO "
					. "FROM " . TBL_AIRLINES . " ORDER BY Name";
			$result_2 = sqlsrv_query($db, $sql_airports);
			
			if (!$result_2 || (sqlsrv_num_rows($result_2) < 1)) 
			{echo "Klaida skaitant lentelę airports"; exit;}
				
			$result_countries = sqlsrv_query($db, $sql_countries);
			if (!$result_countries || (sqlsrv_num_rows($result_countries) < 1)) 
				{echo "Klaida skaitant lentelę airports"; exit;}				
				
			$result_3 = sqlsrv_query($db, $sql_airports_airlines_relations);
			if (!$result_3 || (sqlsrv_num_rows($result_3) < 1)) 
				{echo "Klaida skaitant lentelę airlines"; exit;} 
			
			$result_4 = sqlsrv_query($db, $sql_airlines);
			if (!$result_4 || (sqlsrv_num_rows($result_4) < 1)) 
				{echo "Klaida skaitant lentelę airlines"; exit;} 
			
			// $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			// $db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
			// $result_2 = mysqli_query($db, $sql_airports);
			// if (!$result_2 || (mysqli_num_rows($result_2) < 1)) 
			// {echo "Klaida skaitant lentelę airports"; exit;}
			// $result_countries = mysqli_query($db, $sql_countries);
			// if (!$result_countries || (mysqli_num_rows($result_countries) < 1)) 
				// {echo "Klaida skaitant lentelę airports"; exit;}				
			// $result_3 = mysqli_query($db, $sql_airports_airlines_relations);
			// if (!$result_3 || (mysqli_num_rows($result_3) < 1)) 
				// {echo "Klaida skaitant lentelę airlines"; exit;} 
			// $result_4 = mysqli_query($db, $sql_airlines);
			// if (!$result_4 || (mysqli_num_rows($result_4) < 1)) 
				// {echo "Klaida skaitant lentelę airlines"; exit;} 
			
 ?> 
		</table>
    <table class="center" border="1" cellspacing="0" cellpadding="12">
	<?php $tarpas = ""; 
		$pazymeti = "Pažymėti visus"; 
		echo "<tr><th>".$tarpas. "</th><th>";  
		echo $tarpas."</th><th>";
		echo $tarpas."</th><th>";
		echo $tarpas."</th><th>";
		echo $tarpas."</th><th>";
		echo $tarpas."</th><th><b>";
		echo $pazymeti."</b></th></tr>"; 		
		$tarpas = ""; 
		echo "<tr><td>".$tarpas. "</td><td>";  
		echo $tarpas."</td><td>";
		echo $tarpas."</td><td>";
		echo $tarpas."</td><td>";
		echo $tarpas."</td><td>";
		echo $tarpas."</td><td>"; 
		echo "<label class=\"container\"><input type=\"checkbox\" onClick=\"toggle(this)\" /><span class=\"checkmark\"></span></label></td>"; ?>
		<tr><th><b>Nr.</b></th>
		<th><b>Oro uosto pavadinimas</b></th>
		<th><b>Šalis</b></th>
		<th><b>Lokacija</b></th>
		<th><b>Avialinijų sąrašas</b></th>
		<th><b>Redaguojamas įrašas</b></th>
		<th><b>Trinamas įrašas (-ai)</b></th>  
		</tr>
<?php
	

	
	$i=0; 
	
	// spausdinamas žinučių sąrašas 
    // while($row_2 = mysqli_fetch_assoc($result_2)) 
	// {	 
	    // $ID=$row_2['ID']; 
		// $Name=$row_2['Name']; 
		// $ID_ISO=$row_2['ID_ISO']; 
		// $Location=$row_2['Location']; 
		
		// $result_3 = mysqli_query($db, $sql_airports_airlines_relations); // restartinam $result_3 
		
		// $airlines_array = array();
		// $k=0;
		// while($row_3 = mysqli_fetch_assoc($result_3))
		// {
			// if($row_3['ID_Airports'] == $ID)
			// {
				// $airlines_array[$k++] = $row_3['ID_Airlines'];
			// }
		// }	
		
		// $airlines_name_array = array();
		// $result_4 = mysqli_query($db, $sql_airlines); // restartinam $result_3 
		// $length=count($airlines_array);
		// $z=0;
		// while($row_4 = mysqli_fetch_assoc($result_4))
		// {
			// for($j = 0; $j < $length; $j++)
			// {
				// if($airlines_array[$j]==$row_4['ID'])
				// {
					// $airlines_name_array[$z++] =$row_4['Name'];
				// }
			// }
		// }
		
		// $countryName = null;
		// $result_countries = mysqli_query($db, $sql_countries); // restartinam $result_3 
		// while($row_countries = mysqli_fetch_assoc($result_countries))
		// {
			// if($row_countries['ID_ISO']==$ID_ISO)
			// {
				// $countryName = $row_countries['Name'];
			// }
		// }
		
		// echo "<tr><td>";
		
		// echo $index=$i+1 . "</td><td>"; 
		// echo $Name. "</td><td>";  
		// echo $countryName."</td><td>";
		// echo $Location."</td><td>";
		// echo implode(", ",$airlines_name_array)."</td>";
		// $expiration = false; 
		
		// echo "<td><input type=\"submit\" value=\"Redaguoti\" class=\"v\" name=\"placiau_".$ID."\"></td>";

		// echo "<td><label class=\"container\"><input type=\"checkbox\" class=\"container\" name=\"naikinti_".$ID."\"><span class=\"checkmark\"></span></label></td>"; 
		
		// $a[$i]=$ID; 
		// $i++; 
	// }
	
	while($row_2 = sqlsrv_fetch_assoc($result_2)) 
	{	 
	    $ID=$row_2['ID']; 
		$Name=$row_2['Name']; 
		$ID_ISO=$row_2['ID_ISO']; 
		$Location=$row_2['Location']; 
		
		$result_3 = sqlsrv_query($db, $sql_airports_airlines_relations); // restartinam $result_3 
		
		$airlines_array = array();
		$k=0;
		while($row_3 = sqlsrv_fetch_assoc($result_3))
		{
			if($row_3['ID_Airports'] == $ID)
			{
				$airlines_array[$k++] = $row_3['ID_Airlines'];
			}
		}	
		
		$airlines_name_array = array();
		$result_4 = sqlsrv_query($db, $sql_airlines); // restartinam $result_3 
		$length=count($airlines_array);
		$z=0;
		while($row_4 = sqlsrv_fetch_assoc($result_4))
		{
			for($j = 0; $j < $length; $j++)
			{
				if($airlines_array[$j]==$row_4['ID'])
				{
					$airlines_name_array[$z++] =$row_4['Name'];
				}
			}
		}
		
		$countryName = null;
		$result_countries = sqlsrv_query($db, $sql_countries); // restartinam $result_3 
		while($row_countries = sqlsrv_fetch_assoc($result_countries))
		{
			if($row_countries['ID_ISO']==$ID_ISO)
			{
				$countryName = $row_countries['Name'];
			}
		}
		
		echo "<tr><td>";
		
		echo $index=$i+1 . "</td><td>"; 
		echo $Name. "</td><td>";  
		echo $countryName."</td><td>";
		echo $Location."</td><td>";
		echo implode(", ",$airlines_name_array)."</td>";
		$expiration = false; 
		
		echo "<td><input type=\"submit\" value=\"Redaguoti\" class=\"v\" name=\"placiau_".$ID."\"></td>";

		echo "<td><label class=\"container\"><input type=\"checkbox\" class=\"container\" name=\"naikinti_".$ID."\"><span class=\"checkmark\"></span></label></td>"; 
		
		$a[$i]=$ID; 
		$i++; 
	}
	 ?> 
	 
	<script language="JavaScript">
	// skriptas paskritas galybei "pasirinti visus įrašus"
	function toggle(source) {

		var aaa = <?php echo json_encode($a); ?>; 
		
		var j;
		
		for (j = 0; j < aaa.length; j++) { 
			var naikinti = "naikinti_" + aaa[j]; 
			checkboxes = document.getElementsByName(naikinti); 
			for(var i=0, n=checkboxes.length;i<n;i++) {
				checkboxes[i].checked = source.checked;
			}
		} 
	} 
	</script> 

        </table>
        <br> <input type="submit" name="Vykdyti" class=v value="Ištrinti Pažymėtus">
        </form>
		
    </body></html> 
