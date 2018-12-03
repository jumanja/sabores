<?php if(!defined("SPECIALCONSTANT")) die(ACCESSERROR);

function getConnection()
{
	try{
    $ip = getenv("REMOTE_ADDR"); // get the ip number of the user
		//echo "Llamado desde " . $ip . " ";
    if ($ip == '127.0.0.1' || $ip == "::1" || $ip == "localhost") {
			//$db_username = "provbas_jumanja";
			$db_username = "adminsabor";
			$db_password = "uAdmin2018";

			$connection = new PDO("mysql:host=db;dbname=sabor", $db_username, $db_password,
							array(
										PDO::ATTR_TIMEOUT => "5",
										PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
										PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
							)
			);

/*
			$connection = new PDO('sqlite:sabor.sqlite3') or die('No pude abrir la base de datos');
		         $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		         $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
*/

//										PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"

			//$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	}
    } else {
			$db_username = "adminsabor";
			$db_password = "uAdmin2018";

			$connection = new PDO("mysql:host=db;dbname=sabor", $db_username, $db_password,
							array(
										PDO::ATTR_TIMEOUT => "5",
										PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
										PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
							)
			);
/*
			$connection = new PDO('sqlite:sabor.sqlite3') or die('No pude abrir la base de datos');
		         $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		         $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

*/
/*,
PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
*/
			//);
										//PDO::ATTR_EMULATE_PREPARES => true
			//$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	return $connection;
}
