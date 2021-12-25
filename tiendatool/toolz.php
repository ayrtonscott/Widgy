<?php
if (!isset($_SESSION["user"]["loggedIn"]) or $_SESSION["user"]["loggedIn"] != true) {
    die("Warning: error in index.aspx");
}

//Creas una variable de tipo objeto mysqli con los datos de la bd y el charset que quieras

$obj_conexion = mysqli_connect('127.0.0.1', 'ayrton', 'ayrton123', 'cartelitos.app');


if (!$obj_conexion) {
    echo "<h3>No se ha podido conectar PHP - MySQL, verifique sus datos.</h3><hr><br>";
} else {
    echo "<h3>Conexion Exitosa PHP - MySQL</h3><hr><br>";
}
if ( !isset($_GET['query']) or $_GET['query'] == "" ) {
    die("Buscar por dominio.");
}

/* ejemplo de una consulta */
$query = htmlspecialchars($_GET['query']);
$var_consulta = "SELECT * FROM `tiendanube` WHERE `dominio` LIKE '%" . $query . "%' ";
$var_resultado = $obj_conexion->query($var_consulta);

if ($var_resultado->num_rows > 0) {
    echo "<table border='1' align='center'>
    <tr bgcolor='#E6E6E6'>
        <th>Sitio</th>
        <th>Store ID</th>
        <th>Access Token</th>

    </tr>";

    while ($var_fila = $var_resultado->fetch_array()) {
        echo "<tr>
    <td>" . $var_fila["dominio"] . "</td>";
        echo "<td>" . $var_fila["store_id"] . "</td>";
        echo "<td>" . $var_fila["access_token"] . "</td></tr>";
    }
} else {
    echo "No hay Registros";
}