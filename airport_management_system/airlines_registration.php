
<link href="include/styles_extra.css" rel="stylesheet" type="text/css" > 

<?php

include("include/nustatymai.php");
include("include/functions.php");

?>
    <html>
        <head>  
            <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8"> 
            <title>Oro linijų registracija</title>
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
                document.getElementById('lat').value = startLat + ((posy/350) * (endLat - startLat));
                document.getElementById('lng').value = startLng + ((posx/500) * (endLng - startLng));
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>
        </head>
        <body>   
                    <table class="center"><tr><td><img src="include/top.png"></td></tr><tr><td> 
                        <table class="meniu" style="border-width: 2px;"><tr><td>
                           <a href="index.php"><input type="button" class="v" value="Atgal į Pradžia" > </a></td></tr>
				    </table>   
								<div align="center">
                    			<table> <tr><td>
                                    <form action="proc_airport_registration.php" method="POST" class="login" onsubmit="return confirm('Ar tikrai norite įkelti?')";>              
                                                <center style="font-size:18pt;"><b>Oro uosto registracija</b></center>
										
									<!-- rubric, date, airport_name, description - visi šitie perkeliami į duombazę per proc_airport_registration -->
									<p style="text-align:left;">Oro uosto pavadinimas:<br>
            						<input class ="s1"  maxlength="50" name="airport_name" type="text" value="<?php echo $_SESSION['airport_name_login'];  ?>"><br>
           							<?php echo $_SESSION['airport_name_error']; ?>
        							</p>
									<p style="text-align:left;">Šalis:<br>
            						<!--<input class ="s1" name="rubric" type="text" value="<?php //echo $_SESSION['rubric_login'];  ?>"><br>-->
									<select name="rubric" >
									<option value="Siūlo" <?php if($_SESSION['rubric_login']=="Siūlo"){echo "selected"; } ?> >Siūlo</option>
									<option value="Ieško" <?php if($_SESSION['rubric_login']=="Ieško"){echo "selected"; } ?> >Ieško</option>
								    </select>
           							<?php echo $_SESSION['rubric_error']; ?>
        							</p>
									<p style="text-align:left;">Lokacija:<br>
									<!-- šia gautą informaciją perduoti į duomenų bazę suvedant platumos, ilgumos duomenis -->
									<div id="map_canvas" onclick="mapDivClicked(event);" style="height: 350px; width: 500px;  margin:0 auto;"></div>
										<!-- pozicijos isspausdinamos -->
										<input type="hidden" id="posX" />
										<input type="hidden" id="posY" />
										<!-- lokacija -->
										platuma: <input id="lat" />
										Ilguma: <input id="lng" />
										<div />
										<input type="hidden" id="latMap" />
										<input type="hidden" id="lngMap" />
									<!-- reiks imesti error type     -->
        							</p>
									<!-- avialinijos turėtų būti su add mygtuku, reikės implementinti, kaip būna internete -->
									<p style="text-align:left;">Priklausančios avialinijos:<br>
									</p?
									
									<?php
									//	 if ($_SESSION['ulevel'] == $user_roles[ADMIN_LEVEL] )
									//	{echo "<p style=\"text-align:left;\">Rolė<br>";
									//	 echo "<select name=\"role\">";
   									//   	 foreach($user_roles as $x=>$x_value)
  									//		{echo "<option ";
        	 						//			if ($x == DEFAULT_LEVEL) echo "selected ";
             					    //			echo "value=\"".$x_value."\" ";
         	 						//			echo ">".$x."</option></p>";
									//		}
									//	}
									?>
                      	
                                    <p style="text-align:left;">
                                    <input type="submit" class="v" value="Įkelti">
                                    </p>
                                    </form>
                                    </td></tr>
			                    </table>
                             </div>
                </td></tr>
                </table>           
        </body>
    </html>
   
