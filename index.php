<?php 
//inclure ce dont on a besoin
include 'user.php';

//création d'un nouvel user avec 'new'
$marie = new user();
//enregistrer les infos du nouvel user
/*$marie->register('marielol', 'mariepass','marie.clerc@laplateforme.io','marie', 'clerc');
//dump les infos dans un tableau
var_dump($marie);*/

//-------------------------------------------------------------------------

/*$marie->connect('marielol', 'mariepass');
//dump les infos dans un tableau
var_dump($marie);*/

$marie->disconnect();



?>