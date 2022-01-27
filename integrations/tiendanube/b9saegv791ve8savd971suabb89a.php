<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Access-Control-Max-Age: 1728000');

session_start();
error_reporting(E_ALL);
ini_set("allow_url_fopen", true);
ini_set("zlib.output_compression", 1);
date_default_timezone_set('America/Argentina/Buenos_Aires');

require $_SERVER['DOCUMENT_ROOT'] . '/integrations/tiendanube/con_mysql.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once  $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

// Paso 1 - Verificaciones de seguridad

# Vereficamos si este archivo fue solicitado por un POST REQUEST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    # Parseamos los datos enviados por POST
    $request = file_get_contents('php://input');
    $req_dump = print_r($request, true);
    $aux = json_decode($req_dump, true);
} else {
    // Error 1 es que no recibió datos vía POST
    die('Error 1');
}

if (!isset($aux['store_id'])) {
    die("Error 2");
} else {
    $iStoreID = sec($aux['store_id']);
}

$iStoreID = sec($iStoreID);

$con = new ConnectionMySQL();
$con->CreateConnection();
$SQL = "SELECT * FROM tiendanube WHERE store_id = '$iStoreID'";
$Result = $con->ExecuteQuery($SQL);
$filaTN = mysqli_fetch_assoc($Result);

$iUserID = $filaTN['user_id'];

$SQL = "SELECT * FROM users WHERE user_id = '$iUserID'";
$Result = $con->ExecuteQuery($SQL);
$filaUSER = mysqli_fetch_assoc($Result);

$content = "

Fecha: " . date('l jS \of F Y h:i:s A') . "\n
Nombre: " . $filaUSER['name'] . "\n
Usuario: " . $filaUSER['email'] . "\n
Plan: " . $filaUSER['plan_id'] . "\n
Trial hecho: " . $filaUSER['plan_trial_done'] . "\n


";
if (file_put_contents("uninstalls/" . $filaTN['store_id'] . ".txt", $content)) {
    echo "Correcto";
} else {
    echo "Error";
}
