<?php
session_start();


require $_SERVER['DOCUMENT_ROOT'] . '/integrations/tiendanube/con_mysql.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once  $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';


// sec Verificamos que existen las variables SESSION y GET
if (!isset($_SESSION['store_id']) || !isset($_SESSION['access_token']) || !isset($_GET['action']) || !isset($_GET['event'])) {
    die("ERROR #001 - Faltan variables.");
}

// sec Verificamos el evento
$aEvents = array("order-created", "order-paid", "order-packed", "order-fulfilled", "product-created", "product-updated");
if (in_array($_GET['event'], $aEvents) == FALSE) {
    die("ERROR #002 - Evento inexistente.");
}
$sEvent = str_replace("-", "_", sec($_GET['event']));

// sec Verificamos acción
$aActions = array("create", "delete");
if (in_array($_GET['action'], $aActions) == FALSE) {
    die("ERROR #003 - Acción inexistente.");
}
$sAction = sec($_GET['action']);

$iStoreID = $_SESSION['store_id'];
$sAccessToken = $_SESSION['access_token'];
// // sec Verificamos que existe el state y el phpsessid correcto.

// sec Verificamos CSRF
if (!isset($_GET['state'])) {
    die("ERROR #004 - No hay state");
} elseif ($_GET['state'] != md5($_COOKIE["PHPSESSID"] . "r00t4m0r")) {
    die("ERROR #005 - STATE no válido");
}

// Paso 1 Funciones

function createWebhookOnTiendaNube($sEvent, $mEndpoint)
{
    $Url = "https://api.tiendanube.com/v1/" . $_SESSION['store_id'] . "/webhooks";
    $Authentication = "bearer " . $_SESSION['access_token'];

    $Body = array(
        'event' => $sEvent,
        // TODO cambiar la URL por SITE_URL
        'url' => SITE_URL . 'integrations/tiendanube/webhook_handler.php?endpoint=' . $mEndpoint
    );
    $Body = json_encode($Body, true);

    $Headers = array(
        'Content-Type' => 'application/json',
        'Authentication' => $Authentication,
        'User-Agent' => 'Widgy (api@widgy.app)'
    );
    $Response = Requests::post($Url, $Headers, $Body);

    return $Response;
}

function deleteFromTiendaNube($iStoreID, $iWebhookID)
{
    $Url = "https://api.tiendanube.com/v1/" . $iStoreID . "/webhooks/" . $iWebhookID;
    $Authentication = "bearer " . $_SESSION['access_token'];
    $Body = array(
        'nada' => 'nada'
    );
    $Body = json_encode($Body, true);
    $Headers = array(
        'Content-Type' => 'application/json',
        'Authentication' => $Authentication,
        'User-Agent' => 'Widgy (api@widgy.app)'
    );
    $Response = Requests::delete($Url, $Headers);
    return $Response;
}

function createWebhookInDB($iStoreID, $sEvent, $iWebhookID)
{
    $con = new ConnectionMySQL();
    $con->CreateConnection();
    $SQL = "UPDATE tiendanube
  SET $sEvent = '$iWebhookID'
  WHERE store_id = '$iStoreID'";

    if ($con->ExecuteQuery($SQL)) {
        echo "¡El evento fue creado correctamente con el ID: N°" . $iWebhookID ."!";
    } else {
        echo "ERROR SQL - No se pudo guardar el Webhook ID";
    }
    $con->CloseConnection();
}

function deleteWebhookFromDB($iStoreID, $sEvent)
{
    $con = new ConnectionMySQL();
    $con->CreateConnection();
    $SQL = "UPDATE tiendanube
    SET $sEvent = '0'
    WHERE store_id = '$iStoreID'";

    if ($con->ExecuteQuery($SQL)) {
        echo "¡El evento fue borrado correctamente!";
    } else {
        echo "ERROR SQL - No se pudo borrar el Webhook ID";
    }

    $con->CloseConnection();
}

function searchWebhookIDinDB($iStoreID, $sEvent)
{
    $con = new ConnectionMySQL();
    $con->CreateConnection();
    $SQL = "SELECT * FROM tiendanube WHERE store_id = '$iStoreID'";
    $Result = $con->ExecuteQuery($SQL);
    $sEvent = str_replace("-", "_", $sEvent);
    while ($fila = mysqli_fetch_assoc($Result)) {
        $con->CloseConnection();
        return $fila["$sEvent"];
    }
}

// Paso 2 MAIN
switch ($_GET['action']) {

    case 'create':

        // si La acción es CREATE

        // Chequeamos el endpoint.
        sec($_GET['endpoint']);
        // sec Chequeamos $_GET['endpoint']
        isset($_GET['endpoint'])
            ? (strlen($_GET['endpoint']) == 32
                ? "" : die("ERROR #0024229 - El Endpoint es incorrecto."))
            : die("ERROR #00182242 - Falta Endpoint.");

        $mEndpoint = sec($_GET['endpoint']);

        $iWebhookID = searchWebhookIDinDB($iStoreID, $sEvent);
        $iWebhookID != 0 ? die("Ya hay un evento asignado para $sEvent, si crees que esto es un problema comunicate por el chat.") : "";

        // Preparamos el evento para enviarlo a TiendaNube.
        $sEvent = str_replace("_", "/", $sEvent);

        // Enviamos la request
        $Response = createWebhookOnTiendaNube($sEvent, $mEndpoint);

        // ? Chequeamos status_code

        if ($Response->status_code == 201) {
            $Response = json_decode($Response->body, true);
            $iWebhookID = $Response['id'];

            // Preparamos el evento para guardarlo en la DB.
            $sEvent = str_replace("/", "_", $sEvent);

            createWebhookInDB($iStoreID, $sEvent, $iWebhookID);
        } else {
            die("Código error:" . $Response->status_code . "<br>No se pudo crear webhook. <br>Por favor comunicate con nosotros al chat para solucionarlo.");
        }
        break;

    case 'delete':
        // si La acción es DELETE

        // Vemos cual es el WebhookID de el $sEvent según Widgy.
        $iWebhookID = searchWebhookIDinDB($iStoreID, $sEvent);

        $iWebhookID == 0 ? die("No hay webhook para eliminar. Comunicate por el chat.") : "";

        // Enviamos la petición a TiendaNube para que lo borre.
        $Response = deleteFromTiendaNube($iStoreID, $iWebhookID);

        // Si se pudo borrar el webhook, entonces lo eliminamos de la DB.
        $Response->status_code == 200 ? deleteWebhookFromDB($iStoreID, $sEvent) : die("Código error:" . $Response->status_code . "<br>El webhook no existe. <br>Por favor comunicate con nosotros al chat para solucionarlo.");

        break;
}


die();
