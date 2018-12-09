USE `sabor` ;

-- -----------------------------------------------------
-- Table `sabor`.`grupos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`grupos` (
  `grupo` VARCHAR(15) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `estado` CHAR(1) NOT NULL,
  `logo` VARCHAR(45) NULL DEFAULT NULL,
  `direccion` VARCHAR(45) NULL DEFAULT NULL,
  `ciudad` VARCHAR(45) NULL DEFAULT NULL,
  `email` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `grupounico` (`grupo` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sabor`.`categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`categorias` (
  `grupo` VARCHAR(15) NOT NULL,
  `categria` VARCHAR(11) NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `categorias_UNIQUE` (`nombre` ASC),
  INDEX `fk_categ_idx` (`grupo` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `sabor`.`unidades`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`unidades` (
  `grupo` VARCHAR(15) NOT NULL,
  `unidad` VARCHAR(11) NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `unidades_UNIQUE` (`nombre` ASC),
  INDEX `fk_unidad_idx` (`grupo` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sabor`.`factores`
-- -----------------------------------------------------
CREATE TABLE `factores` (
  `grupo` varchar(15) NOT NULL,
  `id` int(11) NOT NULL,
  `unidad1` varchar(11) NOT NULL,
  `unidad2` varchar(11) NOT NULL,
  `multip` double NOT NULL,
  `adicion` int(11) NOT NULL,
  `estado` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- -----------------------------------------------------
-- Table `sabor`.`articulos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`articulos` (
  `grupo` VARCHAR(15) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  `categoria` VARCHAR(45) NOT NULL,
  `codigo` VARCHAR(45) NOT NULL,
  `nombre` TEXT NOT NULL,
  `vencim` INT(11) NOT NULL,
  `observaciones` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fx_grupo_idx` (`grupo` ASC),
  INDEX `fx_categ_idx` (`categoria` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `sabor`.`inventarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`inventarios` (
  `grupo` VARCHAR(15) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  `lugar` VARCHAR(30) NOT NULL,
  `idart` INT(11) NOT NULL,
  `unidad` varchar(11) NOT NULL,
  `cantidad` double NOT NULL,
  `observaciones` TEXT NOT NULL,
  `fechaant` DATETIME NULL DEFAULT NULL,
  `cantaant` double NOT NULL,
  `fechasig` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fx_inv_idx` (`grupo` ASC, `lugar` ASC, `idart` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sabor`.`etiquetas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`etiquetas` (
  `grupo` VARCHAR(15) NOT NULL,
  `etiqueta` VARCHAR(45) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `etiq_UNIQUE` (`etiqueta` ASC),
  INDEX `fx_grupo_idx` (`grupo` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sabor`.`ingredientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`ingredientes` (
  `grupo` VARCHAR(15) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  `idart` INT(11) NOT NULL,
  `cantidad` INT(11) NOT NULL,
  `observaciones` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fx_grupo_idx` (`grupo` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sabor`.`lugares`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`lugares` (
  `grupo` VARCHAR(15) NOT NULL,
  `lugar` VARCHAR(45) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `lugares_UNIQUE` (`lugar` ASC),
  INDEX `fk_grupos_idx` (`grupo` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sabor`.`notificaciones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`notificaciones` (
  `idart` INT(11) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  `origen` VARCHAR(15) NOT NULL,
  `destino` VARCHAR(15) NOT NULL,
  `fechahora` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `lugares_UNIQUE` (`idart` ASC, `fechahora` ASC),
  INDEX `fx_origen_idx` (`origen` ASC),
  INDEX `fx_destino_idx` (`destino` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sabor`.`recetas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`recetas` (
  `grupo` VARCHAR(15) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  `nombre` TEXT NOT NULL,
  `preparacion` TEXT NOT NULL,
  `observaciones` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fx_grupo_idx` (`grupo` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sabor`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`roles` (
  `rol` VARCHAR(10) NOT NULL,
  `tiporol` VARCHAR(15) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` TEXT NOT NULL,
  `estado` CHAR(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `roles_UNIQUE` (`rol` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sabor`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`usuarios` (
  `grupo` VARCHAR(15) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(25) NOT NULL,
  `apellidos` TEXT NOT NULL,
  `nombres` TEXT NOT NULL,
  `password` TEXT NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `rol` VARCHAR(10) NOT NULL,
  `token` VARCHAR(100) NOT NULL,
  `tokenexpira` DATETIME NOT NULL,
  `estado` CHAR(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `usuario_UNIQUE` (`usuario` ASC),
  INDEX `fk_grupos` (`grupo` ASC),
  INDEX `fk_serv_idx` (`rol` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = utf8;

/*
Poblar roles, se requiere después de limpiar o crear la Bd, o de no, no deja entar
*/
INSERT INTO `sabor`.`roles` (`rol`, `tiporol`, `id`, `nombre`, `estado`) VALUES ('A', 'A', '1', 'Administrador', 'A');
INSERT INTO `sabor`.`roles` (`rol`, `tiporol`, `id`, `nombre`, `estado`) VALUES ('U', 'I', '2', 'Usuario', 'A');
INSERT INTO `sabor`.`roles` (`rol`, `tiporol`, `id`, `nombre`, `estado`) VALUES ('V', 'V', '3', 'Visitante', 'A');


/*
Poblar grupos, se crea grupo demostración
*/
INSERT INTO `grupos` (`grupo`, `id`, `nombre`, `estado`, `logo`, `direccion`, `ciudad`, `email`) VALUES ('demo', '1', 'Demostración', 'A', '', '', '', '');

/*
Poblar usuarios, se requiere un usuario administrador para poder poblarla por scripts
*/
INSERT INTO `sabor`.`usuarios` (`grupo`, `id`, `usuario`, `apellidos`, `nombres`, `password`, `email`, `rol`, `token`, `tokenexpira`, `estado`) VALUES
('demo', 1, 'admin', 'Del Sistema', 'Administrador', '$2y$10$kR0suOkr3Qx8bbqeLzDDyey54FcRyOgMm2p3d3PyLjCONLKWtWFju', 'jumanja@gmail.com', 'A', '$2y$10$yZGPGWIz2nV5SxzxHYJi4OjAuUWRoPtKORQ1xxDl2LusKu2IYAGyG', '2018-10-25 15:33:27', 'A');
