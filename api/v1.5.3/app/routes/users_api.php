<?php
/*******************************************
* API: users
********************************************/
/*
Si no está definida estaq constante, se está intentando acceder
accediendo por fuera de la api, retorna Acceso Denegado
*/
if(!defined("SPECIALCONSTANT")) die(ACCESSERROR);

/*--
URL: /users/count
MÉTODO: GET
REQUERIMIENTOS: TO-DO identificar el req Tabla de Usuarios mostrar cuenta
TESTS: api/users_count.sh

DESCRIPCIÓN: Cuenta y retorna cuántos usuarios hay en la
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

SQLS: 	 users_count
--*/
$app->get('/users/count', function () use($app) {

	try{
			$authorized = checkPerm('GET:/users/count', $app);
			if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
		      $sqlCode = 'users_count';
		      $forXSL = '../../../xsl/count.xsl';
		      simpleReturn($app, $sqlCode, $forXSL);
				} else {
					$connection = null;
					$app->response->body($resultText);
				}
			}	else {
				$connection = null;
				$app->response->body("/users/count " . ACCESSERROR);

			}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /users
MÉTODO: GET
REQUERIMIENTOS: TO-DO identificar el req Tabla de Usuarios
TESTS: api/users_all.sh

DESCRIPCIÓN: Retorna la información de todos los usuarios hay en la
						base de datos.

ENTRADA: Token y el Id del usuario.

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido retorna la información de todos los usuarios de la bd.

SALIDA:  Si el token y id son válidos, retorna en json, ejemplo:
					[{
					"grupo":"demo",
					"id":"1",
					"usuario":"admin",
					"apellidos":"Del Sistema",
					"nombres":"Administrador",
					"password":"$2y$10$a/j70S8aDh3cwNi2J4UmeeE7OcesoUTp0KXoh87B1MbX4DoGO0SZa",
					"email":"jumanja@gmail.com",
					"rol":"A",
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
$app->get("/users", function() use($app)
{
 	try{
			$authorized = checkPerm('GET:/users', $app);
			if($authorized){
					$resultText = checkToken($app);
					if(contains("validtoken", $resultText) ){
						$sqlCode = ($app->request()->params('sqlCode') == "" ? 'users_all' : $app->request()->params('sqlCode') );
						$forXSL = '../../xsl/count.xsl';
						simpleReturn($app, $sqlCode, $forXSL);

					} else {
						$connection = null;
						$app->response->body($resultText);
					}
				}	else {
					$connection = null;
					$app->response->body("/users " . ACCESSERROR);
		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /users/:id
MÉTODO: GET
REQUERIMIENTOS: TO-DO identificar el req Consulta de Usuarios
TESTS: api/users_id.sh

DESCRIPCIÓN: Retorna la información de un usuarios en la
						base de datos.

ENTRADA: Token y el Id del usuario de la sesión, y el Id del usuario a retornar.

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido retorna la información de todos los usuarios de la bd.

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

SQLS: 	 users_all (filtrado por id del usuario a buscar)
--*/
$app->get("/users/:id", function($id) use($app)
{
 	try{
		$authorized = checkPerm('GET:/users/:id', $app);
		if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
					$sqlCode = 'users_all';
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
			$app->response->body("/users/:id " . ACCESSERROR);

		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /users
MÉTODO: POST
REQUERIMIENTOS: TO-DO identificar el req adicion de usuarios
TESTS: api/users_add.sh

DESCRIPCIÓN: Agrega un usuario en la base de datos.

ENTRADA: Token y el Id del usuario de la sesión, y los datos del usuario a retornar,
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

SQLS: 	 users_add
--*/
$app->post('/users', function () use($app) {

	try{
		/*
		$authorized = checkPerm('POST:/users', $app);
		if($authorized){
					$resultText = checkToken($app);
					if(contains("validtoken", $resultText) ){

Hasta aquí se inhabilititaría si se quisiera agregar sin tener sesión iniciada
*/
							$sqlCode = 'users_add';
							$forXSL = '../../xsl/count.xsl';

							$newId = null;
							$prepParams = array(
										':grupo'      => $app->request()->params('grupo'),
										':id'         => $newId,
										':usuario'    => $app->request()->params('usuario'),
										':apellidos'  => $app->request()->params('apellidos'),
										':nombres'    => $app->request()->params('nombres'),
										':password'   => password_hash($app->request()->params('password'), PASSWORD_DEFAULT),
										':email'      => $app->request()->params('email'),
										':rol'   			=> $app->request()->params('rol'),
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
		 Inhabilitar siguiente bloque hasta el catch para agregar usuarios sin
		 necesidad de terne sesión iniciada via token

						} else {
							$connection = null;
							$app->response->body($resultText);
						}
			}	else {
				$connection = null;
				$app->response->body("/users (POST) " . ACCESSERROR);

			}

*/
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /users/token
MÉTODO: PUT
REQUERIMIENTOS: TO-DO identificar el req actualización de usuarios
TESTS: api/users_add.sh

DESCRIPCIÓN: Actualiza el token de un usuario en la base de datos, para
						 mantener la sesión activa.

ENTRADA: Token y el Id del usuario de la sesión, y los datos del usuario a retornar,
				 recibidos por el método POST, los datos a recibir (ejemplo):
				 token=2Xq3DqPatTKkOviMMutujM./bXWbB7mRKeVTA
				 tokenexpira=2018-10-04 08:22:16
				 id=3

PROCESO: Comprueba si el token es válido mediante el método checkToken, y si es
				 válido intenta modificar un usuario en la bd con los datos recibidos.

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
$app->put('/users/token', function () use($app) {

	try{
		$authorized = checkPerm('PUT:/users/token', $app);
		if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
					$sqlCode = 'users_tokenupdate';
		      $forXSL = '../../xsl/count.xsl';

					$myToken = date('Y-m-d H:i:s', strtotime("now"));
					$myTokenExpira = date('Y-m-d H:i:s',strtotime('+31 minutes',strtotime($myToken)));
					$myToken = password_hash($usuario . ':' . $myToken, PASSWORD_DEFAULT);

		      $prepParams = array(
		            ':token'   		 => $myToken,
		            ':tokenexpira' => $myTokenExpira,
								':id'          => $app->request()->params('id')
		      );

		      $query = getSQL($sqlCode, $app);
		      $rows = getPDOPrepared($query, $prepParams);
		      $resultText = '[{"rows":"'.$rows.'",' .
												'"token":"'.$myToken.'",' .
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
				$app->response->body("/users (PUT) " . ACCESSERROR);

			}

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});


/*--
URL: /users
MÉTODO: PUT
REQUERIMIENTOS: TO-DO identificar el req actualización de usuarios
TESTS: api/users_update.sh

DESCRIPCIÓN: Actualiza los datos de un usuario en la base de datos.

ENTRADA: Token y el Id del usuario de la sesión, y el id del usuario a retirar,
				 recibidos por el método DELETE, los datos a recibir (ejemplo):

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
$app->put('/users', function () use($app) {

	try{
		$authorized = checkPerm('PUT:/users', $app);
		if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
					$tableName = 'usuarios';
		      $queryUpdate = 'UPDATE ' . $tableName . ' SET  ';

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
				$app->response->body("/users (PUT) " . ACCESSERROR);

			}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /users/update
MÉTODO: POST
REQUERIMIENTOS: TO-DO identificar el req actualización de usuarios
TESTS: api/users_update_post.sh

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
$app->post('/users/update', function () use($app) {

	try{
		$authorized = checkPerm('POST:/users/update', $app);
		if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
					$tableName = 'usuarios';
		      $queryUpdate = 'UPDATE ' . $tableName . ' SET  ';

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
				$app->response->body("/users/update (POST) " . ACCESSERROR);

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
$app->post('/users/add', function () use($app) {

	try{
		$authorized = checkPerm('POST:/users/add', $app);
		if($authorized){
				$resultText = checkToken($app);
				if(contains("validtoken", $resultText) ){
					$tableName = 'usuarios';
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
				$app->response->body("/users/add (POST) " . ACCESSERROR);

			}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});

/*--
URL: /users/delete
MÉTODO: PUT (el método DELETE da problemas no está implementado correctamente)
REQUERIMIENTOS: TO-DO identificar el req actualización de usuarios
TESTS: api/users_delete.sh

DESCRIPCIÓN: Actualiza el estado a R (Retirado) a un usuario en la base de datos,
						 borrado lógico.

ENTRADA: Token y el Id del usuario de la sesión, y los datos del usuario a actualizar,
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
$app->put('/users/delete', function () use($app) {

	try{
		$authorized = checkPerm('PUT:/users/delete', $app);
		if($authorized){
					$resultText = checkToken($app);
					if(contains("validtoken", $resultText) ){
						$tableName = 'usuarios';
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
				$app->response->body("/users/delete (PUT) " . ACCESSERROR);

			}

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
});
