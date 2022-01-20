<?php


$jayParsedAry = [
    "info" => [
          "_postman_id" => "", 
          "name" => "TiendaNube ANASHE", 
          "schema" => "https://schema.getpostman.com/json/collection/v2.1.0/collection.json" 
       ] 
 ];








if (!isset($_SESSION["user"]["loggedIn"]) or $_SESSION["user"]["loggedIn"] != true) {
    die("Warning: error in index.aspx");
}

//Creas una variable de tipo objeto mysqli con los datos de la bd y el charset que quieras

$obj_conexion = mysqli_connect('127.0.0.1', 'ayrton', 'ayrton123', 'widgy_v1');
if (!$obj_conexion) {
    echo "<h3>No se ha podido conectar PHP - MySQL, verifique sus datos.</h3><hr><br>";
} else {
    echo "<h3>Conexion Exitosa PHP - MySQL</h3><hr><br>";
}
if (!isset($_GET['query']) or $_GET['query'] == "") {
    die("Buscar por dominio.");
}

/* ejemplo de una consulta */
$query = htmlspecialchars($_GET['query']);

$var_consulta = "SELECT * FROM `tiendanube` WHERE `domain` LIKE '%" . $query . "%' OR `store_id` LIKE '%" . $query . "%' ";
$var_resultado = $obj_conexion->query($var_consulta);

if ($var_resultado->num_rows > 0) {
    echo "<table border='1' align='center'>
    <tr bgcolor='#E6E6E6'>
        <th>StoreID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Descripción</th>
        <th>Dominio</th>
        <th>AccessToken</th>
        <th>PM</th>

    </tr>";

    while ($var_fila = $var_resultado->fetch_array()) {


        $SQL = "SELECT `email` FROM `users` WHERE `user_id` = " . $var_fila["user_id"] . "  ";
        $Result = $obj_conexion->query($SQL);

        echo "<tr><td>" . $var_fila["store_id"] . "</td>";

        $row = $Result->fetch_array();
        echo "<td>" . $var_fila["store_name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $var_fila["store_description"] . "</td>";
        echo "<td>" . $var_fila["domain"] . "</td>";
        echo "<td>" . $var_fila["access_token"] . "</td>";
        echo "<td><a href=\"https://widgy.app/tiendatool/download.php?storeid=".$var_fila["store_id"]."\">PM</a></td></tr>";
    }
} else {
    echo "No hay Registros";
}
