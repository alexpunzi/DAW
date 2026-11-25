-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2026 at 10:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pibd`
--

-- --------------------------------------------------------

--
-- Table structure for table `anuncios`
--

CREATE TABLE `anuncios` (
  `IdAnuncio` int(11) NOT NULL,
  `TAnuncio` tinyint(4) NOT NULL,
  `TVivienda` tinyint(4) NOT NULL,
  `FPrincipal` varchar(255) DEFAULT NULL,
  `Alternativo` varchar(255) NOT NULL,
  `Titulo` varchar(150) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `Texto` text DEFAULT NULL,
  `Ciudad` varchar(100) DEFAULT NULL,
  `Pais` int(11) DEFAULT NULL,
  `Superficie` float DEFAULT NULL,
  `NHabitaciones` int(11) DEFAULT NULL,
  `NBanyos` int(11) DEFAULT NULL,
  `Planta` int(11) DEFAULT NULL,
  `Anyo` int(11) DEFAULT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anuncios`
--

INSERT INTO `anuncios` (`IdAnuncio`, `TAnuncio`, `TVivienda`, `FPrincipal`, `Alternativo`, `Titulo`, `Precio`, `Texto`, `Ciudad`, `Pais`, `Superficie`, `NHabitaciones`, `NBanyos`, `Planta`, `Anyo`, `FRegistro`, `Usuario`) VALUES
(1, 1, 2, 'img/chalet-piscina.jpg', 'Chalet independiente con piscina en Altea', 'Chalet Independiente con Piscina', 320000.00, 'Espectacular chalet independiente con piscina privada y amplio jardín en zona residencial tranquila.', 'Altea', 1, 240, 4, 3, 0, 2018, '2026-10-15 08:30:00', 1),
(2, 1, 2, 'img/piso-terraza.jpg', 'Ático luminoso con terraza en Alicante', 'Ático Luminoso en el Centro Histórico', 185000.00, 'Ático céntrico reformado con terraza exterior y luz natural todo el día.', 'Alicante', 1, 95, 2, 2, 5, 2005, '2026-10-20 10:15:00', 1),
(3, 2, 2, 'img/estudio.jpg', 'Estudio moderno cerca de la universidad en San Vicente del Raspeig', 'Estudio Moderno Cerca de la Universidad', 650.00, 'Coqueto estudio ideal para estudiantes o docentes, muy cerca del campus de la Universidad de Alicante.', 'San Vicente del Raspeig', 1, 45, 1, 1, 2, 2021, '2026-10-25 15:45:00', 1),
(4, 1, 2, 'img/piso-valencia.jpg', 'Piso amplio en el barrio de Ruzafa, Valencia', 'Piso Amplio en Ruzafa', 240000.00, 'Vivienda señorial en el moderno barrio de Ruzafa con techos altos, molduras originales y amplio balcón.', 'Valencia', 1, 130, 3, 2, 3, 1998, '2026-11-01 08:00:00', 1),
(5, 2, 3, 'img/piso-terraza.jpg', 'Oficina luminosa en el centro financiero', 'Oficina Diáfana en Avenida Maisonnave', 1100.00, 'Oficina recién acondicionada, muy luminosa y lista para entrar a trabajar en pleno centro comercial.', 'Alicante', 1, 110, 2, 1, 4, 2015, '2026-11-10 17:20:00', 1),
(6, 1, 1, 'img/chalet-piscina.jpg', 'Bungalow de obra nueva en Playa de San Juan', 'Bungalow Obra Nueva Playa de San Juan', 415000.00, 'Promoción de obra nueva a 500 metros de la playa con urbanización completa, piscina y pista de pádel.', 'Alicante', 1, 165, 3, 2, 1, 2026, '2026-11-20 10:10:00', 1),
(17, 2, 4, 'img/local-alicante.jpg', 'Escaparate amplio de local comercial reformado en el centro de Alicante', 'Local comercial diáfano junto a Maisonnave', 1450.00, 'Magnífico local a pie de calle con gran escaparate, aire acondicionado por conductos, almacén trasero y aseo adaptado. Zona de altísimo paso peatonal.', 'Alicante', 1, 110, 0, 1, 0, 2005, '2026-11-24 09:15:00', 1),
(18, 1, 1, 'img/obra-nueva-alicante.jpg', 'Fachada moderna de residencial de obra nueva con piscina en Playa de San Juan', 'Ático de obra nueva con terraza en San Juan Playa', 385000.00, 'Promoción exclusiva a 400 metros del mar. Cuenta con aerotermia, suelo radiante, cocina equipada con isla, urbanización con piscina climatizada y pista de pádel.', 'Alicante', 1, 125, 3, 2, 6, 2026, '2026-11-23 17:30:00', 5),
(19, 2, 3, 'img/oficina-madrid.jpg', 'Interior luminoso de oficina moderna con sala de reuniones acristalada en Madrid', 'Oficina ejecutiva equipada en Paseo de la Castellana', 2200.00, 'Espacio de trabajo listo para entrar, con capacidad para 15 puestos, sala de juntas insonorizada, fibra óptica simétrica y servicio de conserjería 24 horas.', 'Madrid', 1, 140, 2, 2, 4, 2018, '2026-11-20 08:45:00', 1),
(20, 2, 5, 'img/garaje-valencia.jpg', 'Plaza de garaje amplia y de fácil maniobra en sótano iluminado en Valencia', 'Plaza de garaje para coche grande y moto en Ruzafa', 95.00, 'Plaza muy cómoda en primer sótano con puerta automática, mando a distancia, cámaras de videovigilancia y acceso peatonal con ascensor.', 'Valencia', 1, 18, 0, 0, -1, 2012, '2026-11-18 15:20:00', 1),
(21, 2, 2, 'img/piso-alicante-alquiler.jpg', 'Salón comedor luminoso con vistas despejadas en piso de alquiler en Alicante', 'Piso reformado de 2 dormitorios junto al Mercado Central', 850.00, 'Vivienda recién pintada y totalmente amueblada, balcón exterior, cocina independiente con galería y excelente conexión con el TRAM hacia la Universidad.', 'Alicante', 1, 78, 2, 1, 3, 1998, '2026-11-15 11:00:00', 5),
(22, 1, 2, 'img/piso-barcelona.jpg', 'Salón con suelos hidráulicos originales y techos altos en el Eixample de Barcelona', 'Piso modernista con galería en l\'Eixample', 495000.00, 'Encantadora propiedad que conserva molduras y carpintería original restaurada. Dispone de 3 habitaciones dobles, balcón a calle tranquila y mucha luz natural.', 'Barcelona', 1, 115, 3, 2, 2, 1930, '2026-11-10 13:10:00', 1),
(23, 1, 1, 'img/chalet-elche.jpg', 'Chalet adosado de obra nueva con jardín privado y pérgola en Elche', 'Bungalow de obra nueva con solárium y jardín', 265000.00, 'Vivienda unifamiliar de diseño contemporáneo con parcela privada, preinstalación de cargador para vehículo eléctrico y zonas verdes comunitarias.', 'Elche', 1, 135, 3, 3, 0, 2026, '2026-11-05 10:25:00', 1),
(24, 1, 2, 'img/depto-buenos-aires.jpg', 'Departamento moderno con balcón aterrazado y parrilla en Palermo Buenos Aires', 'Departamento de 3 ambientes con balcón en Palermo Soho', 185000.00, 'Luminoso departamento al frente con balcón corrido, cocina integrada con barra de silestone, dormitorio en suite y amenities con piscina en terraza.', 'Buenos Aires', 2, 82, 2, 2, 5, 2021, '2026-10-28 18:00:00', 5),
(25, 1, 4, 'img/local-cdmx.jpg', 'Local comercial de doble altura en esquina en la colonia Roma Norte de CDMX', 'Local comercial en esquina en Roma Norte', 310000.00, 'Excelente oportunidad de inversión en zona gastronómica y cultural de alta plusvalía. Doble altura, terraza autorizada y dos medios baños.', 'Ciudad de México', 3, 95, 0, 2, 0, 2015, '2026-10-20 15:40:00', 1),
(26, 1, 5, 'img/garaje-alicante.jpg', 'Garaje cerrado tipo box con espacio para trastero en el barrio de Benalúa Alicante', 'Garaje cerrado con trastero integrado en Benalúa', 21500.00, 'Plaza cerrada con persiana metálica individual, ideal tanto para estacionar vehículo como para almacenamiento seguro con luz propia.', 'Alicante', 1, 22, 0, 0, -1, 2002, '2026-10-12 06:50:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `estilos`
--

CREATE TABLE `estilos` (
  `IdEstilo` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Fichero` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estilos`
--

INSERT INTO `estilos` (`IdEstilo`, `Nombre`, `Descripcion`, `Fichero`) VALUES
(1, 'Normal', 'Estilo visual por defecto de la aplicación', ''),
(2, 'Modo Noche', 'Paleta oscura para reducir la fatiga visual', 'css/noche.css'),
(3, 'Alto contraste', 'Colores de máximo contraste para mejorar la legibilidad', 'css/contraste.css'),
(4, 'Letra grande', 'Tamaño de tipografía ampliado en toda la web', 'css/letra-grande.css'),
(5, 'Contraste y letra grande', 'Combinación de alto contraste y tipografía ampliada', 'css/contraste-letra-grande.css');

-- --------------------------------------------------------

--
-- Table structure for table `fotos`
--

CREATE TABLE `fotos` (
  `IdFoto` int(11) NOT NULL,
  `Titulo` varchar(150) DEFAULT NULL,
  `Foto` varchar(255) NOT NULL,
  `Alternativo` varchar(255) NOT NULL,
  `Anuncio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fotos`
--

INSERT INTO `fotos` (`IdFoto`, `Titulo`, `Foto`, `Alternativo`, `Anuncio`) VALUES
(1, 'Jardín y piscina', 'img/chalet-piscina.jpg', 'Vista de la piscina privada del chalet', 1),
(2, 'Terraza exterior', 'img/piso-terraza.jpg', 'Terraza soleada con vistas al centro', 1),
(3, 'Salón principal', 'img/piso-valencia.jpg', 'Salón comedor amplio y luminoso', 2),
(4, 'Vista del edificio', 'img/estudio.jpg', 'Exterior del edificio junto al campus', 3),
(5, 'Interior principal del local', 'img/local-alicante-2.jpg', 'Vista interior diáfana del local comercial con iluminación LED en Alicante', 17),
(6, 'Zona de almacén y aseo', 'img/local-alicante-3.jpg', 'Detalle del almacén trasero y acceso al baño adaptado del local', 17),
(7, 'Terraza con vistas al mar', 'img/obra-nueva-alicante-2.jpg', 'Amplia terraza privada del ático de obra nueva con mobiliario exterior', 18),
(8, 'Cocina con isla equipada', 'img/obra-nueva-alicante-3.jpg', 'Cocina abierta moderna con electrodomésticos integrados y barra de desayuno', 18),
(9, 'Piscina comunitaria', 'img/obra-nueva-alicante-4.jpg', 'Piscina y jardines de la urbanización residencial en Playa de San Juan', 18),
(10, 'Sala de reuniones acristalada', 'img/oficina-madrid-2.jpg', 'Sala de juntas con mesa para diez personas y pantalla de presentaciones', 19),
(11, 'Zona de puestos operativos', 'img/oficina-madrid-3.jpg', 'Área abierta de trabajo con grandes ventanales hacia Paseo de la Castellana', 19);

-- --------------------------------------------------------

--
-- Table structure for table `mensajes`
--

CREATE TABLE `mensajes` (
  `IdMensaje` int(11) NOT NULL,
  `TMensaje` tinyint(4) NOT NULL,
  `Texto` varchar(4000) NOT NULL,
  `Anuncio` int(11) NOT NULL,
  `UsuOrigen` int(11) NOT NULL,
  `UsuDestino` int(11) NOT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mensajes`
--

INSERT INTO `mensajes` (`IdMensaje`, `TMensaje`, `Texto`, `Anuncio`, `UsuOrigen`, `UsuDestino`, `FRegistro`) VALUES
(5, 1, 'Hola, ¿el precio del alquiler del local incluye los gastos de comunidad e IBI? Gracias.', 17, 5, 1, '2026-11-24 11:30:00'),
(6, 2, 'Buenos días, nos gustaría concertar una visita este viernes por la tarde para ver el ático.', 18, 1, 5, '2026-11-24 13:15:00'),
(7, 3, 'Buenas tardes, estamos muy interesados en el piso del Mercado Central. ¿Aceptarían 800 €/mes para contrato de larga duración?', 21, 1, 5, '2026-11-25 08:20:00'),
(8, 2, 'Hola, ¿podría pasar mañana por la mañana a probar si cabe mi coche en la plaza de garaje de Ruzafa?', 20, 1, 1, '2026-11-25 10:05:00');

-- --------------------------------------------------------

--
-- Table structure for table `paises`
--

CREATE TABLE `paises` (
  `IdPais` int(11) NOT NULL,
  `NomPais` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paises`
--

INSERT INTO `paises` (`IdPais`, `NomPais`) VALUES
(1, 'España'),
(2, 'Argentina'),
(3, 'México'),
(4, 'Francia');

-- --------------------------------------------------------

--
-- Table structure for table `solicitudes`
--

CREATE TABLE `solicitudes` (
  `IdSolicitud` int(11) NOT NULL,
  `Anuncio` int(11) NOT NULL,
  `Texto` varchar(4000) DEFAULT NULL,
  `Nombre` varchar(200) NOT NULL,
  `Email` varchar(254) NOT NULL,
  `Direccion` varchar(255) NOT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Color` varchar(20) DEFAULT NULL,
  `Copias` int(11) NOT NULL DEFAULT 1,
  `Resolucion` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `IColor` tinyint(1) NOT NULL DEFAULT 0,
  `IPrecio` tinyint(1) NOT NULL DEFAULT 0,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Coste` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tiposanuncios`
--

CREATE TABLE `tiposanuncios` (
  `IdTAnuncio` tinyint(4) NOT NULL,
  `NomTAnuncio` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiposanuncios`
--

INSERT INTO `tiposanuncios` (`IdTAnuncio`, `NomTAnuncio`) VALUES
(1, 'Venta'),
(2, 'Alquiler');

-- --------------------------------------------------------

--
-- Table structure for table `tiposmensajes`
--

CREATE TABLE `tiposmensajes` (
  `IdTMensaje` tinyint(4) NOT NULL,
  `NomTMensaje` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiposmensajes`
--

INSERT INTO `tiposmensajes` (`IdTMensaje`, `NomTMensaje`) VALUES
(1, 'Más información'),
(2, 'Solicitar una cita'),
(3, 'Comunicar una oferta');

-- --------------------------------------------------------

--
-- Table structure for table `tiposviviendas`
--

CREATE TABLE `tiposviviendas` (
  `IdTVivienda` tinyint(4) NOT NULL,
  `NomTVivienda` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiposviviendas`
--

INSERT INTO `tiposviviendas` (`IdTVivienda`, `NomTVivienda`) VALUES
(1, 'Obra nueva'),
(2, 'Vivienda'),
(3, 'Oficina'),
(4, 'Local'),
(5, 'Garaje');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `IdUsuario` int(11) NOT NULL,
  `NomUsuario` varchar(15) NOT NULL,
  `Clave` varchar(255) NOT NULL,
  `Email` varchar(254) NOT NULL,
  `Sexo` tinyint(4) DEFAULT NULL,
  `FNacimiento` date DEFAULT NULL,
  `Ciudad` varchar(100) DEFAULT NULL,
  `Pais` int(11) DEFAULT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `FRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Estilo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`IdUsuario`, `NomUsuario`, `Clave`, `Email`, `Sexo`, `FNacimiento`, `Ciudad`, `Pais`, `Foto`, `FRegistro`, `Estilo`) VALUES
(1, 'juana', '1234', 'juana@pi.es', 2, '2006-05-15', 'Alicante', 1, 'img/default.png', '2026-11-24 14:08:53', 3),
(5, 'Alex', '$2y$10$GyvWfOjaP7jqxqoawYl3me3CXEoxXYJnFt16rsLkhmb/PrKFLWlEa', 'punzi@pi.es', 1, '2006-01-10', 'Alicante', 1, 'img/default.png', '2026-11-25 10:55:32', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anuncios`
--
ALTER TABLE `anuncios`
  ADD PRIMARY KEY (`IdAnuncio`),
  ADD KEY `caj_anuncios_tanuncio` (`TAnuncio`),
  ADD KEY `caj_anuncios_tvivienda` (`TVivienda`),
  ADD KEY `caj_anuncios_pais` (`Pais`),
  ADD KEY `caj_anuncios_usuario` (`Usuario`);

--
-- Indexes for table `estilos`
--
ALTER TABLE `estilos`
  ADD PRIMARY KEY (`IdEstilo`);

--
-- Indexes for table `fotos`
--
ALTER TABLE `fotos`
  ADD PRIMARY KEY (`IdFoto`),
  ADD KEY `caj_fotos_anuncio` (`Anuncio`);

--
-- Indexes for table `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`IdMensaje`),
  ADD KEY `caj_mensajes_tmensaje` (`TMensaje`),
  ADD KEY `caj_mensajes_anuncio` (`Anuncio`),
  ADD KEY `caj_mensajes_origen` (`UsuOrigen`),
  ADD KEY `caj_mensajes_destino` (`UsuDestino`);

--
-- Indexes for table `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`IdPais`);

--
-- Indexes for table `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`IdSolicitud`),
  ADD KEY `caj_solicitudes_anuncio` (`Anuncio`);

--
-- Indexes for table `tiposanuncios`
--
ALTER TABLE `tiposanuncios`
  ADD PRIMARY KEY (`IdTAnuncio`);

--
-- Indexes for table `tiposmensajes`
--
ALTER TABLE `tiposmensajes`
  ADD PRIMARY KEY (`IdTMensaje`);

--
-- Indexes for table `tiposviviendas`
--
ALTER TABLE `tiposviviendas`
  ADD PRIMARY KEY (`IdTVivienda`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IdUsuario`),
  ADD UNIQUE KEY `NomUsuario` (`NomUsuario`),
  ADD KEY `caj_usuarios_pais` (`Pais`),
  ADD KEY `caj_usuarios_estilo` (`Estilo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anuncios`
--
ALTER TABLE `anuncios`
  MODIFY `IdAnuncio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `estilos`
--
ALTER TABLE `estilos`
  MODIFY `IdEstilo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fotos`
--
ALTER TABLE `fotos`
  MODIFY `IdFoto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `IdMensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `paises`
--
ALTER TABLE `paises`
  MODIFY `IdPais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `IdSolicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tiposanuncios`
--
ALTER TABLE `tiposanuncios`
  MODIFY `IdTAnuncio` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tiposmensajes`
--
ALTER TABLE `tiposmensajes`
  MODIFY `IdTMensaje` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tiposviviendas`
--
ALTER TABLE `tiposviviendas`
  MODIFY `IdTVivienda` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IdUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anuncios`
--
ALTER TABLE `anuncios`
  ADD CONSTRAINT `caj_anuncios_pais` FOREIGN KEY (`Pais`) REFERENCES `paises` (`IdPais`),
  ADD CONSTRAINT `caj_anuncios_tanuncio` FOREIGN KEY (`TAnuncio`) REFERENCES `tiposanuncios` (`IdTAnuncio`),
  ADD CONSTRAINT `caj_anuncios_tvivienda` FOREIGN KEY (`TVivienda`) REFERENCES `tiposviviendas` (`IdTVivienda`),
  ADD CONSTRAINT `caj_anuncios_usuario` FOREIGN KEY (`Usuario`) REFERENCES `usuarios` (`IdUsuario`);

--
-- Constraints for table `fotos`
--
ALTER TABLE `fotos`
  ADD CONSTRAINT `caj_fotos_anuncio` FOREIGN KEY (`Anuncio`) REFERENCES `anuncios` (`IdAnuncio`) ON DELETE CASCADE;

--
-- Constraints for table `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `caj_mensajes_anuncio` FOREIGN KEY (`Anuncio`) REFERENCES `anuncios` (`IdAnuncio`) ON DELETE CASCADE,
  ADD CONSTRAINT `caj_mensajes_destino` FOREIGN KEY (`UsuDestino`) REFERENCES `usuarios` (`IdUsuario`),
  ADD CONSTRAINT `caj_mensajes_origen` FOREIGN KEY (`UsuOrigen`) REFERENCES `usuarios` (`IdUsuario`),
  ADD CONSTRAINT `caj_mensajes_tmensaje` FOREIGN KEY (`TMensaje`) REFERENCES `tiposmensajes` (`IdTMensaje`);

--
-- Constraints for table `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `caj_solicitudes_anuncio` FOREIGN KEY (`Anuncio`) REFERENCES `anuncios` (`IdAnuncio`) ON DELETE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `caj_usuarios_estilo` FOREIGN KEY (`Estilo`) REFERENCES `estilos` (`IdEstilo`),
  ADD CONSTRAINT `caj_usuarios_pais` FOREIGN KEY (`Pais`) REFERENCES `paises` (`IdPais`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
