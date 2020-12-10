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
            //regarder si le login existe deja
            $reqlogin = mysqli_query($db,"SELECT * FROM `utilisateurs` WHERE `login`='$login'");
            // si il y a un résultat, mysqli_num_rows() nous donnera alors 1
            // si mysqli_num_rows() retourne 0 c'est qu'il a trouvé aucun résultat
            if(mysqli_num_rows($reqlogin) == 0) 
            { 
                //enregistrer les infos dans la bdd 
                $reqinsert = mysqli_query($db,"INSERT INTO `utilisateurs`(`login`, `password`, `email`, `firstname`, `lastname`) VALUES (\"$login\", \"$password\", \"$email\", \"$firstname\", \"$lastname\")");
                if ($reqinsert) {
                    echo 'vous etes bien enregistré';
                    return $reqinsert;
                }
                else echo 'ca marche pas (la req pour insert into)';
            }
            else echo 'login existe déjà';
        }
        else echo 'remplir tous les champs';
    }

    /*----------------------------------------------------------------------------------------------------*/
    
    public function connect($login, $password) 
    {
        if(isset($login) && isset($password))
        {
            //on se connecte à la base de données:
            $db = mysqli_connect('localhost','root', '', 'classes');
            //on fait la requête dans la bd pour rechercher si ces données existent et correspondent:
            $query = mysqli_query($db,"SELECT * FROM `utilisateurs` WHERE login='$login'");

            //variable necessaire pour récupérer les infos du l'utilisateur, et pour les utiliser sur d'autre page 
            $var = mysqli_fetch_array($query);// résultat mis dans un tableau, une ligne par résultat si xieurs
            
            // si il y a un résultat, mysqli_num_rows() nous donnera alors 1
            // si mysqli_num_rows() retourne 0 c'est qu'il a trouvé aucun résultat
            if(mysqli_num_rows($query) == 0) {
                echo "Le login n'existe pas";
            }
            //si login exist, vérifier le hash password et le password entré par l'utilisateur
            else if (mysqli_num_rows($query) == 1) 
            {
                //je fait la requête pour le password qui correspont au login. 
                $query2 = mysqli_query($db, "SELECT password FROM `utilisateurs` WHERE login=\"$login\"");
                //je vais créer un tableau avec mon résultat
                $row = mysqli_fetch_array($query2);
                //je transforme ma ligne password (ligne de la bdd) en variable
                $hash = $row['password'];
                //je vérif si post password et le password dans bdd : row password, sont les mêmes
                if(password_verify($password, $hash)) //toujours dans cet ordre
                {
                    echo 'vous etes connecté';
                    //mon tableau $var que je fait plus haut me sert pour set mes this 
                    $this->id = $var['id'];
                    $this->login = $var ['login'];
                    $this->password = $var ['password'];
                    $this->email = $var ['email'];
                    $this->firstname = $var ['firstname'];
                    $this->lastname = $var ['lastname'];
                }
                else {
                    echo 'mdp incorrect';
                }
            }
        }
        else echo 'remplir tous les champs pour se connecter';
    }

    /*----------------------------------------------------------------------------------------------------*/
        
        
        public function disconnect()
        {
            $db = mysqli_connect('localhost','root', '', 'classes');
            if (mysqli_close($db))
            {
                $this->id = '';
                $this->login = '';
                $this->password = '';
                $this->email = '';
                $this->firstname = '';
                $this->lastname = '';
                echo 'vous etes deconecté';
            }
            else echo 'la déconnexion a échoué';
        }

    /*----------------------------------------------------------------------------------------------------*/


    public function delete() 
    {
        $db = mysqli_connect("localhost", "root", "", "classes"); 
        
        $sql = "DELETE FROM utilisateurs WHERE id='$this->id'";

        if(mysqli_query($db, $sql)){ 
            echo 'Suppression des données avec succès'; 
        }  
        else 
        {
            echo 'echec de la suppression des données';
        }
        mysqli_close($db); 
    }

    /*----------------------------------------------------------------------------------------------------*/


    public function update($login, $password, $email, $firstname, $lastname)
    { 
        $db = mysqli_connect('localhost','root', '', 'classes');

        //mysqli real escpae string attend 2 parametres
        $login = mysqli_real_escape_string($db, htmlspecialchars(trim($login)));
        $password = $hash = mysqli_real_escape_string($db, htmlspecialchars(trim(password_hash($password, PASSWORD_BCRYPT))));
        $email = mysqli_real_escape_string($db, htmlspecialchars(trim($email)));
        $firstname = mysqli_real_escape_string($db, htmlspecialchars(trim($firstname)));
        $lastname = mysqli_real_escape_string($db, htmlspecialchars(trim($lastname)));

        if (isset($login) && isset($password) && isset($email) && isset($firstname) && isset($lastname)) 
        {
            //regarder si le login existe deja
            $reqlogin = mysqli_query($db,"SELECT * FROM `utilisateurs` WHERE `login`='$login'");
            // si il y a un résultat, mysqli_num_rows() nous donnera alors 1
            // si mysqli_num_rows() retourne 0 c'est qu'il a trouvé aucun résultat
            if(mysqli_num_rows($reqlogin) == 0) 
            { 
                //enregistrer les infos dans la bdd 
                $requpdate = mysqli_query($db,"UPDATE `utilisateurs` SET `login`='$login',`password`='$password',`email`='$email',`firstname`='$firstname',`lastname`='$lastname' WHERE id='$this->id'");
                if ($requpdate) 
                {
                    echo 'Vous avez mis votre profil à jour';
                    return $requpdate;
                }
                else echo 'ca marche pas (la req pour update)';
            }
            else echo 'le nouveau login choisi existe déjà';
        }
        else echo 'remplir tous les champs';
    }

    /*----------------------------------------------------------------------------------------------------*/


    public function isConnected()
    { 
        if ($this->id != Null)
        {
            echo 'L\'utilisateur ' .$this->login. ' est connecté.';
            return true;
        }
        else echo 'pas d\'utilisateur connecté';
    }





}

/*

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