<?php
//Store all sqls with a code to be used in json, xml and xslt versions
function getSQL($name, $app) {
    $lang = $app->request()->params('lang');
    $grupo = $app->request()->params('grupo');
    $lang = strtolower(substr($lang, 0, 2));

    $SQLs  = array(
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
            "groups_act"   => "SELECT grupo, id, nombre, estado, logo, direccion, ciudad, email " .
                             "FROM grupos WHERE estado = 'A'",
            "groups_add"   => "INSERT INTO grupos (grupo, id, nombre, estado, logo, direccion, ciudad, email) " .
                             "VALUES (:grupo, :id, :nombre, :estado, :logo, :direccion, :ciudad, :email)",
            "groups_all"   => "SELECT grupo, id, nombre, estado, logo, direccion, ciudad, email FROM grupos",
            "groups_sel"   => "SELECT nombre, grupo FROM grupos where estado = 'A'",
            "groups_count" => "SELECT count(1) as count FROM grupos",

            "places_act"  => "SELECT grupo, lugar, id, estado FROM lugares WHERE estado = 'A' AND grupo = '" . $grupo . "'",
            "places_add"  => "INSERT INTO lugares (grupo, lugar, id, estado) " .
                             "VALUES (:grupo, :lugar, :id, :estado)",
            "places_count"=> "SELECT count(1) as count FROM lugares WHERE grupo = '" . $grupo . "'",
            "places_all"  => "SELECT grupo, lugar, id, estado FROM lugares WHERE grupo = '" . $grupo . "'",

            "servs_add"   => "INSERT INTO roles (rol, tiporol, id, nombre, estado) " .
                             "VALUES (:rol, :tiporol, :id, :nombre, :estado)",
            "servs_count" => "SELECT count(1) as count FROM roles",
            "servs_act"   => "SELECT rol, tiporol, id, nombre, estado FROM roles WHERE estado = 'A'",
            "servs_sel"   => "SELECT nombre, rol FROM roles WHERE estado = 'A'",
            "servs_all"   => "SELECT rol, tiporol, id, nombre, estado FROM roles",

            "token_check" => "SELECT tokenexpira FROM usuarios WHERE token = :token AND id = :id ",

            "tags_act"  => "SELECT grupo, etiqueta, id, estado FROM etiquetas WHERE estado = 'A' AND grupo = '" . $grupo . "'",
            "tags_add"  => "INSERT INTO etiquetas (grupo, etiqueta, id, estado) " .
                             "VALUES (:grupo, :etiqueta, :id, :estado)",
            "tags_count"=> "SELECT count(1) as count FROM etiquetas WHERE grupo = '" . $grupo . "'",
            "tags_all"  => "SELECT grupo, etiqueta, id, estado FROM etiquetas WHERE grupo = '" . $grupo . "'",

            "tags_minretire" => "UPDATE etiquetasActa SET estado = 'R' WHERE idacta = :idacta",
            "tags_mindelete" => "DELETE from etiquetasActa WHERE idacta = :idacta ",
            "tags_minadd"    => "INSERT into etiquetasActa (idacta, etiqueta, estado) VALUES ( :idacta, :etiqueta, :estado) ",
            "tags_minid"     => "SELECT etiqueta FROM etiquetasActa WHERE idacta = :idacta ",

            "types_act"   => "SELECT grupo, tipo, id, nombre, estado " .
                             "FROM tipoactas a WHERE estado = 'A' AND grupo = '" . $grupo . "'",
            "types_all"   => "SELECT grupo, tipo, id, nombre, estado FROM tipoactas WHERE grupo = '" . $grupo . "'",
            "types_add"   => "INSERT INTO tipoactas (grupo, tipo, id, nombre, estado) " .
                             "VALUES (:grupo, :tipo, :id, :nombre, :estado)",
            "types_count" => "SELECT count(1) as count FROM tipoactas WHERE grupo = '" . $grupo . "'",

            "users_act"   => "SELECT a.grupo, a.id, a.usuario, a.apellidos, a.nombres, a.password, a.email, a.rol, b.tiporol " .
                             "FROM usuarios a, roles b WHERE a.estado = 'A' and a.rol = b.rol",
            "users_all"   => "SELECT grupo, id, usuario, apellidos, nombres, password, email, rol, estado FROM usuarios WHERE grupo = '" . $grupo . "'",
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
