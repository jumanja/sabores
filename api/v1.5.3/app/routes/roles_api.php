<?php
/*******************************************
* API: servs
********************************************/
/*
Si no está definida estaq constante, se está intentando acceder
accediendo por fuera de la api, retorna Acceso Denegado
*/
if(!defined("SPECIALCONSTANT")) die(ACCESSERROR);

/*--
URL: /servs/count
MÉTODO: GET
REQUERIMIENTOS: TO-DO identificar el req Tabla de roles mostrar cuenta
TESTS: api/servs_count.sh

DESCRIPCIÓN: Cuenta y retorna cuántos roles hay en la
						base de datos.

ENTRADA: Token y el Id del usuario.

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido cuenta cuántos roles hay en la bd.

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

SQLS: 	 servs_count
--*/
$app->get('/servs/count', function () use($app) {

	try{
			$authorized = checkPerm('GET:/servs/count', $app);
			if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
		      $sqlCode = 'servs_count';
		      $forXSL = '../../../xsl/count.xsl';
		      simpleReturn($app, $sqlCode, $forXSL);
				} else {
					$connection = null;
					$app->response->body($resultText);
				}
			}	else {
				$connection = null;
				$app->response->body("/servs/count " . ACCESSERROR);

			}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});


/*--
URL: /servs
MÉTODO: GET
REQUERIMIENTOS: TO-DO identificar el req Tabla de roles
TESTS: api/servs_all.sh

DESCRIPCIÓN: Retorna la información de todos los roles hay en la
						base de datos.

ENTRADA: Token y el Id del usuario.

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido retorna la información de todos los roles de la bd.

SALIDA:  Si el token y id son válidos, retorna en json, ejemplo:
					[{
					"rol":"S",
					"tiporol":"M",					//A - Administrador, I - Integrante, V - Invitado
					"id":"1",
					"nombres":"Secretaria",
					"estado":"A"},
					{"rol":"A",
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

SQLS: 	 servs_all
--*/
$app->get("/servs", function() use($app)
{
 	try{
		$authorized = checkPerm('GET:/servs', $app);
		if($authorized){
			$resultText = checkToken($app);
			if(contains("validtoken", $resultText) ){
				if($app->request()->params('selpop') == "1"){
					$sqlCode = 'servs_sel';
				} else {
					$sqlCode = 'servs_all';
				}
	      $forXSL = '../../xsl/count.xsl';
	      simpleReturn($app, $sqlCode, $forXSL);
			} else {
				$connection = null;
				$app->response->body($resultText);
			}
		}	else {
			$connection = null;
			$app->response->body("/servs " . ACCESSERROR);

		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /servs/:id
MÉTODO: GET
REQUERIMIENTOS: TO-DO identificar el req Consulta de roles
TESTS: api/servs_id.sh

DESCRIPCIÓN: Retorna la información de un roles en la
						base de datos.

ENTRADA: Token y el Id del usuario de la sesión, y el Id del rol a retornar.

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido retorna la información de todos los roles de la bd.

SALIDA:  Si el token y id son válidos, y existe es usuario con ese id, retorna en json, ejemplo:
				 [{
				 		"grupo":"demo",
						"id":"3",
						"usuario":"secretaria",
						"apellidos":"Apellidos Secretaria",
						"nombres":"Nombres Secretaria",
						"password":"$2y$10$9Jdu2a5VL2Xq3DqPatTKkOviMMutujM./bXWbB7mRKeVTA5g8QMmK",
						"email":"secre@demo.com",
						"rol":"S",
						"estado":"A"
					}]

				 Si no es válido:
					[{
						"acceso":"Denegado.",
						"motivo":"Token no existe o Ya ha expirado."
					}]

 				 Si no se encontró un usuario con ese id:
				 []

				 Si hubo error de programación no resuelto en el servidor:
				 <br />
				 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: 	 servs_all (filtrado por id del usuario a buscar)
--*/
$app->get("/servs/:id", function($id) use($app)
{
 	try{
		$authorized = checkPerm('GET:/servs/:id', $app);
		if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
					$sqlCode = 'servs_all';
		      $forXSL = '../../xsl/count.xsl';
		      if($id){
		        $filter = ' id = ' . $id;
		        simpleReturn($app, $sqlCode, $forXSL, $filter);
		      }

				} else {
					$connection = null;
					$app->response->body($resultText);
				}
		}	else {
			$connection = null;
			$app->response->body("/servs/:id " . ACCESSERROR);

		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /servs
MÉTODO: POST
REQUERIMIENTOS: TO-DO identificar el req adicion de roles
TESTS: api/servs_add.sh

DESCRIPCIÓN: Agrega un usuario en la base de datos.

ENTRADA: Token y el Id del usuario de la sesión, y los datos del rol a retornar,
				 recibidos por el método POST, los datos a recibir (ejemplo):
				 grupo=demo
				 usuario=admin
				 apellidos=Del Sistema
				 nombres=Administrador
				 password=webmaster
				 email=jumanja@gmail.com
				 rol=A
				 estado=A

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido intenta adicionar un usuario en la bd con los datos recibidos.

SALIDA:  Si el token y id son válidos, y existe es usuario con ese id, retorna la cantidad de registros, ejemplo:
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

SQLS: 	 servs_add
--*/
$app->post('/servs', function () use($app) {

	try{
		$authorized = checkPerm('POST:/servs', $app);
		if($authorized){
					$resultText = checkToken($app);
					if(contains("validtoken", $resultText) ){
/*
Hasta aquí se inhabilititaría si se quisiera agregar sin tener sesión iniciada
*/
							$sqlCode = 'servs_add';
							$forXSL = '../../xsl/count.xsl';

							$newId = null;
							$prepParams = array(
										':rol'   => $app->request()->params('roladd'),
										':tiporol'   => $app->request()->params('tiporoladd'),
										':id'         => $newId,
										':nombre'  	  => $app->request()->params('nombre'),
										':estado'     => $app->request()->params('estado')
							);

							$query = getSQL($sqlCode, $app);
							$rows = getPDOPrepared($query, $prepParams);
							$resultText = '[{"rows":"'.$rows.'"}]';

							normalheader($app, 'json', '');
							//setResult($resultText, $app);
							//echo "4. " . $resultText;
							$connection = null;
							$app->response->body($resultText);
		/*
		 Inhabilitar siguiente bloque hasta el catch para agregar roles sin
		 necesidad de terne sesión iniciada via token
		*/
						} else {
							$connection = null;
							$app->response->body($resultText);
						}
			}	else {
				$connection = null;
				$app->response->body("/servs (POST) " . ACCESSERROR);

			}


	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});


/*--
URL: /servs
MÉTODO: PUT
REQUERIMIENTOS: TO-DO identificar el req actualización de usuarios
TESTS: api/servs_update.sh

DESCRIPCIÓN: Actualiza los datos de un usuario en la base de datos.

ENTRADA: Token y el Id del usuario de la sesión, y el id del tipoacta a actualizar,
				 recibidos por el método PUT, los datos a recibir (ejemplo):

				 id=2
				 iddelete=10
				 token=updatedToken

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido intenta modificar los datos de un tipo de acta en la bd con los
				 datos recibidos.

SALIDA:  Si el token y id son válidos, y existe es usuario con ese id, retorna
				 la cantidad de registros actualizados, ejemplo:

				 Si realizó algún cambio en el registro:
				 [{"rows":"1"}]

				 Si la información estaba igual y no actualizó nada:
				 [{"rows":"0"}]

				 Si no es válido el token, retorna en json:
					[{
						"acceso":"Denegado.",
						"motivo":"Token no existe o Ya ha expirado."
					}]

				 Si hubo error de programación no resuelto en el servidor:
				 <br />
				 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: 	 autogenerado
--*/
$app->put('/servs', function () use($app) {

	try{
		$authorized = checkPerm('PUT:/servs', $app);
		if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
					$tableName = 'roles';
		      $queryUpdate = 'UPDATE ' . $tableName . ' SET ';

					$arr = $app->request()->put();
					foreach ( $arr as $key => $value) {
							//echo substr($key, 0, 5);
							if(substr($key, 0, 5) == 'edit_'){
								  $key  = substr($key, 5);
									if($key == 'id'){
										$queryUpdate = $queryUpdate . "{$key} = '" . $app->request()->params('idupdate') . "', ";
									} else {
										if($key == 'idupdate' || $key == 'token' || $key == 'tokenexpira'){
											//saltese idupdate, token y tokenexpira. El id no se puede actualizar
										} else {
											$queryUpdate = $queryUpdate ."{$key} = '{$value}', ";
										}
									}
							}
					}
					$queryUpdate = substr($queryUpdate, 0, -2);

					$queryUpdate = $queryUpdate . " WHERE id = " . $app->request()->params('idupdate') ;

					//echo "2. " . $queryUpdate;


		      $rows = getPDO($queryUpdate);
		      $resultText = '[{"rows":"'. $rows->rowCount() .'"}]';

		      normalheader($app, 'json', '');
		      //setResult($resultText, $app);
		      $connection = null;
		  		$app->response->body($resultText);

				} else {
					$connection = null;
					$app->response->body($resultText);
				}

			}	else {
				$connection = null;
				$app->response->body("/servs (PUT) " . ACCESSERROR);

			}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /servs/update
MÉTODO: POST
REQUERIMIENTOS: TO-DO identificar el req actualización de usuarios
TESTS: api/servs_update_post.sh

DESCRIPCIÓN: Actualiza los datos de un usuario en la base de datos.

ENTRADA: Token y el Id del usuario de la sesión, y el id del usuario a retirar,
				 recibidos por el método POST, los datos a recibir (ejemplo):

				 id=2
				 iddelete=10
				 token=updatedToken

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido intenta modificar los datos de un usuario en la bd con los datos recibidos.

SALIDA:  Si el token y id son válidos, y existe es usuario con ese id, retorna la cantidad de
				 registros actualizados, ejemplo:

				 Si realizó algún cambio en el registro:
				 [{"rows":"1"}]

				 Si la información estaba igual y no actualizó nada:
				 [{"rows":"0"}]

				 Si no es válido el token, retorna en json:
					[{
						"acceso":"Denegado.",
						"motivo":"Token no existe o Ya ha expirado."
					}]

				 Si hubo error de programación no resuelto en el servidor:
				 <br />
				 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: 	 autogenerado
--*/
$app->post('/servs/update', function () use($app) {

	try{
		$authorized = checkPerm('POST:/servs/update', $app);
		if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
					$tableName = 'roles';
		      $queryUpdate = 'UPDATE ' . $tableName . ' SET ';

					$arr = $app->request()->post();
					foreach ( $arr as $key => $value) {
							//echo substr($key, 0, 5);
							if(substr($key, 0, 5) == 'edit_'){
								  $key  = substr($key, 5);
									if($key == 'id'){
										$queryUpdate = $queryUpdate . "{$key} = '" . $app->request()->params('idupdate') . "', ";
									} else {
										if($key == 'confpwd_password' || $key == 'idupdate' || $key == 'token' || $key == 'tokenexpira'){
											//saltese idupdate, token y tokenexpira. El id no se puede actualizar
										} else {
											$queryUpdate = $queryUpdate ."{$key} = '{$value}', ";
										}
									}
							}
					}
					$queryUpdate = substr($queryUpdate, 0, -2);

					$queryUpdate = $queryUpdate . " WHERE id = " . $app->request()->params('edit_idupdate') ;

					//echo "2. " . $queryUpdate;


		      $rows = getPDO($queryUpdate);
		      $resultText = '[{"rows":"'. $rows->rowCount() .'"}]';

		      normalheader($app, 'json', '');
		      //setResult($resultText, $app);
		      $connection = null;
		  		$app->response->body($resultText);

				} else {
					$connection = null;
					$app->response->body($resultText);
				}

			}	else {
				$connection = null;
				$app->response->body("/servs/update (POST) " . ACCESSERROR);

			}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /[tabla]/add
MÉTODO: POST
REQUERIMIENTOS: TO-DO identificar el req adicionar en esta tabla
TESTS: api/[tabla]_add_post.sh

DESCRIPCIÓN: Adiciona los datos de un registro de esta tabka en la base de datos.

ENTRADA: Token y el Id del usuario de la sesión, y los datos a agregar,
				 recibidos por el método POST, los datos a recibir (ejemplo):

				 id=2
				 iddelete=10
				 token=updatedToken

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido intenta agregar los datos del registro en la bd con los datos recibidos.

SALIDA:  Si el token y id son válidos, y existe es usuario con ese id, retorna la cantidad de
				 registros agregados, ejemplo:

				 Si realizó algún cambio en el registro:
				 [{"rows":"1"}]

				 Si no agregó nada:
				 [{"rows":"0"}]

				 Si no es válido el token, retorna en json:
					[{
						"acceso":"Denegado.",
						"motivo":"Token no existe o Ya ha expirado."
					}]

				 Si hubo error de programación no resuelto en el servidor:
				 <br />
				 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: 	 autogenerado
--*/
$app->post('/servs/add', function () use($app) {

	try{
		$authorized = checkPerm('POST:/servs/add', $app);
		if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
					$tableName = 'roles';
		      $queryAdd = 'INSERT INTO ' . $tableName . ' (';
					$queryFields = "";
					$queryValues = " VALUES (";

					$arr = $app->request()->post();
					foreach ( $arr as $key => $value) {
							//echo substr($key, 0, 5);
							if(substr($key, 0, 4) == 'add_'){
								  $key  = substr($key, 4);
									$queryFields = $queryFields ."{$key}, ";
									$queryValues = $queryValues ."'{$value}', ";
							}
					}
					$queryFields = substr($queryFields, 0, -2);
					$queryValues = substr($queryValues, 0, -2);

					$queryAdd = $queryAdd .
											$queryFields . ") " .
											$queryValues . ");";

					//echo "1. " . $queryAdd;

		      $rows = getPDO($queryAdd);
		      $resultText = '[{"rows":"'. $rows->rowCount() .'"}]';

		      normalheader($app, 'json', '');
		      //setResult($resultText, $app);
		      $connection = null;
		  		$app->response->body($resultText);

				} else {
					$connection = null;
					$app->response->body($resultText);
				}

			}	else {
				$connection = null;
				$app->response->body("/servs/add (POST) " . ACCESSERROR);

			}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /servs/delete
MÉTODO: PUT (el método DELETE da problemas no está implementado correctamente)
REQUERIMIENTOS: TO-DO identificar el req actualización de roles
TESTS: api/servs_delete.sh

DESCRIPCIÓN: Actualiza el estado a R (Retirado) a un rol en la base de datos,
						 borrado lógico.

ENTRADA: Token y el Id del usuario de la sesión, y los datos del rol a actualizar,
				 los datos a recibir (ejemplo):

				 id=2
				 email=nuevoemail@email.com
				 token=updatedToken
				 tokenexpira=2018-12-31 11:59:59

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido intenta retirar un usuario (cambiando su estado a R) en la bd con los datos recibidos.

SALIDA:  Si el token y id son válidos, y existe es usuario con ese id, retorna la cantidad de
				 registros actualizados, ejemplo:

				 Si realizó algún cambio en el registro:
				 [{"rows":"1"}]

				 Si la información estaba igual y no actualizó nada:
				 [{"rows":"0"}]

				 Si no es válido el token, retorna en json:
					[{
						"acceso":"Denegado.",
						"motivo":"Token no existe o Ya ha expirado."
					}]

				 Si hubo error de programación no resuelto en el servidor:
				 <br />
				 <b>Parse error</b>:  parse error .. y el mensaje de error.

SQLS: 	 autogenerado
--*/
$app->put('/servs/delete', function () use($app) {

	try{
		$authorized = checkPerm('PUT:/servs/delete', $app);
		if($authorized){
					$resultText = checkToken($app);
					if(contains("validtoken", $resultText) ){
						$tableName = 'roles';
			      $queryUpdate = "UPDATE " . $tableName . " SET ESTADO = 'R' ".
													 " WHERE id = " . $app->request()->params('iddelete') ;

						//echo "21. " . $queryUpdate;


			      $rows = getPDO($queryUpdate);
			      $resultText = '[{"rows":"'. $rows->rowCount() .'"}]';

			      normalheader($app, 'json', '');
			      //setResult($resultText, $app);
			      $connection = null;
			  		$app->response->body($resultText);

					} else {
						$connection = null;
						$app->response->body($resultText);
					}
			}	else {
				$connection = null;
				$app->response->body("/servs/delete (PUT) " . ACCESSERROR);

			}

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});
