<?php
//nustatymai.php

//header('Content-Type: text/html; charset=utf-8'); // LIETUVIŲ KALBOS AKTYVAVIMAS 

// čia apibrėžiami database duomenys 
/*
define("DB_SERVER", "https://remotemysql.com/phpmyadmin/index.php");
define("DB_USER", "GLAyOHcjc4");
define("DB_PASS", "tpgcb5PXNJ");
define("DB_NAME", "GLAyOHcjc4");
*/
define("DB_SERVER", "localhost");
define("DB_USER", "id17330113_sistema");
define("DB_PASS", "DQgV3qO{6g?KrRz<");
define("DB_NAME", "d17330113_orouostusistema");
//define("TBL_USERS", "vartotojas");
//define("TBL_MESSAGES", "zinute");
//define("TBL_POSTERS", "skelbimas");
//define("TBL_VIEWS", "perziureta");
 
$user_roles=array(      // vartotojų rolių vardai lentelėse ir  atitinkamos userlevel reikšmės
	"Administratorius"=>"9",
	"Reg_vartotojas"=>"4",
	"Kontrolierius"=>"5",);   // galioja ir vartotojas "guest", kuris neturi userlevel
define("DEFAULT_LEVEL","Reg_vartotojas");  // kokia rolė priskiriama kai registruojasi
define("SPECIAL_LEVEL","Kontrolierius");  // kokia rolė priskiriama kai registruojasi
define("ADMIN_LEVEL","Administratorius");  // kas turi vartotojų valdymo teisę
define("UZBLOKUOTAS","255");      // vartotojas negali prisijungti kol administratorius nepakeis rolės

$uregister="both";  // kaip registruojami vartotojai
// self - pats registruojasi, admin - tik ADMIN_LEVEL, both - abu atvejai

// * Email Constants - 
define("EMAIL_FROM_NAME", "Demo");
define("EMAIL_FROM_ADDR", "demo@ktu.lt");
define("EMAIL_WELCOME", false);

?>
