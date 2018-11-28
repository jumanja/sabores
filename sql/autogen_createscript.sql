-- MySQL Script generated by MySQL Workbench
-- Tue Nov  6 17:05:50 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema sabor
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema sabor
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `sabor` DEFAULT CHARACTER SET utf8 ;
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
  `tipo` VARCHAR(11) NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `categorias_UNIQUE` (`nombre` ASC),
  INDEX `fk_grupo_idx` (`grupo` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sabor`.`articulos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`articulos` (
  `grupo` VARCHAR(15) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  `fecha` DATETIME NULL DEFAULT NULL,
  `categoria` VARCHAR(45) NOT NULL,
  `nombre` TEXT NOT NULL,
  `lugar` VARCHAR(30) NOT NULL,
  `observaciones` TEXT NOT NULL,
  `fechasig` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fx_lug_idx` (`lugar` ASC),
  INDEX `fx_grupo_idx` (`grupo` ASC),
  INDEX `fx_categ_idx` (`categoria` ASC))
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
-- Table `sabor`.`etiquetasrece`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sabor`.`etiquetasrece` (
  `idrece` INT(11) NOT NULL,
  `etiqueta` VARCHAR(45) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `estado` CHAR(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `etiqacta_UNIQUE` (`idrece` ASC, `etiqueta` ASC),
  INDEX `fx_etiq_idx` (`etiqueta` ASC))
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


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;