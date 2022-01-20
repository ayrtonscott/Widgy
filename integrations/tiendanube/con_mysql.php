<?php
// Creamos el metodo
class ConnectionMySQL
{

    private $host;
    private $user;
    private $password;
    private $database;
    private $conn;

    public function __construct()
    {
        include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        $this->host = DATABASE_SERVER;
        $this->user = DATABASE_USERNAME;
        $this->password = DATABASE_PASSWORD;
        $this->database = DATABASE_NAME;
    }

    public function CreateConnection()
    {

        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
    }

    public function CloseConnection()
    {

        $this->conn->close();
    }

    public function ExecuteQuery($sql)
    {

        $result = $this->conn->query($sql);
        return $result;
    }
}

function sec($usString)
{
    $sString = htmlspecialchars($usString, ENT_QUOTES);
    return $sString;
}

function dump($data, $coment = NULL)
{
    echo '<pre>';
    echo '<br>';
    echo "<h3>$coment</h3>";
    var_dump($data);
    echo '</br>';
    echo '</pre>';
}
