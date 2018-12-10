<?php
//Store all sqls with a code to be used in json, xml and xslt versions
function getSQL($name, $app) {
    $lang = $app->request()->params('lang');
    $grupo = $app->request()->params('grupo');
    $lang = strtolower(substr($lang, 0, 2));

    $SQLs  = array(

             "artics_act"   => "SELECT grupo, id, categoria, codigo, nombre, vencim, observaciones, estado " .
                              "FROM articulos a WHERE estado = 'A' AND grupo = '" . $grupo . "'",
             "artics_all"   => "SELECT grupo, id, categoria, codigo, nombre, vencim, observaciones, estado FROM articulos WHERE grupo = '" . $grupo . "' ORDER by nombre",
             "artics_add"   => "INSERT INTO articulos (grupo, id, categoria, codigo, nombre, vencim, observaciones, estado) " .
                              "VALUES (:grupo, :id, :categoria, :codigo, :nombre, :vencim, :observaciones, :estado)",
             "artics_count" => "SELECT count(1) as count FROM articulos WHERE grupo = '" . $grupo . "'",

            "mins_add"    => "INSERT INTO actas (grupo, id, estado, fecha, tipoacta, tema, lugar, objetivos, responsable, conclusiones, fechasig, lugarsig) " .
                             "VALUES (:grupo, :id, :estado, :fecha, :tipoacta, :tema, :lugar, :objetivos, :responsable, :conclusiones, :fechasig, :lugarsig) ",
            "mins_exec"   => "SELECT estado, count(1) as cuenta FROM actas grupo BY 1 ",
            "mins_nro"    => "SELECT a.grupo, a.id, a.estado, a.fecha, a.tipoacta, a.tema, a.lugar, " .
                             "a.objetivos, a.responsable, a.conclusiones, a.fechasig, " .
                             "a.lugarsig, a.estado FROM actas a " .
                             "WHERE a.id = " . $app->request()->params('nroActa') .
                             "",
            "mins_prog"   => "SELECT fecha, id, tema, objetivos, conclusiones FROM actas WHERE estado = 'G' ",
            "mins_update" => "UPDATE actas set estado = :estado, fecha = :fecha, tipoacta = :tipoacta, tema = :tema, " .
                             "lugar = :lugar, objetivos = :objetivos, conclusiones = :conclusiones, fechasig = :fechasig, lugarsig = :lugarsig  " .
                             "WHERE id = :id",

           "factors_act"   => "SELECT grupo, id, unidad1, unidad2, multip, adicion, estado " .
                            "FROM factores a WHERE estado = 'A' AND grupo = '" . $grupo . "'",
           "factors_all"   => "SELECT grupo, id, unidad1, unidad2, multip, adicion, estado FROM factores WHERE grupo = '" . $grupo . "' ORDER by unidad1, unidad2",
           "factors_add"   => "INSERT INTO factores (grupo, id, unidad1, unidad2, multip, adicion, estado) " .
                            "VALUES (:grupo, :id, :unidad1, :unidad2, :multip, :adicion, :estado)",
           "factors_count" => "SELECT count(1) as count FROM factores WHERE grupo = '" . $grupo . "'",

            "groups_act"   => "SELECT grupo, id, nombre, estado, logo, direccion, ciudad, email " .
                             "FROM grupos WHERE estado = 'A'",
            "groups_add"   => "INSERT INTO grupos (grupo, id, nombre, estado, logo, direccion, ciudad, email) " .
                             "VALUES (:grupo, :id, :nombre, :estado, :logo, :direccion, :ciudad, :email)",
            "groups_all"   => "SELECT grupo, id, nombre, estado, logo, direccion, ciudad, email FROM grupos ORDER by nombre",
            "groups_sel"   => "SELECT nombre, grupo FROM grupos where estado = 'A'",
            "groups_count" => "SELECT count(1) as count FROM grupos",

            "invens_act"   => "SELECT grupo, id, lugar, idart, unidad, cantidad, fechaant, cantiant, fechasig, observaciones, estado " .
                             "FROM inventarios a WHERE estado = 'A' AND grupo = '" . $grupo . "'",
            "invens_all"   => "SELECT a.grupo, a.id, a.lugar, a.idart, b.nombre as desc_idart, a.unidad, a.cantidad, a.fechaant, a.cantiant, a.fechasig, a.observaciones, a.estado " .
                              "FROM inventarios a, articulos b WHERE a.grupo = '" . $grupo . "' and b.id = a.idart ORDER BY lugar, desc_idart",
            "invens_add"   => "INSERT INTO inventarios (grupo, id, idart, unidad, cantidad, fechaant, cantiant, fechasig, observaciones, estado) " .
                             "VALUES (:grupo, :id, :lugar, :idart, :unidad, :cantidad, fechaant, cantiant, fechasig, :observaciones, :estado)",
            "invens_count" => "SELECT count(1) as count FROM inventarios WHERE grupo = '" . $grupo . "'",

            "places_act"  => "SELECT grupo, lugar, id, estado FROM lugares WHERE estado = 'A' AND grupo = '" . $grupo . "'",
            "places_add"  => "INSERT INTO lugares (grupo, lugar, id, estado) " .
                             "VALUES (:grupo, :lugar, :id, :estado)",
            "places_count"=> "SELECT count(1) as count FROM lugares WHERE grupo = '" . $grupo . "'",
            "places_all"  => "SELECT grupo, lugar, id, estado FROM lugares WHERE grupo = '" . $grupo . "' ORDER by lugar",

            "servs_add"   => "INSERT INTO roles (rol, tiporol, id, nombre, estado) " .
                             "VALUES (:rol, :tiporol, :id, :nombre, :estado)",
            "servs_count" => "SELECT count(1) as count FROM roles",
            "servs_act"   => "SELECT rol, tiporol, id, nombre, estado FROM roles WHERE estado = 'A'",
            "servs_sel"   => "SELECT nombre, rol FROM roles WHERE estado = 'A'",
            "servs_all"   => "SELECT rol, tiporol, id, nombre, estado FROM roles ORDER by nombre",

            "token_check" => "SELECT tokenexpira FROM usuarios WHERE token = :token AND id = :id ",

            "recipes_act"   => "SELECT grupo, id, nombre, preparacion, observaciones, estado " .
                             "FROM recetas a WHERE estado = 'A' AND grupo = '" . $grupo . "' ORDER BY nombre",
            "recipes_all"   => "SELECT grupo, id, nombre, preparacion, observaciones, estado " .
                              "FROM recetas WHERE grupo = '" . $grupo . "' ORDER BY nombre",
            "recipes_add"   => "INSERT INTO recetas (grupo, id, nombre, preparacion, observaciones, estado) " .
                             "VALUES (:grupo, :id, :preparacion, :observaciones, :estado)",
            "recipes_count" => "SELECT count(1) as count FROM recetas WHERE grupo = '" . $grupo . "'",

            "tags_act"  => "SELECT grupo, etiqueta, id, estado FROM etiquetas WHERE estado = 'A' AND grupo = '" . $grupo . "'",
            "tags_add"  => "INSERT INTO etiquetas (grupo, etiqueta, id, estado) " .
                             "VALUES (:grupo, :etiqueta, :id, :estado)",
            "tags_count"=> "SELECT count(1) as count FROM etiquetas WHERE grupo = '" . $grupo . "'",
            "tags_all"  => "SELECT grupo, etiqueta, id, estado FROM etiquetas WHERE grupo = '" . $grupo . "'  ORDER by etiqueta",

            "tags_minretire" => "UPDATE etiquetasActa SET estado = 'R' WHERE idacta = :idacta",
            "tags_mindelete" => "DELETE from etiquetasActa WHERE idacta = :idacta ",
            "tags_minadd"    => "INSERT into etiquetasActa (idacta, etiqueta, estado) VALUES ( :idacta, :etiqueta, :estado) ",
            "tags_minid"     => "SELECT etiqueta FROM etiquetasActa WHERE idacta = :idacta ",

            "categs_act"   => "SELECT grupo, categoria, id, nombre, estado " .
                             "FROM categorias a WHERE estado = 'A' AND grupo = '" . $grupo . "'",
            "categs_all"   => "SELECT grupo, categoria, id, nombre, estado FROM categorias WHERE grupo = '" . $grupo . "' ORDER by nombre",
            "categs_add"   => "INSERT INTO categorias (grupo, categoria, id, nombre, estado) " .
                             "VALUES (:grupo, :categoria, :id, :nombre, :estado)",
            "categs_count" => "SELECT count(1) as count FROM categorias WHERE grupo = '" . $grupo . "'",

            "units_act"   => "SELECT grupo, unidad, id, nombre, estado " .
                             "FROM unidades a WHERE estado = 'A' AND grupo = '" . $grupo . "'",
            "units_all"   => "SELECT grupo, unidad, id, nombre, estado FROM unidades WHERE grupo = '" . $grupo . "' ORDER by nombre",
            "units_add"   => "INSERT INTO unidades (grupo, unidad, id, nombre, estado) " .
                             "VALUES (:grupo, :unidad, :id, :nombre, :estado)",
            "units_count" => "SELECT count(1) as count FROM unidades WHERE grupo = '" . $grupo . "'",

            "users_act"   => "SELECT a.grupo, a.id, a.usuario, a.apellidos, a.nombres, a.password, a.email, a.rol, b.tiporol " .
                             "FROM usuarios a, roles b WHERE a.estado = 'A' and a.rol = b.rol",
            "users_all"   => "SELECT grupo, id, usuario, apellidos, nombres, password, email, rol, estado FROM usuarios WHERE grupo = '" . $grupo . "'  ORDER by apellidos, nombres",
            "users_add"   => "INSERT INTO usuarios (grupo, id, usuario, apellidos, nombres, password, email, rol, estado) " .
                             "VALUES (:grupo, :id, :usuario, :apellidos, :nombres, :password, :email, :rol, :estado)",
            "users_count" => "SELECT count(1) as count FROM usuarios WHERE grupo = '" . $grupo . "'",
            "users_int"   => "SELECT a.grupo, a.id, a.usuario, a.apellidos, a.nombres, a.password, a.email, a.estado, a.rol, " .
                             "b.tiporol, b.nombre as nombreser, b.id as idserv " .
                             "FROM usuarios a, roles b WHERE a.rol = b.rol and b.tiporol = 'I' " .
                             "ORDER BY idserv, apellidos, nombres",
            "users_tokenupdate" => "UPDATE usuarios set token = :token, tokenexpira = :tokenexpira WHERE id = :id ",
            "" => "");
    //echo "144. sqls name : " . $name . " / " .  $SQLs[$name];
    return $SQLs[$name];
}
