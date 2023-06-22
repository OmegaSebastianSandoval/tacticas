-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.24-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla inspecciones_opain.contenido
CREATE TABLE IF NOT EXISTS `contenido` (
  `contenido_id` int(11) NOT NULL AUTO_INCREMENT,
  `contenido_seccion` int(11) DEFAULT NULL,
  `contenido_tipo` int(11) DEFAULT NULL,
  `contenido_padre` int(11) DEFAULT NULL,
  `contenido_columna_alineacion` int(11) DEFAULT NULL,
  `contenido_columna` varchar(255) DEFAULT NULL,
  `contenido_columna_espacios` int(11) DEFAULT NULL,
  `contenido_disenio` int(11) DEFAULT NULL,
  `contenido_borde` int(11) DEFAULT NULL,
  `contenido_estado` int(11) DEFAULT NULL,
  `contenido_fecha` date DEFAULT NULL,
  `contenido_titulo` varchar(500) DEFAULT NULL,
  `contenido_titulo_ver` int(11) DEFAULT NULL,
  `contenido_imagen` varchar(500) DEFAULT NULL,
  `contenido_archivo` varchar(255) DEFAULT NULL,
  `contenido_fondo_imagen` varchar(50) DEFAULT NULL,
  `contenido_fondo_imagen_tipo` int(11) DEFAULT NULL,
  `contenido_fondo_color` varchar(100) DEFAULT NULL,
  `contenido_introduccion` text DEFAULT NULL,
  `contenido_descripcion` text DEFAULT NULL,
  `contenido_enlace` varchar(500) DEFAULT NULL,
  `contenido_enlace_abrir` int(11) DEFAULT NULL,
  `contenido_vermas` varchar(100) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`contenido_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla inspecciones_opain.contenido: ~0 rows (aproximadamente)

-- Volcando estructura para tabla inspecciones_opain.enlaces
CREATE TABLE IF NOT EXISTS `enlaces` (
  `enlaces_id` int(11) NOT NULL AUTO_INCREMENT,
  `enlaces_titulo` varchar(255) DEFAULT NULL,
  `enlaces_link` varchar(255) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`enlaces_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla inspecciones_opain.enlaces: ~0 rows (aproximadamente)

-- Volcando estructura para tabla inspecciones_opain.info_pagina
CREATE TABLE IF NOT EXISTS `info_pagina` (
  `info_pagina_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `info_pagina_facebook` varchar(255) DEFAULT NULL,
  `info_pagina_instagram` varchar(255) DEFAULT NULL,
  `info_pagina_twitter` varchar(255) DEFAULT NULL,
  `info_pagina_pinterest` varchar(255) DEFAULT NULL,
  `info_pagina_youtube` varchar(255) DEFAULT NULL,
  `info_pagina_flickr` varchar(255) DEFAULT NULL,
  `info_pagina_linkedin` varchar(255) DEFAULT NULL,
  `info_pagina_google` varchar(255) DEFAULT NULL,
  `info_pagina_telefono` varchar(255) DEFAULT NULL,
  `info_pagina_whatsapp` varchar(255) DEFAULT NULL,
  `info_pagina_correos_contacto` varchar(255) DEFAULT NULL,
  `info_pagina_direccion_contacto` text DEFAULT NULL,
  `info_pagina_informacion_contacto` text DEFAULT NULL,
  `info_pagina_informacion_contacto_footer` text DEFAULT NULL,
  `info_pagina_latitud` varchar(255) DEFAULT NULL,
  `info_pagina_longitud` varchar(255) DEFAULT NULL,
  `info_pagina_zoom` varchar(255) DEFAULT NULL,
  `info_pagina_descripcion` text DEFAULT NULL,
  `info_pagina_tags` text DEFAULT NULL,
  `info_pagina_robot` varchar(500) DEFAULT NULL,
  `info_pagina_sitemap` varchar(500) DEFAULT NULL,
  `info_pagina_scripts` text DEFAULT NULL,
  `info_pagina_metricas` text DEFAULT NULL,
  `orden` bigint(20) DEFAULT NULL,
  `info_pagina_host` varchar(200) NOT NULL,
  `info_pagina_port` int(11) NOT NULL,
  `info_pagina_username` varchar(200) NOT NULL,
  `info_pagina_password` varchar(200) NOT NULL,
  `info_pagina_correo_remitente` varchar(200) NOT NULL,
  `info_pagina_nombre_remitente` varchar(200) NOT NULL,
  `info_pagina_correo_oculto` varchar(200) NOT NULL,
  `info_pagina_titulo_legal` varchar(200) NOT NULL,
  `info_pagina_descripcion_legal` longtext NOT NULL,
  `info_pagina_favicon` varchar(500) NOT NULL,
  PRIMARY KEY (`info_pagina_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla inspecciones_opain.info_pagina: ~1 rows (aproximadamente)
INSERT INTO `info_pagina` (`info_pagina_id`, `info_pagina_facebook`, `info_pagina_instagram`, `info_pagina_twitter`, `info_pagina_pinterest`, `info_pagina_youtube`, `info_pagina_flickr`, `info_pagina_linkedin`, `info_pagina_google`, `info_pagina_telefono`, `info_pagina_whatsapp`, `info_pagina_correos_contacto`, `info_pagina_direccion_contacto`, `info_pagina_informacion_contacto`, `info_pagina_informacion_contacto_footer`, `info_pagina_latitud`, `info_pagina_longitud`, `info_pagina_zoom`, `info_pagina_descripcion`, `info_pagina_tags`, `info_pagina_robot`, `info_pagina_sitemap`, `info_pagina_scripts`, `info_pagina_metricas`, `orden`, `info_pagina_host`, `info_pagina_port`, `info_pagina_username`, `info_pagina_password`, `info_pagina_correo_remitente`, `info_pagina_nombre_remitente`, `info_pagina_correo_oculto`, `info_pagina_titulo_legal`, `info_pagina_descripcion_legal`, `info_pagina_favicon`) VALUES
	(1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '1', 1, '1', '1', '1', '1', '1', '', '', 'favicon.png');

-- Volcando estructura para tabla inspecciones_opain.log
CREATE TABLE IF NOT EXISTS `log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_usuario` varchar(50) DEFAULT NULL,
  `log_tipo` varchar(50) DEFAULT NULL,
  `log_fecha` datetime DEFAULT NULL,
  `log_log` text DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla inspecciones_opain.log: 0 rows
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;

-- Volcando estructura para tabla inspecciones_opain.publicidad
CREATE TABLE IF NOT EXISTS `publicidad` (
  `publicidad_id` int(11) NOT NULL AUTO_INCREMENT,
  `publicidad_seccion` int(11) DEFAULT NULL,
  `publicidad_nombre` varchar(500) DEFAULT NULL,
  `publicidad_fecha` date DEFAULT NULL,
  `publicidad_imagen` varchar(500) DEFAULT NULL,
  `publicidad_imagenresponsive` varchar(255) DEFAULT NULL,
  `publicidad_color_fondo` varchar(255) DEFAULT NULL,
  `publicidad_video` text DEFAULT NULL,
  `publicidad_posicion` varchar(255) DEFAULT NULL,
  `publicidad_descripcion` text DEFAULT NULL,
  `publicidad_estado` int(11) DEFAULT NULL,
  `publicidad_click` int(11) DEFAULT NULL,
  `publicidad_enlace` varchar(500) DEFAULT NULL,
  `publicidad_tipo_enlace` int(11) DEFAULT NULL,
  `publicidad_texto_enlace` varchar(255) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`publicidad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla inspecciones_opain.publicidad: ~0 rows (aproximadamente)

-- Volcando estructura para tabla inspecciones_opain.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_date` date DEFAULT NULL,
  `user_names` varchar(255) DEFAULT NULL,
  `user_cedula` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_telefono` varchar(255) DEFAULT NULL,
  `user_level` int(11) DEFAULT NULL,
  `user_state` int(11) DEFAULT NULL,
  `user_user` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_delete` int(11) DEFAULT NULL,
  `user_current_user` bigint(20) DEFAULT NULL,
  `user_code` varchar(500) DEFAULT NULL,
  `user_empresa` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_user` (`user_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla inspecciones_opain.user: ~2 rows (aproximadamente)
INSERT INTO `user` (`user_id`, `user_date`, `user_names`, `user_cedula`, `user_email`, `user_telefono`, `user_level`, `user_state`, `user_user`, `user_password`, `user_delete`, `user_current_user`, `user_code`, `user_empresa`) VALUES
	(1, '2018-07-17', 'Administrador', '1232321321', 'gerencia@omegawebsystems.com', '123213123123', 1, 1, 'admin', '$2y$10$ULs0eFV/YanZb7L386//7O0wf6W4urgVrAaWDnRcSb.bLWi0ZmO8y', 1, 1, '1', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
