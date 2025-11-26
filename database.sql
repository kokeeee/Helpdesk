-- Estructura de la base de datos HelpDesk

-- Tabla de Usuarios
CREATE TABLE IF NOT EXISTS `tm_usuario` (
  `usu_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL DEFAULT 1,
  `estado` int(11) DEFAULT 1,
  `fecha_crea` timestamp DEFAULT CURRENT_TIMESTAMP,
  `fecha_elim` datetime DEFAULT NULL,
  PRIMARY KEY (`usu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla de Categorías
CREATE TABLE IF NOT EXISTS `tm_categoria` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_nom` varchar(100) NOT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla de Tickets
CREATE TABLE IF NOT EXISTS `tm_ticket` (
  `tick_id` int(11) NOT NULL AUTO_INCREMENT,
  `usu_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `tick_asunto` varchar(255) NOT NULL,
  `tick_descrip` longtext NOT NULL,
  `fecha_crea` timestamp DEFAULT CURRENT_TIMESTAMP,
  `est` int(11) DEFAULT 1,
  `tick_estado` varchar(50) DEFAULT 'Abierto',
  PRIMARY KEY (`tick_id`),
  FOREIGN KEY (`usu_id`) REFERENCES `tm_usuario` (`usu_id`),
  FOREIGN KEY (`cat_id`) REFERENCES `tm_categoria` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla de Detalles de Ticket
CREATE TABLE IF NOT EXISTS `td_ticketdetalle` (
  `tickd_id` int(11) NOT NULL AUTO_INCREMENT,
  `tick_id` int(11) NOT NULL,
  `usu_id` int(11) NOT NULL,
  `tickd_descrip` longtext NOT NULL,
  `fech_crea` timestamp DEFAULT CURRENT_TIMESTAMP,
  `est` int(11) DEFAULT 1,
  PRIMARY KEY (`tickd_id`),
  FOREIGN KEY (`tick_id`) REFERENCES `tm_ticket` (`tick_id`),
  FOREIGN KEY (`usu_id`) REFERENCES `tm_usuario` (`usu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insertar datos de prueba

-- Insertar Categorías
INSERT INTO `tm_categoria` (`cat_nom`) VALUES
('Consulta Stock'),
('Personal'),
('Cotización'),
('Otros');

-- Insertar Usuarios de prueba
INSERT INTO `tm_usuario` (`nombre`, `apellido`, `email`, `password`, `rol_id`, `estado`) VALUES
('Jorge', 'Lagos', 'usuario@test.com', '123456', 1, 1),
('Rodrigo', 'Alvarez', 'soporte@test.com', '123456', 2, 1);

-- Insertar Tickets de prueba
INSERT INTO `tm_ticket` (`usu_id`, `cat_id`, `tick_asunto`, `tick_descrip`, `tick_estado`) VALUES
(1, 1, 'TEST', 'Descripción de prueba', 'Cerrado'),
(1, 1, 'prueba', 'Descripción de prueba', 'Cerrado'),
(1, 1, 'prueba ticket', 'Descripción de prueba', 'Abierto');

-- Insertar Detalles de Ticket
INSERT INTO `td_ticketdetalle` (`tick_id`, `usu_id`, `tickd_descrip`) VALUES
(1, 2, 'Respuesta de soporte'),
(2, 2, 'Respuesta de soporte');
