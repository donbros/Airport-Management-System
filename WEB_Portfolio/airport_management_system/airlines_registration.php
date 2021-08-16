<?php

session_start(); 

// nauja uzduotis: padaryti apsaugas nuo neteisingai įvedamų duomenų; 
// kol kas visai nesutvarkyta lokacija ir galima vesti ką tik nori; 
// galima pagrazinti ir priklausancias avialinijas, nors su jomis visai neblogai (nebent paprasyti nevesto duplikatu arba pasirinkus viena, kitą panaikinti (cia jau fancy labai gal veliau gal nzn)
// problema: po postinimo nereloadina del to error veluoja

// duomenų bazės baziniai dalykai
include("include/settingsdb.php");
// funkcijos dirbat su DB
include("include/functions.php");
if(($_SESSION['prev'] != "airlines_registration.php") && ($_SESSION['prev'] != "proc_airlines_registration.php")) 
{
	$_SESSION['message'] = ""; 
}
// atnaujins isejus i pagr. meniu 
if ($_SESSION['prev'] != "proc_airlines_registration.php") { inisession("part"); } // pradinis bandymas registruoti
$_SESSION['prev']="airlines_registration.php";
?>

<link href="include/styles_extra.css" rel="stylesheet" type="text/css" > 

    <html>
        <head>  
            <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8"> 
            <title>Oro Linijų Registracija</title>
            <link href="include/styles.css" rel="stylesheet" type="text/css" >
		
		<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>

		<?php
			$db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			$db ->set_charset("utf8"); // LIETUVIŲ KALBOS AKTYVAVIMAS 
			
			$sql_airlines = "SELECT ID,Name,ID_ISO "
					. "FROM " . TBL_AIRLINES . " ORDER BY Name";
			$result_airlines = mysqli_query($db, $sql_airlines);
			if (!$result_airlines || (mysqli_num_rows($result_airlines) < 1)) 
				{echo "Klaida skaitant lentelę airlines"; exit;} 
			
			$sql_countries = "SELECT ID_ISO,Name "
					. "FROM " . TBL_COUNTRIES . " ORDER BY Name";
			$result_countries = mysqli_query($db, $sql_countries);
			if (!$result_countries || (mysqli_num_rows($result_countries) < 1)) 
				{echo "Klaida skaitant lentelę airlines"; exit;} 
		?>
		
        </head>
        <body>   
                <table class="center"><tr><td><img src="include/top.png"></td></tr>
					<tr><td> 
                        <table align="center" class="meniu" style="border-width: 2px;"><tr><td>
                           <a href="index.php"><input type="button" class="v" value="Atgal į Pradžia" > </a></td></tr>
						</table>   
							<div align="center">
								<table> 
									<tr>
										<td>
										<form action="proc_airlines_registration.php" method="POST" class="login" name="add_name" id="add_name" > <!-- onsubmit="return confirm('Ar tikrai norite įkelti?')" -->             
													<center style="font-size:18pt;"><b>Oro Linijų Registracija</b></center>
											
										<!-- rubric, date, airport_name, description - visi šitie perkeliami į duombazę per proc_airlines_registration -->
										
										<h3 align="left">Pavadinimas</h3> <!-- <p style="text-align:left;">Oro uosto pavadinimas:<br> -->
										<!-- tas echo $_session - yra tiesiog imetimas duomenu i ta vieta -->
										<input class ="s1"  maxlength="32" name="Name" type="text" placeholder="oro linijų pavadinimas" value=""><br>
										<!-- Parodo atitinkamą error'ą -->
										<?php echo $_SESSION['Name_error']; ?>
										</p>
										
										<h3 align="left">Šalis</h3> <!-- <p style="text-align:left;">Šalis:<br> -->
										<!--<input class ="s1" name="rubric" type="text" value="<?php //echo $_SESSION['rubric_login'];  ?>"><br>-->
										<select name="ID_ISO" >
											<option value="-1">---</option> 
											<?php
											while($row=mysqli_fetch_array($result_countries))
											{
												echo "<option value='" . $row['ID_ISO'] . "'>" . $row['Name'] . "</option>"; 
											}; ?> 
										</select>
										
										<?php echo $_SESSION['ID_ISO_error']; ?>
										
										</p>
										<p style="text-align:left;">
										<!-- nepalikti šito submit, jei jau jQuery naudojam nes gaunasi dvigubas submitinimas -->
										<input type="submit" name="submit" id="submit" class="v" value="Įkelti">
										</p>
										</form>
										</td>
									</tr>
								</table>
							</div>
					</td></tr>
				</table>           
        </body>
    </html>
   
