<?php
/*--
URL: /logout
MÉTODO: PUT
REQUERIMIENTOS: REQ-001
TESTS: api/logout.sh

DESCRIPCIÓN: Actualiza el token de un usuario en la base de datos, para
						 que no haya forma de usar la sesión activa, por lo tanto es
						 como si cerrara la sesión.

ENTRADA: Token y el Id del usuario de la sesión, No hay datos a recibir

PROCESO: Intenta modificar un usuario en la bd para cambiar el token.

SALIDA:  Si el token y id son válidos, y existe es usuario con ese id, retorna la cantidad de registros, ejemplo:
				 [{
				 		"rows":"1"
					}]
				 Si no es válido el token, retorna en json:
					[{
						"acceso":"Denegado.",
						"motivo":"Token no existe o Ya ha expirado."
					}]

				 Si hubo error de programación no resuelto en el servidor:
				 <br />
				 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: 	 users_tokenupdate
--*/
$app->put('/logout', function () use($app) {

	try{
		$authorized = checkPerm('PUT:/logout', $app);
		if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
					$sqlCode = 'users_tokenupdate';
		      $forXSL = '../../xsl/count.xsl';

					$myToken = date('Y-m-d H:i:s', strtotime("now"));
					$myTokenExpira = date('Y-m-d H:i:s',strtotime('-1 minutes',strtotime($myToken)));
					$myToken = password_hash($usuario . ':' . $myToken, PASSWORD_DEFAULT);

		      $prepParams = array(
		            ':token'   		 => $myToken,
		            ':tokenexpira' => $myTokenExpira,
								':id'          => $app->request()->params('id')
		      );

		      $query = getSQL($sqlCode, $app);
		      $rows = getPDOPrepared($query, $prepParams);
		      $resultText = '[{"rows":"'.$rows.'",' .
												'"token":"",' .
												'"tokenexpira":"'.$myTokenExpira.'"' .
												'}]';

		      normalheader($app, 'json', '');
		      //setResult($resultText, $app);
		      //echo "4. " . $resultText;
		      $connection = null;
		  		$app->response->body($resultText);

				} else {
					$connection = null;
					$app->response->body($resultText);
				}
			}	else {
				$connection = null;
				$app->response->body("/logout (PUT) " . ACCESSERROR);

			}

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});
