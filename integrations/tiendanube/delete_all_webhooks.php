<?php
session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('error_reporting', 0);
if ($_GET['state'] != md5($_COOKIE["PHPSESSID"] . "r00t4m0r")) {
    die("Error en protección CSRF");
}

require_once "../../vendor/autoload.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once 'con_mysql.php';
$iStoreID = $_SESSION['store_id'];
$Url = "https://api.tiendanube.com/v1/" . $_SESSION['store_id'] . "/webhooks";
$Headers = array(
    'Content-Type' => 'application/json',
    'Authentication' => 'bearer ' . $_SESSION['access_token'],
    'User-Agent' => 'Widgy (api@widgy.app)'
);
$Response = Requests::get($Url, $Headers);

if ($Response->status_code != 200) {
    die("No se pueden obtener los webhooks, comunicate por el chat.");
}

$Response = json_decode($Response->body, true);
$iWebhooks = array_column($Response, 'id');

if ($iWebhooks == false) {
    echo "No tienes eventos activados.";
}

foreach ($iWebhooks as $iWebhook) {
    $Url = "https://api.tiendanube.com/v1/" . $_SESSION['store_id'] . "/webhooks/" . $iWebhook;
    $Headers = array(
        'Content-Type' => 'application/json',
        'Authentication' => 'bearer ' . $_SESSION['access_token'],
        'User-Agent' => 'Widgy (api@widgy.app)'
    );
    $Response = Requests::delete($Url, $Headers);
}

if ($Response->status_code == 200) {
    echo "Se han cancelado correctamente los eventos.";
}

$con = new ConnectionMySQL();
$con->CreateConnection();

$SQL = "UPDATE `tiendanube` SET 
`order_created` = 0, 
`order_paid` = 0,
`order_packed` = 0,
`order_fulfilled` = 0,
`product_created` = 0,
`product_updated` = 0
WHERE store_id = '$iStoreID'";

if ($con->ExecuteQuery($SQL)) {
    echo " Hemos borrado los eventos de nuestra base de datos.";
} else {
    echo " No se pueden borrar los webhooks de la base de datos. Comunicate con nosotros por favor.";
}


