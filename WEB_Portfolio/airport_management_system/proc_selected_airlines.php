<!DOCTYPE html> 

<?php
// proc_airport_management.php parodoma išsamesnė pasirinktų skelbimų informacija 
	session_start(); 
	
	$_SESSION['prev'] = "proc_selected_airlines.php"; // pažymime kad šitas puslapis yra "buvęs puslapis"

	include("include/settingsdb.php");
	include("include/functions.php");
 
	$_SESSION['ID_ISO_error']=""; 
	
	// galime tikrinimą pagal ID_ISO daryti, nes vis tiek jį visada įvedinėja 
	if(isset($_POST['Name']))
	{
		$ID_ISO_Selected=$_POST['ID_ISO'];
		
		// pasiemimas I (retai trinamus) sesijos kintamuosius
		$_SESSION['ID_ISO_login']=$_POST['ID_ISO'];  
	}
	else
	{
		// pasiemimas IS (retai trinamu) sesijos kintamuju
		$ID_ISO_Selected=$_SESSION['ID_ISO_login'];
	}
	
	// prisijungiame prie duomenų bazės 
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
	
// header('Content-Type: text/html; charset=utf-8'); // LIETUVIŲ KALBOS AKTYVAVIMAS 

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
			
			$sql_airports = "SELECT ID,Name,ID_ISO,Location "
					. "FROM " . TBL_AIRPORTS . " ORDER BY ID ASC"; 
			$result_airports = mysqli_query($db, $sql_airports);
			if (!$result_airports || (mysqli_num_rows($result_airports) < 1)) 
				{echo "Klaida skaitant lentelę airports"; exit;}
					
			$sql_airports_airlines_relations = "SELECT ID_Airports,ID_Airlines "
					. "FROM " . TBL_AIRPORTS_AIRLINES_RELATIONS . " ORDER BY ID_Airports";
			$result_airports_airlines_relations = mysqli_query($db, $sql_airports_airlines_relations);
			if (!$result_airports_airlines_relations || (mysqli_num_rows($result_airports_airlines_relations) < 1)) 
				{echo "Klaida skaitant lentelę airlines"; exit;} 
					
	if($_POST['ID_ISO']!=-1){			
 ?> 
		</table>
    <table class="center" border="1" cellspacing="0" cellpadding="12">
		<tr><th><b>Nr.</b></th>
		<th><b>Oro Linijos Pavadinimas</b></th>
		<th><b>Šalis</b></th>
		<?php //if(($_SESSION['ulevel'] == $user_roles["Kontrolierius"])||($_SESSION['ulevel'] == $user_roles["Reg_vartotojas"])) { ?>
		<!-- <td><b>Naikinti</b></td> --> 
		<?php //} ?> 
		</tr>
<?php
	
	// echo " " . $_POST['ID_ISO'] . " ";
	//$array = [];
	$i=0; 
	// $value=0;
	// spausdinamas žinučių sąrašas 
	$rado = false;
    while($row_airlines = mysqli_fetch_assoc($result_4)) 
	{	 
	    $ID=$row_airlines['ID']; 
		$Name=$row_airlines['Name']; 
		$ID_ISO=$row_airlines['ID_ISO']; 
		// echo $_POST['ID_ISO'] . "==" . $ID_ISO. "; ";
		if ($_POST['ID_ISO']==$ID_ISO)
		{
			$rado=true;
			echo "<tr>";
			echo "<td>";
			$result_AAR = mysqli_query($db, $sql_airports_airlines_relations); // restartinam $result_3 
			while($row_AAR = mysqli_fetch_assoc($result_AAR))
			{
				
				if($ID == $row_AAR['ID_Airlines'])
				{
					// rodo oro uostus bet čia bus pasikartojančių, reik padaryti, kad nesikartotų
					echo $row_AAR['ID_Airports'] . " "; 
					if(!in_array($row_AAR['ID_Airports'], $array, true)){
						$array[] = $row_AAR['ID_Airports'];
					}
				}
			}
			echo "</td>";
			
			echo "<td>" . $i. "</td>";
			echo "<td>";  
			echo $Name."</td></tr>";
			$a[$i]=$ID; 
			$i++; 
		}
	}
	if($rado == false)
	{
		echo "<tr>";
		echo "<td>-</td>";
		echo "<td>-</td>";
		echo "<td>-</td></tr>";
	}
	}
	if(count($array)>0){
	?>
	<table class="center" border="1" cellspacing="0" cellpadding="12">
		<tr><th><b>Nr.</b></th>
		<th><b>Oro Linijos Pavadinimas</b></th>
		<th><b>Šalis</b></th>
		<?php //if(($_SESSION['ulevel'] == $user_roles["Kontrolierius"])||($_SESSION['ulevel'] == $user_roles["Reg_vartotojas"])) { ?>
		<!-- <td><b>Naikinti</b></td> --> 
		<?php //} ?> 
		</tr>
<?php
	
	 
	// spausdinamas žinučių sąrašas 
    for($i=0; $i<count($array); $i++)
	{	 
	    
			echo "<tr>";
			echo "<td>" . $i . "</td>";
			echo "<td>";
			$result_airports = mysqli_query($db, $sql_airports); // restartinam $result_3 
			while($row_airports = mysqli_fetch_assoc($result_airports))
			{
				
				if($array[$i] == $row_airports['ID'])
				{
					echo $row_airports['Name'];
				}
			}	
			echo "</td>";
			echo "<td>" . $array[$i] . "</td>";
			echo "</tr>";  
	}		
 ?> 
	<?php } ?>
 <!-- JavaScript kodukas skirtas checkbox'inimui --> 
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

 <!-- Užžymėti visus <br/> -->
		
        </table>
        <br> 
        </form>
    </body></html> 
