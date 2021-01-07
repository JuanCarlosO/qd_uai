-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2020 a las 10:45:29
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_siqd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actas`
--

CREATE TABLE `actas` (
  `id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL COMMENT 'fk de areas',
  `persona_id` int(11) NOT NULL COMMENT 'fk de personal',
  `queja_id` int(11) DEFAULT NULL COMMENT 'FK de quejas',
  `clave` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `t_actuacion` enum('INSPECCION','VERIFICACION','SUPERVISION','INVESTIGACION','USUARIO SIMULADO','AGENTE ENCUBIERTO') NOT NULL,
  `procedencia` enum('SS','CPRS') NOT NULL,
  `municipio_id` int(11) NOT NULL COMMENT 'fk de municipios',
  `lugar` varchar(255) DEFAULT NULL,
  `comentarios` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de actuaciones SIRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acuerdos_improcedencia`
--

CREATE TABLE `acuerdos_improcedencia` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de quejas',
  `f_acuerdo` date DEFAULT NULL,
  `f_turno` date DEFAULT NULL,
  `comentario` longtext DEFAULT NULL,
  `archivo` longblob DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de acuerdos de improcedencia';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `animales`
--

CREATE TABLE `animales` (
  `id` int(11) NOT NULL,
  `tipo` enum('PERRO','CABALLO') NOT NULL,
  `raza` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `edad` varchar(30) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `inv` varchar(100) DEFAULT NULL,
  `corporacion` int(11) DEFAULT NULL COMMENT 'fk de corporaciones',
  `acta_id` int(11) DEFAULT NULL COMMENT 'fk de actas',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de animales de un acta';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apersonamientos`
--

CREATE TABLE `apersonamientos` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL,
  `oficio` varchar(100) DEFAULT NULL,
  `f_oficio` date DEFAULT NULL,
  `f_acuse` date DEFAULT NULL,
  `f_apersonamiento` date DEFAULT NULL,
  `comentario` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Apersonamientos de las demandas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id` int(11) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de areas de la UAI';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `armas`
--

CREATE TABLE `armas` (
  `id` int(11) NOT NULL,
  `tipo` enum('FUEGO','BLANCA') NOT NULL,
  `matricula` varchar(100) DEFAULT NULL,
  `inv` varchar(100) DEFAULT NULL,
  `corporacion` int(11) DEFAULT NULL COMMENT 'fk de tbl corporaciones',
  `acta_id` int(11) DEFAULT NULL COMMENT 'fk de tbl actas',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de armas por actas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `a_responsables`
--

CREATE TABLE `a_responsables` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de quejas',
  `persona_id` int(11) NOT NULL COMMENT 'fk de personal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Abogado responsables de SC';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalogo de cargos de los policias ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo_conductas`
--

CREATE TABLE `catalogo_conductas` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `ley_id` int(11) NOT NULL COMMENT 'fk de tbl leyes',
  `articulo` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalogo de conductas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogo_vias`
--

CREATE TABLE `catalogo_vias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalogo de vias de recepción';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores`
--

CREATE TABLE `colores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de colores disponibles de vehiculos ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductas_respo`
--

CREATE TABLE `conductas_respo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de conductas para pronta ref respo';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `demandas`
--

CREATE TABLE `demandas` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL,
  `t_demanda` enum('PRIMER DEMANDA','RECURSO DE REVISION') DEFAULT NULL,
  `oficio` varchar(100) DEFAULT NULL,
  `f_oficio` date DEFAULT NULL,
  `f_acuse` date DEFAULT NULL,
  `dependencia` varchar(100) DEFAULT NULL,
  `comentario` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de demandas realizadas por queja';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE `devoluciones` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'FK de quejas ',
  `f_devolucion` date DEFAULT NULL,
  `f_oficio` date DEFAULT NULL,
  `oficio` varchar(100) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `estado` enum('ACTIVO','VENCIDO') DEFAULT NULL,
  `archivo` longblob DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Expedientes devueltos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_oin`
--

CREATE TABLE `documentos_oin` (
  `id` int(11) NOT NULL,
  `oin_id` int(11) NOT NULL COMMENT 'fk de ordenes de inspeccion',
  `nombre` varchar(255) NOT NULL,
  `comentarios` text DEFAULT NULL,
  `archivo` longblob DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='listado de documentos por oin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_qr`
--

CREATE TABLE `documentos_qr` (
  `id` int(11) NOT NULL,
  `oficio` varchar(100) DEFAULT NULL,
  `f_oficio` date DEFAULT NULL,
  `f_acuse` date DEFAULT NULL,
  `comentario` longtext DEFAULT NULL,
  `archivo` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de documentos de quejas_respo';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_quejas`
--

CREATE TABLE `documentos_quejas` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de quejas',
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext DEFAULT NULL,
  `archivo` longblob DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de documentos por queja';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_sapa`
--

CREATE TABLE `documentos_sapa` (
  `id` int(11) NOT NULL,
  `oficio` varchar(100) DEFAULT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `comentario` tinyblob DEFAULT NULL,
  `archivo` longblob DEFAULT NULL,
  `f_acuse` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Documentos de asignacion sapa';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_sc`
--

CREATE TABLE `documentos_sc` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de quejas',
  `oficio` varchar(100) DEFAULT NULL,
  `f_oficio` date DEFAULT NULL,
  `f_acuse` date DEFAULT NULL,
  `asunto` varchar(100) NOT NULL,
  `archivo` longblob NOT NULL,
  `persona_id` int(11) DEFAULT NULL COMMENT 'fk de personal',
  `nivel` int(11) DEFAULT NULL,
  `comentario` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Documentos sc';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_sira`
--

CREATE TABLE `documentos_sira` (
  `id` int(11) NOT NULL,
  `acta_id` int(11) NOT NULL COMMENT 'fk de actas',
  `nombre` varchar(255) DEFAULT NULL,
  `comentarios` longtext DEFAULT NULL,
  `archivo` longblob DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de documentos por acta ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_turno`
--

CREATE TABLE `documentos_turno` (
  `id` int(11) NOT NULL,
  `qd_res` int(11) NOT NULL COMMENT 'fk de quejas_respo',
  `oficio` varchar(100) NOT NULL,
  `f_oficio` date DEFAULT NULL,
  `f_acuse` date DEFAULT NULL,
  `asunto` varchar(100) DEFAULT NULL,
  `comentario` longtext DEFAULT NULL,
  `archivo` longblob DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='documentos del turnado de sbid al Jefe ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_guarda`
--

CREATE TABLE `estado_guarda` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalogo de los estado guarda ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `e_turnados`
--

CREATE TABLE `e_turnados` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de tbl quejas',
  `persona_id` int(11) NOT NULL COMMENT 'fk de tbl personal',
  `origen_id` int(11) NOT NULL COMMENT 'fk de areas',
  `t_tramite` enum('NUEVO','ENVIADO') NOT NULL,
  `estado` enum('ACTIVO','VENCIDO') NOT NULL,
  `f_turno` date NOT NULL,
  `oficio_cve` varchar(100) DEFAULT NULL,
  `comentarios` text DEFAULT NULL,
  `of_sapa` varchar(100) DEFAULT NULL,
  `f_sapa` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Asignaciones de las quejas ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `e_vistos`
--

CREATE TABLE `e_vistos` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'FK de tbl quejas',
  `estado` enum('NUEVO','VISTO') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalogo de expedientes visto o nuevo';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `investigadores`
--

CREATE TABLE `investigadores` (
  `id` int(11) NOT NULL,
  `acta_id` int(11) NOT NULL COMMENT 'fk de actas',
  `persona_id` int(11) NOT NULL COMMENT 'FK de personal',
  `rol` enum('INVESTIGADOR','APOYO') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado del personal por acta';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `leyes`
--

CREATE TABLE `leyes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalogo de leyes ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de marcas de vehiculos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE `municipios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `zona` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalogo de municipios ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opiniones_analistas`
--

CREATE TABLE `opiniones_analistas` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL COMMENT 'fk de personal',
  `queja_id` int(11) NOT NULL COMMENT 'fk de quejas',
  `comentario` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Opinión del analista por expediente DR';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_inspeccion`
--

CREATE TABLE `orden_inspeccion` (
  `id` int(11) NOT NULL,
  `t_orden` enum('INSPECCION','VERIFICACION','SUPERVISION','INVESTIGACION','USUARIO SIMULADO','AGENTE ENCUBIERTO') NOT NULL,
  `oficio_id` int(11) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `despachador_id` int(11) DEFAULT NULL,
  `estatus` enum('Cumplida','Parcial sin resultado',' Parcial con resultado','Cumplida sin resultado') DEFAULT NULL,
  `f_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `acuse` longblob DEFAULT NULL,
  `comentario` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ap_pat` varchar(100) NOT NULL,
  `ap_mat` varchar(100) NOT NULL,
  `genero` enum('M','F') NOT NULL,
  `clave` varchar(100) NOT NULL,
  `nivel` enum('TITULAR','DIRECTOR','SUBDIRECTOR','JEFE','ANALISTA','SECRETARIA') NOT NULL,
  `area_id` int(11) NOT NULL,
  `estado` enum('ACTIVO','INACTICO') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Personal adscrito a la UAI';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pista_auditoria`
--

CREATE TABLE `pista_auditoria` (
  `id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `person_id` int(11) NOT NULL COMMENT 'fk de tbl person',
  `tipo` enum('INCIO DE SESIÓN','GUARDAR','EDITAR','ELIMINAR') NOT NULL,
  `sistema` enum('QUEJAS','SIRA','RESPO') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pista de auditoria';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presuntos`
--

CREATE TABLE `presuntos` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de tbl quejas',
  `genero` enum('M','F') NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `procedencia` enum('SS','CPRS') DEFAULT NULL,
  `cargo_id` int(11) DEFAULT NULL COMMENT 'fk de tbl cargos',
  `municipio_id` int(11) NOT NULL COMMENT 'fk de tbl municipios',
  `adscripcion` varchar(100) DEFAULT NULL,
  `subdireccion` int(11) DEFAULT NULL,
  `agrupamiento` int(11) DEFAULT NULL,
  `agencia` varchar(100) DEFAULT NULL,
  `fiscalia` varchar(100) DEFAULT NULL,
  `mesa` varchar(100) DEFAULT NULL,
  `turno` varchar(100) DEFAULT NULL,
  `comentarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de presunto responsables por queja ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procedencias`
--

CREATE TABLE `procedencias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='catalogo de procedencias';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_conductas`
--

CREATE TABLE `p_conductas` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de tbl quejas',
  `conducta_id` int(11) NOT NULL COMMENT 'fk de tbl catalogo_conductas',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Presuntas conductas ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `p_responsables`
--

CREATE TABLE `p_responsables` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ap_pat` varchar(100) NOT NULL,
  `ap_mat` varchar(100) NOT NULL,
  `genero` enum('M','F') NOT NULL,
  `cargo_id` int(11) DEFAULT NULL COMMENT 'FK  de cargos',
  `procedencia` enum('SS','CPRS') NOT NULL,
  `media_f` longtext NOT NULL,
  `acta_id` int(11) NOT NULL COMMENT 'fk de actas',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Presuntos responsables para SIRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quejas`
--

CREATE TABLE `quejas` (
  `id` int(11) NOT NULL,
  `t_asunto` enum('POLICIAL','ESPECIAL') NOT NULL,
  `ref_id` int(11) NOT NULL COMMENT 'fk de tbl tipos_referencia',
  `cve_ref` varchar(60) NOT NULL COMMENT 'clave de la referencia ',
  `n_turno` varchar(50) NOT NULL COMMENT 'numero del turno de la referencia',
  `prioridad` enum('NORMAL','URGENTE') NOT NULL,
  `estado` int(11) NOT NULL COMMENT 'fk del estado_guarda',
  `evidencia` enum('CD','USB','FOTOS','DOCUMENTOS') DEFAULT NULL,
  `fojas` int(11) DEFAULT NULL,
  `procedencia` enum('CPRS','SS') DEFAULT NULL,
  `t_tramite` int(11) NOT NULL COMMENT 'fk de tbl tipos_tramite',
  `cve_exp` varchar(100) NOT NULL,
  `f_hechos` date DEFAULT NULL,
  `h_hechos` time DEFAULT NULL,
  `genero` enum('M','F') NOT NULL,
  `t_afectado` enum('QUEJOSO','DENUNCIANTE','VISTA') NOT NULL,
  `categoria` enum('CIUDADANO','SERVIDOR PUBLICO','OTRO') NOT NULL,
  `d_ano` enum('SI','NO') NOT NULL COMMENT 'denuncia anonima',
  `comentario` varchar(255) DEFAULT NULL,
  `descripcion` longtext DEFAULT NULL,
  `multiple_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de expedientes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quejas_acumuladas`
--

CREATE TABLE `quejas_acumuladas` (
  `id` int(11) NOT NULL,
  `q_original` int(11) NOT NULL COMMENT 'fk de quejas',
  `q_acumulado` int(11) NOT NULL COMMENT 'fk de quejas',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de quejas acumuladas ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quejas_respo`
--

CREATE TABLE `quejas_respo` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de quejas',
  `oficio` varchar(100) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL COMMENT 'Solo si es turnado a SC o QD',
  `comentarios` text DEFAULT NULL,
  `jefatura` int(11) NOT NULL COMMENT 'fk de personal',
  `analista` int(11) NOT NULL COMMENT 'FK de personal',
  `estado` enum('ACTIVO','VENCIDO') NOT NULL,
  `e_procesal` enum('ENVIADO','TRAMITE','DEVUELTO') NOT NULL,
  `autoridad` enum('CHyJ','OIC','SC') DEFAULT NULL,
  `f_acuse` date DEFAULT NULL,
  `n_semana` int(11) DEFAULT NULL,
  `fojas` int(11) DEFAULT NULL,
  `t_doc` enum('ORIGINAL','COPIAS') DEFAULT NULL,
  `f_disponibilidad` date DEFAULT NULL,
  `c_respo` int(11) DEFAULT NULL COMMENT 'fk de  conductas_respo',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de Estados procesal';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quejosos`
--

CREATE TABLE `quejosos` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de quejas',
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `municipio_id` int(11) NOT NULL COMMENT 'FK de municipios',
  `cp` int(11) NOT NULL,
  `n_int` varchar(10) NOT NULL,
  `n_ext` varchar(10) NOT NULL,
  `comentarios` longtext NOT NULL,
  `genero` enum('M','F') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quejosos_sira`
--

CREATE TABLE `quejosos_sira` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `ap_pat` varchar(100) NOT NULL,
  `ap_mat` varchar(100) NOT NULL,
  `genero` enum('M','F') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `acta_id` int(11) NOT NULL COMMENT 'fk de actas',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de presuntos para SIRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencia_queja`
--

CREATE TABLE `referencia_queja` (
  `id` int(11) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `origen` enum('FGJEM','TRIBUNAL','CODHEM','UAI','SS','MUNICIPAL') NOT NULL,
  `tipo` enum('ACTA','AVERIGUACIÓN PREV','NOTICIA','CARPETA INV','CAUSA PENAL','EXPEDIENTE') NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de tbl quejas',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='datos de una averiguación previa';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relacion_oin_actas`
--

CREATE TABLE `relacion_oin_actas` (
  `id` int(11) NOT NULL,
  `oin_id` int(11) NOT NULL COMMENT 'fk de orden_inspeccion',
  `acta_id` int(11) NOT NULL COMMENT 'fk de actas',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='relacion de acta por orden de inspeccion';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de quejas',
  `f_reserva` date DEFAULT NULL,
  `duracion` varchar(30) DEFAULT NULL,
  `comentario` longtext DEFAULT NULL,
  `f_limite` date DEFAULT NULL,
  `archivo` longblob DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de reservas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resoluciones`
--

CREATE TABLE `resoluciones` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de quejas',
  `sancion` enum('SANCIONADO','NO SANCIONADO') NOT NULL,
  `oficio` varchar(100) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` enum('PENDIENTE','RESUELTO','CONCLUIDO') NOT NULL,
  `comentario` longtext DEFAULT NULL,
  `oficio_e` varchar(100) DEFAULT NULL,
  `f_oficio` date DEFAULT NULL,
  `f_acuse` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Resolucion de las demandas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `r_demanda`
--

CREATE TABLE `r_demanda` (
  `id` int(11) NOT NULL,
  `demanda_id` int(11) NOT NULL COMMENT 'fk de demandas',
  `f_resolucion` date DEFAULT NULL,
  `comentario` longtext DEFAULT NULL,
  `estado` enum('SATISFACTORIA','INSATISFACTORIA') DEFAULT NULL,
  `oficio` varchar(100) DEFAULT NULL,
  `f_oficio` date DEFAULT NULL,
  `f_acuse` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='resolucion de las demandas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `submarcas`
--

CREATE TABLE `submarcas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `marca_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de submarcas por marca';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_referencia`
--

CREATE TABLE `tipos_referencia` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catalogo de tipos de referencia';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_tramite`
--

CREATE TABLE `tipos_tramite` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `short_name` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='catalogo de tipos de tramite';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion_referencia`
--

CREATE TABLE `ubicacion_referencia` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de tbl quejas',
  `calle` varchar(100) NOT NULL,
  `e_calle` varchar(100) NOT NULL,
  `y_calle` varchar(100) NOT NULL,
  `edificacion` varchar(100) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `municipio` int(11) NOT NULL COMMENT 'fk de tbl  municipios',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Datos de la ubicacion de las quejas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nick` varchar(50) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `personal_id` int(11) NOT NULL,
  `perfil` enum('TITULAR','DI','QDP','ESPECIAL','SIRA','DR','SAPA','SC') NOT NULL,
  `nivel` enum('TITULAR','DIRECTOR','SUBDIRECTOR','JEFE','ANALISTA','SECRETARIA') NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Listado de cuentas de usuario';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `u_implicadas`
--

CREATE TABLE `u_implicadas` (
  `id` int(11) NOT NULL,
  `queja_id` int(11) NOT NULL COMMENT 'fk de quejas ',
  `procedencia` enum('SS','CPRS') DEFAULT NULL,
  `t_vehiculo` enum('MOTO','CAMIONETA','AUTO','CAMION') NOT NULL,
  `color` int(11) DEFAULT NULL COMMENT 'fk de colores',
  `n_eco` varchar(30) DEFAULT NULL,
  `placas` varchar(20) DEFAULT NULL,
  `comentario` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vehiculos involucrados en una queja';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `u_implicadas_sira`
--

CREATE TABLE `u_implicadas_sira` (
  `id` int(11) NOT NULL,
  `acta_id` int(11) NOT NULL COMMENT 'fk de actas',
  `sub_marca` int(11) DEFAULT NULL COMMENT 'FK de marcas',
  `t_auto` enum('MOTO','CAMIONETA','AUTO','CAMION') DEFAULT NULL,
  `modelo` int(11) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL COMMENT 'fk de colores',
  `placa` varchar(20) DEFAULT NULL,
  `niv` varchar(100) DEFAULT NULL,
  `n_inventario` varchar(100) DEFAULT NULL,
  `corporacion` int(11) DEFAULT NULL COMMENT 'fk de corporaciones',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='listado de unidades implicadas por acta';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vias_recepcion`
--

CREATE TABLE `vias_recepcion` (
  `id` int(11) NOT NULL,
  `via_id` int(11) NOT NULL COMMENT 'fk de tbl vias_recepcion',
  `queja_id` int(11) NOT NULL COMMENT 'fk de tbl quejas',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vias de recepcion por queja';

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actas`
--
ALTER TABLE `actas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `acuerdos_improcedencia`
--
ALTER TABLE `acuerdos_improcedencia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `animales`
--
ALTER TABLE `animales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `apersonamientos`
--
ALTER TABLE `apersonamientos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `armas`
--
ALTER TABLE `armas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `a_responsables`
--
ALTER TABLE `a_responsables`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `catalogo_conductas`
--
ALTER TABLE `catalogo_conductas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ley_id` (`ley_id`);

--
-- Indices de la tabla `catalogo_vias`
--
ALTER TABLE `catalogo_vias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `colores`
--
ALTER TABLE `colores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `conductas_respo`
--
ALTER TABLE `conductas_respo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `demandas`
--
ALTER TABLE `demandas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos_oin`
--
ALTER TABLE `documentos_oin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos_qr`
--
ALTER TABLE `documentos_qr`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos_quejas`
--
ALTER TABLE `documentos_quejas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos_sapa`
--
ALTER TABLE `documentos_sapa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos_sc`
--
ALTER TABLE `documentos_sc`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos_sira`
--
ALTER TABLE `documentos_sira`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos_turno`
--
ALTER TABLE `documentos_turno`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_guarda`
--
ALTER TABLE `estado_guarda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `e_turnados`
--
ALTER TABLE `e_turnados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `queja_id` (`queja_id`),
  ADD KEY `persona_id` (`persona_id`),
  ADD KEY `origen_id` (`origen_id`);

--
-- Indices de la tabla `e_vistos`
--
ALTER TABLE `e_vistos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `investigadores`
--
ALTER TABLE `investigadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acta_id` (`acta_id`),
  ADD KEY `persona_id` (`persona_id`);

--
-- Indices de la tabla `leyes`
--
ALTER TABLE `leyes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `opiniones_analistas`
--
ALTER TABLE `opiniones_analistas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orden_inspeccion`
--
ALTER TABLE `orden_inspeccion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area_id` (`area_id`);

--
-- Indices de la tabla `pista_auditoria`
--
ALTER TABLE `pista_auditoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `presuntos`
--
ALTER TABLE `presuntos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `municipio_id` (`municipio_id`),
  ADD KEY `cargo_id` (`cargo_id`);

--
-- Indices de la tabla `procedencias`
--
ALTER TABLE `procedencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `p_conductas`
--
ALTER TABLE `p_conductas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `queja_id` (`queja_id`),
  ADD KEY `conducta_id` (`conducta_id`);

--
-- Indices de la tabla `p_responsables`
--
ALTER TABLE `p_responsables`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `quejas`
--
ALTER TABLE `quejas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cve_exp` (`cve_exp`),
  ADD KEY `ref_id` (`ref_id`),
  ADD KEY `estado` (`estado`);

--
-- Indices de la tabla `quejas_acumuladas`
--
ALTER TABLE `quejas_acumuladas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `q_original` (`q_original`),
  ADD KEY `q_acumulado` (`q_acumulado`);

--
-- Indices de la tabla `quejas_respo`
--
ALTER TABLE `quejas_respo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `quejosos`
--
ALTER TABLE `quejosos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `queja_id` (`queja_id`),
  ADD KEY `municipio_id` (`municipio_id`);

--
-- Indices de la tabla `quejosos_sira`
--
ALTER TABLE `quejosos_sira`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `relacion_oin_actas`
--
ALTER TABLE `relacion_oin_actas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `resoluciones`
--
ALTER TABLE `resoluciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `queja_id` (`queja_id`);

--
-- Indices de la tabla `r_demanda`
--
ALTER TABLE `r_demanda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `submarcas`
--
ALTER TABLE `submarcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_referencia`
--
ALTER TABLE `tipos_referencia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_tramite`
--
ALTER TABLE `tipos_tramite`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ubicacion_referencia`
--
ALTER TABLE `ubicacion_referencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `municipio` (`municipio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personal_id` (`personal_id`);

--
-- Indices de la tabla `u_implicadas`
--
ALTER TABLE `u_implicadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `queja_id` (`queja_id`);

--
-- Indices de la tabla `u_implicadas_sira`
--
ALTER TABLE `u_implicadas_sira`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vias_recepcion`
--
ALTER TABLE `vias_recepcion`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actas`
--
ALTER TABLE `actas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `acuerdos_improcedencia`
--
ALTER TABLE `acuerdos_improcedencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `animales`
--
ALTER TABLE `animales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `apersonamientos`
--
ALTER TABLE `apersonamientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `armas`
--
ALTER TABLE `armas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `a_responsables`
--
ALTER TABLE `a_responsables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catalogo_conductas`
--
ALTER TABLE `catalogo_conductas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `catalogo_vias`
--
ALTER TABLE `catalogo_vias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `colores`
--
ALTER TABLE `colores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conductas_respo`
--
ALTER TABLE `conductas_respo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `demandas`
--
ALTER TABLE `demandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos_oin`
--
ALTER TABLE `documentos_oin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos_qr`
--
ALTER TABLE `documentos_qr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos_quejas`
--
ALTER TABLE `documentos_quejas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos_sapa`
--
ALTER TABLE `documentos_sapa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos_sc`
--
ALTER TABLE `documentos_sc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos_sira`
--
ALTER TABLE `documentos_sira`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos_turno`
--
ALTER TABLE `documentos_turno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_guarda`
--
ALTER TABLE `estado_guarda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `e_turnados`
--
ALTER TABLE `e_turnados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `e_vistos`
--
ALTER TABLE `e_vistos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `investigadores`
--
ALTER TABLE `investigadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `leyes`
--
ALTER TABLE `leyes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opiniones_analistas`
--
ALTER TABLE `opiniones_analistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orden_inspeccion`
--
ALTER TABLE `orden_inspeccion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pista_auditoria`
--
ALTER TABLE `pista_auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `presuntos`
--
ALTER TABLE `presuntos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `procedencias`
--
ALTER TABLE `procedencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_conductas`
--
ALTER TABLE `p_conductas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `p_responsables`
--
ALTER TABLE `p_responsables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quejas`
--
ALTER TABLE `quejas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quejas_acumuladas`
--
ALTER TABLE `quejas_acumuladas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quejas_respo`
--
ALTER TABLE `quejas_respo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quejosos`
--
ALTER TABLE `quejosos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quejosos_sira`
--
ALTER TABLE `quejosos_sira`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `relacion_oin_actas`
--
ALTER TABLE `relacion_oin_actas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resoluciones`
--
ALTER TABLE `resoluciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `r_demanda`
--
ALTER TABLE `r_demanda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `submarcas`
--
ALTER TABLE `submarcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_referencia`
--
ALTER TABLE `tipos_referencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_tramite`
--
ALTER TABLE `tipos_tramite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ubicacion_referencia`
--
ALTER TABLE `ubicacion_referencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `u_implicadas`
--
ALTER TABLE `u_implicadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `u_implicadas_sira`
--
ALTER TABLE `u_implicadas_sira`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vias_recepcion`
--
ALTER TABLE `vias_recepcion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `catalogo_conductas`
--
ALTER TABLE `catalogo_conductas`
  ADD CONSTRAINT `catalogo_conductas_ibfk_1` FOREIGN KEY (`ley_id`) REFERENCES `leyes` (`id`);

--
-- Filtros para la tabla `e_turnados`
--
ALTER TABLE `e_turnados`
  ADD CONSTRAINT `e_turnados_ibfk_1` FOREIGN KEY (`queja_id`) REFERENCES `quejas` (`id`),
  ADD CONSTRAINT `e_turnados_ibfk_2` FOREIGN KEY (`persona_id`) REFERENCES `personal` (`id`),
  ADD CONSTRAINT `e_turnados_ibfk_3` FOREIGN KEY (`origen_id`) REFERENCES `areas` (`id`);

--
-- Filtros para la tabla `investigadores`
--
ALTER TABLE `investigadores`
  ADD CONSTRAINT `investigadores_ibfk_1` FOREIGN KEY (`acta_id`) REFERENCES `actas` (`id`),
  ADD CONSTRAINT `investigadores_ibfk_2` FOREIGN KEY (`persona_id`) REFERENCES `personal` (`id`);

--
-- Filtros para la tabla `personal`
--
ALTER TABLE `personal`
  ADD CONSTRAINT `personal_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`);

--
-- Filtros para la tabla `presuntos`
--
ALTER TABLE `presuntos`
  ADD CONSTRAINT `presuntos_ibfk_1` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id`),
  ADD CONSTRAINT `presuntos_ibfk_2` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`);

--
-- Filtros para la tabla `p_conductas`
--
ALTER TABLE `p_conductas`
  ADD CONSTRAINT `p_conductas_ibfk_1` FOREIGN KEY (`queja_id`) REFERENCES `quejas` (`id`),
  ADD CONSTRAINT `p_conductas_ibfk_2` FOREIGN KEY (`conducta_id`) REFERENCES `catalogo_conductas` (`id`);

--
-- Filtros para la tabla `quejas`
--
ALTER TABLE `quejas`
  ADD CONSTRAINT `quejas_ibfk_1` FOREIGN KEY (`ref_id`) REFERENCES `tipos_referencia` (`id`),
  ADD CONSTRAINT `quejas_ibfk_2` FOREIGN KEY (`estado`) REFERENCES `estado_guarda` (`id`);

--
-- Filtros para la tabla `quejas_acumuladas`
--
ALTER TABLE `quejas_acumuladas`
  ADD CONSTRAINT `quejas_acumuladas_ibfk_1` FOREIGN KEY (`q_original`) REFERENCES `quejas` (`id`),
  ADD CONSTRAINT `quejas_acumuladas_ibfk_2` FOREIGN KEY (`q_acumulado`) REFERENCES `quejas` (`id`);

--
-- Filtros para la tabla `quejosos`
--
ALTER TABLE `quejosos`
  ADD CONSTRAINT `quejosos_ibfk_1` FOREIGN KEY (`queja_id`) REFERENCES `quejas` (`id`),
  ADD CONSTRAINT `quejosos_ibfk_2` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id`);

--
-- Filtros para la tabla `resoluciones`
--
ALTER TABLE `resoluciones`
  ADD CONSTRAINT `resoluciones_ibfk_1` FOREIGN KEY (`queja_id`) REFERENCES `quejas` (`id`);

--
-- Filtros para la tabla `ubicacion_referencia`
--
ALTER TABLE `ubicacion_referencia`
  ADD CONSTRAINT `ubicacion_referencia_ibfk_1` FOREIGN KEY (`municipio`) REFERENCES `municipios` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`);

--
-- Filtros para la tabla `u_implicadas`
--
ALTER TABLE `u_implicadas`
  ADD CONSTRAINT `u_implicadas_ibfk_1` FOREIGN KEY (`queja_id`) REFERENCES `quejas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
