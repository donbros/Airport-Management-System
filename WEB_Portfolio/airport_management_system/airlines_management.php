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
			$result_4 = mysqli_query($db, $sql_airlines);
			if (!$result_4 || (mysqli_num_rows($result_4) < 1)) 
				{echo "Klaida skaitant lentelę airlines"; exit;} 
			
			$sql_countries = "SELECT ID_ISO,Name "
				   . "FROM " . TBL_COUNTRIES . " ORDER BY Name ASC"; 
			$result_countries = mysqli_query($db, $sql_countries);
			if (!$result_countries || (mysqli_num_rows($result_countries) < 1)) 
				{echo "Klaida skaitant lentelę airports"; exit;}				
				
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
		// $pazymeti = "pažymėti visus"; 
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
		<?php //if(($_SESSION['ulevel'] == $user_roles["Kontrolierius"])||($_SESSION['ulevel'] == $user_roles["Reg_vartotojas"])) { ?>
		<!-- <td><b>Naikinti</b></td> --> 
		<?php //} ?> 
		</tr>
<?php
	

	
	$i=0; 
	// spausdinamas žinučių sąrašas 
    while($row_airlines = mysqli_fetch_assoc($result_4)) 
	{	 
	    $ID=$row_airlines['ID']; 
		$Name=$row_airlines['Name']; 
		$ID_ISO=$row_airlines['ID_ISO']; 
		
		$countryName = null;
		$result_countries = mysqli_query($db, $sql_countries); // restartinam $result_3 
		while($row_countries = mysqli_fetch_assoc($result_countries))
		{
			if($row_countries['ID_ISO']==$ID_ISO)
			{
				$countryName = $row_countries['Name'];
			}
		}
		
		echo "<tr><td>";
		
		echo $i. "</td><td>"; 
		echo $Name. "</td><td>";  
		echo $countryName."</td>";
		// echo "<input type=\"submit\" value=\"placiau\" name=\"placiau_".$ID."\">"; 
		//  echo "<input type=\"submit\" name=$ID value=$ID >";
		
		$expiration = false; 
		// if($ID_ISO <= date("Y-m-d"))
		// {
			// $expiration = true; 
			// echo "<td>nebegalioja</td>";
		// }
		// else
		// {
			// $expiration = false; 
			// echo "<td>galioja</td>";
		// }
		// if((!$expiration) || ($expiration && $_SESSION['ulevel'] == $user_roles["Kontrolierius"]))
		// {
			// echo "<td><label class=\"container\"><input type=\"checkbox\" class=\"container\" name=\"placiau_".$ID."\"><span class=\"checkmark\"></span></label></td>"; 
		
			echo "<td><input type=\"submit\" value=\"Redaguoti\" class=\"container\" name=\"placiau_".$ID."\"></td>";
		
		// }
		// else 
			// echo "<td></td>"; 

		echo "<td><label class=\"container\"><input type=\"checkbox\" class=\"container\" name=\"naikinti_".$ID."\"><span class=\"checkmark\"></span></label></td>"; 
		
		$a[$i]=$ID; 
		$i++; 
		
		//if(($_SESSION['ulevel'] == $user_roles["Kontrolierius"])||($_SESSION['ulevel'] == $user_roles["Reg_vartotojas"])) 
		//	echo "<td><input type=\"checkbox\" name=\"naikinti_".$ID."\"></td>";
	}
	
 ?> 
 
 <!-- JavaScript kodukas skirtas checkbox'inimui --> 
<script language="JavaScript">
function toggle(source) {

	//<?php echo $a; 
	//print_r("--------"); ?> 
	
	var aaa = <?php echo json_encode($a); ?>; 
	
	var j;
	
	// document.write(aaa); 
	
	for (j = 0; j < aaa.length; j++) { 
		// text += cars[i] + "<br>"; 
		var naikinti = "naikinti_" + aaa[j]; 
		checkboxes = document.getElementsByName(naikinti); 
		for(var i=0, n=checkboxes.length;i<n;i++) {
			checkboxes[i].checked = source.checked;
		}
	} 
} 
</script> 

 <!-- Užžymėti visus <br/> -->
		
        </table>
        <br> <input type="submit" name="Vykdyti" class=v value="Ištrinti Pažymėtus">
        </form>
    </body></html> 
