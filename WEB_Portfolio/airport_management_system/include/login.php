
<link href="include/styles_extra.css" rel="stylesheet" type="text/css" > 

<?php 
// login.php - tai prisijungimo forma, index.php puslapio dalis 
// formos reikšmes tikrins proclogin.php. Esant klaidų pakartotinai rodant formą rodomos klaidos
// formos laukų reikšmės ir klaidų pranešimai grįžta per sesijos kintamuosius
// taip pat iš čia išeina priminti slaptažodžio.
// perėjimas į registraciją rodomas jei nustatyta $uregister kad galima pačiam registruotis

if (!isset($_SESSION)) { header("Location: logout.php");exit;}

$_SESSION['prev'] = "login.php"; 

include("include/nustatymai.php");
?>
		<!-- PROCLOGIN !!!!! --> 
		<form action="proclogin.php" method="POST" class="login">             
        <center style="font-size:18pt;"><b>Prisijungimas</b></center>
        <p style="text-align:left;">Vartotojo vardas:<br>
            <input class ="s1" name="user" type="text" value="<?php echo $_SESSION['name_login'];  ?>"/><br>
            <?php echo $_SESSION['name_error']; 
			?>
        </p>
        <p style="text-align:left;">Slaptažodis:<br>
            <input class ="s1" name="pass" type="password" value="<?php echo $_SESSION['pass_login']; ?>"/><br>
            <?php echo $_SESSION['pass_error']; 
			?>
        </p>  
        <p style="text-align:left;">
            <input type="submit" name="login" class="v" value="Prisijungti"/>   
            <input type="submit" name="problem" class="v" value="Pamiršote slaptažodį?"/>   
        </p>
        <p>
 <?php
			if ($uregister != "admin") { echo "<a href=\"register.php\"><input type=\"button\" class=\"v\" value=\"Registracija\"/></a>";}
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"guest.php\"><input type=\"button\" class=\"v\" value=\"Svečias\"/></a>";
?>
        </p>     
    </form>
	


