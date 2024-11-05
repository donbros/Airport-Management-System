<?php
//settingsdb.php aka nustatymai.php

// header('Content-Type: text/html; charset=utf-8'); // LIETUVIŲ KALBOS AKTYVAVIMAS 

// čia apibrėžiami database duomenys (senieji) MYSQLI
define("DB_SERVER", "localhost");
define("DB_USER", "id17330113_sistema");
define("DB_PASS", "Kosmosaszxc-000");
define("DB_NAME", "id17330113_orouostusistema");

// MSSQL
define("DB_SERVER_SQLSRV", "DOMANTAS-PC\\DOMANTAS");
define("DB_USER_SQLSRV", "sa");
define("DB_PASS_SQLSRV", "x");
define("DB_NAME_SQLSRV", "DonbrosWebsitePHP");

// šitie airports, airlines, countries ir airports_airlines_relations yra realūs duombazės lentelių pavadinimai
define("TBL_AIRPORTS", "airports");
define("TBL_AIRLINES", "airlines");
define("TBL_COUNTRIES", "countries");
define("TBL_AIRPORTS_AIRLINES_RELATIONS", "airports_airlines_relations");

//$user_roles=array(      // vartotojų rolių vardai lentelėse ir  atitinkamos userlevel reikšmės
//	"Administratorius"=>"9",
//	"Reg_vartotojas"=>"4",
//	"Kontrolierius"=>"5",);   // galioja ir vartotojas "guest", kuris neturi userlevel
//define("DEFAULT_LEVEL","Reg_vartotojas");  // kokia rolė priskiriama kai registruojasi
//define("SPECIAL_LEVEL","Kontrolierius");  // kokia rolė priskiriama kai registruojasi
//define("ADMIN_LEVEL","Administratorius");  // kas turi vartotojų valdymo teisę
//define("UZBLOKUOTAS","255");      // vartotojas negali prisijungti kol administratorius nepakeis rolės

$uregister="both";  // kaip registruojami vartotojai
// self - pats registruojasi, admin - tik ADMIN_LEVEL, both - abu atvejai

// * Email Constants - 
//define("EMAIL_FROM_NAME", "Demo");
//define("EMAIL_FROM_ADDR", "demo@ktu.lt");
//define("EMAIL_WELCOME", false);

?>
