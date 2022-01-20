<?php
require $_SERVER['DOCUMENT_ROOT'] . '/integrations/tiendanube/con_mysql.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', 1);
// Declaramos variables especiales.
$sStoreDomain = sec($data->notification->domain);
$iUserID = $this->user->user_id;

$_SESSION['step'] = 0; // Declaramos un step para no duplicar las requests.

// Paso 1 : Verificamos la tabla TIENDANUBE 

$con = new ConnectionMySQL();
$con->CreateConnection();
$SQL = "SELECT * FROM tiendanube WHERE user_id = '$iUserID' ";
$Result = $con->ExecuteQuery($SQL);

// Obtenemos el array asociativo de la columna
while ($row = mysqli_fetch_assoc($Result)) {
  $SQL_StoreID = $row['store_id'];
  $SQL_Domain = $row['domain'];
  $SQL_Access_Token = $row['access_token'];
  $SQL_Order_Created = $row['order_created'];
  $SQL_Order_Paid = $row['order_paid'];
  $SQL_Order_Packed = $row['order_packed'];
  $SQL_Order_Fulfilled = $row['order_fulfilled'];
  $SQL_Product_Created = $row['product_created'];
  $SQL_Product_Updated = $row['product_updated'];

  $_SESSION['store_id'] = sec($row['store_id']);
  $_SESSION['access_token'] = sec($row['access_token']);
  $_SESSION['notification_url'] =  SITE_URL . \Altum\Language::$language_code . "/notification/" . $data->notification->notification_id;
}

function webhookGetKey($SQL_StoreID, $SQL_Access_Token, $iWebhookID)
{
  if ($iWebhookID == 0) {
    return NULL;
  }

  $Url = "https://api.tiendanube.com/v1/" . $SQL_StoreID . "/webhooks/" . $iWebhookID;
  $Headers = array(
    'Content-Type' => 'application/json',
    'Authentication' => 'bearer ' . $SQL_Access_Token,
    'User-Agent' => 'Cartelitos (Cartelitos.app@gmail.com)'
  );
  $Response = Requests::get($Url, $Headers);
  $Response = json_decode($Response->body, true);

  if (!empty($Response['id'])) {
    $pixel_url = explode("endpoint=", $Response['url']);

    $aBundle[] = $Response['id'];
    $aBundle[] = $Response['event'];
    $aBundle[] = $pixel_url[1];

    return $aBundle;
  }
}

?>

<!-- SECCION TIENDANUBE -->
<section name="TiendaNube">


  <? // Si NO existe la StoreID en TiendaNube: 
  ?>
  <?php if (empty($SQL_StoreID)) { ?>

    <script>
      function Handle_TN_Auth() {
        location.href = "https://<?= $data->notification->domain ?>/admin/apps/<?= TN_CLIENT_ID ?>/authorize/";
      }
    </script>

    <section class="align-items-center justify-content-center" id="Instalar">
      <div class="d-flex justify-content-center align-items-center">
        <h3>¡Integra tu <span class="glow">TiendaNube</span> con Cartelitos!</h3>
      </div>
      <div class="d-flex justify-content-center">
        <h6 class="text-center">Por ejemplo, puedes crear confianza en tu marca <b>mostrando tus últimas ventas</b> o también generar interacción con tus clientes <b>avisando de un nuevo producto</b>
          <p>
          <p>Todo esto al alcance de un click.</p></a>
        </h6>
      </div>
      <hr>
      <div class="d-flex justify-content-center align-items-center">
        <img class="" src="https://img.icons8.com/clouds/150/000000/shop.png">
      </div>
      <div class=" input-group py-1">
        <div class="input-group-prepend">
          <span class="input-group-text">https://</span>
        </div>
        <input type="text" id="dominio" readonly="readonly" value="<?= $data->notification->domain ?>" data-toggle="tooltip" data-placement="top" title="Se integrará con <?= $data->notification->domain ?> o puedes crear otra campaña, con otro dominio..." class="form-control text-uppercase">
      </div>

      <div name="Integrar" class=" mt-1 mb-2 d-flex justify-content-center" data-width="fit">
        <button class="btn btn-success shadow glow col-lg-8 col-md-6 col-sm-6 col-xs-6" type="button" onclick="Handle_TN_Auth()" name="integrar">
          <div class="d-flex align-items-center justify-content-center"><span>¡Integrar con mi tienda!<span></div>
        </button>
      </div>
      <hr>

      <? // Si existe la StoreID en TiendaNube: 
      ?>
    <?php } else {

    // sec Chequeamos que el Access Token sea el correcto
    $Authentication = "bearer " . $SQL_Access_Token;
    $Headers = array(
      'Content-Type' => 'application/json',
      'Authentication' => $Authentication,
      'User-Agent' => 'Socialroot (hola@socialroot.io)'
    );
    $Url = "https://api.tiendanube.com/v1/" . $SQL_StoreID . "/store/";
    $Response = Requests::get($Url, $Headers);
    $Response = json_decode($Response->body, true);

    // No podemos acceder por la API, updateamos el Token.
    if (empty($Response['id'])) {
      $update_accs_url = "https://" . $data->notification->domain . "/admin/apps/" . TN_CLIENT_ID . "/authorize/";
      echo "<center><h2>Error con Acceso a TiendaNube<p><a href=\"";
      echo $update_accs_url;
      echo "\">SOLICITAR NUEVO ACCESO.</a></p></h2></center>";
    }
  }
    ?>

    <?php if (!empty($Response['id'])) { ?>


      <?php

      // Pedimos las notifications keys en cada webhook que tenemos en la DB
      $SQL_Webhook_ids = array($SQL_Order_Created, $SQL_Order_Paid, $SQL_Order_Packed, $SQL_Order_Fulfilled, $SQL_Product_Created, $SQL_Product_Updated);

      foreach ($SQL_Webhook_ids as $nWebhookID) {
        $tempNotificationKey = webhookGetKey($SQL_StoreID, $SQL_Access_Token, $nWebhookID);
        $aNotificationKeys[] = $tempNotificationKey; // Las guardamos en un array
      }

      // Vemos si alguna key pertenece a esta notificación.
      foreach ($aNotificationKeys as $mKey[0]) {

        if (isset($mKey[0][2]) == $data->notification->notification_key) {
          // Entonces guardamos tanto la key como el evento.
          $msWebhookID = $mKey[0][0];
          $msWebhookEvent = $mKey[0][1];
          $msNotificationKey = $mKey[0][2];
        }
      }


      ?>

      <section id="Tiendanube">

        <?php $PHPSESSIDhash = md5($_COOKIE["PHPSESSID"] . "r00t4m0r") ?>

        <!-- // TODO establecer CSRF -->
        <div class="d-flex justify-content-center">
          <h2>Configuración de eventos de TiendaNube</h2>
        </div>

        <hr>


        <?php if ($msNotificationKey != $data->notification->notification_key) : ?>


          <div class="container">
            <div class="d-flex justify-content-start">
              <h4>¿Qué evento te gustaría recibir en esta notificación?</h4>
            </div>
          </div>

          <div>
            <div class="row mt-2 d-flex align-items-center justify-content-center">
              <div class="text-center col-xs-12 col-md-4">
                <p><a <?= $SQL_Order_Created != 0 ? "" : "href=\"" . SITE_URL . "integrations/tiendanube/event_handler.php?action=create&endpoint=" . urlencode($data->notification->notification_key) . "&event=order-created&state=" . $PHPSESSIDhash . " \" " ?>><span class="badge bg-<?= $SQL_Order_Created != 0 ? "dark" : "primary" ?> btn-block" <?= $SQL_Order_Created != 0 ? " data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Este evento ha sido activado en otra notificación.\"" : "" ?>><img src="https://img.icons8.com/bubbles/150/000000/buy.png" /></span></a></p>
                <p><strong>Orden Creada</strong></p>
              </div>
              <div class="text-center col-md-4">
                <p><a <?= $SQL_Order_Paid != 0 ? "" : "href=\"" . SITE_URL . "integrations/tiendanube/event_handler.php?action=create&endpoint=" . urlencode($data->notification->notification_key) . "&event=order-paid&state=" . $PHPSESSIDhash . " \" " ?>><span class="badge bg-<?= $SQL_Order_Paid != 0 ? "dark" : "primary" ?> btn-block" <?= $SQL_Order_Paid != 0 ? " data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Este evento ha sido activado en otra notificación.\"" : "" ?>><img src="https://img.icons8.com/bubbles/150/000000/check.png" /></span></a></p>
                <p><strong>Orden Pagada</strong></p>
              </div>
              <div class="text-center col-md-4">
                <p><a <?= $SQL_Order_Packed != 0 ? "" : "href=\"" . SITE_URL . "integrations/tiendanube/event_handler.php?action=create&endpoint=" . urlencode($data->notification->notification_key) . "&event=order-packed&state=" . $PHPSESSIDhash . " \" " ?>><span class="badge bg-<?= $SQL_Order_Packed != 0 ? "dark" : "primary" ?> btn-block" <?= $SQL_Order_Packed != 0 ? " data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Este evento ha sido activado en otra notificación.\"" : "" ?>><img src="https://img.icons8.com/bubbles/150/000000/product.png" /></span></a></p>
                <p><strong>Orden Empaquetada</strong></p>
              </div>
            </div>
            <div class="row mt-2 d-flex align-items-center justify-content-center">
              <div class="text-center col-xs-12 col-md-4">
                <p><a <?= $SQL_Order_Fulfilled != 0 ? "" : "href=\"" . SITE_URL . "integrations/tiendanube/event_handler.php?action=create&endpoint=" . urlencode($data->notification->notification_key) . "&event=order-fulfilled&state=" . $PHPSESSIDhash . " \" " ?>><span class="badge bg-<?= $SQL_Order_Fulfilled != 0 ? "dark" : "primary" ?> btn-block" <?= $SQL_Order_Fulfilled != 0 ? " data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Este evento ha sido activado en otra notificación.\"" : "" ?>><img src="https://img.icons8.com/bubbles/150/000000/truck.png" /></span></a></p>
                <p><strong>Orden Cumplida</strong></p>
              </div>
              <div class="text-center col-md-4">
                <p><a <?= $SQL_Product_Created != 0 ? "" : "href=\"" . SITE_URL . "integrations/tiendanube/event_handler.php?action=create&endpoint=" . urlencode($data->notification->notification_key) . "&event=product-created&state=" . $PHPSESSIDhash . " \" " ?>><span class="badge bg-<?= $SQL_Product_Created != 0 ? "dark" : "primary" ?> btn-block" <?= $SQL_Product_Created != 0 ? " data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Este evento ha sido activado en otra notificación.\"" : "" ?>><img src="https://img.icons8.com/bubbles/150/000000/new.png" /></span></a></p>
                <p><strong>Producto Creado</strong></p>
              </div>
              <div class="text-center col-md-4">
                <p><a <?= $SQL_Product_Updated != 0 ? "" : "href=\"" . SITE_URL . "integrations/tiendanube/event_handler.php?action=create&endpoint=" . urlencode($data->notification->notification_key) . "&event=product-updated&state=" . $PHPSESSIDhash . " \" " ?>><span class="badge bg-<?= $SQL_Product_Updated != 0 ? "dark" : "primary" ?> btn-block" <?= $SQL_Product_Updated != 0 ? " data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Este evento ha sido activado en otra notificación.\"" : "" ?>><img src="https://img.icons8.com/bubbles/150/000000/price-tag.png" /></span></a></p>
                <p><strong>Producto Actualizado</strong></p>
              </div>
            </div>
          </div>
        <?php endif ?>

        <?php if ($msNotificationKey == $data->notification->notification_key) : ?>

          <?php
          switch ($msWebhookEvent) {

            case 'order/created': ?>
              <div class="container">
                <div class="d-flex justify-content-start">
                  <h4>Evento activado: <span class="text-success"><strong>Orden Creada</strong></span></h4>
                </div>
              </div>
              <p class="h6">
                <br>¡Genial! ¡Has activado correctamente este evento!
              </p>
              <p><br>Lo que significa que cuando una orden haya sido creada en tu tienda, <b>TiendaNube</b> nos enviará los datos para procesarlos y mostrarlos
                en la notificación.
                <br>Estos <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/notification/" . $data->notification->notification_id . "/data" ?>">datos</a>, que guardaremos en forma de variables dinámicas, pueden usarse en los campos de tu notificación
                como veremos en el siguiente ejemplo:<br><br>
                <input type="text" id="example" class="form-control" value="{nombre} de {ciudad} compro...">
                <hr>Si quieres recibir otro tipo de evento, puedes <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/dashboard/" ?>">crear otra notificación</a>,
                o directamente <a class="text-danger" href="<?= SITE_URL . "integrations/tiendanube/event_handler.php?state=" . $PHPSESSIDhash . "&action=delete&event=" . str_replace("/", "-", $msWebhookEvent)  ?>">desactivar este evento</a>.
              </p>
            <?php break;

            case 'order/paid': ?>
              <div class="container">
                <div class="d-flex justify-content-start">
                  <h4>Evento activado: <span class="text-success"><strong>Orden Pagada</strong></span></h4>
                </div>
              </div>
              <p class="h6">
                <br>¡Genial! ¡Has activado correctamente este evento!
              </p>
              <p><br>Lo que significa que cuando una orden haya sido abonada en tu tienda, <b>TiendaNube</b> nos enviará los datos para procesarlos y mostrarlos
                en la notificación.
                <br>Estos <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/notification/" . $data->notification->notification_id . "/data" ?>">datos</a>, que guardaremos en forma de variables dinámicas, pueden usarse en los campos de tu notificación
                como veremos en el siguiente ejemplo:<br><br>
                <input type="text" id="example" class="form-control" value="{nombre} de {ciudad} ha abonado...">
                <hr>Si quieres recibir otro tipo de evento, puedes <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/dashboard/" ?>">crear otra notificación</a>,
                o directamente <a class="text-danger" href="<?= SITE_URL . "integrations/tiendanube/event_handler.php?state=" . $PHPSESSIDhash . "&action=delete&event=" . str_replace("/", "-", $msWebhookEvent)  ?>">desactivar este evento</a>.
              </p>

            <?php break;

            case 'order/packed': ?>
              <div class="container">
                <div class="d-flex justify-content-start">
                  <h4>Evento activado: <span class="text-success"><strong>Orden Empaquetada</strong></span></h4>
                </div>
              </div>
              <p class="h6">
                <br>¡Genial! ¡Has activado correctamente este evento!
              </p>
              <p><br>Lo que significa que cuando una orden haya sido marcada como empaquetada en tu tienda, <b>TiendaNube</b> nos enviará los datos para procesarlos y mostrarlos
                en la notificación.
                <br>Estos <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/notification/" . $data->notification->notification_id . "/data" ?>">datos</a>, que guardaremos en forma de variables dinámicas, pueden usarse en los campos de tu notificación
                como veremos en el siguiente ejemplo:<br><br>
                <input type="text" id="example" class="form-control" value="{producto} está listo para enviarse a {nombre} de {ciudad}">
                <hr>Si quieres recibir otro tipo de evento, puedes <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/dashboard/" ?>">crear otra notificación</a>,
                o directamente <a class="text-danger" href="<?= SITE_URL . "integrations/tiendanube/event_handler.php?state=" . $PHPSESSIDhash . "&action=delete&event=" . str_replace("/", "-", $msWebhookEvent)  ?>">desactivar este evento</a>.
              </p>

            <?php break;

            case 'order/fulfilled': ?>
              <div class="container">
                <div class="d-flex justify-content-start">
                  <h4>Evento activado: <span class="text-success"><strong>Orden Cumplida</strong></span></h4>
                </div>
              </div>
              <p class="h6">
                <br>¡Genial! ¡Has activado correctamente este evento!
              </p>
              <p><br>Lo que significa que cuando una orden haya sido completada en tu tienda, <b>TiendaNube</b> nos enviará los datos para procesarlos y mostrarlos
                en la notificación.
                <br>Estos <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/notification/" . $data->notification->notification_id . "/data" ?>">datos</a>, que guardaremos en forma de variables dinámicas, pueden usarse en los campos de tu notificación
                como veremos en el siguiente ejemplo:<br><br>
                <input type="text" id="example" class="form-control" value="{producto} ha sido entregado en {ciudad} ...">
                <hr>Si quieres recibir otro tipo de evento, puedes <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/dashboard/" ?>">crear otra notificación</a>,
                o directamente <a class="text-danger" href="<?= SITE_URL . "integrations/tiendanube/event_handler.php?state=" . $PHPSESSIDhash . "&action=delete&event=" . str_replace("/", "-", $msWebhookEvent)  ?>">desactivar este evento</a>.
              </p>

            <?php break;

            case 'product/created': ?>
              <div class="container">
                <div class="d-flex justify-content-start">
                  <h4>Evento activado: <span class="text-success"><strong>Producto Creado</strong></span></h4>
                </div>
              </div>
              <p class="h6">
                <br>¡Genial! ¡Has activado correctamente este evento!
              </p>
              <p><br>Lo que significa que cuando crees un nuevo producto en tu tienda, <b>TiendaNube</b> nos enviará los datos para procesarlos y mostrarlos
                en la notificación.
                <br>Estos <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/notification/" . $data->notification->notification_id . "/data" ?>">datos</a>, que guardaremos en forma de variables dinámicas, pueden usarse en los campos de tu notificación
                como veremos en el siguiente ejemplo:<br><br>
                <input type="text" id="example" class="form-control" value="Atención! Llegaron los nuevos {producto} a solo ${precio}!">
                <hr>Si quieres recibir otro tipo de evento, puedes <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/dashboard/" ?>">crear otra notificación</a>,
                o directamente <a class="text-danger" href="<?= SITE_URL . "integrations/tiendanube/event_handler.php?state=" . $PHPSESSIDhash . "&action=delete&event=" . str_replace("/", "-", $msWebhookEvent)  ?>">desactivar este evento</a>.
              </p>

            <?php break;

            case 'product/updated': ?>
              <div class="container">
                <div class="d-flex justify-content-start">
                  <h4>Evento activado: <span class="text-success"><strong>Producto Actualizado</strong></span></h4>
                </div>
              </div>
              <p class="h6">
                <br>¡Genial! ¡Has activado correctamente este evento!
              </p>
              <p><br>Lo que significa que cuando actualices un producto, <b>TiendaNube</b> nos enviará los nuevos datos para procesarlos y mostrarlos
                en la notificación.
                <br>Estos <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/notification/" . $data->notification->notification_id . "/data" ?>">datos</a>, que guardaremos en forma de variables dinámicas, pueden usarse en los campos de tu notificación
                como veremos en el siguiente ejemplo:<br><br>
                <input type="text" id="example" class="form-control" value="{producto} ahora tiene un precio de ${precio}.">
                <hr>Si quieres recibir otro tipo de evento, puedes <a class="text-primary" href="<?= SITE_URL . \Altum\Language::$language_code . "/dashboard/" ?>">crear otra notificación</a>,
                o directamente <a class="text-danger" href="<?= SITE_URL . "integrations/tiendanube/event_handler.php?state=" . $PHPSESSIDhash . "&action=delete&event=" . str_replace("/", "-", $msWebhookEvent)  ?>">desactivar este evento</a>.
              </p>
          <?php break;
          }
          ?>

        <?php endif ?>


        <hr>

        <style>
          .project-tab #tabs {
            background: #007b5e;
            color: #eee;
          }

          .project-tab #tabs h6.section-title {
            color: #eee;
          }

          .project-tab #tabs .nav-tabs .nav-item.show .nav-link,
          .nav-tabs .nav-link.active {
            color: #0062cc;
            background-color: transparent;
            border-color: transparent transparent #f3f3f3;
            border-bottom: 3px solid !important;
            font-size: 16px;
            font-weight: bold;
          }

          .project-tab .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
            color: #0062cc;
            font-size: 16px;
            font-weight: 600;
          }

          .project-tab .nav-link:hover {
            border: none;
          }

          .project-tab thead {
            background: #f3f3f3;
            color: #333;
          }

          .project-tab a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
          }
        </style>



        <?php
        if ($msNotificationKey == $data->notification->notification_key) {
          switch ($msWebhookEvent) {

            case 'order/created': ?>

              <div class="container">
                <div class="d-flex justify-content-start">
                  <h4>Variables que puedes utilizar con este evento..</h4>
                </div>
              </div>
              <br>

              <div class="container">
                <div id="tabs" class="project-tab">
                  <div class="container">
                    <div class="row">
                      <div class="col-md-12">
                        <table class="table table-bordered table-hover" cellspacing="0">
                          <thead class="thead-dark">
                            <tr>
                              <th>Variable</th>
                              <th>Valor de ejemplo</th>
                              <th>Ejemplo de uso</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td><span class="text-primary">nombre</span></td>
                              <td>Roxana</td>
                              <td>¡<span class="text-primary">{nombre}</span> se ha registrado al newsletter!</td>
                            </tr>
                            <tr>
                              <td><span class="text-primary">ciudad</span></td>
                              <td>Balcarce</td>
                              <td><span class="text-primary">{nombre}</span> de <span class="text-primary">{ciudad}</span> compró...</td>
                            </tr>
                            <tr>
                              <td><span class="text-primary">provincia</span></td>
                              <td>Salta</td>
                              <td><span class="text-primary">{nombre}</span> de <span class="text-primary">{provincia}</span> compró...</td>
                            </tr>
                            <tr>
                              <td><span class="text-primary">producto</span></td>
                              <td>Medias de lana</td>
                              <td>¡Acaban de comprar <span class="text-primary">{producto}</span>!</td>
                            </tr>
                            <tr>
                              <td><span class="text-primary">variacion</span></td>
                              <td>T: XXL</td>
                              <td>Se vendieron <span class="text-primary">{producto}</span> <span class="text-primary">{variacion}</span>!</td>
                            </tr>
                            <tr>
                              <td><span class="text-primary">cantidad</span></td>
                              <td>2</td>
                              <td>¡Alguien compro <span class="text-primary">{cantidad}</span> <span class="text-primary">{producto}</span>!</td>
                            </tr>
                            <tr>
                              <td><span class="text-primary">stock</span></td>
                              <td>4</td>
                              <td>¡Solo quedan <span class="text-primary">{stock}</span> <span class="text-primary">{producto}</span>!</td>
                            </tr>
                            <tr>
                              <td><span class="text-primary">precio</span></td>
                              <td>399</td>
                              <td>¡Vendimos <span class="text-primary">{producto}</span> a solo $<span class="text-primary">{precio}</span>!</td>
                            </tr>
                            <tr>
                              <td><span class="text-primary">imagen</span></td>
                              <td>
                                <...>/imagenes/medias.jpg
                              </td>
                              <td><u><span class="text-primary">Se utiliza en campos de IMAGEN</u></td>
                            </tr>
                            <tr>
                              <td><span class="text-primary">enlace</span></td>
                              <td>
                                <...>/productos/medias-lana
                              </td>
                              <td><u><span class="text-primary">Se utiliza en campos de URL</u></td>
                            </tr>
                          </tbody>
                        </table>

                        <hr>
                        <div name="INFORMACION" class="mt-2 d-flex align-items-center justify-content-center">
                          <div>
                            <span class="badge btn-sm btn-light-secondary">Nota: Las notificaciones mostrarán solo el primer producto del carrito.</span>
                          </div>
                        </div>
                        <hr>
                      <?php break;

                    case 'order/paid': ?>
                        <div class="container">
                          <div class="d-flex justify-content-start">
                            <h4>Variables que puedes utilizar con este evento..</h4>
                          </div>
                        </div>
                        <br>

                        <div class="container">
                          <div id="tabs" class="project-tab">
                            <div class="container">
                              <div class="row">
                                <div class="col-md-12">
                                  <table class="table table-bordered table-hover" cellspacing="0">
                                    <thead class="thead-dark">
                                      <tr>
                                        <th>Variable</th>
                                        <th>Valor de ejemplo</th>
                                        <th>Ejemplo de uso</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td><span class="text-primary">numero</span></td>
                                        <td>40212</td>
                                        <td>¡La orden N°<span class="text-primary">{numero}</span> ha sido pagada!</td>
                                      </tr>
                                      <tr>
                                        <td><span class="text-primary">nombre</span></td>
                                        <td>Roxana</td>
                                        <td>¡<span class="text-primary">{nombre}</span> se ha registrado al newsletter!</td>
                                      </tr>
                                      <tr>
                                        <td><span class="text-primary">ciudad</span></td>
                                        <td>Balcarce</td>
                                        <td><span class="text-primary">{nombre}</span> de <span class="text-primary">{ciudad}</span> compró...</td>
                                      </tr>
                                      <tr>
                                        <td><span class="text-primary">provincia</span></td>
                                        <td>Salta</td>
                                        <td><span class="text-primary">{nombre}</span> de <span class="text-primary">{provincia}</span> compró...</td>
                                      </tr>
                                      <tr>
                                        <td><span class="text-primary">producto</span></td>
                                        <td>Medias de lana</td>
                                        <td>¡Acaban de comprar <span class="text-primary">{producto}</span>!</td>
                                      </tr>
                                      <tr>
                                        <td><span class="text-primary">variacion</span></td>
                                        <td>T: XXL</td>
                                        <td>Se vendieron <span class="text-primary">{producto}</span> <span class="text-primary">{variacion}</span>!</td>
                                      </tr>
                                      <tr>
                                        <td><span class="text-primary">cantidad</span></td>
                                        <td>2</td>
                                        <td>¡Alguien compro <span class="text-primary">{cantidad}</span> <span class="text-primary">{producto}</span>!</td>
                                      </tr>
                                      <tr>
                                        <td><span class="text-primary">stock</span></td>
                                        <td>4</td>
                                        <td>¡Solo quedan <span class="text-primary">{stock}</span> <span class="text-primary">{producto}</span>!</td>
                                      </tr>
                                      <tr>
                                        <td><span class="text-primary">precio</span></td>
                                        <td>399</td>
                                        <td>¡Vendimos <span class="text-primary">{producto}</span> a solo $<span class="text-primary">{precio}</span>!</td>
                                      </tr>
                                      <tr>
                                        <td><span class="text-primary">imagen</span></td>
                                        <td>
                                          <...>/imagenes/medias.jpg
                                        </td>
                                        <td><u><span class="text-primary">Se utiliza en campos de IMAGEN</u></td>
                                      </tr>
                                      <tr>
                                        <td><span class="text-primary">enlace</span></td>
                                        <td>
                                          <...>/productos/medias-lana
                                        </td>
                                        <td><u><span class="text-primary">Se utiliza en campos de URL</u></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                <?php break;

                              case 'order/packed': ?>
                                  <div class="container">
                                    <div class="d-flex justify-content-start">
                                      <h4>Variables que puedes utilizar con este evento..</h4>
                                    </div>
                                  </div>
                                  <br>

                                  <div class="container">
                                    <div id="tabs" class="project-tab">
                                      <div class="container">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <table class="table table-bordered table-hover" cellspacing="0">
                                              <thead class="thead-dark">
                                                <tr>
                                                  <th>Variable</th>
                                                  <th>Valor de ejemplo</th>
                                                  <th>Ejemplo de uso</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <tr>
                                                  <td><span class="text-primary">numero</span></td>
                                                  <td>40212</td>
                                                  <td>¡La orden N°<span class="text-primary">{numero}</span> ha sido enpaquetada!</td>
                                                </tr>
                                                <tr>
                                                  <td><span class="text-primary">nombre</span></td>
                                                  <td>Roxana</td>
                                                  <td>¡<span class="text-primary">{nombre}</span> se ha registrado al newsletter!</td>
                                                </tr>
                                                <tr>
                                                  <td><span class="text-primary">ciudad</span></td>
                                                  <td>Balcarce</td>
                                                  <td><span class="text-primary">{nombre}</span> de <span class="text-primary">{ciudad}</span> compró...</td>
                                                </tr>
                                                <tr>
                                                  <td><span class="text-primary">provincia</span></td>
                                                  <td>Salta</td>
                                                  <td><span class="text-primary">{nombre}</span> de <span class="text-primary">{provincia}</span> compró...</td>
                                                </tr>
                                                <tr>
                                                  <td><span class="text-primary">producto</span></td>
                                                  <td>Medias de lana</td>
                                                  <td>¡Acaban de comprar <span class="text-primary">{producto}</span>!</td>
                                                </tr>
                                                <tr>
                                                  <td><span class="text-primary">variacion</span></td>
                                                  <td>T: XXL</td>
                                                  <td>Se vendieron <span class="text-primary">{producto}</span> <span class="text-primary">{variacion}</span>!</td>
                                                </tr>
                                                <tr>
                                                  <td><span class="text-primary">cantidad</span></td>
                                                  <td>2</td>
                                                  <td>¡Alguien compro <span class="text-primary">{cantidad}</span> <span class="text-primary">{producto}</span>!</td>
                                                </tr>
                                                <tr>
                                                  <td><span class="text-primary">stock</span></td>
                                                  <td>4</td>
                                                  <td>¡Solo quedan <span class="text-primary">{stock}</span> <span class="text-primary">{producto}</span>!</td>
                                                </tr>
                                                <tr>
                                                  <td><span class="text-primary">precio</span></td>
                                                  <td>399</td>
                                                  <td>¡Vendimos <span class="text-primary">{producto}</span> a solo $<span class="text-primary">{precio}</span>!</td>
                                                </tr>
                                                <tr>
                                                  <td><span class="text-primary">imagen</span></td>
                                                  <td>
                                                    <...>/imagenes/medias.jpg
                                                  </td>
                                                  <td><u><span class="text-primary">Se utiliza en campos de IMAGEN</u></td>
                                                </tr>
                                                <tr>
                                                  <td><span class="text-primary">enlace</span></td>
                                                  <td>
                                                    <...>/productos/medias-lana
                                                  </td>
                                                  <td><u><span class="text-primary">Se utiliza en campos de URL</u></td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          <?php break;

                                        case 'order/fulfilled': ?>
                                            <div class="container">
                                              <div class="d-flex justify-content-start">
                                                <h4>Variables que puedes utilizar con este evento..</h4>
                                              </div>
                                            </div>
                                            <br>

                                            <div class="container">
                                              <div id="tabs" class="project-tab">
                                                <div class="container">
                                                  <div class="row">
                                                    <div class="col-md-12">
                                                      <table class="table table-bordered table-hover" cellspacing="0">
                                                        <thead class="thead-dark">
                                                          <tr>
                                                            <th>Variable</th>
                                                            <th>Valor de ejemplo</th>
                                                            <th>Ejemplo de uso</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody>
                                                          <tr>
                                                          <tr>
                                                            <td><span class="text-primary">numero</span></td>
                                                            <td>40212</td>
                                                            <td>¡La orden N°<span class="text-primary">{numero}</span> ha sido enviada!</td>
                                                          </tr>
                                                          <td><span class="text-primary">nombre</span></td>
                                                          <td>Roxana</td>
                                                          <td>¡<span class="text-primary">{nombre}</span> ya está esperando su <span class="text-primary">{producto}</span>!</td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">ciudad</span></td>
                                                            <td>Balcarce</td>
                                                            <td>¡Enviamos <span class="text-primary">{cantidad}</span> <span class="text-primary">{producto}</span> a <span class="text-primary">{ciudad}</span>!</td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">provincia</span></td>
                                                            <td>Salta</td>
                                                            <td><span class="text-primary">{nombre}</span> de <span class="text-primary">{provincia}</span> compró...</td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">producto</span></td>
                                                            <td>Medias de lana</td>
                                                            <td>¡Acabamos de enviar <span class="text-primary">{producto}</span>!</td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">variacion</span></td>
                                                            <td>T: XXL</td>
                                                            <td>Se vendieron <span class="text-primary">{producto}</span> <span class="text-primary">{variacion}</span>!</td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">cantidad</span></td>
                                                            <td>2</td>
                                                            <td>¡Alguien compro <span class="text-primary">{cantidad}</span> <span class="text-primary">{producto}</span>!</td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">stock</span></td>
                                                            <td>4</td>
                                                            <td>¡Enviamos <span class="text-primary">{cantidad}</span> <span class="text-primary">{producto}</span>, solo quedan <span class="text-primary">{stock}</span>!</td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">precio</span></td>
                                                            <td>399</td>
                                                            <td>¡Vendimos <span class="text-primary">{producto}</span> a solo $<span class="text-primary">{precio}</span>!</td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">envio</span></td>
                                                            <td>Correo Argentino</td>
                                                            <td>¡Enviamos <span class="text-primary">{producto}</span> a <span class="text-primary">{nombre}</span> vía <span class="text-primary">{envio}</span>!</td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">fecha</span></td>
                                                            <td>04/07/21</td>
                                                            <td>¡El pedido de <span class="text-primary">{nombre}</span> salió el <span class="text-primary">{fecha}</span>!</td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">hora</span></td>
                                                            <td>15:32</td>
                                                            <td>¡El pedido de <span class="text-primary">{nombre}</span> fue enviado el <span class="text-primary">{fecha}</span> a las <span class="text-primary">{hora}</span>!</td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">imagen</span></td>
                                                            <td>
                                                              <...>/imagenes/medias.jpg
                                                            </td>
                                                            <td><u><span class="text-primary">Se utiliza en campos de IMAGEN</u></td>
                                                          </tr>
                                                          <tr>
                                                            <td><span class="text-primary">enlace</span></td>
                                                            <td>
                                                              <...>/productos/medias-lana
                                                            </td>
                                                            <td><u><span class="text-primary">Se utiliza en campos de URL</u></td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                    <?php break;

                                                  case 'product/created': ?>
                                                      <div class="container">
                                                        <div class="d-flex justify-content-start">
                                                          <h4>Variables que puedes utilizar con este evento..</h4>
                                                        </div>
                                                      </div>
                                                      <br>

                                                      <div class="container">
                                                        <div id="tabs" class="project-tab">
                                                          <div class="container">
                                                            <div class="row">
                                                              <div class="col-md-12">
                                                                <table class="table table-bordered table-hover" cellspacing="0">
                                                                  <thead class="thead-dark">
                                                                    <tr>
                                                                      <th>Variable</th>
                                                                      <th>Valor de ejemplo</th>
                                                                      <th>Ejemplo de uso</th>
                                                                    </tr>
                                                                  </thead>
                                                                  <tbody>
                                                                    <tr>
                                                                      <td><span class="text-primary">producto</span></td>
                                                                      <td>Camisa Estilo Militar</td>
                                                                      <td>¡Acabamos de subir las nuevas <span class="text-primary">{producto}</span>!</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><span class="text-primary">variacion</span></td>
                                                                      <td>Verde Oliva</td>
                                                                      <td>¡Llegaron las <span class="text-primary">{producto}</span> <span class="text-primary">{variacion}</span>!</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><span class="text-primary">stock</span></td>
                                                                      <td>4</td>
                                                                      <td>¡Entraron <span class="text-primary">{stock}</span> <span class="text-primary">{producto}</span>!</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><span class="text-primary">precio</span></td>
                                                                      <td>399</td>
                                                                      <td>¡Tenemos <span class="text-primary">{producto}</span> a solo $<span class="text-primary">{precio}</span>!</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><span class="text-primary">preciopromo</span></td>
                                                                      <td>276</td>
                                                                      <td>¡Tenemos <span class="text-primary">{producto}</span> a <span class="text-primary">{preciopromo}</span>!</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><span class="text-primary">fecha</span></td>
                                                                      <td>04/07/21</td>
                                                                      <td>¡<span class="text-primary">{producto}</span> disponible a partir del <span class="text-primary">{fecha}</span>!</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><span class="text-primary">imagen</span></td>
                                                                      <td>
                                                                        <...>/imagenes/medias.jpg
                                                                      </td>
                                                                      <td><u><span class="text-primary">Se utiliza en campos de IMAGEN</u></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><span class="text-primary">enlace</span></td>
                                                                      <td>
                                                                        <...>/productos/medias-lana
                                                                      </td>
                                                                      <td><u><span class="text-primary">Se utiliza en campos de URL</u></td>
                                                                    </tr>
                                                                  </tbody>
                                                                </table>
                                                              <?php break;

                                                            case 'product/updated': ?>
                                                                <div class="container">
                                                                  <div class="d-flex justify-content-start">
                                                                    <h4>Variables que puedes utilizar con este evento..</h4>
                                                                  </div>
                                                                </div>
                                                                <br>

                                                                <div class="container">
                                                                  <div id="tabs" class="project-tab">
                                                                    <div class="container">
                                                                      <div class="row">
                                                                        <div class="col-md-12">
                                                                          <table class="table table-bordered table-hover" cellspacing="0">
                                                                            <thead class="thead-dark">
                                                                              <tr>
                                                                                <th>Variable</th>
                                                                                <th>Valor de ejemplo</th>
                                                                                <th>Ejemplo de uso</th>
                                                                              </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                              <tr>
                                                                                <td><span class="text-primary">producto</span></td>
                                                                                <td>Camisa Estilo Militar</td>
                                                                                <td>¡Acabamos de subir las nuevas <span class="text-primary">{producto}</span>!</td>
                                                                              </tr>
                                                                              <tr>
                                                                                <td><span class="text-primary">variacion</span></td>
                                                                                <td>Verde Oliva</td>
                                                                                <td>¡Llegaron las <span class="text-primary">{producto}</span> <span class="text-primary">{variacion}</span>!</td>
                                                                              </tr>
                                                                              <tr>
                                                                                <td><span class="text-primary">stock</span></td>
                                                                                <td>4</td>
                                                                                <td>¡Entraron <span class="text-primary">{stock}</span> <span class="text-primary">{producto}</span>!</td>
                                                                              </tr>
                                                                              <tr>
                                                                                <td><span class="text-primary">precio</span></td>
                                                                                <td>399</td>
                                                                                <td>¡El precio de <span class="text-primary">{producto}</span> es ahora $<span class="text-primary">{precio}</span>!</td>
                                                                              </tr>
                                                                              <tr>
                                                                                <td><span class="text-primary">preciopromo</span></td>
                                                                                <td>276</td>
                                                                                <td>¡El precio de <span class="text-primary">{producto}</span> es ahora $<span class="text-primary">{preciopromo}</span>!</td>
                                                                              </tr>
                                                                              <tr>
                                                                                <td><span class="text-primary">fecha</span></td>
                                                                                <td>04/07/21</td>
                                                                                <td>¡A partir del <span class="text-primary">{fecha}</span> actualizamos <span class="text-primary">{producto}</span>!</td>
                                                                              </tr>
                                                                              <tr>
                                                                                <td><span class="text-primary">imagen</span></td>
                                                                                <td>
                                                                                  <...>/imagenes/medias.jpg
                                                                                </td>
                                                                                <td><u><span class="text-primary">Se utiliza en campos de IMAGEN</u></td>
                                                                              </tr>
                                                                              <tr>
                                                                                <td><span class="text-primary">enlace</span></td>
                                                                                <td>
                                                                                  <...>/productos/medias-lana
                                                                                </td>
                                                                                <td><u><span class="text-primary">Se utiliza en campos de URL</u></td>
                                                                              </tr>
                                                                            </tbody>
                                                                          </table>
                                                                    <?php break;
                                                                }
                                                              }
                                                                    ?>

                                                                        </div>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                                </div>

      </section>



    <?php } else { ?>
    <?php } ?>

    </section>
    <!-- FIN SECCION TIENDANUBE -->