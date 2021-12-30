<?php

session_start();
if (!isset($_SESSION["user"]["loggedIn"]) or $_SESSION["user"]["loggedIn"] != true) {
    die("Warning: error in index.aspx");
}

if (!isset($_GET["storeid"])) {
    die("No ID Selected");
}

$obj_conexion = mysqli_connect('127.0.0.1', 'ayrton', 'ayrton123', 'cartelitos.app');
if (!$obj_conexion) {
    echo "<h3>No se ha podido conectar PHP - MySQL, verifique sus datos.</h3><hr><br>";
}

if (empty($_GET["storeid"]) || !is_numeric($_GET["storeid"])) {
    die("Script terminado. Error crítico.");
  }

/* ejemplo de una consulta */
$storeid = htmlspecialchars($_GET['storeid'], ENT_QUOTES);

$var_consulta = "SELECT * FROM `tiendanube` WHERE `store_id` = " . $storeid . " ";
$var_resultado = $obj_conexion->query($var_consulta);

    while ($var_fila = $var_resultado->fetch_array()) {

        $jayParsedAry = [
            'info' => [
              '_postman_id' => '',
              'name' => $var_fila["domain"],
              'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            ],
            'item' => [
              0 => [
                'name' => '[STORE] Datos de la tienda',
                'protocolProfileBehavior' => [
                  'disabledSystemHeaders' => [
                    'user-agent' => true,
                  ],
                ],
                'request' => [
                  'auth' => [
                    'type' => 'noauth',
                  ],
                  'method' => 'GET',
                  'header' => [
                    0 => [
                      'key' => 'User-Agent',
                      'value' => 'Cartelitos (Cartelitos.app@gmail.com)',
                      'type' => 'text',
                    ],
                    1 => [
                      'key' => 'Content-Type',
                      'value' => 'application/json',
                      'type' => 'text',
                    ],
                    2 => [
                      'key' => 'Authentication',
                      'value' => 'bearer '. $var_fila["access_token"],
                      'type' => 'text',
                    ],
                  ],
                  'url' => [
                    'raw' => 'https://api.tiendanube.com/v1/'.$var_fila["store_id"].'/store',
                    'protocol' => 'https',
                    'host' => [
                      0 => 'api',
                      1 => 'tiendanube',
                      2 => 'com',
                    ],
                    'path' => [
                      0 => 'v1',
                      1 => $var_fila["store_id"],
                      2 => 'store',
                    ],
                  ],
                ],
                'response' => [
                ],
              ],
              1 => [
                'name' => '[ORDERS] Última orden',
                'protocolProfileBehavior' => [
                  'disabledSystemHeaders' => [
                    'user-agent' => true,
                  ],
                ],
                'request' => [
                  'auth' => [
                    'type' => 'noauth',
                  ],
                  'method' => 'GET',
                  'header' => [
                    0 => [
                      'key' => 'User-Agent',
                      'value' => 'Cartelitos (Cartelitos.app@gmail.com)',
                      'type' => 'text',
                    ],
                    1 => [
                      'key' => 'Content-Type',
                      'value' => 'application/json',
                      'type' => 'text',
                    ],
                    2 => [
                      'key' => 'Authentication',
                      'value' => 'bearer '. $var_fila["access_token"],
                      'type' => 'text',
                    ],
                  ],
                  'url' => [
                    'raw' => 'https://api.tiendanube.com/v1/'.$var_fila["store_id"].'/orders?page=1&per_page=1',
                    'protocol' => 'https',
                    'host' => [
                      0 => 'api',
                      1 => 'tiendanube',
                      2 => 'com',
                    ],
                    'path' => [
                      0 => 'v1',
                      1 => $var_fila["store_id"],
                      2 => 'orders',
                    ],
                    'query' => [
                      0 => [
                        'key' => 'page',
                        'value' => '1',
                      ],
                      1 => [
                        'key' => 'per_page',
                        'value' => '1',
                      ],
                    ],
                  ],
                ],
                'response' => [
                ],
              ],
              2 => [
                'name' => '[SCRIPTS] Lista de scripts',
                'protocolProfileBehavior' => [
                  'disabledSystemHeaders' => [
                    'user-agent' => true,
                  ],
                ],
                'request' => [
                  'auth' => [
                    'type' => 'noauth',
                  ],
                  'method' => 'GET',
                  'header' => [
                    0 => [
                      'key' => 'User-Agent',
                      'value' => 'Cartelitos (Cartelitos.app@gmail.com)',
                      'type' => 'text',
                    ],
                    1 => [
                      'key' => 'Content-Type',
                      'value' => 'application/json',
                      'type' => 'text',
                    ],
                    2 => [
                      'key' => 'Authentication',
                      'value' => 'bearer '. $var_fila["access_token"],
                      'type' => 'text',
                    ],
                  ],
                  'url' => [
                    'raw' => 'https://api.tiendanube.com/v1/'.$var_fila["store_id"].'/scripts',
                    'protocol' => 'https',
                    'host' => [
                      0 => 'api',
                      1 => 'tiendanube',
                      2 => 'com',
                    ],
                    'path' => [
                      0 => 'v1',
                      1 => $var_fila["store_id"],
                      2 => 'scripts',
                    ],
                  ],
                ],
                'response' => [
                ],
              ],
              3 => [
                'name' => '[WEBHOOKS] Lista de webhooks',
                'protocolProfileBehavior' => [
                  'disabledSystemHeaders' => [
                    'user-agent' => true,
                  ],
                ],
                'request' => [
                  'auth' => [
                    'type' => 'noauth',
                  ],
                  'method' => 'GET',
                  'header' => [
                    0 => [
                      'key' => 'User-Agent',
                      'type' => 'text',
                      'value' => 'Cartelitos (Cartelitos.app@gmail.com)',
                    ],
                    1 => [
                      'key' => 'Content-Type',
                      'type' => 'text',
                      'value' => 'application/json',
                    ],
                    2 => [
                      'key' => 'Authentication',
                      'type' => 'text',
                      'value' => 'bearer '. $var_fila["access_token"],
                    ],
                  ],
                  'url' => [
                    'raw' => 'https://api.tiendanube.com/v1/'.$var_fila["store_id"].'/webhooks/',
                    'protocol' => 'https',
                    'host' => [
                      0 => 'api',
                      1 => 'tiendanube',
                      2 => 'com',
                    ],
                    'path' => [
                      0 => 'v1',
                      1 => $var_fila["store_id"],
                      2 => 'webhooks',
                      3 => '',
                    ],
                  ],
                ],
                'response' => [
                ],
              ],
              4 => [
                'name' => '[SCRIPTS] Crear un script',
                'event' => [
                  0 => [
                    'listen' => 'prerequest',
                    'script' => [
                      'exec' => [
                        0 => '',
                      ],
                      'type' => 'text/javascript',
                    ],
                  ],
                  1 => [
                    'listen' => 'test',
                    'script' => [
                      'exec' => [
                        0 => '',
                      ],
                      'type' => 'text/javascript',
                    ],
                  ],
                ],
                'protocolProfileBehavior' => [
                  'disabledSystemHeaders' => [
                    'user-agent' => true,
                  ],
                ],
                'request' => [
                  'auth' => [
                    'type' => 'noauth',
                  ],
                  'method' => 'POST',
                  'header' => [
                    0 => [
                      'key' => 'User-Agent',
                      'type' => 'text',
                      'value' => 'Cartelitos (Cartelitos.app@gmail.com)',
                    ],
                    1 => [
                      'key' => 'Content-Type',
                      'type' => 'text',
                      'value' => 'application/json',
                    ],
                    2 => [
                      'key' => 'Authentication',
                      'type' => 'text',
                      'value' => 'bearer '. $var_fila["access_token"],
                    ],
                  ],
                  'body' => [
                    'mode' => 'raw',
                    'raw' => '{
            "src" : "https://cartelitos.app/pixel/REEMPLAZARPORLAIDDECAMPAÑA",
            "event" : "onload",
            "where" : "store,checkout"
          }',
                  ],
                  'url' => [
                    'raw' => 'https://api.tiendanube.com/v1/'.$var_fila["store_id"].'/scripts',
                    'protocol' => 'https',
                    'host' => [
                      0 => 'api',
                      1 => 'tiendanube',
                      2 => 'com',
                    ],
                    'path' => [
                      0 => 'v1',
                      1 => $var_fila["store_id"],
                      2 => 'scripts',
                    ],
                  ],
                ],
                'response' => [
                ],
              ],
              5 => [
                'name' => '[WEBHOOKS] Crear nuevo webhook',
                'protocolProfileBehavior' => [
                  'disabledSystemHeaders' => [
                    'user-agent' => true,
                  ],
                ],
                'request' => [
                  'auth' => [
                    'type' => 'noauth',
                  ],
                  'method' => 'POST',
                  'header' => [
                    0 => [
                      'key' => 'User-Agent',
                      'type' => 'text',
                      'value' => 'Cartelitos (Cartelitos.app@gmail.com)',
                    ],
                    1 => [
                      'key' => 'Content-Type',
                      'type' => 'text',
                      'value' => 'application/json',
                    ],
                    2 => [
                      'key' => 'Authentication',
                      'type' => 'text',
                      'value' => 'bearer '. $var_fila["access_token"],
                    ],
                  ],
                  'body' => [
                    'mode' => 'raw',
                    'raw' => '{
            "url" : "https://cartelitos.app/pixel-webhook/ÑÑPRUEBA",
            "event": "order/created"
          }',
                    'options' => [
                      'raw' => [
                        'language' => 'json',
                      ],
                    ],
                  ],
                  'url' => [
                    'raw' => 'https://api.tiendanube.com/v1/'.$var_fila["store_id"].'/webhooks',
                    'protocol' => 'https',
                    'host' => [
                      0 => 'api',
                      1 => 'tiendanube',
                      2 => 'com',
                    ],
                    'path' => [
                      0 => 'v1',
                      1 => $var_fila["store_id"],
                      2 => 'webhooks',
                    ],
                    'query' => [
                      0 => [
                        'key' => 'event',
                        'value' => 'order/created',
                        'disabled' => true,
                      ],
                      1 => [
                        'key' => 'url',
                        'value' => 'sdas',
                        'disabled' => true,
                      ],
                    ],
                  ],
                ],
                'response' => [
                ],
              ],
              6 => [
                'name' => '[WEBHOOKS] Borrar un Script',
                'protocolProfileBehavior' => [
                  'disabledSystemHeaders' => [
                    'user-agent' => true,
                  ],
                ],
                'request' => [
                  'auth' => [
                    'type' => 'noauth',
                  ],
                  'method' => 'DELETE',
                  'header' => [
                    0 => [
                      'key' => 'User-Agent',
                      'type' => 'text',
                      'value' => 'Cartelitos (Cartelitos.app@gmail.com)',
                    ],
                    1 => [
                      'key' => 'Content-Type',
                      'type' => 'text',
                      'value' => 'application/json',
                    ],
                    2 => [
                      'key' => 'Authentication',
                      'type' => 'text',
                      'value' => 'bearer '. $var_fila["access_token"],
                    ],
                  ],
                  'body' => [
                    'mode' => 'raw',
                    'raw' => '{
            "url" : "https://google.com",
            "event": "order/created"
          }',
                    'options' => [
                      'raw' => [
                        'language' => 'json',
                      ],
                    ],
                  ],
                  'url' => [
                    'raw' => 'https://api.tiendanube.com/v1/'.$var_fila["store_id"].'/script/NUMERODESCRIPT',
                    'protocol' => 'https',
                    'host' => [
                      0 => 'api',
                      1 => 'tiendanube',
                      2 => 'com',
                    ],
                    'path' => [
                      0 => 'v1',
                      1 => $var_fila["store_id"],
                      2 => 'script',
                      3 => '11705672',
                    ],
                    'query' => [
                      0 => [
                        'key' => 'event',
                        'value' => 'order/created',
                        'disabled' => true,
                      ],
                      1 => [
                        'key' => 'url',
                        'value' => 'sdas',
                        'disabled' => true,
                      ],
                    ],
                  ],
                ],
                'response' => [
                ],
              ],
              7 => [
                'name' => '[WEBHOOKS] Borrar un webhook',
                'protocolProfileBehavior' => [
                  'disabledSystemHeaders' => [
                    'user-agent' => true,
                  ],
                ],
                'request' => [
                  'auth' => [
                    'type' => 'noauth',
                  ],
                  'method' => 'DELETE',
                  'header' => [
                    0 => [
                      'key' => 'User-Agent',
                      'type' => 'text',
                      'value' => 'Cartelitos (Cartelitos.app@gmail.com)',
                    ],
                    1 => [
                      'key' => 'Content-Type',
                      'type' => 'text',
                      'value' => 'application/json',
                    ],
                    2 => [
                      'key' => 'Authentication',
                      'type' => 'text',
                      'value' => 'bearer '. $var_fila["access_token"],
                    ],
                  ],
                  'body' => [
                    'mode' => 'raw',
                    'raw' => '{
            "url" : "https://google.com",
            "event": "order/created"
          }',
                    'options' => [
                      'raw' => [
                        'language' => 'json',
                      ],
                    ],
                  ],
                  'url' => [
                    'raw' => 'https://api.tiendanube.com/v1/'.$var_fila["store_id"].'/webhooks/NUMERODEWEBHOOK',
                    'protocol' => 'https',
                    'host' => [
                      0 => 'api',
                      1 => 'tiendanube',
                      2 => 'com',
                    ],
                    'path' => [
                      0 => 'v1',
                      1 => $var_fila["store_id"],
                      2 => 'webhooks',
                      3 => '1170567',
                    ],
                    'query' => [
                      0 => [
                        'key' => 'event',
                        'value' => 'order/created',
                        'disabled' => true,
                      ],
                      1 => [
                        'key' => 'url',
                        'value' => 'sdas',
                        'disabled' => true,
                      ],
                    ],
                  ],
                ],
                'response' => [
                ],
              ],
              8 => [
                'name' => 'Enviar orden a endpoint',
                'protocolProfileBehavior' => [
                  'disabledSystemHeaders' => [
                    'user-agent' => true,
                  ],
                ],
                'request' => [
                  'auth' => [
                    'type' => 'noauth',
                  ],
                  'method' => 'POST',
                  'header' => [
                    0 => [
                      'key' => 'User-Agent',
                      'value' => 'Cartelitos (Cartelitos.app@gmail.com)',
                      'type' => 'text',
                    ],
                    1 => [
                      'key' => 'Content-Type',
                      'value' => 'application/json',
                      'type' => 'text',
                    ],
                    2 => [
                      'key' => 'Authentication',
                      'value' => 'bearer '. $var_fila["access_token"],
                      'type' => 'text',
                      'disabled' => true,
                    ],
                  ],
                  'body' => [
                    'mode' => 'raw',
                    'raw' => '{
            "store_id": $var_fila["store_id"],
            "event": "order/created",
            "id":460600668
          }',
                    'options' => [
                      'raw' => [
                        'language' => 'json',
                      ],
                    ],
                  ],
                  'url' => [
                    'raw' => 'https://cartelitos.app/integrations/tiendanube/webhook_handler.php?webhook=https://cartelitos.app/pixel-webhook/REEMPLAZAR POR EL PIXEL',
                    'protocol' => 'https',
                    'host' => [
                      0 => 'app',
                      1 => 'cartelitos',
                      2 => 'io',
                    ],
                    'path' => [
                      0 => 'integrations',
                      1 => 'tiendanube',
                      2 => 'webhook_handler.php',
                    ],
                    'query' => [
                      0 => [
                        'key' => 'webhook',
                        'value' => 'https://cartelitos.app/pixel-webhook/e57a3dfe6c00f0217641cbfa83d3268c',
                      ],
                    ],
                  ],
                ],
                'response' => [
                ],
              ],
            ],
            'variable' => [
              0 => [
                'key' => 'TOKEN',
                'value' => 'asdasd',
              ],
              1 => [
                'key' => 'STOREID',
                'value' => 'asdasd',
              ],
            ],
        ];

        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=".$var_fila["domain"].".json");

        //  // do your Db stuff here to get the content into $content
         print json_encode($jayParsedAry);

    }

?>
