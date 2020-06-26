<?php
// Copyright (c) 2018, Altiria TIC SL
// All rights reserved.
// El uso de este c�digo de ejemplo es solamente para mostrar el uso de la pasarela de env�o de SMS de Altiria
// Para un uso personalizado del c�digo, es necesario consultar la API de especificaciones t�cnicas, donde tambi�n podr�s encontrar
// m�s ejemplos de programaci�n en otros lenguajes y otros protocolos (http, REST, web services)
// https://www.altiria.com/api-envio-sms/

// XX, YY y ZZ se corresponden con los valores de identificacion del
// usuario en el sistema.
include('httpPHPAltiria.php');

$altiriaSMS = new AltiriaSMS();
$altiriaSMS->setUrl("http://www.altiria.net/api/http");
$altiriaSMS->setDomainId('XX');
$altiriaSMS->setLogin('YY');
$altiriaSMS->setPassword('ZZ');

$altiriaSMS->setDebug(true);

//$sDestination = '346xxxxxxxx';
$sDestination = '346xxxxxxxx,346yyyyyyyy';
//$sDestination = array('346xxxxxxxx','346yyyyyyyy');

//No es posible utilizar el remitente en Am�rica pero s� en Espa�a y Europa
$response = $altiriaSMS->sendSMS($sDestination, "Mensaje de prueba");
//Utilizar esta llamada solo si se cuenta con un remitente autorizado por Altiria
//$response = $altiriaSMS->sendSMS($sDestination, "Mensaje de prueba", "remitente");

if (!$response)
  echo "El env�o ha terminado en error";
else
  echo $response;
?>
