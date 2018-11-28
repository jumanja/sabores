<?php
if(!defined("SPECIALCONSTANT")) die(ACCESSERROR);

$app->get('/echo', function () use($app) {
		echo "echo !: Current PHP version: " . phpversion();
});

function parseQueryToPDO($app, $sqlCode, $style, $filter = '') {

    $format = $app->request()->params('format');
    $max = $app->request()->params('max');
    $flds = $app->request()->params('fields');
    $sort = $app->request()->params('sort');
    $lang = $app->request()->params('lang');

    $query = parseParams($sqlCode, $flds, $sort, $max, $filter, $app);

		//echo "1:" . $query;
		return $query;
}

function getPDO($query) {

    $connection = getConnection();
    $dbh = $connection->prepare($query);
		$dbh->execute();

		return $dbh;
}

function getPDOPrepared($query, $arrayParams) {
		$connection = getConnection();

		$dbh = $connection->prepare($query);

		//echo '9.1 ';
		$dbh->execute($arrayParams);

		//echo '9.2 getPDOPrepared_query:' . $query . ' ';
		//echo '9.3 ' . $arrayParams[":fecha"] ;
		//echo '9.4 ' . $arrayParams[":fechasig"] ;
		//echo '9.3 ' . $arrayParams[":token"];
		//echo '9.4 ' . $arrayParams[":tokenexpira"];
		//echo '9.5 ' . $arrayParams[":id"];

		//echo '9.6 ' . $dbh->rowCount();
		return $dbh->rowCount();
}
function getPDOPreparedIns($query, $arrayParams) {
		$connection = getConnection();

		$dbh = $connection->prepare($query);

		//echo '9.1 ';
		$dbh->execute($arrayParams);

		//echo '9.2 getPDOPrepared_query:' . $query . ' ';
		//echo '9.3 ' . $arrayParams[":fecha"] ;
		//echo '9.4 ' . $arrayParams[":fechasig"] ;
		//echo '9.3 ' . $arrayParams[":token"];
		//echo '9.4 ' . $arrayParams[":tokenexpira"];
		//echo '9.5 ' . $arrayParams[":id"];

		//echo '9.6 ' . $dbh->rowCount();
		return $connection->lastInsertId();
}

//$resultText .= PDO2json($dbh, '');
function findInPDO($dbh, $fldname, $fldvalue) {
	  //echo "2:" . $fldname . "/".  $fldvalue;

		$found = false;
		$table =  "";
		while ($fila = $dbh->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
				$rec = '';
				$rec .= '{';
        foreach($fila as $key => $value ) {

						if($key == $fldname){
							//echo "\n<br>debug findInPDO: " . $fila . "/" . $key . "/" . $fldname . "/" . $fldvalue . "/" .  $value;

							if($fldname == "password"){
								 if (password_verify($fldvalue, $value)) {
									 $found = true;
								 }
							} else {
								if($value == $fldvalue){
									$found = true;
								}
							}

						}
						$rec .= '"' . $key . '":"' . $value . '",';
        }
				$rec = substr($rec, 0, -1);
				$rec .= '},';

				$table .= $rec;

    }

		if($table == '') {
			$table = '[]';
		} else {
			$table = '[' . substr($table, 0, -1) . ']';
		}

		//echo "\n<br>debug PDO2json: " . $table;
		if(!$found){
			$table = '[{"acceso":"Denegado.","motivo":"Usuario y Clave No Encontrados."}]';
		} else {

			//Si estamos procesando intento de login
			if($fldname == "password"){
					$tokenstr = ',"token":"myToken","tokenexpira":"myTokenExpira"';
					$table = substr_replace($table, $tokenstr, strlen($table)-2, 0);
			}

		}
		return $table;

}

function setResult($resultText, $app) {

		$connection = null;
		$app->response->body($resultText);

		//echo "3:" . $resultText;

}

function simpleReturn($app, $sqlCode, $style, $filter = '') {

    $format = $app->request()->params('format');
    $max = $app->request()->params('max');
    $flds = $app->request()->params('fields');
    $sort = $app->request()->params('sort');
    $lang = $app->request()->params('lang');

    $query = parseParams($sqlCode, $flds, $sort, $max, $filter, $app);

    //echo "143. " . $query . '\n';
    $connection = getConnection();
    $dbh = $connection->prepare($query);
		$dbh->execute();

		if($format == 'xml' || $format == 'xsl') {
        $xslt = ($format == 'xsl' ? $style : '');
        $resultText = '';

        normalheader($app, 'xml', $xslt);
        $resultText .= PDO2xml($dbh, $xslt);
        $connection = null;

        $app->response->body($resultText);

		} else {

        /*$readings = $dbh->fetchAll();
        $connection = null;

        $app->response->body(json_encode($readings)); */
        normalheader($app, 'json', '');
        $resultText .= PDO2json($dbh, '');
        $connection = null;

        $app->response->body($resultText);
    }

}
function normalHeader($app, $format, $style) {
    //$typeHeader = false;
    $typeHeader = true;
    if($typeHeader) {
      if($format == '' || $format == 'json') {
        $app->response->headers->set("Content-type", "application/json; charset=utf-8");
      } else if($format == 'xml'){
        $app->response->headers->set("Content-type", "text/xml; charset=utf-8");
      }
    } else {
        $app->response->headers->set("Content-type", "application/json; charset=utf-8");
    }
		$app->response->headers->set("Access-Control-Allow-Origin","*");
		$app->response->headers->set("Access-control-allow-credentials","true");
		$app->response->headers->set("Expires", "Mon, 26 Jul 1997 05:00:00 GMT");
		$app->response->headers->set("Cache-Control", "no-cache, no-store, must-revalidate");
		$app->response->headers->set("Pragma", "no-cache");
		$app->response->status(200);
		return;
}

function PDO2json($dbh) {
		$table =  "";
		while ($fila = $dbh->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $rec = '';
        $rec .= '{';
        foreach($fila as $key => $value ) {
            $rec .= '"' . $key . '":"' . $value . '",';
        }
        $rec = substr($rec, 0, -1);
        $rec .= '},';

        $table .= $rec;

    }
		if($table == '') {
			$table = '[]';
		} else {
			$table = '[' . substr($table, 0, -1) . ']';
		}

    //echo "\n<br>debug PDO2json: " . $table;
    return $table;
}

function PDO2xml($dbh, $style) {

    $table = '<?xml version="1.0" encoding="UTF-8" ?>';
    if(!$style == ''){
      $table .='<?xml-stylesheet type="text/xsl" href="' . $style . '" ?>';
    }
		$table .=  "<matrix>";
		while ($fila = $dbh->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        $rec = '';
        $rec .= '<row>';
        foreach($fila as $key => $value ) {
            $rec .= '<col><name>' . $key . '</name><value>' . $value . '</value></col>';
        }
        $rec .= '</row>';
        $table .= $rec;
    }
    $table .= '</matrix>';

    return $table;
}

function json2xml($json, $style) {
return "alguisimo";
    $table = '<?xml version="1.0" encoding="UTF-8" ?>';
    if(!$style == ''){
      $table .='<?xml-stylesheet type="text/xsl" href="' . $style . '" ?>';
    }
		$table .=  "<matrix>";
/*		foreach($json as $fila ) {
        $rec = '';
        $rec .= '<row>';
        foreach($fila as $key => $value ) {
            $rec .= '<col><name>' . $key . '</name><value>' . $value . '</value></col>';
        }
        $rec .= '</row>';
        $table .= $rec;
    } */
    $table .= '</matrix>';

    return $table;
}

function parseParams($name, $flds, $sort, $max, $filter, $app) {

   $query = getSQL($name, $app);
   if(!$flds == ''){
      $query = str_replace(" * ", " " . $flds . " ", $query );
   }

   if(!$filter == ''){
   		if(contains("WHERE", $query) ){
				$query .= $filter . " ";
   		} else {
				$query .= " WHERE " . $filter . " ";
			}
   }

   if(!$sort == '') {
     $pos = strrpos($sort, "-");
      if ($pos === false) { // nota: tres signos de igual
          // no encontrado...
          $sort = " ORDER BY " . $sort . " ASC ";
      } else {
          $sort = str_replace("-", "", $sort );
          $sort = " ORDER BY " . $sort . " DESC ";
      }
      $query.= $sort;
   }
   $query.= ($max == '' ? '' : ' limit ' . $max);

//	 echo "query: " . $query ;

   return $query;
}

// returns true if $needle is a substring of $haystack
function contains($needle, $haystack)
{
		if($needle == ""){
			return false;
		} else {
			return strpos($haystack, $needle) !== false;
		}
}

// confirmar si el token todavía es válido
function checkToken($app)
{
		$sqlCode = 'token_check';
		$query = getSQL($sqlCode, $app);
		$prepParams = array(
					':token'   		 => $app->request()->params('token'),
					':id'          => $app->request()->params('id')
		);

		$connection = getConnection();

		$dbh = $connection->prepare($query);
		$dbh->execute($prepParams);

		$fldname = 'tokenexpira';

		$valid = false;
		$table =  "";
		while ($fila = $dbh->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
				$rec = '';
				$rec .= '{';
        foreach($fila as $key => $value ) {

						if($key == $fldname){

								$rightnow = date('Y-m-d H:i:s', strtotime("now"));
								//$expira  = strtotime($value);

							/*	echo "\n<br>debug checktoken: " . $app->request()->params('token') .
								" / " . $value . " / " . $rightnow ;
*/
								if($value > $rightnow){
									$valid = true;
								}


						}
						$rec .= '"' . $key . '":"' . $value . '",';
        }
				$rec = substr($rec, 0, -1);
				$rec .= '},';

				$table .= $rec;

    }

		if($table == '') {
			$table = '[]';
		} else {
			$table = '[' . substr($table, 0, -1) . ']';
		}

		//echo "\n<br>debug PDO2json: " . $table;
		if(!$valid){
			$table = '[{"acceso":"Denegado.","motivo":"Token no existe o Ya ha expirado."}]';
		} else {
			$tokenstr = ',"tokenstatus":"validtoken"';
			$table = substr_replace($table, $tokenstr, strlen($table)-2, 0);

		}
		return $table;
}

function getPermissions(){
	//$str = file_get_contents('http://example.com/example.json/');
	//$str = file_get_contents('permissions.json', FILE_USE_INCLUDE_PATH);
	$str = file_get_contents('permissions.json');
	$json = json_decode($str, true); // decode the JSON into an associative array
	//echo '<pre>' . print_r($json, true) . '</pre>';
	//echo $json[0]['GET:/users/count']['tiporol']['incluye'] . '</pre>';
	return $json;
}

function checkPerm($route, $app){
	//$permArray, $app->request()->params('tiporol'), $app->request()->params('rol')
	//echo $permArray[0]['GET:/users/count']['tiporol']['incluye'];
	$permArray = getPermissions();

	$tiporol = $app->request()->params('tiporol');
	$rol = $app->request()->params('rol');

	$tiporol_include = $permArray[0][$route]['tiporol']['incluye'];
	$tiporol_exclude = $permArray[0][$route]['tiporol']['excluye'];

	$rol_include = $permArray[0][$route]['rol']['incluye'];
	$rol_exclude = $permArray[0][$route]['rol']['excluye'];

	//$app->request()->params('format');
 	/*
	echo 'Método:' . $app->request()->getMethod();
	echo '<br>get tiporol:' . $app->request()->get('tiporol');
	echo '<br>put tiporol:' . $app->request()->put('tiporol');
	echo '<br>post tiporol:' . $app->request()->post('tiporol');
	echo '<br>all_put:' . $app->request()->put();
	echo '<br>$tiporol: ' . $tiporol;
	echo '<br>$rol: ' . $rol;
	echo '<br>$tipserv_include: ' . $tiporol_include;
	echo '<br>$tipserv_exclude: ' . $tiporol_exclude;
	echo '<br>$rol_include: ' . $rol_include;
	echo '<br>$rol_exclude: ' . $rol_exclude;
	*/
	/*
	$arr = $app->request()->put();
	echo "\n<br>put:" . $key . "/" . $value;
	foreach ( $arr as $key => $value) {
			echo $key . "/" . $value;
	}
	$arr = $app->request()->post();
	echo "\n<br>post:" . $key . "/" . $value;
	foreach ( $arr as $key => $value) {
			echo $key . "/" . $value;
	}
	$arr = $app->request()->get();
	echo "\n<br>get:" . $key . "/" . $value;
	foreach ( $arr as $key => $value) {
			echo $key . "/" . $value;
	}
*/
	$authorized = false;
	if( $tiporol_include == "*" && !contains($tiporol, $tiporol_exclude) ) {
		if( $rol_include == "*" && !contains($rol, $rol_exclude) ) {
			$authorized = true;
		}
	}

	if(	!$authorized ) {
		if( contains($tiporol, $tiporol_include) ) {
			if( contains($rol, $rol_include) ||
					($rol_include == "*" && !contains($rol, $rol_exclude)) ) {
				$authorized = true;
			}
		}
	}
	/*
	//$authorized = false;
	echo "<br>autho:" . $authorized;
	var_export($authorized);
	*/
	return $authorized;
}
