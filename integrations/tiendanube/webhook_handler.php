<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Access-Control-Max-Age: 1728000');

session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set("allow_url_fopen", true);
ini_set("zlib.output_compression", 0);

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

    $iStoreID = sec($aux['store_id']);
    $sEvent = sec($aux["event"]);
    $iEventid = sec($aux['id']);
} else {
    // Error 1 es que no recibió datos vía POST
    die('Error 1');
}

// Error 2 Es que no reconoce el webhook vía GET
if (!isset($_GET['endpoint'])) {
    die("Error 2");
} else {
    $mEndpoint = sec($_GET['endpoint']);
}

// Paso 2 - Buscamos el Access Token en la correspondiente StoreID

$con = new ConnectionMySQL();
$con->CreateConnection();
$SQL = "SELECT * FROM tiendanube WHERE store_id = '$iStoreID'";
$Result = $con->ExecuteQuery($SQL);
$fila = mysqli_fetch_assoc($Result);
$sAccessToken = $fila['access_token'];

// Ya dejamos establecidos los headers.
$Headers = array(
    'Content-Type' => 'application/json',
    'Authentication' => $sAccessToken,
    'User-Agent' => 'Widgy (api@widgy.app)'
);
$Options = [
    'verify' => false
];

// Paso 3 - Tomamos los datos que correspondan según el evento.

if ($sEvent == 'order/created' || $sEvent == 'order/paid' || $sEvent == 'order/packed' || $sEvent == 'order/fulfilled') { // ? Es una orden?

    // • Pedimos los datos de la orden:
    $Url = "https://api.tiendanube.com/v1/" . $iStoreID . "/orders/" . $iEventid;
    $ResponseORDER = Requests::get($Url, $Headers);
    $ResponseORDER = json_decode($ResponseORDER->body, true);

    // Si no existe la variable lo transformamos en otra del cliente
    $ResponseORDER["billing_name"] == "" ? $ResponseORDER["billing_name"] = $ResponseORDER["customer"]["name"] : "";
    $ResponseORDER["billing_city"] == "" ? $ResponseORDER["billing_city"] = $ResponseORDER["customer"]["default_address"]["city"] : "";
    $ResponseORDER["billing_province"] == "" ? $ResponseORDER["billing_province"] = $ResponseORDER["customer"]["default_address"]["province"] : "";

    // Cortamos el apellido si es que existe.
    $arr = explode(' ', trim($ResponseORDER["billing_name"]));
    $ResponseORDER["billing_name"] = $arr[0];

    // Arreglamos el texto de los nombres propios, con mayuscula en cada primer letra
    $ResponseORDER["billing_name"] = strtolower($ResponseORDER["billing_name"]);
    $ResponseORDER["billing_name"] = ucwords($ResponseORDER["billing_name"]);
    $ResponseORDER["billing_city"] = strtolower($ResponseORDER["billing_city"]);
    $ResponseORDER["billing_city"] = ucwords($ResponseORDER["billing_city"]);
    $ResponseORDER["billing_province"] = strtolower($ResponseORDER["billing_province"]);
    $ResponseORDER["billing_province"] = ucwords($ResponseORDER["billing_province"]);
    // Quitamos los decimales del precio
    $ResponseORDER["products"][0]["price"] = round($ResponseORDER["products"][0]["price"]);
    // Limpiamos el método de pago
    $ResponseORDER["payment_details"]["method"] == "debit_card" ? $ResponseORDER["payment_details"]["method"] = "tarjeta de débito" : "";
    $ResponseORDER["payment_details"]["method"] == "credit_card" ? $ResponseORDER["payment_details"]["method"] = "tarjeta de crédito" : "";
    $ResponseORDER["payment_details"]["method"] == "account_money" or "digital_currency" ? $ResponseORDER["payment_details"]["method"] = "MercadoPago" : "";
    $ResponseORDER["payment_details"]["method"] == "custom" or "offline" ? $ResponseORDER["payment_details"]["method"] = "dinero en efectivo" : "";
    // Limpiamos envío
    $pos = strpos($ResponseORDER["shipping_option"], "-");
    !empty($pos) ? $ResponseORDER["shipping_option"] = mb_substr($ResponseORDER["shipping_option"], 0, $pos - 1) : "";
    unset($pos);

    // • Pedimos los datos del producto:
    $iProductID = $ResponseORDER["products"][0]["product_id"];
    $Url = "https://api.tiendanube.com/v1/" . $iStoreID . "/products/" . $iProductID;
    $ResponsePRODUCT = Requests::get($Url, $Headers);
    $ResponsePRODUCT = json_decode($ResponsePRODUCT->body, true);

    // Limpiamos la variación
    $pos = strpos($ResponseORDER["products"][0]["name"], "(");
    if (!empty($pos)) {
        $sProductName = mb_substr($ResponseORDER["products"][0]["name"], 0, $pos - 1);
        $sVariation = mb_substr($ResponseORDER["products"][0]["name"], $pos - 1, null);
    } else {
        $sProductName = $ResponseORDER["products"][0]["name"];
        $sVariation = "";
    }
    unset($pos);
}

if ($sEvent == 'product/created' || $sEvent == 'product/updated') { // ? Es un producto?

    // • Pedimos los datos del producto:
    $Url = "https://api.tiendanube.com/v1/" . $iStoreID . "/products/" . $iEventid;
    $ResponsePRODUCT = Requests::get($Url, $Headers);
    $ResponsePRODUCT = json_decode($ResponsePRODUCT->body, true);

    // Limpiamos la fecha de creación
    $pos = strpos($ResponsePRODUCT["variants"][0]["created_at"], "T");
    !empty($pos) ? $ResponsePRODUCT["variants"][0]["created_at"] = date("d-m-Y", strtotime(mb_substr($ResponsePRODUCT["variants"][0]["created_at"], 0, $pos))) : "";
    unset($pos);
    // Limpiamos la fecha de modificación
    $pos = strpos($ResponsePRODUCT["variants"][0]["updated_at"], "T");
    !empty($pos) ? $ResponsePRODUCT["variants"][0]["updated_at"] = date("d-m-Y", strtotime(mb_substr($ResponsePRODUCT["variants"][0]["updated_at"], 0, $pos))) : "";
    unset($pos);

} else {
    die("ERROR: Evento es $sEvent");
}

// Paso 4 - Enviamos los datos al endpoint.

if ($sEvent == 'order/created' || $sEvent ==  'order/paid' || $sEvent ==  'order/packed' || $sEvent ==  'order/fulfilled') { // ? Es una orden?

    $aData = array(
        'id' => $ResponseORDER["id"],
        'nombre' => $ResponseORDER["billing_name"],
        'ciudad' => $ResponseORDER["billing_city"],
        'provincia' => $ResponseORDER["billing_province"],
        'producto' => $sProductName,
        'variacion' => $sVariation,
        'cantidad' => $ResponseORDER["products"][0]["quantity"],
        'stock' => $ResponsePRODUCT["variants"][0]["stock"],
        'precio' => $ResponseORDER["products"][0]["price"],
        'url-imagen' => $ResponseORDER["products"][0]["image"]["src"],
        'url-enlace' => $ResponsePRODUCT["canonical_url"]
    );

    $sEvent == 'order/paid' || $sEvent == 'order/packed' || $sEvent == 'order/fulfilled' ? $aData += ['numero' => $ResponseORDER["number"]] : "";
    $sEvent == 'order/paid' ? $aData += ['metodo' => $ResponseORDER["payment_details"]["method"]] : "";
    $sEvent == 'order/fulfilled' ? $aData += ['envio' => $ResponseORDER["shipping_option"]] : "";
}

if ($sEvent == 'product/created' || $sEvent ==  'product/updated') { // ? Es un producto?

    $aData = array(
        'id' => $ResponsePRODUCT["id"],
        'producto' => $ResponsePRODUCT["name"]["es"],
        'variacion' => $ResponsePRODUCT["variants"][0]["values"][0]["es"],
        'stock' => $ResponsePRODUCT["variants"][0]["stock"],
        'precio' => $ResponsePRODUCT["variants"][0]["price"],
        'preciopromo' => $ResponsePRODUCT["variants"][0]["promotional_price"],
        'url-imagen' => $ResponsePRODUCT['images'][0]['src'],
        'url-enlace' => $ResponsePRODUCT["canonical_url"]
    );

    $sEvent == 'product/created' ? $aData += ['fecha' => $ResponsePRODUCT["variants"][0]["created_at"]] : "";
    $sEvent == 'product/updated' ? $aData += ['fecha' => $ResponsePRODUCT["variants"][0]["updated_at"]] : "";

}


// • Enviamos los datos al endpoint de Widgy
$Url = SITE_URL . 'pixel-webhook/' . $mEndpoint;

$Body = json_encode($aData, JSON_PRETTY_PRINT);
Requests::post($Url, $Headers, $Body, $Options);
echo "Se envió la data a : " . SITE_URL . 'pixel-webhook/' . $mEndpoint;

// • Terminamos de enviar los datos.
