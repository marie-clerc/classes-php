<?php
class userpdo
{
    private $id; // pas de ='' car auto incrementation en bdd
    public $login = '';
    public $password = '';
    public $email = '';
    public $firstname = '';
    public $lastname = '';

    public function register($login, $password, $email, $firstname, $lastname)
    {
        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;

        $host = 'localhost';
        $db   = 'classes';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        try {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            exit($e->getMessage());
        }

        $login = htmlspecialchars(trim($login));
        $password = htmlspecialchars(trim(password_hash($password, PASSWORD_BCRYPT)));
        $email = htmlspecialchars(trim($email));
        $firstname =  htmlspecialchars(trim($firstname));
        $lastname = htmlspecialchars(trim($lastname));


        //Verifcation 
        if (empty($login) || empty($password) || empty($email) || empty($firstname) || empty($lastname)){
            $error = "Complete all fields";
        }
        // Password match
        /*if ($password != $password1){
            $error = "Passwords don't match";
        }
        // Email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = "Enter a  valid email";
        }*/
        if(!isset($error)){
            //no error
            $stmpdo = $pdo->prepare("SELECT login FROM utilisateurs WHERE login = :login");
            $stmpdo->bindParam(':login', $login);
            $stmpdo->execute();

            if($stmpdo->rowCount() > 0){
                echo "login déjà prit";
            } 
            else {
                $sql= 'INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (:login, :password, :email, :firstname, :lastname)';

                $query = $pdo->prepare($sql);
                $query->execute(['login' => $login,'email' => $email, 'password' => $password, 'firstname' => $firstname, 'lastname' => $lastname]);
                
                echo "Vous êtes enregistré !";
                var_dump($query);
                return[$query];
            }
        }
        else {
            echo "erreur: ".$error;
            exit();
        }
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function connect ($login, $password){
    
        $host = 'localhost';
        $db   = 'classes';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        try {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            exit($e->getMessage());
        }

        if(isset($login, $password)){

            $sql = 'SELECT * FROM utilisateurs WHERE login = :login';
            //Retrieve the user account information for the given login.
            $stmpdo = $pdo->prepare($sql);

            $stmpdo->execute(["login"=>$login]);
            //Fetch row.
            $user = $stmpdo->fetch(PDO::FETCH_ASSOC);
            //var_dump($user);
            //If $row is FALSE.
            if($user === false){
                //Could not find a user with that username!
                die('Login ou mot de passe incorrect.');
            }
            else {
                //Compare the passwords.
                $validPassword = password_verify($password, $user['password']);
                //If $validPassword is TRUE, the login has been successful.
                if($validPassword){
                    echo 'vous êtes connecté.';
                    $this->id = $user['id'];
                    $this->login = $user ['login'];
                    $this->password = $user ['password'];
                    $this->email = $user ['email'];
                    $this->firstname = $user ['firstname'];
                    $this->lastname = $user ['lastname'];
                } 
                else{
                    //$validPassword was FALSE. Passwords do not match.
                    die('Login ou mot de passe incorrect.');
                }
            }
        }
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function disconnect(){
        
        $this->id = null;
        $this->login = null;
        $this->password = null;
        $this->email = null;
        $this->firstname = null;
        $this->lastname = null;

        echo "Vous êtes déconnecté.";
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function delete(){

        $host = 'localhost';
        $db   = 'classes';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        try {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            exit($e->getMessage());
        }


        $login = $this->login;

            $req = $pdo -> prepare("SELECT * FROM utilisateurs WHERE login = :login");
            $req->execute(["login"=>$login]);

            if($req = true){
                $delete = $pdo -> prepare("DELETE FROM utilisateurs WHERE login = :login");
                $delete->execute(["login"=>$login]);

                echo 'suppresion des données réusssie';
                $this->id = null;
                $this->login = null;
                $this->password = null;
                $this->email = null;
                $this->firstname = null;
                $this->lastname = null;
            }
            else {
                echo 'la supression a échoué';
            }
        
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function update($newlogin, $newpassword, $newemail, $newfirstname, $newlastname){

        $host = 'localhost';
        $db   = 'classes';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        try {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            exit($e->getMessage());
        }


        $login= $this->login;
        
        $newlogin = htmlspecialchars(trim($newlogin));
        $newpassword = password_hash($newpassword, PASSWORD_BCRYPT);
        $newemail = htmlspecialchars(trim($newemail));
        $newfirstname = htmlspecialchars(trim($newfirstname));
        $newlastname = htmlspecialchars(trim($newlastname));
        
        $update = $pdo -> prepare("UPDATE utilisateurs SET login = :login, password = :password, email = :email, firstname = :firstname, lastname = :lastname WHERE login ='$login'");
        $update->execute([
            "login"=>$newlogin,
            "password"=>$newpassword,
            "email"=>$newemail,
            "firstname"=>$newfirstname,
            "lastname"=>$newlastname
        ]);
       
        echo "Modification prises en compte.";
        $this->login = $newlogin;
        $this->password = $newpassword;
        $this->email = $newemail;
        $this->firstname = $newfirstname;
        $this->lastname = $newlastname;
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function isConnected(){

        $login = $this->login;

        if($login){
           
            echo " Vous êtes Connecté ";
            
           return true;

        }
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function getAllInfos(){

        $login = $this->login;
        $password = $this->password;
        $email = $this->email;
        $firstname = $this->firstname;
        $lastname = $this->lastname;

        return[$login,$password,$email,$firstname,$lastname];

    }

    /*----------------------------------------------------------------------------------------------------*/

    public function getLogin(){
        return ($this->login);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function getEmail(){
        return ($this->email);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function getFirstname(){
        return ($this->firstname);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function getLastname(){
        return ($this->lastname);
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function refresh()
    {
        $host = 'localhost';
        $db   = 'classes';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        try {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            exit($e->getMessage());
        }

        $id = $this->id;

        $req = $pdo -> prepare("SELECT * FROM utilisateurs WHERE id = :id");
        $req->execute(["id"=>$id]);

        if ($req)
        {
            $result = $req->fetch(PDO::FETCH_ASSOC);

            $this ->login = $result['login'];
            $this ->password = $result['password'];
            $this ->email = $result['email'];
            $this ->firstname = $result['firstname'];
            $this ->lastname = $result['lastname'];

            return $result;
        }
    }

}


//création d'un nouvel user avec 'new'
$Nuser = new userpdo();

//Création de l'utilisateur :
    //$Nuser->register('lo', 'lulupass','marie.clerc@laplateforme.io','marie', 'clerc');
    //dump les infos dans un tableau
    //var_dump($Nuser);

//Connexion de l'utilisateur :
    $Nuser->connect('lo','lulupass');
    var_dump($Nuser);

//Déconnexion de l'utilisateur :
    //$Nuser->disconnect();
    //var_dump($Nuser);

//Delete l'utilisateur :
    //$Nuser->delete();
    //var_dump($Nuser);

// Modifier les données : 
   // $Nuser->update('v', 'o', 'm', 'm', 'm');
    //var_dump($Nuser);

//Vous êtes connecté :
    //$Nuser->isConnected();
    //var_dump($Nuser);

//Informations de l'utilisateur : 
    //$Nuser->getAllInfos();
    //var_dump($Nuser);

//Informations login :
    //$Nuser->getLogin();
    //var_dump($Nuser->getLogin());

//Informations email : 
    //$Nuser->getEmail();
    //var_dump($Nuser->getEmail());

//Informations firstname : 
    //$Nuser->getFirstname();
    //var_dump($Nuser->getFirstname());

//Informations lastname : 
    //$Nuser->getLastname();
    //var_dump($Nuser->getLastname());

//Refresh : 
    $Nuser->refresh();
    //var_dump($Nuser->refresh());

?>