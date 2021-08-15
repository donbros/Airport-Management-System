<?php
// index.php
// jei vartotojas prisijungęs rodomas demonstracinis meniu pagal jo rolę
// jei neprisijungęs - prisijungimo forma per include("login.php");
// toje formoje daugiau galimybių...

session_start();
include("include/functions.php");

?>

<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Oro uostų valdymo sistema</title>
        <link href="include/styles.css" rel="stylesheet" type="text/css" medsssssssia="all" > 
		
    </head>
    <body>
        <table class="center" ><tr><td>
            <center><img src="include/top.png"></center>
        </td></tr><tr><td> 
		
		<?php       
		$_SESSION['prev']="index.php"; 
        include("include/meniu.php"); //įterpiamas meniu pagal vartotojo rolę
		?>
		
		<div style="text-align: center; color:blue">
        <br><br>
			<h1>Pradinis sistemos apie oro uosto sistemos valdymo puslapis.</h1>
		</div><br>
    </body>
</html>
