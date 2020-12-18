<?php

class lpdo{

    private $host = '';
    private $username = '';
    private $password = '';
    private $db = '';

    private $db_connect;
    private $db_disconnect;
    private $query;
    

    public function __construct($host, $username, $password, $db){

        $this->db_connect;
        echo 'Connexion avec succès...<br />';
    }

    public function connect($host, $username, $password, $db){

        $this->db_connect = mysqli_connect($host, $username, $password, $db);
        $this->db_disconnect = mysqli_close($this->db_connect);

        if ($this->db_connect)
        {
            $this->db_disconnect;
            echo 'MySQL : Fermeture de la session<br />';
        }
        if ($this->db_disconnect)
        {
            $this->host = $host;
            $this->username = $username;
            $this->password = $password;
            $this->db = $db;

            $this->db_connect;
            echo 'Connexion avec succès...<br />';
        }
    }

    public function __destruct(){

        $this->db_disconnect;

        unset($this->db_connect);
        
    }

    public function close(){

        $host = $this->host;
        $password = $this->password;
        $username = $this->username;
        $db = $this->db;

        $this->db_connect = mysqli_connect($host, $username, $password, $db);
        $this->db_disconnect = mysqli_close($this->db_connect);

        echo 'MySQL : Fermeture de la session<br />';
    }

    public function execute($query){

        $host = $this->host;
        $password = $this->password;
        $username = $this->username;
        $db = $this->db;

        $this->query = $query;

        $bdd = $this->db_connect = mysqli_connect($host, $username, $password, $db);
        $req = mysqli_query($bdd,$query);

        if($req){

            return $req;
        }

    }

    public function getLastQuery(){

       $query=$this->query;

       if($query == false){
           echo "Aucune requete executé";
       }
       else{
           echo "Requete " . $query. " executé <br />";
       }
    }

    public function getLastResult(){

        $host = $this ->host;
        $password = $this ->password;
        $username = $this ->username;
        $db = $this ->db;

        $bdd = $this ->db_connect = mysqli_connect($host, $username, $password, $db);
        $query = $this ->query;

        $res = mysqli_query($bdd, $query);

        if (mysqli_num_rows($res) > 0)
        {
            while ($row = mysqli_fetch_assoc($res))
            {
                print_r($row);

                echo '<br />';
            }
        }
    }

    public function getTables(){

        $getTables = $this->query;
        $ShowTables = $this->execute($getTables);

        if($ShowTables){

            $result = mysqli_fetch_assoc($ShowTables);

            print_r($result);

            echo '<br />';

            return $result;
        }

    }

    public function getFields($table){

        $infos = $this->execute($table);

        if($infos){

            $result = mysqli_fetch_all($infos);

            echo"<br />";

            var_dump($result);

            return $result;
        }
    }
}

try{

    $conn = new lpdo('localhost','root','','classes');

    $conn->connect('localhost','root','','classes');

    //$conn->__destruct();

    //$conn->close();

    $conn->execute('SELECT login FROM utilisateurs');
    //var_dump($conn);

    $conn->getLastQuery();

    $conn->getLastResult();

    $conn->getTables();

    $conn->getFields('SHOW COLUMNS FROM utilisateurs');

}

catch (Exception $e){
    print ('Erreur :' . $e -> getMessage() . '<br />');
}
?>