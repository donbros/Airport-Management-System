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
		include("include/meniu.php"); //įterpiamas meniu pagal vartotojo rolę
 			$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
			
			$sql_2 = "SELECT posterid,rubric,expiration_date,airport_name,description,fk_userid "
            . "FROM " . TBL_POSTERS . " ORDER BY expiration_date DESC"; 
			$result_2 = mysqli_query($db, $sql_2);
			if (!$result_2 || (mysqli_num_rows($result_2) < 1)) 
			{echo "Klaida skaitant lentelę users"; exit;}
				
			$sql_3 = "SELECT userid,realname,surname,username,userlevel,post,email,timestamp "
            . "FROM " . TBL_USERS . " ORDER BY realname";
			$result_3 = mysqli_query($db, $sql_3);
			if (!$result_3 || (mysqli_num_rows($result_3) < 1)) 
			{echo "Klaida skaitant lentelę users"; exit;} 	
 ?> 
		</table>
    <table class="center" border="1" cellspacing="0" cellpadding="12">
	<?php $tarpas = ""; 
		$pazymeti = "Pažymėti visus"; 
		echo "<tr><th>".$tarpas. "</th><th>";  
		echo $tarpas."</th><th>";
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
		echo $tarpas."</td><td>"; 
		echo "<label class=\"container\"><input type=\"checkbox\" onClick=\"toggle(this)\" /><span class=\"checkmark\"></span></label></td>"; ?>
    <tr><th><b>Oro uosto pavadinimas</b></th><th><b>Šalis</b></th><th><b>Lokacija</b></th><th><b>Avialinijų sąrašas</b></th><th><b>---</b></th><th><b>---</b></th> 
		<?php //if(($_SESSION['ulevel'] == $user_roles["Kontrolierius"])||($_SESSION['ulevel'] == $user_roles["Reg_vartotojas"])) { ?>
		<!-- <td><b>Naikinti</b></td> --> 
		<?php //} ?> 
		</tr>
<?php
	

	
	$i=0; 
	// spausdinamas žinučių sąrašas 
    while($row_2 = mysqli_fetch_assoc($result_2)) 
	{	 
	    $posterid=$row_2['posterid']; 
		$rubric=$row_2['rubric']; 
		$expiration_date=$row_2['expiration_date']; 
		$airport_name=$row_2['airport_name'];

		$result_3 = mysqli_query($db, $sql_3); // restartinam $result_3 
		while($row_3 = mysqli_fetch_assoc($result_3))
		{
			if($row_3['userid'] == $row_2['fk_userid'])
			{
				$poster_owner = $row_3['username']; 
			}
		}	
		
		echo "<tr><td>".$rubric. "</td><td>";  
		echo $expiration_date."</td><td>";
		echo $airport_name."</td><td>";
		echo $poster_owner."</td>";
		// echo "<input type=\"submit\" value=\"placiau\" name=\"placiau_".$posterid."\">"; 
		//  echo "<input type=\"submit\" name=$posterid value=$posterid >";
		
		$expiration = false; 
		if($expiration_date <= date("Y-m-d"))
		{
			$expiration = true; 
			echo "<td>nebegalioja</td>";
		}
		else
		{
			$expiration = false; 
			echo "<td>galioja</td>";
		}
		if((!$expiration) || ($expiration && $_SESSION['ulevel'] == $user_roles["Kontrolierius"]))
		{
			echo "<td><label class=\"container\"><input type=\"checkbox\" class=\"container\" name=\"placiau_".$posterid."\"><span class=\"checkmark\"></span></label></td>"; 
		}
		else 
			echo "<td></td>"; 

		$a[$i]=$posterid; 
		$i++; 
		
		//if(($_SESSION['ulevel'] == $user_roles["Kontrolierius"])||($_SESSION['ulevel'] == $user_roles["Reg_vartotojas"])) 
		//	echo "<td><input type=\"checkbox\" name=\"naikinti_".$posterid."\"></td>";
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
		var placiau = "placiau_" + aaa[j]; 
		checkboxes = document.getElementsByName(placiau); 
		for(var i=0, n=checkboxes.length;i<n;i++) {
			checkboxes[i].checked = source.checked;
		}
	} 
} 
</script> 

 <!-- Užžymėti visus <br/> -->
		
        </table>
        <br> <input type="submit" name="Vykdyti" class=v value="Vykdyti">
        </form>
    </body></html> 
