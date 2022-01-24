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

function login($iUserID) // Devuelve la URL de one-time-login
{
  $Url = SITE_URL . "admin-api/users/" . $iUserID . "/one-time-login-code";
  $Options = [
    'verify' => false
  ];
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
  $Response = Requests::post($Url, $Headers, $Body, $Options);
  $decodedResponse = json_decode($Response->body, true);

  if ($Response->status_code != 200) {
    return "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_server_down_s4lk.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">Error</div>
      <div class=\"blog-slider__text\">No pudimos obtener el enlace de acceso para tu cuenta.</div>
      <a href=\"#\" onclick=\"Intercom('show');\" class=\"blog-custom__button \">Habla con nosotros</a>
    </div>
  </div>
  ";
  } else {
    return "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_secure_login_pdn4.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">Ingresa</div>
      <div class=\"blog-slider__text\">Click para ingresar con tu cuenta de Widgy.</div>
    <a href=\"" . $decodedResponse['data']['url'] . "\" class=\"blog-custom__button\">Ingresar con mi cuenta</a>
    </div>
  </div>
  ";
  }
}

function insertOnDB($iUserID, $iStoreID, $sStoreName, $txtStoreDescription, $sStoreDomain, $sAccessToken)
{
  // Insertamos en la Tabla TiendaNube los datos.
  $con = new ConnectionMySQL();
  $con->CreateConnection();


  $SQL = "INSERT INTO tiendanube ( user_id, store_id, store_name, store_description, domain, access_token, order_created, order_paid, order_packed, order_fulfilled, product_created, product_updated)
    VALUES ( '" . $iUserID . "', '" . $iStoreID . "', '" . $sStoreName . "', '" . $txtStoreDescription . "', '" . $sStoreDomain . "', '" . $sAccessToken . "', '0', '0', '0', '0', '0', '0')";

  if ($con->ExecuteQuery($SQL)) {
    return "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_The_world_is_mine_re_j5cr.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">¡Integración completa!</div>
      <div class=\"blog-slider__text\">Has conectado tu tienda a Widgy correctamente!<br><br>Ya puedes volver a editar tus geniales notificaciones.</div>
      <a href=\"#\" class=\"blog-slider__button \">Siguiente</a>
    </div>
  </div>
  ";
  } else {
    return "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_server_down_s4lk.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">Error en DB</div>
      <div class=\"blog-slider__text\">No pudimos guardar los datos de TiendaNube.</div>
      <a href=\"#\" onclick=\"Intercom('show');\" class=\"blog-custom__button \">Habla con nosotros</a>
    </div>
  </div>
  ";
  }
}

function updateAccessToken($iStoreID, $sAccessToken)
{
  $con = new ConnectionMySQL();
  $con->CreateConnection();
  $SQL = "UPDATE tiendanube
    SET access_token = '$sAccessToken'
    WHERE store_id = '$iStoreID'";

  if (!$con->ExecuteQuery($SQL)) {
    return "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_Cancel_re_ctke.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">¡Ups!</div>
      <div class=\"blog-slider__text\">ERROR: No pudimos recibir los datos de acceso de TiendaNube.. \n Por favor comunicate con el servicio de soporte.</div>
      <a href=\"#\" onclick=\"Intercom('show');\" class=\"blog-custom__button \">Habla con nosotros</a>
    </div>
  </div>
  ";
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
  $Options = [
    'verify' => false
  ];
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

  $Response = Requests::post($Url, $Headers, $Body, $Options);
  $Response = json_decode($Response->body, true);

  // * Verificamos que recibimos la ID del usuario creado.
  if (!isset($Response['data']['id'])) {
    return "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_server_down_s4lk.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">Error en creación de cuenta</div>
      <div class=\"blog-slider__text\">Por alguna razón no pudimos crearte una cuenta en Widgy.</div>
      <a href=\"#\" onclick=\"Intercom('show');\" class=\"blog-custom__button \">Habla con nosotros</a>
    </div>
  </div>
  ";
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
    echo "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_spread_love_r9jb.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">¡Felicidades!</div>
      <div class=\"blog-slider__text\">¡Has creado una cuenta en Widgy!<br><br>Preparate para llenar de vida esa hermosa tienda 🚀<br><br>Tu email de acceso es: <strong>$sEmail</strong> </div>
      <a href=\"#\" class=\"blog-slider__button \">Siguiente</a>
    </div>
  </div>
    ";
  } else {
    return "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_server_down_s4lk.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">Error en creación de cuenta</div>
      <div class=\"blog-slider__text\">Por alguna razón no pudimos editar la cuenta creada en Widgy.</div>
      <a href=\"#\" onclick=\"Intercom('show');\" class=\"blog-custom__button \">Habla con nosotros</a>
    </div>
  </div>
  ";
  }



  // Paso 3 Llenamos la tabla TiendaNube

  insertOnDB($iCreatedUserID, $iStoreID, $sStoreName, $txtStoreDescription, $sStoreDomain, $sAccessToken);

  //Limpiamos el url_with_protocol
  $aStoreDomain = explode("//", $sStoreDomain);


  // Paso 4 Creamos la campaña
  $SQL = "INSERT INTO `campaigns` (`user_id`, `pixel_key`, `name`, `domain`, `include_subdomains`, `branding`, `is_enabled`, `last_datetime`, `datetime`)
      VALUES ( '" . $iCreatedUserID . "', '" . $sCampaignPixelKey . "', '" . $sStoreName . "', '" . $aStoreDomain[1] . "', '1', NULL, '1', NULL, '" . $datToday . "')";

  if ($con->ExecuteQuery($SQL)) {
    echo "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_Website_builder_re_ii6e.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">Campaña</div>
      <div class=\"blog-slider__text\">Hemos creado una campaña para tu tienda llamada <strong>$sStoreName</strong>.<br><br>Esta campaña solo funcionará en <strong>$aStoreDomain[0]//$aStoreDomain[1]</strong>.</div>
      <a href=\"#\" class=\"blog-slider__button \">Siguiente</a>
    </div>
  </div>
    ";
  } else {
    return "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_server_down_s4lk.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">Error en creación de campaña</div>
      <div class=\"blog-slider__text\">No hemos podido crear una campaña. <br>Por favor, crea una contraseña y luego comunicate con nosotros al chat.</div>
      <a href=\"   " . SITE_URL . "reset-password/" . $sEmail . "/" . $iLostPasswordCode . "        \" class=\"blog-custom__button \">Crear contraseña</a>
    </div>
  </div>
  ";
  }


  // Paso 5 Creamos la Notificaciones

  // Buscamos la campaign_id de la campaña previamente creada

  $SQL = "SELECT `campaign_id` FROM `campaigns` WHERE `pixel_key` = '$sCampaignPixelKey'";
  $Campaigns = $con->ExecuteQuery($SQL);
  $rowCampaigns = mysqli_fetch_assoc($Campaigns);

  if (empty($Campaigns)) {
    return "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_server_down_s4lk.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">Error en creación de cuenta</div>
      <div class=\"blog-slider__text\">No pudimos crear una campaña con la tienda $sStoreName.</div>
      <a href=\"#\" onclick=\"Intercom('show');\" class=\"blog-custom__button \">Habla con nosotros</a>
    </div>
  </div>
  ";
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
      echo "
      <div class=\"blog-slider__item swiper-slide\">
      <div class=\"blog-slider__img\">
        <img src=\"img/undraw_building_websites_i78t.png\" alt=\"\">
      </div>
      <div class=\"blog-slider__content\">
        <div class=\"blog-slider__title\">Notificaciones</div>
        <div class=\"blog-slider__text\">Hemos añadido <strong>4</strong> excelentes notificaciones de ejemplo a tu campaña.<br><br>Podrás activarlas, editarlas o eliminarlas una vez dentro de tu tablero. </div>
        <a href=\"#\" class=\"blog-slider__button \">Siguiente</a>
      </div>
    </div>
      ";
    } else {
      echo "
      <div class=\"blog-slider__item swiper-slide\">
      <div class=\"blog-slider__img\">
        <img src=\"img/undraw_server_down_s4lk.png\" alt=\"\">
      </div>
      <div class=\"blog-slider__content\">
        <div class=\"blog-slider__title\">¡Ups!</div>
        <div class=\"blog-slider__text\">No pudimos crear notificaciones de ejemplo para $sStoreName. 
        <br><br> Tendras que crearlas por tu cuenta una vez dentro del panel.</div>
        <a href=\"#\" class=\"blog-slider__button \">Siguiente</a>
      </div>
    </div>
    ";
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
    echo "
      <div class=\"blog-slider__item swiper-slide\">
      <div class=\"blog-slider__img\">
        <img src=\"img/undraw_No_data_re_kwbl.png\" alt=\"\">
      </div>
      <div class=\"blog-slider__content\">
        <div class=\"blog-slider__title\">¿No hay ventas?</div>
        <div class=\"blog-slider__text\">No conseguimos leer la última venta. 
        <br><br> Nada de que preocuparse, seguro es una tienda nueva.<br><br> Si no es asi, avisanos al chat y continúa con el proceso.<br><br>Te felicitamos por la valentía de emprender un negocio 💪..</div>
        <div class=\"row\">
        <a onclick=\"Intercom('show');\" class=\"blog-custom__button \">Habla con nosotros</a>
        <a href=\"#\" class=\"blog-slider__button \">Siguiente</a>
        </div>
      </div>
    </div>
    ";
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
    $Response = Requests::post($Url, $Headers, $Body, $Options);


    // Paso 7 Activamos el webhook de últimas ventas
    $Url = "https://api.tiendanube.com/v1/" . $iStoreID . "/webhooks";
    //Seteamos el cuerpo del mensaje que vamos a enviar luego
    $Body = array(
      'event' => 'order/created',
      'url' => SITE_URL . 'integrations/tiendanube/webhook_handler.php?endpoint=' . $sNotificationKey
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

    if ($Response->status_code != 201) {
      echo "
      <div class=\"blog-slider__item swiper-slide\">
      <div class=\"blog-slider__img\">
        <img src=\"img/undraw_Page_not_found_re_e9o6.png\" alt=\"\">
      </div>
      <div class=\"blog-slider__content\">
        <div class=\"blog-slider__title\">Error en conexión</div>
        <div class=\"blog-slider__text\">No pudimos activar el evento <strong>Última venta</strong>. 
        <br><br> Nada de que preocuparse igualmente.<br><br>Por favor, avisanos al chat y continúa con el proceso.</div>
        <a href=\"#\" class=\"blog-slider__button \">Siguiente</a>
      </div>
    </div>
    ";
    } else {
      $Response = json_decode($Response->body, true);
      $iWebhookID = $Response['id'];
    }


    // Paso 8 Actualizamos el WebhookID en la tabla TiendaNube
    $SQL = "UPDATE `tiendanube` SET `order_created` = '$iWebhookID' WHERE store_id = '$iStoreID'";
    $con->ExecuteQuery($SQL);
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

  if ($Response->status_code == 201) {
    echo "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_Developer_activity_re_39tg.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">Pixel</div>
      <div class=\"blog-slider__text\">Para que Widgy pueda mostrar notificaciones en tu sitio, necesitas <strong>insertar un código Javascript en tu sitio</strong>.
      <br><br>Afortunadamente <strong>¡Ya hicimos esto por ti!</strong> 😚.<br><br>Se ha insertado el código y no es necesario que lo hagas manualmente.</div>
      <a href=\"#\" class=\"blog-slider__button \">Siguiente</a>
    </div>
  </div>
  ";
  } else {
    echo "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_Notify_re_65on.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">Error en Script</div>
      <div class=\"blog-slider__text\">No pudimos insertar nuestro Javascript en tu tienda.. 
      <br><br> Tendrás que hacerlo manualmente.<br><br>Por favor, escribenos al chat y te ayudaremos.</div>
      <a href=\"#\" class=\"blog-slider__button \">Siguiente</a>
    </div>
  </div>
  ";
  }

  $con->CloseConnection();

  return "
  <div class=\"blog-slider__item swiper-slide\">
  <div class=\"blog-slider__img\">
    <img src=\"img/undraw_noted_pc9f.png\" alt=\"\">
  </div>
  <div class=\"blog-slider__content\">
    <div class=\"blog-slider__title\">¡Todo listo!</div>
    <div class=\"blog-slider__text\">Recuerda que tu email de acceso es: <strong>$sEmail</strong></div>
    <a href=\"   " . SITE_URL . "reset-password/" . $sEmail . "/" . $iLostPasswordCode . "        \" class=\"blog-custom__button \">Crear contraseña</a>
  </div>
</div>
";
}

function createLimitedAccount($sMerchantName, $sEmail, $iOldUserID)
{
  //  Seteamos datos para crear cuenta
  global $iLostPasswordCode;
  $iPassword = rand(100000, 999999);
  $iLostPasswordCode = md5($iPassword . microtime());
  // Creamos la cuenta que irá con restricciones.
  $Url = SITE_URL . "admin-api/users/";
  $Options = [
    'verify' => false
  ];
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
  $Response = Requests::post($Url, $Headers, $Body, $Options);
  $Response = json_decode($Response->body, true);

  //  Verificamos que recibimos la ID del usuario creado.
  if (empty($Response['data']['id'])) {
    return "
    <div class=\"blog-slider__item swiper-slide\">
    <div class=\"blog-slider__img\">
      <img src=\"img/undraw_authentication_re_svpt.png\" alt=\"\">
    </div>
    <div class=\"blog-slider__content\">
      <div class=\"blog-slider__title\">Error en API</div>
      <div class=\"blog-slider__text\">Detectamos que ya existió una cuenta para tu tienda, pero no pudimos crearte otra.</div>
      <a href=\"#\" onclick=\"Intercom('show');\" class=\"blog-custom__button \">Habla con nosotros</a>
    </div>
  </div>
  ";
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

  if ($con->ExecuteQuery($SQL)) {
    return "
  <div class=\"blog-slider__item swiper-slide\">
  <div class=\"blog-slider__img\">
    <img src=\"img/undraw_authentication_re_svpt.png\" alt=\"\">
  </div>
  <div class=\"blog-slider__content\">
    <div class=\"blog-slider__title\">¡Cuenta creada!</div>
    <div class=\"blog-slider__text\">Detectamos que esta tienda ya ha sido integrada con Widgy.<br><br>
    Te hemos creado una cuenta con el email <strong>$sEmail</strong> para que puedas seguir dandole vida a tu TiendaNube.</div>
    <a href=\"   " . SITE_URL . "reset-password/" . $sEmail . "/" . $iLostPasswordCode . "        \" class=\"blog-custom__button \">Crear contraseña</a>
  </div>
</div>
";
  }
}
#endregion


?>


<?php

// Paso 1 - Recibimos STOREID y ACCESSTOKEN.

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


// Paso 3 - ROUTER.
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Widgy.app - Integración con TiendaNube </title>
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/css/swiper.min.css'>
  <link rel="stylesheet" href="./style.css">

  <script>
    window.intercomSettings = {
      app_id: "nds4bz0l"
    };
  </script>

  <script>
    // We pre-filled your app ID in the widget URL: 'https://widget.intercom.io/widget/nds4bz0l'
    (function() {
      var w = window;
      var ic = w.Intercom;
      if (typeof ic === "function") {
        ic('reattach_activator');
        ic('update', w.intercomSettings);
      } else {
        var d = document;
        var i = function() {
          i.c(arguments);
        };
        i.q = [];
        i.c = function(args) {
          i.q.push(args);
        };
        w.Intercom = i;
        var l = function() {
          var s = d.createElement('script');
          s.type = 'text/javascript';
          s.async = true;
          s.src = 'https://widget.intercom.io/widget/nds4bz0l';
          var x = d.getElementsByTagName('script')[0];
          x.parentNode.insertBefore(s, x);
        };
        if (document.readyState === 'complete') {
          l();
        } else if (w.attachEvent) {
          w.attachEvent('onload', l);
        } else {
          w.addEventListener('load', l, false);
        }
      }
    })();
  </script>

</head>

<body>
  <!-- partial:index.partial.html -->
  <div class="blog-slider">
    <div class="blog-slider__wrp swiper-wrapper">
      <!-- partial:CARD -->
      <?php

      // * Verificamos si existe la Storeid en 'TIENDANUBE'

      $con = new ConnectionMySQL();
      $con->CreateConnection();
      $SQL = "SELECT * FROM tiendanube WHERE store_id = '$iStoreID'";
      $Result = $con->ExecuteQuery($SQL);
      $bStoreIDExistOnDB = false; // Suponemos que no existe.

      while ($row = mysqli_fetch_assoc($Result)) {
        if ($row['store_id'] == $iStoreID) {
          $iUserID = $row['user_id'];
          $bStoreIDExistOnDB = true;
        }
      }

      switch ($bStoreIDExistOnDB) {

        case true:
          // • StoreID en 'TiendaNube' = SI
          // * Verificamos si existe el User_ID en 'USERS'
          !$iUserID ? "Error Critico. No sabemos cual es el USER ID." : "";

          $SQL = "SELECT * FROM users WHERE user_id = '$iUserID'";
          $Result = $con->ExecuteQuery($SQL);
          $bUserExistsOnDB = false; // Suponemos que no existe.

          while ($row = mysqli_fetch_assoc($Result)) {
            if ($row["user_id"] == $iUserID) {
              $bUserExistsOnDB = true;
            }
          }

          switch ($bUserExistsOnDB) {
            case true:
              // • StoreID en 'TiendaNube' = SI 
              // • UserID en 'Users' = SI
              printf(updateAccessToken($iStoreID, $sAccessToken));
              printf(login($iUserID));
              break;

            case false:
              // • StoreID en 'TiendaNube' = SI 
              // • UserID en 'Users' = NO
              printf(createLimitedAccount($sMerchantName, $sEmail, $iUserID));
              break;
          }

          break;


        case false:
          // • StoreID en 'TiendaNube' = NO
          // * Verificamos si existe el Email en 'USERS'

          $SQL = "SELECT * FROM users WHERE email = '$sEmail'";
          $Result = $con->ExecuteQuery($SQL);
          $bUserExistsOnDB = false; // Suponemos que no existe.

          while ($row = mysqli_fetch_assoc($Result)) {
            if ($row["email"] == $sEmail) {
              $iUserID = $row["user_id"];
              $bUserExistsOnDB = true;
            }
          }

          // ? Reconocemos si está logeado con otro mail:
          if (isset($_SESSION["user_id"]) & is_numeric($_SESSION["user_id"])) {
            $iUserID = $_SESSION["user_id"];
            $bUserExistsOnDB = true;
          }


          switch ($bUserExistsOnDB) {
            case true:
              // • StoreID en 'TiendaNube' = NO 
              // • UserID en 'Users' O Logeado = SI
              printf(insertOnDB($iUserID, $iStoreID, $sStoreName, $txtStoreDescription, $sStoreDomain, $sAccessToken));
              printf(login($iUserID));
              break;

            case false:
              // • StoreID en 'TiendaNube' = NO 
              // • UserID en 'Users' O Logeado = NO
              printf(createNewFullAccount($iStoreID, $sAccessToken, $sMerchantName, $sStoreName, $txtStoreDescription, $sEmail, $sStoreDomain));
              break;
          }
          break;
      }

      ?>
      <!-- partial:CARD -->
    </div>
  </div>
  <!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/js/swiper.min.js'></script>
  <script src="./script.js"></script>

</body>

</html>