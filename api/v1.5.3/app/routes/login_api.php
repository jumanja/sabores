<?php
/*******************************************
* API: login
********************************************/
/*
Si no está definida estaq constante, se está intentando acceder
accediendo por fuera de la api, retorna Acceso Denegado
*/
if(!defined("SPECIALCONSTANT")) die(ACCESSERROR);

/*--
URL: /login
MÉTODO: POST
REQUERIMIENTOS: REQ-001, REQ-002
TESTS: api/login.sh

DESCRIPCIÓN: Si usuario y clave son válidos, genera token y lo graba en la bd
						junto con la fecha de expiración del token.

ENTRADA: Usuario, password y formato de la salida (opcional).

PROCESO: Valida que exista un usuario activo con esa clave (encriptada) y si es así,
				 genera un token encriptado, y lo graba en la bd junto con una fechahora de
				 expiración del token.

SALIDA:  Si no recibe el formato en la entrada, por defecto retorna en json, ejemplo:
				 [{
				 		"grupo":"demo",
						"id":"3",
						"usuario":"secretaria",
						"apellidos":"Apellidos Secretaria",
						"nombres":"Nombres Secretaria",
						"password":"$2y$10$9Jdu2a5VL2Xq3DqPatTKkOviMMutujM./bXWbB7mRKeVTA5g8QMmK",
						"email":"secre@demo.com",
						"rol":"S",
						"tiporol":"S",
						"token":"2Xq3DqPatTKkOviMMutujM./bXWbB7mRKeVTA",
						"tokenexpira":"2018-10-04 08:22:16"
					}]

 				 Si recibe el parámetro formato se asume que se está ejecutando pruebas
				 automáticas,  texto el id y el token y el rol, por ejemplo:
				 id=9&token=xxxxxxxx&rol=S&tiporol=L

				 Si no se pudo hacer el proceso pues no se encontró usuario y clave, json de error:
				 [{"acceso":"Denegado.","motivo":"Usuario y Clave No Encontrados."}]

				 Si hubo error de programación no resuelto en el servidor:
				 <br />
				 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: 	 users_act, users_tokenupdate
--*/
$app->post('/login', function () use($app) {

	try{
      $forXSL = '../../xsl/count.xsl';
      $sqlCode = 'users_act';

      $filter = "";
      $usuario  = $app->request()->params('usuario');
      $password = $app->request()->params('password');

      //echo $usuario . " / " . $password;

      $filter .= ($usuario ==''     ? '' : " AND usuario = '" . $usuario . "' " );
      //Si dentro de los resultados está
      $query = parseQueryToPDO($app, $sqlCode, $forXSL, $filter);

      //echo "hist:" .  $query;

      $dbh = getPDO($query);
      $resultText = findInPDO($dbh, "password", $password);

			//Ahora genere token, tokenexpira y actualícelo en la db
			//echo "4. " . $resultText;
			if(contains("myTokenExpira", $query) == ''){

				$json = json_decode($resultText, true);
				$myToken = date('Y-m-d H:i:s', strtotime("now"));
				$myTokenExpira = date('Y-m-d H:i:s',strtotime('+1 hour +1 minutes',strtotime($myToken)));
				$myToken = password_hash($usuario . ':' . $myToken, PASSWORD_DEFAULT);

				$prepParams = array(
							':token'   		 => $myToken,
							':tokenexpira' => $myTokenExpira,
							':id'          => $json[0]['id']
				);

				$resultText = str_replace("myTokenExpira", $myTokenExpira, $resultText);
				$resultText = str_replace("myToken", $myToken, $resultText);

				$sqlCode = 'users_tokenupdate';
				$query = getSQL($sqlCode, $app);
				$rows = getPDOPrepared($query, $prepParams);
				if($app->request()->params('format')) {
					normalheader($app, $app->request()->params('format'), '');
					$resultText = "id=" . $json[0]['id'] .
												"&token=" . $myToken .
												"&grupo=" . $json[0]['grupo'] .
												"&rol=" . $json[0]['rol'] .
												"&tiporol=" . $json[0]['tiporol'] ;
				} else {
      		normalheader($app, 'json', '');
				}
			} else {
				normalheader($app, 'json', '');
			}

      $connection = null;
  		$app->response->body($resultText);
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /login/check
MÉTODO: POST
REQUERIMIENTO: REQ-002
TESTS: api/login_check.sh

DESCRIPCIÓN: Invoca al méteodo checkToken en api.php para verificar si el token
						 todavía es válido (si aún no ha expirado).

ENTRADA: El objeto $app completo, dentro se espera id, token y lang (idioma, opcional)

PROCESO: Comprueba si el topken aún es válido (si aún no ha expirado).

SALIDA: Si es válido, retorna por ejemplo:
				[{
					"tokenexpira":"2018-10-04 09:23:16",
					"tokenstatus":"validtoken"}]
				Si no es valido, ejemplo:
				[{
					"acceso":"Denegado.",
					"motivo":"Token no existe o Ya ha expirado."
				}]

			 Si hubo error de programación no resuelto en el servidor:
			 <br />
			 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: ninguno
--*/
$app->post('/login/check', function () use($app) {

	try{
			$resultText = checkToken($app);
			normalheader($app, 'json', '');

      $connection = null;
  		$app->response->body($resultText);
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});
