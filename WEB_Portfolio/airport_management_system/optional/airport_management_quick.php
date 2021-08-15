
<link href="include/styles_extra.css" rel="stylesheet" type="text/css" > 

<?php
// admin.php
// vartotojų įgaliojimų keitimas ir naujo vartotojo registracija, jei leidžia nustatymai
// galima keisti vartotojų roles, tame tarpe uzblokuoti ir/arba juos pašalinti
// sužymėjus pakeitimus į procadmin.php, bus dar perklausta

session_start();
include("include/settingsdb.php");
include("include/functions.php");
// cia sesijos kontrole
// if (!isset($_SESSION['prev']) || ($_SESSION['ulevel'] != $user_roles[ADMIN_LEVEL]))   { header("Location: logout.php");exit;}
// $_SESSION['prev']="admin.php";
?>
 
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Oro uostų tvarkymas</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" >
    </head>
    <body>
        <table class="center" ><tr><td>
            <center><img src="include/top.png"></center>
            </td></tr><tr><td>
		<center><font size="5">Oro uostų redagavimas</font></center></td></tr></table> <br>
		<center><b><?php echo $_SESSION['message']; ?></b></center>
		<form name="vartotojai" action="procadmin.php" method="post">
	    <table class="center" style=" width:75%; border-width: 2px; border-style: solid; background-color: #FFF7B7">
		         <tr><td width=30%><a href="index.php"><input type="button" class="v" value="Atgal" ></a></td><td width=30%> 
	<?php
		   if ($uregister != "self") echo "<a href=\"register.php\"><input type=\"button\" class=\"v\" value=\"Registruoti naują vartotoją\" /></a><td>";
		   else echo "</td>";
	?>
		   
			<td width="30%" style=" background-color: #996633; color: #F8E9FC; border-radius:3px 3px 3px 3px; padding: 5px 11px; display: inline-block; font-size: 12px; margin: 4px 2px; " >Atlikite reikalingus pakeitimus</td><td width="10%"> <input type="submit" class="v" value="Vykdyti"></td></tr></table> <br> 
<?php
    
	$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$sql_airports = "SELECT ID,Name,ID_ISO,Location "
            . "FROM " . TBL_AIRPORTS . " ORDER BY Name ASC"; 
			$result_2 = mysqli_query($db, $sql_airports);
			if (!$result_2 || (mysqli_num_rows($result_2) < 1)) 
			{echo "Klaida skaitant lentelę airports"; exit;}
			
			$sql_countries = "SELECT ID_ISO,Name "
            . "FROM " . TBL_COUNTRIES . " ORDER BY Name ASC"; 
			$result_countries = mysqli_query($db, $sql_countries);
			if (!$result_countries || (mysqli_num_rows($result_countries) < 1)) 
			{echo "Klaida skaitant lentelę airports"; exit;}
				
			$sql_airports_airlines_relations = "SELECT ID_Airports,ID_Airlines "
					. "FROM " . TBL_AIRPORTS_AIRLINES_RELATIONS . " ORDER BY ID_Airports";
			$result_3 = mysqli_query($db, $sql_airports_airlines_relations);
			if (!$result_3 || (mysqli_num_rows($result_3) < 1)) 
				{echo "Klaida skaitant lentelę airlines"; exit;} 
			
			$sql_airlines = "SELECT ID,Name,ID_ISO "
					. "FROM " . TBL_AIRLINES . " ORDER BY Name";
			$result_4 = mysqli_query($db, $sql_airlines);
			if (!$result_4 || (mysqli_num_rows($result_4) < 1)) 
				{echo "Klaida skaitant lentelę airlines"; exit;} 
			
?>
    
			<table class="center"  border="1" cellspacing="0" cellpadding="3">
    <tr><th><b>Oro uostas</b></th><th><b>Šalis</b></th><th><b>Lokacija</b></th><th><b>Avialinijos</b></th>
	<!-- <th><b>Šalinti?</b></th> --> 
	</tr>
<?php
		$counter = 0;
		
		$new_array = array();
		$k=0;
		while($row_countries = mysqli_fetch_assoc($result_countries))
		{
			 $new_array[$k] = $row_countries['ID_ISO'];
			 $k++;
		}
		
        while($row = mysqli_fetch_assoc($result_2)) 
	    {	 
	    $ID=$row['ID']; 
	  	$Name= $row['Name'];
	  	$ID_ISO = $row['ID_ISO'];
		$Location = $row['Location']; // ikelia standarta 
		
		$result_3 = mysqli_query($db, $sql_airports_airlines_relations); // restartinam $result_3 
		
		$airlines_array = array();
		
		$n=0;
		while($row_3 = mysqli_fetch_assoc($result_3))
		{
			if($row_3['ID_Airports'] == $ID)
			{
				//$airlines_array[$row_3['ID_Airlines'].($i+1)] = $GLOBALS[$row_3['ID_Airlines'].($i+1)];
				$airlines_array[$n++] = $row_3['ID_Airlines'];
			}
		}	
		
		$result_4 = mysqli_query($db, $sql_airlines); // restartinam $result_3 
		$length=count($airlines_array);
		$z=0;
		while($row_4 = mysqli_fetch_assoc($result_4))
		{
			for($j = 0; $j < $length; $j++)
			{
				if($airlines_array[$j]==$row_4['ID'])
				{
					// echo $airlines_name_array[$z];
					$airlines_name_array[$z++] =$row_4['Name'];
				}
			}
		}
		
		
		
      	//$time = date("Y-m-d G:i", strtotime($row['timestamp']));
		echo '<tr><td><input type="text" name="name1" value="'.$Name.'"></td><td>';
		//echo "<tr><td>".$Name. "</td><td>";
    	echo "<select name=\"role_".$Name."\">";
      	//$yra=false;
		$allow =true;
		
		
			// $sql_countries = "SELECT ID_ISO,Name "
            // . "FROM " . TBL_COUNTRIES . " ORDER BY Name ASC"; 
			// $result_countries = mysqli_query($db, $sql_countries);
			// if (!$result_countries || (mysqli_num_rows($result_countries) < 1)) 
			// {echo "Klaida skaitant lentelę airports"; exit;}
		
		// reikia pazymeti ta kuris is tiesu yra ir isvardyti kitus
		// while($row_countries = mysqli_fetch_assoc($result_countries)) 
	    // {	
			// if($allow==true)
			// {
				// $allow =false;
				// $counter++;
			// }
			// $row_countries_2 = $row_countries['ID_ISO'];
			// echo "<option ";
			// if ($row_countries_2 == $ID_ISO) {
				// echo "selected ";
			// }
			
			// echo "value=\"".$row_countries_2."\" ";
			// echo ">".$row_countries_2."</option>";
		// }
		
		for($i=0; $i<count($new_array); $i++)
	    {	
			if($allow==true)
			{
				$allow =false;
				$counter++;
			}
			$row_countries_2 = $new_array[$i];
			echo "<option ";
			if ($row_countries_2 == $ID_ISO) {
				echo "selected ";
			}
			
			echo "value=\"".$row_countries_2."\" ";
			echo ">".$row_countries_2."</option>";
		}
		
		//mysql_data_seek($result_countries, 0); 
		
		// foreach($user_roles as $x=>$x_value)
  			// {echo "<option ";
        	 // if ($x_value == $ID) {$yra=true;echo "selected ";}
             // echo "value=\"".$x_value."\" ";
         	 // echo ">".$x."</option>";
        	 // }
		// if (!$yra)
        // {
			// // parodo Neegzistuoja = 255 (turbūt šitas reikalingas trynimo operacijai (gal)) 
			// // echo "<option selected value=".$ID.">Neegzistuoja=".$ID."</option>";
	// }
        // $UZBLOKUOTAS=UZBLOKUOTAS; echo "<option ";
        // // if ($ID == UZBLOKUOTAS)
			// // echo "selected ";
        // echo "value=".$UZBLOKUOTAS." ";
        // echo ">-</option>";      // papildoma opcija
      echo "</select></td>";
	  $yooo = implode(" ",$airlines_name_array);
	  echo '<td><input type="text" name="name1" value="'.$Location.'"></td>';
	  echo '<td>'.$yooo.'</td>';
		// echo '<td><input type="text" name="name1" value="'.$yooo.'"></td>';
		
		// echo "<td><select name=\"role_".$Name."\">";
		// for($i=0; $i<count($airlines_name_array); $i++)
	    // {	
			// if($allow==true)
			// {
				// $allow =false;
				// $counter++;
			// }
			// $row_countries_2 = $airlines_name_array[$i];
			// echo "<option ";
			// if ($row_countries_2 == $ID_ISO) {
				// echo "selected ";
			// }
			
			// echo "value=\"".$row_countries_2."\" ";
			// echo ">".$row_countries_2."</option>";
		// }
		
		
	  if ($Location == 1) 
	  	  echo "<td><label class=\"container\"><input type=\"checkbox\" name=\"keisti_".$Name."\" checked onClick=\"toggle(this)\" /><span class=\"checkmark\"></span></label>";
	  else
		  echo "<td><label class=\"container\"><input type=\"checkbox\" name=\"keisti_".$Name."\" onClick=\"toggle(this)\" /><span class=\"checkmark\"></span></label>";
		}
		echo "Count: " . $counter;
?>
        </table>
        <br> <input type="submit" class="v" value="Vykdyti">
        </form>
    </body></html>
