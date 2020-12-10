<?php 
//inclure ce dont on a besoin
include 'user.php';

//création d'un nouvel user avec 'new'
$marie = new user();
//enregistrer les infos du nouvel user
//$marie->register('marielolabc', 'mariepass','marie.clerc@laplateforme.io','marie', 'clerc');
//dump les infos dans un tableau
//var_dump($marie);

//-------------------------------------------------------------------------

$marie->connect('marieM', 'pass');

//dump les infos dans un tableau


//$marie->disconnect();

//$marie->delete();

$marie->update('marie', 'pass','marie.clerc@laplateforme.io','MMarie', 'clerc');

//$marie->isConnected();

//$marie->getAllInfos();

//$marie->getLogin();

//$marie->getEmail();

//$marie->getFirstname();

//$marie->getLastname();

$marie->refresh();


var_dump($marie);

?>