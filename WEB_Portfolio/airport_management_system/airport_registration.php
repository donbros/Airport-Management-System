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
if(($_SESSION['prev'] != "airport_registration.php") && ($_SESSION['prev'] != "proc_airport_registration.php")) 
{
	$_SESSION['message'] = ""; 
}

?>

<link href="include/styles_extra.css" rel="stylesheet" type="text/css" > 

    <html>
        <head>  
            <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8"> 
            <title>Oro Uosto Registracija</title>
            <link href="include/styles.css" rel="stylesheet" type="text/css" >
			
			
		<!-- kodas skirtas google maps ir jo žymėjimui atvaizduot -->
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
        <script>
            var map;

            function initialize() {
                var mapOptions = {
                    zoom: 12,
                    center: new google.maps.LatLng(54.876, 23.882),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById('map_canvas'),
                    mapOptions
                );
                google.maps.event.addListener(map, 'click', function(event) {
                    document.getElementById('latMap').value = event.latLng.lat();
                    document.getElementById('lngMap').value = event.latLng.lng();
                });
            }
            function mapDivClicked (event) {
                var target = document.getElementById('map_canvas'),
                    posx = event.pageX - target.offsetLeft,
                    posy = event.pageY - target.offsetTop,
                    bounds = map.getBounds(),
                    neLatlng = bounds.getNorthEast(),
                    swLatlng = bounds.getSouthWest(),
                    startLat = neLatlng.lat(),
                    endLng = neLatlng.lng(),
                    endLat = swLatlng.lat(),
                    startLng = swLatlng.lng();

                document.getElementById('posX').value = posx;
                document.getElementById('posY').value = posy;
				// suapvalinimui skirti kitamieji
				var lat = startLat + ((posy/350) * (endLat - startLat));
				var lng = startLng + ((posx/500) * (endLng - startLng));
                document.getElementById('lat').value = lat.toFixed(4);
                document.getElementById('lng').value = lng.toFixed(4);
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
		
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
                        <table class="meniu" style="border-width: 2px;"><tr><td>
                           <a href="index.php"><input type="button" class="v" value="Atgal į Pradžia" > </a></td></tr>
						</table>   
							<div align="center">
								<table> 
									<tr>
										<td>
										<form action="proc_airport_registration.php" method="POST" class="login" name="add_name" id="add_name" > <!-- onsubmit="return confirm('Ar tikrai norite įkelti?')" -->             
													<center style="font-size:18pt;"><b>Oro Uosto Registracija</b></center>
											
										<!-- rubric, date, airport_name, description - visi šitie perkeliami į duombazę per proc_airport_registration -->
										
										<h3 align="left">Pavadinimas</h3> <!-- <p style="text-align:left;">Oro uosto pavadinimas:<br> -->
										<!-- tas echo $_session - yra tiesiog imetimas duomenu i ta vieta -->
										<input class ="s1"  maxlength="32" name="Name" type="text" placeholder="oro uosto pavadinimas" <?php
										echo 
										//"value=" .
										//$_SESSION['Name_login'] .
										">"
										?>
										<br>
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
										
											<!--<option value="Siūlo" <?php //if($_SESSION['rubric_login']=="Siūlo"){echo "selected"; } ?> >Siūlo</option>-->
											<!--<option value="Ieško" <?php //if($_SESSION['rubric_login']=="Ieško"){echo "selected"; } ?> >Ieško</option>-->
										
										</p>
										
										<h3 align="left">Lokacija</h3> <!-- <p style="text-align:left;">Lokacija:<br> -->
										<!-- šia gautą informaciją perduoti į duomenų bazę suvedant platumos, ilgumos duomenis -->
										<div id="map_canvas" onclick="mapDivClicked(event);" style="height: 350px; width: 500px;  margin:0 auto;"></div>
											<!-- pozicijos isspausdinamos -->
											<input type="hidden" id="posX" />
											<input type="hidden" id="posY" />
											<!-- lokacija: šitą reik perkelt į duomenų bazę -->
											<!-- tas echo session reiskia tiesiog imetima duomenu i ta vieta -->
											platuma: <input id="lat" name="lat" placeholder="platumos koordinatės"/>
											Ilguma: <input id="lng" name="lng" placeholder="ilgumos koordinates"/>
											<?php //$result = $data1 . ' ' . $data2; ?>
											<div/>
											<input type="hidden" id="latMap" />
											<input type="hidden" id="lngMap" />
										<!-- reiks imesti error type     -->
										</p>
										
										<div class="container"></div>
										<h3 align="left">Priklausančios avialinijos</h3>
										<div class="form-group">
											<!-- čia pagal tą gidą buvo taip surašyta (galima trinti) -->
											<!-- <form name="add_name" id="add_name"> -->
											<!-- tas table yra toks reikalas kad gali is kitur accesinti -->
												<table class="table table-bordered" id="dynamic_field">
													<tr>
														<td>
														<!-- <input type="text" name="name[]" id="name" placeholder="Enter Name" class="form-control name_list" /> -->
														<select name="airline[]" id="airline">
															<option value="-1">---</option> 
															<?php
															while($row=mysqli_fetch_array($result_airlines))
															{
																$emparray[] = $row;
																echo "<option value='" . $row['ID'] . "'>" . $row['Name'] . "</option>"; 
															}; ?> 
														</select></td>
														<td><button type="button" name="add" id="add" class="btn btn-success">Pridėti</</td>
													</tr>
												</table>
										</div>
										
										<!-- jQuery script'as objektų pridėjimui -->
										<script>
										$(document).ready(function(){
											var i = 1;
											// pridėjimo galimybė
											$('#add').click(function(){
												i++;
												// išsireiškiame masyvą 'jquery' priimtinu būdu
												var masyvas = <?php echo json_encode($emparray); ?>;
												
												// dinamiškai pridedamos oro linijos (airlines)
												$('#dynamic_field').append(
												'<tr id="row'+i+'"><td>'+
												'<select name="airline[]" id="aairline">'+
												'<option value="-1">---</option>'+
												$.map(masyvas, function(key,value){ return '<option value=' + key[0] +'>'+key[1]+'</option>' })+
												'</select></td>'+
												'<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>'
												);
											});
											// trynimo galimybė
											$(document).on('click', '.btn_remove', function(){
												var button_id = $(this).attr("id");
												$('#row'+button_id+'').remove();
											});
											// siuntimo/ikelimo funkcija (optional)
											// $('#submit').click(function(){
												// $.ajax({
													// url:"proc_airport_registration.php",
													// method:"POST",
													// data:$('#add_name').serialize(),
													// success:function(data)
													// {
														// // lentelė print
														// alert(data);
														// // refreshinu, kad laukeliai suveiktų
														// location.reload();
														// $('#add_name')[0].reset();
													// }
												// });
											// });
										});
										</script>
										
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
   
