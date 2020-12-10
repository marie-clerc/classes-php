<?php 
/*Créez un fichier nommé “user.php”. Dans ce fichier, créez une classe
“user” qui contient les attributs suivants :
- id (private)
- login (public)
- email (public)
- firstname (public)
- lastname (public)*/

class user 
{
    private $id; // pas de ='' car auto incrementation en bdd
    public $login = '';
    public $password = '';
    public $email = '';
    public $firstname = '';
    public $lastname = '';

    public function register($login, $password, $email, $firstname, $lastname)
    {
        //connection à la bd
        $db = mysqli_connect('localhost','root', '', 'classes');

        //mysqli real escpae string attend 2 parametres
        $login = mysqli_real_escape_string($db, htmlspecialchars(trim($login)));
        $password = $hash = mysqli_real_escape_string($db, htmlspecialchars(trim(password_hash($password, PASSWORD_BCRYPT))));
        $email = mysqli_real_escape_string($db, htmlspecialchars(trim($email)));
        $firstname = mysqli_real_escape_string($db, htmlspecialchars(trim($firstname)));
        $lastname = mysqli_real_escape_string($db, htmlspecialchars(trim($lastname)));

        if (isset($login) && isset($password) && isset($email) && isset($firstname) && isset($lastname)) 
        {
            $req = mysqli_query($db, "INSERT INTO `utilisateurs`(`login`, `password`, `email`, `fisrtname`, `lastname`) VALUES ('$login', '$password', '$email', '$firstname', '$lastname')");
            if ($req) {
                echo 'vous etes bien enregistré';
                return $req;
            }
            else echo 'ca marche pas';
        }
        else echo 'remplir tous les champs';
    }
}

/* 
Crée l’utilisateur en base de données. Retourne un tableau contenant
l’ensemble des informations concernant l’utilisateur créé.

- public function connect($login, $password)
Connecte l’utilisateur, modifie les attributs présents dans la classe et
retourne un tableau contenant l’ensemble de ses informations.
- public function disconnect()
Déconnecte l’utilisateur.
- public function delete()
Supprime et déconnecte l’utilisateur.
- public function update($login, $password, $email, $firstname,
lastname)
Modifie les informations de l’utilisateur en base de données.

- public function isConnected()
Retourne un booléen permettant de savoir si un utilisateur est connecté ou
non.

- public function getAllInfos()
Retourne un tableau contenant l’ensemble des informations de l’utilisateur.

- public function getLogin()
Retourne le login de l’utilisateur connecté.

- public function getEmail()
Retourne l’adresse email de l’utilisateur connecté.

- public function getFirstname()
Retourne le firstname de l’utilisateur connecté.

- public function getLastname()
Retourne le lastname de l’utilisateur connecté.

- public function refresh()
Met à jour les attributs de la classe à partir de la base de données.
Vos requêtes SQL doivent être faites à l’aide des fonctions mysqli*.*/
?>