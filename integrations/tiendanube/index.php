<?php
session_start();
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('error_reporting', 0);

require_once "../../vendor/autoload.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require_once 'con_mysql.php';

// TODO COOKIE PARA VOLVER A INTENTAR
/* 
if(isset($_COOKIE['wait'])) {
    die("Debes esperar unos ".$_COOKIE['wait']." segundos para volver a intentarlo.");
}*/

#region Funciones

function ending()
{ ?>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </main>

  <script src="/../themes/widgy/assets/js/vendor/jquery-3.3.1.min.js"></script>
  <script src="/../themes/widgy/assets/js/vendor/bootstrap.bundle.min.js"></script>
  <script src="/../themes/widgy/assets/js/dore.script.js"></script>
  <script src="/../themes/widgy/assets/js/scripts.single.theme.js"></script>
  </body>

  </html>

<?php }

function login($iUserID)
{
  $Url = SITE_URL . "admin-api/users/" . $iUserID . "/one-time-login-code";
  $Body = array(
    '0x' => '0x'
  );
  $Body = json_encode($Body, true);
  $Headers = array(
    'Content-Type' => 'application/x-www-form-urlencoded',
    'Authorization' => "Bearer " . SR_ADMIN_API_KEY,
    'User-Agent' => 'Widgy (api@widgy.app)'
  );
  // Enviamos la request.
  $Response = Requests::post($Url, $Headers, $Body);
  $decodedResponse = json_decode($Response->body, true);

  if ($Response->status_code != 200) {
    return "#";
  } else {
    return $decodedResponse['data']['url'];
  }
}

function install($iUserID, $iStoreID, $sStoreName, $txtStoreDescription, $sStoreDomain, $sAccessToken)
{
  // Insertamos en la Tabla TiendaNube los datos.
  $con = new ConnectionMySQL();
  $con->CreateConnection();


  $SQL = "INSERT INTO tiendanube ( user_id, store_id, store_name, store_description, domain, access_token, order_created, order_paid, order_packed, order_fulfilled, product_created, product_updated)
    VALUES ( '" . $iUserID . "', '" . $iStoreID . "', '" . $sStoreName . "', '" . $txtStoreDescription . "', '" . $sStoreDomain . "', '" . $sAccessToken . "', '0', '0', '0', '0', '0', '0')";

  if ($con->ExecuteQuery($SQL)) {
    echo "<li><i class=\"iconsminds-add-basket btn text-success\"></i> Datos recolectados de TiendaNube.</li>";
  } else {
    echo "<li><i class=\"iconsminds-add-basket btn text-danger\"></i>ERROR: No pudimos guardar los datos de TiendaNube.</li>";
  }
}

function updateAccessToken($iStoreID, $sAccessToken)
{
  $con = new ConnectionMySQL();
  $con->CreateConnection();
  $SQL = "UPDATE tiendanube
    SET access_token = '$sAccessToken'
    WHERE store_id = '$iStoreID'";
  $con->ExecuteQuery($SQL);

  if ($con->ExecuteQuery($SQL)) {
    echo "<li><i class=\"iconsminds-profile btn text-success\"></i>Tu código de acceso ha sido restaurado.</li>";
  } else {
    echo "<li><i class=\"iconsminds-profile btn text-danger\"></i>ERROR: No pudimos editar tu código de acceso en nuestra base de datos.</li>";
    ending();
    die();
  }
  $con->CloseConnection();
}

function createNewFullAccount($iStoreID, $sAccessToken, $sMerchantName, $sStoreName, $txtStoreDescription, $sEmail, $sStoreDomain)
{
  // Paso 1 Creamos la cuenta via API

  //  Seteamos datos para crear cuenta
  global $iLostPasswordCode;
  $iPassword = rand(100000, 999999);
  $iLostPasswordCode = md5($iPassword . microtime());
  $datToday = date("Y-m-d H:i:s"); // Fecha de hoy

  // Creamos la cuenta via API
  $Url = SITE_URL . "admin-api/users/";
  $Body = array(
    'name' => $sMerchantName,
    'email' => $sEmail,
    'password' => $iPassword
  );
  $Headers = array(
    'Content-Type' => 'application/x-www-form-urlencoded',
    'Authorization' => "Bearer " . SR_ADMIN_API_KEY,
    'User-Agent' => 'Widgy (api@widgy.app)'
  );
  $Response = Requests::post($Url, $Headers, $Body);
  $Response = json_decode($Response->body, true);

  // * Verificamos que recibimos la ID del usuario creado.
  if (!isset($Response['data']['id'])) {
    echo "<li><i class=\"iconsminds-profile btn text-danger\"></i>ERROR: La cuenta no pudo ser creada.</li>";
    ending();
    die();
  } else {
    $iCreatedUserID = $Response['data']['id'];
  }

  // Paso 2 Editamos la cuenta

  // * CREAMOS LOS PRE-DATOS

  // Datos de la tabla Users.

  // Seteamos el Pixel Key
  function string_generate($length)
  {
    $characters = str_split('abcdefghijklmnopqrstuvwxyz0123456789');
    $content = '';
    for ($i = 1; $i <= $length; $i++) {
      $content .= $characters[array_rand($characters, 1)];
    }
    return $content;
  }
  $sCampaignPixelKey = string_generate(32);


  // * EDITAMOS LA CUENTA
  $con = new ConnectionMySQL();
  $con->CreateConnection();
  $SQL = "UPDATE `users` SET 
    `lost_password_code` = '$iLostPasswordCode',
    `plan_trial_done` = '0',
    `plan_settings` = '{\"no_ads\":false,\"removable_branding\":false,\"custom_branding\":false,\"api_is_enabled\":true,\"affiliate_is_enabled\":false,\"campaigns_limit\":1,\"notifications_limit\":1,\"notifications_impressions_limit\":300,\"track_notifications_retention\":0,\"enabled_notifications\":{\"INFORMATIONAL\":true,\"COUPON\":false,\"LIVE_COUNTER\":false,\"EMAIL_COLLECTOR\":false,\"LATEST_CONVERSION\":false,\"CONVERSIONS_COUNTER\":false,\"VIDEO\":false,\"SOCIAL_SHARE\":false,\"RANDOM_REVIEW\":false,\"EMOJI_FEEDBACK\":false,\"COOKIE_NOTIFICATION\":false,\"SCORE_FEEDBACK\":false,\"REQUEST_COLLECTOR\":false,\"COUNTDOWN_COLLECTOR\":false,\"INFORMATIONAL_BAR\":false,\"IMAGE\":false,\"COLLECTOR_BAR\":false,\"COUPON_BAR\":false,\"BUTTON_BAR\":false,\"COLLECTOR_MODAL\":false,\"COLLECTOR_TWO_MODAL\":false,\"BUTTON_MODAL\":false,\"TEXT_FEEDBACK\":false,\"ENGAGEMENT_LINKS\":false}}',
    `language` = 'español (TiendaNube)' WHERE `users`.`user_id` = '$iCreatedUserID'";

  // * Verificamos que ejecutamos bien la consulta

  if ($con->ExecuteQuery($SQL)) {
    echo "<li><i class=\"iconsminds-profile btn text-success\"></i>Cuenta creada con el e-mail <strong>" . $sEmail . "</strong>.</li>";
  } else {
    echo "<li><i class=\"iconsminds-profile btn text-danger\"></i>ERROR: La cuenta no pudo ser editada.</li>";
    ending();
    die();
  }



  // Paso 3 Llenamos la tabla TiendaNube

  install($iCreatedUserID, $iStoreID, $sStoreName, $txtStoreDescription, $sStoreDomain, $sAccessToken);

  //Limpiamos el url_with_protocol
  $aStoreDomain = explode("//", $sStoreDomain);


  // Paso 4 Creamos la campaña
  $SQL = "INSERT INTO `campaigns` (`user_id`, `pixel_key`, `name`, `domain`, `include_subdomains`, `branding`, `is_enabled`, `last_datetime`, `datetime`)
      VALUES ( '" . $iCreatedUserID . "', '" . $sCampaignPixelKey . "', '" . $sStoreName . "', '" . $aStoreDomain[1] . "', '1', NULL, '1', NULL, '" . $datToday . "')";
  $con->ExecuteQuery($SQL);


  // Paso 5 Creamos la Notificaciones

  // Buscamos la campaign_id de la campaña previamente creada

  $SQL = "SELECT `campaign_id` FROM `campaigns` WHERE `pixel_key` = '$sCampaignPixelKey'";
  $Campaigns = $con->ExecuteQuery($SQL);
  $rowCampaigns = mysqli_fetch_assoc($Campaigns);

  if (empty($Campaigns)) {
    echo "<li><i class=\"iconsminds-profile btn text-danger\"></i>ERROR: No se pudo encontrar la campaña.</li>";
    ending();
    die();
  } else {


    // Creamos Inicio - Hay descuentos
    $sNotificationKey = md5($rowCampaigns['campaign_id'] . "INFORMATIONAL" . time() . rand(0, 1000));
    $aNotificationSettings = '{
    \"trigger_all_pages\": false,
    \"triggers\": [
        {
            \"type\": \"starts_with\",
            \"value\": \"' . $sStoreDomain . '\/\"
        }
    ],
    \"display_trigger\": \"delay\",
    \"display_trigger_value\": 1,
    \"display_frequency\": \"once_per_session\",
    \"display_mobile\": true,
    \"display_desktop\": true,
    \"shadow\": true,
    \"border_radius\": \"rounded\",
    \"border_width\": 1,
    \"border_color\": \"#FFAB6E\",
    \"on_animation\": \"bounceIn\",
    \"off_animation\": \"bounceOut\",
    \"background_pattern\": false,
    \"background_pattern_svg\": false,
    \"display_duration\": -1,
    \"display_position\": \"bottom_left\",
    \"display_close_button\": true,
    \"display_branding\": true,
    \"title\": \"\\\u00a1Bienvenid@!\",
    \"description\": \"\\\u00a1' . $sStoreName . ' es la mejor TiendaNube!\",
    \"image\": \"https:\/\/img.icons8.com\/bubbles\/100\/000000\/price-tag.png\",
    \"image_alt\": \"\",
    \"url\": \"\",
    \"url_new_tab\": false,
    \"title_color\": \"#FFAB6E\",
    \"description_color\": \"#5B5B5B\",
    \"background_color\": \"#f5f8ff\"}';

    $SQL = "INSERT INTO `notifications` (`campaign_id`, `user_id`, `name`, `type`, `settings`,  `last_action_date`, `notification_key`, `is_enabled`, `last_datetime`, `datetime`)
      VALUES ('" . $rowCampaigns['campaign_id'] . "', '" . $iCreatedUserID . "', 'Inicio', 'INFORMATIONAL', '" . $aNotificationSettings . "', NULL, '" . $sNotificationKey . "', '1', NULL, '" . $datToday . "')";
    $con->ExecuteQuery($SQL);

    // Creamos Estado de Pedido - En Camino
    $sNotificationKey = md5($rowCampaigns['campaign_id'] . "INFORMATIONAL" . time() . rand(0, 1000));
    $aNotificationSettings = '{\"trigger_all_pages\":false,\"triggers\":[{\"type\":\"page_contains\",\"value\":\"Tu pedido est\\\u00e1 en camino\"}],\"display_trigger\":\"delay\",\"display_trigger_value\":1,\"display_frequency\":\"all_time\",\"display_mobile\":true,\"display_desktop\":true,\"shadow\":true,\"border_radius\":\"rounded\",\"border_width\":1,\"border_color\":\"#FFAB6E\",\"on_animation\":\"zoomIn\",\"off_animation\":\"zoomOut\",\"background_pattern\":false,\"background_pattern_svg\":false,\"display_duration\":5,\"display_position\":\"bottom_left\",\"display_close_button\":true,\"display_branding\":true,\"title\":\"\\\u00a1En camino!\",\"description\":\"Tu paquete est\\\u00e1 viajando a destino..\",\"image\":\"https:\/\/img.icons8.com\/bubbles\/100\/000000\/in-transit.png\",\"url\":\"\",\"title_color\":\"#FFAB6E\",\"description_color\":\"#474747\",\"background_color\":\"#f5f8ff\"}';

    $SQL = "INSERT INTO `notifications` (`campaign_id`, `user_id`, `name`, `type`, `settings`, `last_action_date`, `notification_key`, `is_enabled`, `last_datetime`, `datetime`)
      VALUES ('" . $rowCampaigns['campaign_id'] . "', '" . $iCreatedUserID . "', 'Estado de Pedido - En Camino', 'INFORMATIONAL', '" . $aNotificationSettings . "', NULL, '" . $sNotificationKey . "', '1', NULL, '" . $datToday . "')";
    $con->ExecuteQuery($SQL);

    // Creamos Next Checkout - Promociones
    $sNotificationKey = md5($rowCampaigns['campaign_id'] . "INFORMATIONAL" . time() . rand(0, 1000));
    $aNotificationSettings = '{\"trigger_all_pages\":false,\"triggers\":[{\"type\":\"contains\",\"value\":\"checkout\/next\"},{\"type\":\"contains\",\"value\":\"checkout\/v3\/next\"}],\"display_trigger\":\"delay\",\"display_trigger_value\":1,\"display_frequency\":\"all_time\",\"display_mobile\":true,\"display_desktop\":true,\"shadow\":true,\"border_radius\":\"rounded\",\"border_width\":1,\"border_color\":\"#FFAB6E\",\"on_animation\":\"bounceIn\",\"off_animation\":\"bounceOut\",\"background_pattern\":false,\"background_pattern_svg\":false,\"display_duration\":6,\"display_position\":\"bottom_right\",\"display_close_button\":true,\"display_branding\":true,\"title\":\"Aceptamos todas las tarjetas..\",\"description\":\"Disponible en 3, 6, y 12 cuotas!\",\"image\":\"https:\/\/img.icons8.com\/bubbles\/100\/000000\/card-in-use.png\",\"url\":\"\",\"title_color\":\"#FFAB6E\",\"description_color\":\"#474747\",\"background_color\":\"#f5f8ff\"}';

    $SQL = "INSERT INTO `notifications` (`campaign_id`, `user_id`, `name`, `type`, `settings`, `last_action_date`, `notification_key`, `is_enabled`, `last_datetime`, `datetime`)
      VALUES ('" . $rowCampaigns['campaign_id'] . "', '" . $iCreatedUserID . "', 'Checkout (Pago) - Promociones', 'INFORMATIONAL', '" . $aNotificationSettings . "', NULL, '" . $sNotificationKey . "', '0', NULL, '" . $datToday . "')";
    $con->ExecuteQuery($SQL);

    // Creamos Productos - Últimas ventas
    $sNotificationKey = md5($rowCampaigns['campaign_id'] . "LATEST_CONVERSION" . time() . rand(0, 1000));
    $aNotificationSettings = '{\"trigger_all_pages\":false,\"triggers\":[{\"type\":\"contains\",\"value\":\"\/productos\/\"}],\"display_trigger\":\"delay\",\"display_trigger_value\":4,\"display_frequency\":\"once_per_session\",\"display_mobile\":true,\"display_desktop\":true,\"shadow\":true,\"border_radius\":\"rounded\",\"border_width\":1,\"border_color\":\"#FFAB6E\",\"on_animation\":\"slideInUp\",\"off_animation\":\"slideOutDown\",\"background_pattern\":false,\"background_pattern_svg\":false,\"display_duration\":5,\"display_position\":\"bottom_left\",\"display_close_button\":true,\"display_branding\":true,\"title\":\"{nombre} de {ciudad} llev\\\u00f3...\",\"description\":\"{producto} a s\\\u00f3lo: ${precio}\",\"image\":\"{imagen}\",\"url\":\"{enlace}?utm_source=Socialroot&utm_medium=' . $sStoreName . '&utm_campaign=Ejemplo: Mostrar \\\u00faltimas ventas\",\"conversions_count\":3,\"title_color\":\"#FFAB6E\",\"description_color\":\"#515151\",\"background_color\":\"#f5f8ff\",\"data_trigger_auto\":false,\"data_triggers_auto\":[{\"type\":\"exact\",\"value\":\"\"}]}';

    $SQL = "INSERT INTO `notifications` (`campaign_id`, `user_id`, `name`, `type`, `settings`, `last_action_date`, `notification_key`, `is_enabled`, `last_datetime`, `datetime`)
      VALUES ('" . $rowCampaigns['campaign_id'] . "', '" . $iCreatedUserID . "', 'Productos - Últimas ventas', 'LATEST_CONVERSION', '" . $aNotificationSettings . "', NULL, '" . $sNotificationKey . "', '1', NULL, '" . $datToday . "')";

    if ($con->ExecuteQuery($SQL)) {
      echo "<li><i class=\"iconsminds-speach-bubble-9 btn text-success\"></i>Te hemos creado 4 notificaciones de ejemplo..</li>";
    } else {
      echo "<li><i class=\"iconsminds-speach-bubble-9 btn text-danger\"></i>ERROR: No pudimos crear las notificaciones de ejemplo.</li>";
      ending();
      die();
    }
  }


  // Paso 6 Cargamos la última venta.
  // Reconocemos la ultima venta de su tienda
  $Headers = array(
    'Content-Type' => 'application/json',
    'Authentication' => $sAccessToken,
    'User-Agent' => 'Widgy (api@widgy.app)'
  );
  $Url = "https://api.tiendanube.com/v1/" . $iStoreID . "/orders?page=1&per_page=1";
  $UltimaVenta = Requests::get($Url, $Headers);
  $UltimaVenta = json_decode($UltimaVenta->body, true);

  // La enviamos a nuestro propio webhook general, de la misma forma que lo haría TiendaNube.


  if (!isset($UltimaVenta[0]['id'])) {
    echo "<li><i class=\"iconsminds-arrow-cross btn text-danger\"></i>ERROR: No pudimos obtener tu última venta.</li>";
  } else {



    $urlNotificationEndPoint = SITE_URL . "pixel-webhook/" . $sNotificationKey; // Es la última notificación que fue creada.


    $Url = SITE_URL . "integrations/tiendanube/webhook_handler.php?webhook=" . $urlNotificationEndPoint;
    $Body = array(
      'store_id' => $iStoreID,
      'event' => 'order/created',
      'id' => $UltimaVenta[0]['id']
    );
    $Body = json_encode($Body, true);
    // Avisamos que nos vamos a enviar contenido en jSon.
    $Headers = array(
      'Content-Type' => 'application/json'
    );

    //Enviamos la data para que la procese como un webhook más.
    $Response = Requests::post($Url, $Headers, $Body);



    // Paso 7 Activamos el webhook de últimas ventas
    $Url = "https://api.tiendanube.com/v1/" . $iStoreID . "/webhooks";
    //Seteamos el cuerpo del mensaje que vamos a enviar luego
    $Body = array(
      'event' => 'order/created',
      'url' => SITE_URL . 'integrations/tiendanube/webhook_handler.php?webhook=' . $urlNotificationEndPoint
    );
    $Body = json_encode($Body, true);
    // Avisamos que vamos a enviar contenido en jSon.
    $Headers = array(
      'Content-Type' => 'application/json',
      'Authentication' => $sAccessToken,
      'User-Agent' => 'Widgy (api@widgy.app)'
    );

    //Enviamos Paso  y recibimos el Access_token y el Store_ID
    $Response = Requests::post($Url, $Headers, $Body);
    // Insertamos el webhook ID en la base de datos.
    $Response = json_decode($Response->body, true);

    if ($Response->status_code != 201) {
      echo "<li><i class=\"iconsminds-arrow-cross btn text-danger\"></i>ERROR: No pudimos dar de alta el webhook.</li>";
    } else {
      $iWebhookID = $Response['id'];
    }


    // Paso 8 Actualizamos el WebhookID en la tabla TiendaNube
    $SQL = "UPDATE `tiendanube` SET `order_created` = '$iWebhookID' WHERE store_id = '$iStoreID'";
    $Result = $con->ExecuteQuery($SQL);
  }


  // Paso 9 Insertamos el script de la campaña, en la tienda.
  $Url = "https://api.tiendanube.com/v1/" . $iStoreID . "/scripts";
  $pixel = SITE_URL . "pixel/" . $sCampaignPixelKey . "/";
  //Seteamos el cuerpo del mensaje que vamos a enviar luego
  $Body = array(
    'src' => $pixel, // El pixel de la campaña
    'event' => 'onload',
    'where' => "store,checkout"
  );
  $Body = json_encode($Body, true);
  // Avisamos que vamos a enviar contenido en jSon.
  $Headers = array(
    'Content-Type' => 'application/json',
    'Authentication' => $sAccessToken,
    'User-Agent' => 'Widgy (api@widgy.app)'
  );
  $Response = Requests::post($Url, $Headers, $Body);

  if ($Response->status_code != 201) {
    echo "<li><i class=\"iconsminds-arrow-cross btn text-danger\"></i>ERROR: No pudimos insertar nuestro script en tu tienda.</li>";
  }

  # Close Connection
  $con->CloseConnection();
}

function createLimitedAccount($sMerchantName, $sEmail, $iOldUserID)
{
  //  Seteamos datos para crear cuenta
  global $iLostPasswordCode;
  $iPassword = rand(100000, 999999);
  $iLostPasswordCode = md5($iPassword . microtime());
  // Creamos la cuenta que irá con restricciones.
  $Url = SITE_URL . "admin-api/users/";
  $Body = array(
    'name' => $sMerchantName,
    'email' => $sEmail,
    'password' => $iPassword
  );
  $Headers = array(
    'Content-Type' => 'application/x-www-form-urlencoded',
    'Authorization' => "Bearer " . SR_ADMIN_API_KEY,
    'User-Agent' => 'Widgy (api@widgy.app)'
  );
  $Response = Requests::post($Url, $Headers, $Body);
  $Response = json_decode($Response->body, true);

  //  Verificamos que recibimos la ID del usuario creado.
  if (empty($Response['data']['id'])) {
    echo "<li><i class=\"iconsminds-arrow-cross btn text-danger\"></i>ERROR: Cuenta no encontrada, no se pudo crear otra cuenta.</li>";
    die();
  } else {
    $iCreatedUserID = $Response['data']['id'];
  }

  //  EDITAMOS LA CUENTA
  $con = new ConnectionMySQL();
  $con->CreateConnection();
  $SQL = "UPDATE `users` SET `user_id` = '$iOldUserID',
        `lost_password_code` = '$iLostPasswordCode',
        `plan_trial_done` = '1',
        `plan_settings` = '{\"no_ads\":false,\"removable_branding\":false,\"custom_branding\":false,\"api_is_enabled\":true,\"affiliate_is_enabled\":false,\"campaigns_limit\":1,\"notifications_limit\":1,\"notifications_impressions_limit\":300,\"track_notifications_retention\":0,\"enabled_notifications\":{\"INFORMATIONAL\":true,\"COUPON\":false,\"LIVE_COUNTER\":false,\"EMAIL_COLLECTOR\":false,\"LATEST_CONVERSION\":false,\"CONVERSIONS_COUNTER\":false,\"VIDEO\":false,\"SOCIAL_SHARE\":false,\"RANDOM_REVIEW\":false,\"EMOJI_FEEDBACK\":false,\"COOKIE_NOTIFICATION\":false,\"SCORE_FEEDBACK\":false,\"REQUEST_COLLECTOR\":false,\"COUNTDOWN_COLLECTOR\":false,\"INFORMATIONAL_BAR\":false,\"IMAGE\":false,\"COLLECTOR_BAR\":false,\"COUPON_BAR\":false,\"BUTTON_BAR\":false,\"COLLECTOR_MODAL\":false,\"COLLECTOR_TWO_MODAL\":false,\"BUTTON_MODAL\":false,\"TEXT_FEEDBACK\":false,\"ENGAGEMENT_LINKS\":false}}',
        `language` = 'español (TiendaNube)'
        WHERE `users`.`user_id` = '$iCreatedUserID'";
  $con->ExecuteQuery($SQL);
}
#endregion


?>


<?php

// Paso 1 - RECIBIR STOREID y ACCESSTOKEN.

// Chequeamos si nos enviaron el code
if (!isset($_GET['code'])) {
  $Error = 1;
  echo "No se pudo recibir el CODE de TiendaNube.<br> Es probable que haya un problema en su sistema.<br> ¡Comunicate con nosotros por el chat!<br>";
  die();
}
// Enviamos el code a TiendaNube para recibir el StoreID y AccessToken
$sCode = $_GET['code'];
$Url = "https://www.tiendanube.com/apps/authorize/token";
$Body = array(
  'code' => $sCode,
  'grant_type' => 'authorization_code',
  'client_secret' => TN_CLIENT_SECRET,
  'client_id' => TN_CLIENT_ID
);
$Headers = array(
  'Content-Type' => 'application/x-www-form-urlencoded'
);

$usResponse = Requests::post($Url, $Headers, $Body);
$usResponse = json_decode($usResponse->body, true);

if (!isset($usResponse["user_id"])) {
  $Error = 1;
  echo "No se pudieron recibir los datos de TiendaNube.<br> Es probable que haya un problema en su sistema.<br> ¡Comunicate con nosotros por el chat!<br>";
  die();
}

// sec Limpiamos y seteamos las variables para utilizarlas mas fácilmente
$iStoreID = sec($usResponse["user_id"]);
$sAccessToken = sec($usResponse["access_token"]);

// sec Verificación de errores antes de avanzar.
if (empty($iStoreID) || !is_numeric($iStoreID) || empty($sAccessToken) || strlen($sAccessToken) != 40) {
  die("Script terminado. Error crítico.");
}

// Paso 2 - Pedimos datos de la tienda

$Url = "https://api.tiendanube.com/v1/" . $iStoreID . "/store/";
$Headers = array(
  'Content-Type' => 'application/json',
  'Authentication' => $sAccessToken,
  'User-Agent' => 'Widgy (api@widgy.app)'
);

$usResponse = Requests::get($Url, $Headers);
$usResponse = json_decode($usResponse->body, true);

// Guardamos las variables en limpio.
$sStoreName          = sec($usResponse["name"]["es"]); // Nombre de compañia
$txtStoreDescription = sec($usResponse["description"]["es"]); //
$sStoreDomain        = sec($usResponse["url_with_protocol"]); // URL Con el protocolo (http/https)
$sMerchantName       = sec($usResponse["business_name"]); // Nombre de la persona
$sEmail              = sec($usResponse["email"]); // Email DE LA TIENDA.



// Sec Vacío el nombre, le ponemos "Nuevo Usuario"
if (empty($sMerchantName)) {
  $sMerchantName = "Nuevo Usuario";
}

// sec Chequeamos el certificado SSL
if (stristr($usResponse["url_with_protocol"], 'https') === FALSE) {
  die("El sitio " . $sStoreDomain . " no posee un certificado seguro SSL (HTTPS). <p> Deberás comunicarte con el soporte de TiendaNube para solucionarlo.");
}


// Paso 3  Verificaciones en Base de Datos.       

// db Verificamos si existe la Storeid en 'TIENDANUBE'
$con = new ConnectionMySQL();
$con->CreateConnection();
$SQL = "SELECT * FROM tiendanube WHERE store_id = '$iStoreID'";
$Result = $con->ExecuteQuery($SQL);


$bTiendaNube = false; // Suponemos que no existe.

while ($row = mysqli_fetch_assoc($Result)) {
  if ($row['store_id'] == $iStoreID) {
    // Si Existe StoreID en 'TIENDANUBE'
    $iUserID = $row['user_id'];
    $bTiendaNube = true;
  }
}

switch ($bTiendaNube) {

  case true:
    // ? TN = TRUE : Verificamos si existe el User_ID en 'USERS'

    $bUserExist = false; // Suponemos que no existe.

    $SQL = "SELECT * FROM users WHERE user_id = '$iUserID'";
    $Result = $con->ExecuteQuery($SQL);
    while ($row = mysqli_fetch_assoc($Result)) {
      if ($row["user_id"] == $iUserID) {
        // Si Existe Usuario en USERS (Usuario conocido, Actualizar AccessToken y Logear)
        $bUserExist = true;
      }
    }

    // Si NO Existe Usuario en USERS (Usuario eliminado. Crear cuenta limitada)
    if ($bUserExist != true) {
      $bUserExist = false;
    }
    break;


  case false:
    // ? TN FALSE : Verificamos si existe el Email en 'USERS'

    $bUserExist = false; // Suponemos que no existe.

    $SQL = "SELECT * FROM users WHERE email = '$sEmail'";
    $Result = $con->ExecuteQuery($SQL);
    while ($row = mysqli_fetch_assoc($Result)) {
      if ($row["email"] == $sEmail) {
        // Si Existe Email en USERS (Insertar tiendanube)
        $iUserID = $row["user_id"];
        $bUserExist = true;
      }
    }

    break;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dore jQuery</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <link rel="stylesheet" href="/../themes/widgy/assets/font/iconsmind-s/css/iconsminds.css" />
  <link rel="stylesheet" href="/../themes/widgy/assets/font/simple-line-icons/css/simple-line-icons.css" />

  <link rel="stylesheet" href="/../themes/widgy/assets/css/vendor/bootstrap.min.css" />
  <link rel="stylesheet" href="/../themes/widgy/assets/css/vendor/bootstrap.rtl.only.min.css" />
  <link rel="stylesheet" href="/../themes/widgy/assets/css/dore.light.orangecarrot.min.css" />
  <link rel="stylesheet" href="/../themes/widgy/assets/css/main.css" />
</head>


<body class="background show-spinner no-footer">
  <div class="fixed-background"></div>
  <main>
    <div class="container">
      <div class="row h-100">
        <div class="col-12 col-sm-8 col-md-10 mx-auto my-auto">
          <div class="card index-card">
            <?php
            switch ($bTiendaNube) {
                // SI existe StoreID
              case true:
                switch ($bUserExist) {
                    //  SI existe StoreID y EMAIL (USUARIO REGISTRADO)
                  case true:
            ?>
                    <div class="card-body text-center form-side">
                      <a href="Dashboard.Default.html">
                        <span class="logo-single"></span>
                      </a>
                      <p class="lead mb-5">¡Logeandote!</p>
                      <div class="row d-flex justify-content-center">

                        <div class="col-12 offset-0 col-md-8 offset-md-2 mb-2">
                          <ul class="list-unstyled" style="text-align:left;">
                            <?php updateAccessToken($iStoreID, $sAccessToken); ?>
                            <p>Esta tienda ya está integrada con Widgy.</p>
                            <a href="<?= login($iUserID) ?>"><button class="btn btn-secondary btn-xl" type="button">INGRESAR EN MI CUENTA</button></a>
                          </ul>
                        </div>
                        <div class="col-12 offset-0 col-md-8 offset-md-2 col-lg-6 offset-lg-3 newsletter-input-container">
                          <div class="input-group mb-3">
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php
                    break;
                    //  SI existe StoreID pero NO EMAIL (USUARIO BORRADO)
                  case false:
                  ?>
                    <div class="card-body text-center form-side">
                      <a href="Dashboard.Default.html">
                        <span class="logo-single"></span>
                      </a>
                      <?php updateAccessToken($iStoreID, $sAccessToken); ?>
                      <?php createLimitedAccount($sMerchantName, $sEmail, $iUserID)  ?>
                      <p class="lead mb-5">¡Ya estás registrado!</p>
                      <div class="row">

                        <div class="col-12 offset-0 col-md-8 offset-md-2 mb-2">
                          <h2>Atención, el sistema nos indica que ya tenias una cuenta con el email. <strong><?= $sEmail ?></strong><br> Solo se permite un período de prueba por tienda.</h2>
                        </div>
                        <div class="col-12 offset-0 col-md-8 offset-md-2 col-lg-6 offset-lg-3 newsletter-input-container">
                          <a href="<?= SITE_URL . 'reset-password/' . $sEmail . '/' . $iLostPasswordCode ?>"><button class="btn btn-secondary btn-xl" type="button">INGRESAR EN MI CUENTA</button></a>
                        </div>
                      </div>
                    </div>
                  <?php
                    break;
                }

                break;
                // SI NO existe StoreID
              case false:
                switch ($bUserExist) {
                    // SI NO existe StoreID pero si EMAIL (INSTALAR TIENDANUBE)
                  case true:
                  ?>
                    <div class="card-body text-center form-side">
                      <a href="Dashboard.Default.html">
                        <span class="logo-single"></span>
                      </a>
                      <p class="lead mb-5">¡Integrando tu cuenta a TiendaNube!</p>
                      <div class="row">

                        <div class="col-12 offset-0 col-md-8 offset-md-2 mb-2">
                          <ul class="list-unstyled" style="text-align:left;">
                            <?php install($iUserID, $iStoreID, $sStoreName, $txtStoreDescription, $sStoreDomain, $sAccessToken);  ?>
                            <?php // TODO if session notification, then back to notif, else login 
                            ?>
                            <?php echo "<script>window.location.href='" . login($iUserID) . "';</script>"; ?>
                          </ul>
                        </div>
                        <div class="col-12 offset-0 col-md-8 offset-md-2 col-lg-6 offset-lg-3 newsletter-input-container">
                          <div class="input-group mb-3">
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php
                    break;
                    // SI NO existe StoreID y tampoco EMAIL (CREAR NUEVA CUENTA)
                  case false:
                  ?>
                    <div class="card-body text-center form-side">
                      <a href="Dashboard.Default.html">
                        <span class="logo-single"></span>
                      </a>
                      <p class="lead mb-5">Creando cuenta!</p>
                      <div class="row">

                        <div class="col-12 offset-0 col-md-8 offset-md-2 mb-2">
                          <ul class="list-unstyled" style="text-align:left;">
                            <?= createNewFullAccount($iStoreID, $sAccessToken, $sMerchantName, $sStoreName, $txtStoreDescription, $sEmail, $sStoreDomain);  ?>
                          </ul>
                        </div>
                        <div class="col-12 offset-0 col-md-8 offset-md-2 col-lg-6 offset-lg-3 newsletter-input-container">
                          <div class="input-group mb-3">
                            <a href="<?= SITE_URL . 'reset-password/' . $sEmail . '/' . $iLostPasswordCode ?>"><button class="btn btn-secondary btn-xl" type="button">INGRESAR EN MI CUENTA</button></a>
                          </div>
                        </div>
                      </div>
                    </div>
            <?php
                    break;
                }

                break;
            }
            ?>

          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="/../themes/widgy/assets/js/vendor/jquery-3.3.1.min.js"></script>
  <script src="/../themes/widgy/assets/js/vendor/bootstrap.bundle.min.js"></script>
  <script src="/../themes/widgy/assets/js/dore.script.js"></script>
  <script src="/../themes/widgy/assets/js/scripts.single.theme.js"></script>
</body>

</html>