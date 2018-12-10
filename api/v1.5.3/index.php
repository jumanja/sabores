<?php
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

define("SPECIALCONSTANT", true);
define("ACCESSERROR", "Error: Access Denied to the API / Acceso Denegado a la API");

require 'app/libs/connect.php';       //conetor via PDO con la base de datos
require 'app/libs/sqls.php';          //sentencias SQL que usa la api

require 'app/routes/api.php';         //métodos y funciones generales de la api
require 'app/routes/login_api.php';   //iniciar sesión
require 'app/routes/logout_api.php';  //cerrar sesión en la bd
require 'app/routes/users_api.php';   //usuarios

require 'app/routes/groups_api.php';   //grupos
require 'app/routes/places_api.php';  //lugares de reunión
require 'app/routes/roles_api.php';   //roles
require 'app/routes/categs_api.php';   //categorias
require 'app/routes/units_api.php';   //unidades
require 'app/routes/artics_api.php';   //articulos
require 'app/routes/recipes_api.php';   //recetas
require 'app/routes/invens_api.php';   //inventarios
require 'app/routes/factors_api.php';   //factores de conversión
require 'app/routes/tags_api.php';    //etiquetas
require 'app/routes/mails_api.php';   //correo electrónico
require 'app/routes/mins_api.php';    //actas

$app->config('debug', true);
$app->setName('sabor');
date_default_timezone_set('America/Lima');

$permArray = getPermissions();

$app->run();
