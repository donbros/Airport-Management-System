<!DOCTYPE html> 

<?php
// Description: Spausdinamos visos oro linijos. Leidžiama redaguoti arba ištrinti (galima sužymėti kuriuos norma trinti).

header('Content-Type: text/html; charset=utf-8'); // LIETUVIŲ KALBOS AKTYVAVIMAS 
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
			include("include/connection.php"); 
 			include("include/meniu.php"); //įterpiamas meniu pagal vartotojo rolę
 			// procedūros
			$sql_airlines = "SELECT ID,Name,ID_ISO "
					. "FROM " . TBL_AIRLINES . " ORDER BY Name";
			
			$sql_countries = "SELECT ID_ISO,Name "
				   . "FROM " . TBL_COUNTRIES . " ORDER BY Name ASC";
			$result_4 = sqlsrv_query($db, $sql_airlines);
			
			if (!$result_4 || (sqlsrv_num_rows($result_4) < 1)) 
				{echo "Klaida skaitant lentelę airlines<br />";} 
			$result_countries = sqlsrv_query($db, $sql_countries);
			if (!$result_countries || (sqlsrv_num_rows($result_countries) < 1)) 
				{echo "Klaida skaitant lentelę airports"; exit;}	   
			// $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			// $db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
			// $result_4 = mysqli_query($db, $sql_airlines);
			// if (!$result_4 || (mysqli_num_rows($result_4) < 1)) 
				// {echo "Klaida skaitant lentelę airlines"; exit;} 
			// $result_countries = mysqli_query($db, $sql_countries);
			// if (!$result_countries || (mysqli_num_rows($result_countries) < 1)) 
				// {echo "Klaida skaitant lentelę airports"; exit;}				
				
 ?> 
		</table>
    <table class="center" border="1" cellspacing="0" cellpadding="12">
	<?php $tarpas = ""; 
		$pazymeti = "Pažymėti Visus"; 
		echo "<tr><th>".$tarpas. "</th><th>";  
		echo $tarpas."</th><th>";
		echo $tarpas."</th><th>";
		echo $tarpas."</th><th><b>";
		echo $pazymeti."</b></th></tr>"; 		
		$tarpas = ""; 
		echo "<tr><td>".$tarpas. "</td><td>";  
		echo $tarpas."</td><td>";
		echo $tarpas."</td><td>"; 
		echo $tarpas."</td><td>"; 
		echo "<label class=\"container\"><input type=\"checkbox\" onClick=\"toggle(this)\" /><span class=\"checkmark\"></span></label></td>"; ?>
		<tr><th><b>Nr.</b></th>
		<th><b>Oro Linijos Pavadinimas</b></th>
		<th><b>Šalis</b></th>
		<th><b>Redagavimas</b></th> 
		<th><b>Trinamas Įrašas (-ai)</b></th> 
		</tr>
<?php
	
	$i=0; 
    
	// while($row_airlines = mysqli_fetch_assoc($result_4)) 
	// {	 
	    // $ID=$row_airlines['ID']; 
		// $Name=$row_airlines['Name']; 
		// $ID_ISO=$row_airlines['ID_ISO']; 
		
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
		// echo $countryName."</td>";
		// echo "<td><input type=\"submit\" value=\"Redaguoti\" class=\"v\" name=\"placiau_".$ID."\"></td>";
		// echo "<td><label class=\"container\"><input type=\"checkbox\" class=\"container\" name=\"naikinti_".$ID."\"><span class=\"checkmark\"></span></label></td>"; 
		
		// $a[$i]=$ID; 
		// $i++; 
	// }
	
	while($row_airlines = sqlsrv_fetch_assoc($result_4)) 
	{	 
	    $ID=$row_airlines['ID']; 
		$Name=$row_airlines['Name']; 
		$ID_ISO=$row_airlines['ID_ISO']; 
		
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
		echo $countryName."</td>";
		echo "<td><input type=\"submit\" value=\"Redaguoti\" class=\"v\" name=\"placiau_".$ID."\"></td>";
		echo "<td><label class=\"container\"><input type=\"checkbox\" class=\"container\" name=\"naikinti_".$ID."\"><span class=\"checkmark\"></span></label></td>"; 
		
		$a[$i]=$ID; 
		$i++; 
	}
 ?> 
 
	 <!-- Script'as skirtas sužymėti viso sąrašo checkbox'us --> 
	<script language="JavaScript">
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
