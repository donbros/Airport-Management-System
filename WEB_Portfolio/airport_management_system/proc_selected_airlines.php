<!DOCTYPE html> 
<!--
Description: puslapyje spausdinami oro uostai į kuriuos skrenda tik pasirinktos šalies avialinijos / oro linijos.
-->
<?php
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

<!--$a=array();-->	

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
		?> 
		</table>
		<?php
		$table_top = "<table class=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"12\">
		<tr><th><b>Nr.</b></th>
		<th><b>Oro linijos pavadinimas</b></th>
		<th><b>Priklauso oro uostams (su id)</b></th>
		</tr>"; 
		$i=0; 
		$rado = false;
		while($row_airlines = mysqli_fetch_assoc($result_4)) // spausdinamas atrinktų pagal šalį oro linijų sąrašas 
		{	 
			$ID=$row_airlines['ID']; 
			$Name=$row_airlines['Name']; 
			$ID_ISO=$row_airlines['ID_ISO']; 
			// echo $_POST['ID_ISO'] . "==" . $ID_ISO. "; ";
			if ($_POST['ID_ISO']==$ID_ISO)
			{
				$rado=true;
				$StartEcho[] = "<tr>";
				$OroLinijosEcho1[] = "<td>";
				$result_AAR = mysqli_query($db, $sql_airports_airlines_relations); // restartinam $result_3 
				while($row_AAR = mysqli_fetch_assoc($result_AAR))
				{
					
					if($ID == $row_AAR['ID_Airlines'])
					{
						// rodo oro uostus bet čia bus pasikartojančių, reik padaryti, kad nesikartotų
						$OroLinijosEcho2[] = $row_AAR['ID_Airports'] . " "; 
						if(!in_array($row_AAR['ID_Airports'], $array, true)){
							$array[] = $row_AAR['ID_Airports'];
						}
					}
				}
				$OroLinijosEcho3[] = "</td>";
				$IndexEcho[] = "<td>" . $index=$i+1 . "</td>";
				$NameEcho[] = "<td>" . $Name."</td>";
				$EndEcho[] = "</tr>";
				$i++; 
			}
		}
		if($rado == false)
		{
			echo "<table class=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"12\">
			<tr><th><b>Sąrašas tuščias</b></th>
			</tr></table>"; 
		}
		else
		{
			echo $table_top;
			for($i=0; $i<count($IndexEcho); $i++)
			{
				echo $StartEcho[$i] . $IndexEcho[$i] . $NameEcho[$i] . $OroLinijosEcho1[$i] . $OroLinijosEcho2[$i] . $OroLinijosEcho3[$i] . $EndEcho[$i];
			}
		}
	
	if(count($array)>0){
	?>
		<table class="center" border="1" cellspacing="0" cellpadding="12">
		<tr><th><b>Nr.</b></th>
		<th><b>Oro uosto pavadinimas</b></th>

		</tr>
	<?php
	
		// spausdinamas žinučių sąrašas 
		for($i=0; $i<count($array); $i++)  // spausdinamas atrinktų pagal oro linijas oro uostų sąrašas 
		{	 
			echo "<tr>";
			echo "<td>" . $index=$i+1 . "</td>";
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
			echo "</tr>";  
		}		
		?> 
	<?php
	}
	?>
        </table>
        <br> 
        </form>
    </body>
</html> 
