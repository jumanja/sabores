<?php
/*******************************************
* API: mins
********************************************/
/*
Si no está definida esta constante, se está intentando acceder
accediendo por fuera de la api, retorna Acceso Denegado
*/
if(!defined("SPECIALCONSTANT")) die(ACCESSERROR);

/*--
URL: /[tabla]/count
MÉTODO: GET
REQUERIMIENTOS: TO-DO identificar el req Tabla de Actas mostrar cuenta
TESTS: api/mins_count.sh

DESCRIPCIÓN: Cuenta y retorna cuántas actas hay en la
						base de datos.

ENTRADA: Token y el Id del usuario.

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido cuenta cuántos usuarios hay en la bd.

SALIDA:  Si el token y id son válidos, retorna en json, ejemplo:
				 [{"count":"8"}]

				 Si no es válido:
					[{
						"acceso":"Denegado.",
						"motivo":"Token no existe o Ya ha expirado."
					}]

				 Si hubo error de programación no resuelto en el servidor:
				 <br />
				 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: 	 mins_count
--*/
$app->get('/mins/count', function () use($app) {

	try{
			$authorized = checkPerm('GET:/mins/count', $app);
			if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
					$sqlCode = ($app->request()->params('sqlCode') == "" ? 'mins_count' : $app->request()->params('sqlCode') );

		      $forXSL = '../../../xsl/count.xsl';
		      simpleReturn($app, $sqlCode, $forXSL);
				} else {
					$connection = null;
					$app->response->body($resultText);
				}
			}	else {
				$connection = null;
				$app->response->body("/mins/count " . ACCESSERROR);

			}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /[tabla]
MÉTODO: POST
REQUERIMIENTOS: TO-DO identificar el req guardar el acta
TESTS: api/[tabla]_add.sh

DESCRIPCIÓN: Guardar el acta.

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
$app->post('/mins', function () use($app) {

	try{
		$authorized = checkPerm('POST:/mins', $app);
		if($authorized){
					$resultText = checkToken($app);
					if(contains("validtoken", $resultText) ){

						$sqlCode = ($app->request()->params('mod_acta') == "add" ? 'mins_add' : 'mins_update' );
						$forXSL = '../../xsl/count.xsl';

						$newId = null;

						if( $app->request()->params('mod_acta') == "add" ){
							$prepParams = array(
										':grupo'      	 	=> $app->request()->params('grupo'),
										':id'         	=> $newId,
										':estado'     	=> $app->request()->params('add_estado'),
										':fecha'    		=> $app->request()->params('add_fecacta'),
										':tipoacta'  		=> $app->request()->params('add_tipo_de_acta'),
										':tema'    			=> $app->request()->params('add_temaacta'),
										':lugar'   			=> $app->request()->params('add_lugar_reunion'),
										':objetivos'  	=> $app->request()->params('add_objetivos'),
										':responsable'  => $app->request()->params('usuario'),
										':conclusiones' => $app->request()->params('add_conclusiones'),
										':fechasig' 		=> $app->request()->params('add_fecproxima'),
										':lugarsig'   	=> $app->request()->params('add_lugar_proxima'),
							);

						} else {
							$prepParams = array(
										':id'         	=> $app->request()->params('edit_idupdate'),
										':estado'     	=> $app->request()->params('edit_estado'),
										':fecha'    		=> $app->request()->params('edit_fecacta'),
										':tipoacta'  		=> $app->request()->params('edit_tipo_de_acta'),
										':tema'    			=> $app->request()->params('edit_temaacta'),
										':lugar'   			=> $app->request()->params('edit_lugar_reunion'),
										':objetivos'  	=> $app->request()->params('edit_objetivos'),
										':conclusiones' => $app->request()->params('edit_conclusiones'),
										':fechasig' 		=> $app->request()->params('edit_fecproxima'),
										':lugarsig' 		=> $app->request()->params('edit_lugar_proxima')
							);

						}

						$query = getSQL($sqlCode, $app);

						//echo "7,5 query: " . $query;

						$idacta = $app->request()->params('edit_idupdate');
						if( $app->request()->params('mod_acta') == "add" ){
							$rows = getPDOPreparedIns($query, $prepParams);
							$resultText = '[{"newId":"'.$rows.'"}]';

							//Acta creada, tome el id
							$idacta = $rows;
						} else {
							$rows = getPDOPrepared($query, $prepParams);
							$resultText = '[{"rows":"'.$rows.'"}]';

							//Actualice EtiquetasActa
							/*$sqlCode = "tags_minretire";
							$prepParams = array(
										':idacta'       => $idacta,
							);
							$query = getSQL($sqlCode, $app);
							$rows = getPDOPrepared($query, $prepParams);*/

							$sqlCode = "tags_mindelete";
							$prepParams = array(
										':idacta'       => $idacta,
							);
							$query = getSQL($sqlCode, $app);
							$rows = getPDOPrepared($query, $prepParams);
							//echo "tags_mindelete: " + $rows;
						}

						//Actualice Asistentes
						//Actualice Comentarios? si, solo los no-secretarios


						$tags = $app->request()->params('upd_etiquetasActa');
						if($tags != ""){

							$sqlCode = "tags_minadd";
							$query = getSQL($sqlCode, $app);

							$connection = getConnection();

							$dbh = $connection->prepare($query);
							$arrayTags = explode(',', $tags);
							//print_r($arrayTags);
							foreach($arrayTags as $tag){
								//print_r($tag);

								$prepParams = array(
											':idacta'     => $idacta,
											':etiqueta'   => $tag,
											':estado'  	  => 'A',
								);

								$dbh->execute($prepParams);

							}
						}

						/*
						if( $app->request()->params('mod_acta') == "add" ){
							//do nothing
						} else {
							$sqlCode = "tags_mindelete";
							$prepParams = array(
										':idacta'       => $idacta,
							);
							$query = getSQL($sqlCode, $app);
							$rows = getPDOPrepared($query, $prepParams);
						}
						*/

						//Actualice Notificaciones
						//Actualice Tareas



						normalheader($app, 'json', '');
						//setResult($resultText, $app);
						//echo "8.1. " . $resultText;
						$connection = null;
						$app->response->body($resultText);

						} else {
							$connection = null;
							$app->response->body($resultText);
						}
			}	else {
				$connection = null;
				$app->response->body("/mins (POST) " . ACCESSERROR);

			}


	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});
/*--
URL: /[tabla]
MÉTODO: GET
REQUERIMIENTOS: TO-DO identificar el req Tabla de Actas
TESTS: api/[tabla]_all.sh

DESCRIPCIÓN: Retorna la información de todos las actas que hay en la
						base de datos.

ENTRADA: Token y el Id del usuario.

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido retorna la información de todos las actas de la bd.

SALIDA:  Si el token y id son válidos, retorna en json, ejemplo:
					[{
					"grupo":"demo",
					"id":"1",
					"usuario":"admin",
					"apellidos":"Del Sistema",
					"nombres":"Administrador",
					"password":"$2y$10$a/j70S8aDh3cwNi2J4UmeeE7OcesoUTp0KXoh87B1MbX4DoGO0SZa",
					"email":"jumanja@gmail.com",
					"servicio":"A",
					"estado":"A"},
					{"grupo":"demo",
					"id":"2",
					...
					"estado":"A"
					}]

				 Si no es válido:
					[{
						"acceso":"Denegado.",
						"motivo":"Token no existe o Ya ha expirado."
					}]

				 Si hubo error de programación no resuelto en el servidor:
				 <br />
				 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: 	 users_all
--*/
$app->get("/mins", function() use($app)
{
 	try{
			$authorized = checkPerm('GET:/mins', $app);
			if($authorized){
					$resultText = checkToken($app);
					if(contains("validtoken", $resultText) ){
						$sqlCode = ($app->request()->params('sqlCode') == "" ? 'mins_all' : $app->request()->params('sqlCode') );
						$forXSL = '../../xsl/count.xsl';
						simpleReturn($app, $sqlCode, $forXSL);

					} else {
						$connection = null;
						$app->response->body($resultText);
					}
				}	else {
					$connection = null;
					$app->response->body("/mins " . ACCESSERROR);
		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /[tabla]/items
MÉTODO: GET
REQUERIMIENTOS: TO-DO identificar el req Tabla de Actas
TESTS: api/[tabla]_items.sh

DESCRIPCIÓN: Retorna la información de todos los items del acta en la bd.

ENTRADA: Token y el Id del usuario.

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido retorna la información de todos las etiquetas del acta de la bd.

SALIDA:  Si el token y id son válidos, retorna en json, ejemplo:
					[{
					"grupo":"demo",
					"id":"1",
					"usuario":"admin",
					"apellidos":"Del Sistema",
					"nombres":"Administrador",
					"password":"$2y$10$a/j70S8aDh3cwNi2J4UmeeE7OcesoUTp0KXoh87B1MbX4DoGO0SZa",
					"email":"jumanja@gmail.com",
					"servicio":"A",
					"estado":"A"},
					{"grupo":"demo",
					"id":"2",
					...
					"estado":"A"
					}]

				 Si no es válido:
					[{
						"acceso":"Denegado.",
						"motivo":"Token no existe o Ya ha expirado."
					}]

				 Si hubo error de programación no resuelto en el servidor:
				 <br />
				 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: 	 items_all
--*/
$app->get("/mins/items", function() use($app)
{
 	try{
			$authorized = checkPerm('GET:/mins/items', $app);
			if($authorized){
					$resultText = checkToken($app);
					if(contains("validtoken", $resultText) ){
						$sqlCode = ($app->request()->params('sqlCode') == "" ? 'mins_all' : $app->request()->params('sqlCode') );
						$forXSL = '../../xsl/count.xsl';

						//Recupere EtiquetasActa
						$prepParams = array(
									':idacta'       => $idacta = $app->request()->params('nroActa'),
						);
						$query = getSQL($sqlCode, $app);

						$connection = getConnection();
						$dbh = $connection->prepare($query);
						$dbh->execute($prepParams);

						$resultText = "";
						normalheader($app, 'json', '');
		        $resultText .= PDO2json($dbh, '');
		        $connection = null;

		        $app->response->body($resultText);

					} else {
						$connection = null;
						$app->response->body($resultText);
					}
				}	else {
					$connection = null;
					$app->response->body("/mins/items " . ACCESSERROR);
		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});
