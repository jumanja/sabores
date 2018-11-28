<?php
/*******************************************
* API: mails
********************************************/
/*
Si no está definida esta constante, se está intentando acceder
accediendo por fuera de la api, retorna Acceso Denegado
*/
if(!defined("SPECIALCONSTANT")) die(ACCESSERROR);

/*--
URL: /[tabla]
MÉTODO: POST
REQUERIMIENTOS: TO-DO identificar el req envío de un email
TESTS: api/[tabla]_add.sh

DESCRIPCIÓN: Enviar un mail.

ENTRADA: Token y el Id del usuario de la sesión, y los datos del registro a retornar,
				 recibidos por el método POST, los datos a recibir (ejemplo):
				 grupo=demo
				 usuario=admin
				 apellidos=Del Sistema
				 nombres=Administrador
				 password=webmaster
				 email=jumanja@gmail.com
				 servicio=A
				 estado=A

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido intenta adicionar un registro en esta tabla en la bd con los
				 datos recibidos.

SALIDA:  Si el token y id son válidos, y existe un registro con ese id, retorna
				 la cantidad de registros, ejemplo:
				 [{
				 		"rows":"1"
					}]';

				 Si no es válido el token, retorna en json:
					[{
						"acceso":"Denegado.",
						"motivo":"Token no existe o Ya ha expirado."
					}]

				 Si hubo error de programación no resuelto en el servidor:
				 <br />
				 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: 	 [tabla]_add
--*/
$app->post('/mails', function () use($app) {

	try{
		$authorized = checkPerm('POST:/mails', $app);
		if($authorized){
					$resultText = checkToken($app);
					if(contains("validtoken", $resultText) ){
/*
Hasta aquí se inhabilititaría si se quisiera agregar sin tener sesión iniciada
*/
							$sqlCode = 'mails_add';
							$forXSL = '../../xsl/count.xsl';

/*
							<?php
							$to      = 'nobody@example.com';
							$subject = 'the subject';
							$message = 'hello';
							$headers = 'From: webmaster@example.com' . "\r\n" .
							    'Reply-To: webmaster@example.com' . "\r\n" .
							    'X-Mailer: PHP/' . phpversion();

							mail($to, $subject, $message, $headers);
							?>
*/
							$to      = $app->request()->params('mail_to');
							$subject = $app->request()->params('mail_sb');
							$message = $app->request()->params('mail_tx');
							$headers = 'From: sisga@jumanja.net' . "\r\n" .
									'Reply-To: sisga@jumanja.net' . "\r\n" .
									'X-Mailer: PHP/' . phpversion();

							if (mail($to, $subject, $message, $headers)) {
								$resultText = '[{"rows":"'.$rows.'"}]';
							} else{
								$resultText = '[]';
							};

							normalheader($app, 'json', '');
							//setResult($resultText, $app);
							//echo "4. " . $resultText;
							$connection = null;
							$app->response->body($resultText);
		/*
		 Inhabilitar siguiente bloque hasta el catch para agregar usuarios sin
		 necesidad de terne sesión iniciada via token
		*/
						} else {
							$connection = null;
							$app->response->body($resultText);
						}
			}	else {
				$connection = null;
				$app->response->body("/mails (POST) " . ACCESSERROR);

			}


	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});
