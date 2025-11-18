DROP TABLE IF EXISTS `actividad`;

CREATE TABLE `actividad` (
  `idActividad` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `tipo` enum('Física','Alimentaria','Mental','Otro') DEFAULT 'Otro',
  `descripcion` text DEFAULT NULL,
  `frecuencia` varchar(80) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idActividad`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO actividad VALUES("1","Caminata matutina","Física","Caminar a paso ligero por 30 minutos al despertar.","Diaria","2025-11-18 13:05:44");
INSERT INTO actividad VALUES("2","Dieta Mediterránea","Alimentaria","Seguir plan basado en aceite de oliva, frutas y verduras.","Semanal","2025-11-18 13:05:44");
INSERT INTO actividad VALUES("3","Mindfulness","Mental","15 minutos de atención plena antes de dormir.","Diaria","2025-11-18 13:05:44");
INSERT INTO actividad VALUES("4","Natación","Física","Sesión de 45 minutos estilo libre.","3 veces/semana","2025-11-18 13:05:44");
INSERT INTO actividad VALUES("5","Registro de Comidas","Alimentaria","Anotar todas las calorías consumidas en la app.","Diaria","2025-11-18 13:05:44");
INSERT INTO actividad VALUES("6","Yoga para Principiantes","Física","Rutina de estiramiento y relajación.","2 veces/semana","2025-11-18 13:05:44");
INSERT INTO actividad VALUES("7","Hidratación 2L","Alimentaria","Beber al menos 2 litros de agua natural.","Diaria","2025-11-18 13:05:44");
INSERT INTO actividad VALUES("8","Lectura Recreativa","Mental","Leer 20 páginas de un libro para reducir estrés.","Diaria","2025-11-18 13:05:44");
INSERT INTO actividad VALUES("9","Crossfit Básico","Física","Entrenamiento de alta intensidad supervisado.","3 veces/semana","2025-11-18 13:05:44");
INSERT INTO actividad VALUES("10","Ayuno Intermitente","Alimentaria","Ventana de alimentación de 8 horas (consultar médico).","Diaria","2025-11-18 13:05:44");



DROP TABLE IF EXISTS `actividadpaciente`;

CREATE TABLE `actividadpaciente` (
  `idAsignacion` int(11) NOT NULL AUTO_INCREMENT,
  `idActividad` int(11) NOT NULL,
  `idPaciente` int(11) NOT NULL,
  `idMedico` int(11) DEFAULT NULL,
  `fechaAsignacion` date NOT NULL,
  `fechaInicio` date DEFAULT NULL,
  `fechaFin` date DEFAULT NULL,
  `progreso` decimal(5,2) DEFAULT 0.00,
  `estado` enum('Activa','Completada','Cancelada') DEFAULT 'Activa',
  `observaciones` text DEFAULT NULL,
  PRIMARY KEY (`idAsignacion`),
  KEY `idActividad` (`idActividad`),
  KEY `idPaciente` (`idPaciente`),
  KEY `idMedico` (`idMedico`),
  CONSTRAINT `actividadpaciente_ibfk_1` FOREIGN KEY (`idActividad`) REFERENCES `actividad` (`idActividad`) ON UPDATE CASCADE,
  CONSTRAINT `actividadpaciente_ibfk_2` FOREIGN KEY (`idPaciente`) REFERENCES `paciente` (`idPaciente`) ON UPDATE CASCADE,
  CONSTRAINT `actividadpaciente_ibfk_3` FOREIGN KEY (`idMedico`) REFERENCES `medico` (`idMedico`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO actividadpaciente VALUES("1","1","1","1","2025-11-18","2025-11-18","","50.00","Activa","");
INSERT INTO actividadpaciente VALUES("2","7","1","1","2025-11-18","2025-11-18","","100.00","Completada","");
INSERT INTO actividadpaciente VALUES("3","3","4","4","2025-11-18","2025-11-18","","0.00","Activa","");
INSERT INTO actividadpaciente VALUES("4","4","5","5","2025-11-18","2025-11-18","","20.00","Activa","");
INSERT INTO actividadpaciente VALUES("5","2","6","1","2025-11-18","2025-11-18","","80.00","Activa","");



DROP TABLE IF EXISTS `administrador`;

CREATE TABLE `administrador` (
  `idAdmin` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `apellidoPaterno` varchar(100) NOT NULL,
  `apellidoMaterno` varchar(100) DEFAULT NULL,
  `correo` varchar(150) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `permisos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permisos`)),
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `estado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idAdmin`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO administrador VALUES("1","Super","Admin","","admin@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Sistemas","[\"all\"]","2025-11-18 13:05:44","1");
INSERT INTO administrador VALUES("2","Laura","Gómez","","laura.gomez@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","RRHH","[\"usuarios\"]","2025-11-18 13:05:44","1");
INSERT INTO administrador VALUES("3","Roberto","Díaz","","roberto.diaz@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Dirección Médica","[\"reportes\", \"medicos\"]","2025-11-18 13:05:44","1");
INSERT INTO administrador VALUES("4","Soporte","Técnico","","soporte@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","IT","[\"backups\", \"usuarios\"]","2025-11-18 13:05:44","1");
INSERT INTO administrador VALUES("5","Marta","Sánchez","","marta.sanchez@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Administración","[\"reportes\"]","2025-11-18 13:05:44","1");



DROP TABLE IF EXISTS `cita`;

CREATE TABLE `cita` (
  `idCita` int(11) NOT NULL AUTO_INCREMENT,
  `idPaciente` int(11) NOT NULL,
  `idMedico` int(11) NOT NULL,
  `fechaHora` datetime NOT NULL,
  `duracionMin` int(11) DEFAULT 30,
  `motivo` varchar(255) DEFAULT NULL,
  `estado` enum('Programada','Completada','Cancelada','NoAsistida') DEFAULT 'Programada',
  `creadoEn` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idCita`),
  KEY `idPaciente` (`idPaciente`),
  KEY `idMedico` (`idMedico`),
  CONSTRAINT `cita_ibfk_1` FOREIGN KEY (`idPaciente`) REFERENCES `paciente` (`idPaciente`) ON UPDATE CASCADE,
  CONSTRAINT `cita_ibfk_2` FOREIGN KEY (`idMedico`) REFERENCES `medico` (`idMedico`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO cita VALUES("1","1","1","2025-11-08 13:05:44","30","Evaluación inicial","Completada","2025-11-18 13:05:44");
INSERT INTO cita VALUES("2","2","2","2025-11-13 13:05:44","30","Chequeo presión arterial","Completada","2025-11-18 13:05:44");
INSERT INTO cita VALUES("3","3","3","2025-11-16 13:05:44","30","Dolor de estómago","Completada","2025-11-18 13:05:44");
INSERT INTO cita VALUES("4","4","4","2025-11-18 13:05:44","30","Consulta de ansiedad","Programada","2025-11-18 13:05:44");
INSERT INTO cita VALUES("5","1","1","2025-11-20 13:05:44","30","Seguimiento nutricional","Programada","2025-11-18 13:05:44");
INSERT INTO cita VALUES("6","5","5","2025-11-21 13:05:44","30","Lesión de rodilla","Programada","2025-11-18 13:05:44");
INSERT INTO cita VALUES("7","6","1","2025-11-23 13:05:44","30","Control de peso","Programada","2025-11-18 13:05:44");
INSERT INTO cita VALUES("8","7","7","2025-11-25 13:05:44","30","Revisión glucosa","Programada","2025-11-18 13:05:44");
INSERT INTO cita VALUES("9","8","6","2025-11-28 13:05:44","30","Consulta pediátrica","Programada","2025-11-18 13:05:44");
INSERT INTO cita VALUES("10","9","2","2025-12-03 13:05:44","30","Prueba de esfuerzo","Programada","2025-11-18 13:05:44");
INSERT INTO cita VALUES("11","1","1","2025-10-19 13:05:44","30","Primera consulta","Completada","2025-11-18 13:05:44");
INSERT INTO cita VALUES("12","2","2","2025-10-29 13:05:44","30","Revisión rutinaria","NoAsistida","2025-11-18 13:05:44");



DROP TABLE IF EXISTS `foro`;

CREATE TABLE `foro` (
  `idPublicacion` int(11) NOT NULL AUTO_INCREMENT,
  `idPaciente` int(11) DEFAULT NULL,
  `idMedico` int(11) DEFAULT NULL,
  `idAdmin` int(11) DEFAULT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `contenido` text NOT NULL,
  `imagenURL` varchar(255) DEFAULT NULL,
  `fechaPublicacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`idPublicacion`),
  KEY `idPaciente` (`idPaciente`),
  KEY `idMedico` (`idMedico`),
  KEY `idAdmin` (`idAdmin`),
  CONSTRAINT `foro_ibfk_1` FOREIGN KEY (`idPaciente`) REFERENCES `paciente` (`idPaciente`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `foro_ibfk_2` FOREIGN KEY (`idMedico`) REFERENCES `medico` (`idMedico`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `foro_ibfk_3` FOREIGN KEY (`idAdmin`) REFERENCES `administrador` (`idAdmin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO foro VALUES("1","1","","","¡Empezando mi cambio!","Hoy inicié con el plan de caminata. Me siento muy motivado.","","2025-11-13 13:05:44");
INSERT INTO foro VALUES("2","2","","","Duda sobre el sodio","¿Alguien sabe qué sales son bajas en sodio recomendables?","","2025-11-15 13:05:44");
INSERT INTO foro VALUES("3","","1","","Tip Nutricional del Día","No olviden hidratarse, al menos 2L de agua al día mejora su metabolismo.","","2025-11-16 13:05:44");
INSERT INTO foro VALUES("4","","4","","Salud Mental","El descanso es tan importante como el ejercicio. Duerman sus 8 horas.","","2025-11-17 13:05:44");
INSERT INTO foro VALUES("5","","","1","Mantenimiento del Sistema","El sistema estará en mantenimiento este sábado por la noche. Gracias por su comprensión.","","2025-11-18 13:05:44");



DROP TABLE IF EXISTS `fororeaccion`;

CREATE TABLE `fororeaccion` (
  `idReaccion` int(11) NOT NULL AUTO_INCREMENT,
  `idPublicacion` int(11) NOT NULL,
  `idPaciente` int(11) DEFAULT NULL,
  `idMedico` int(11) DEFAULT NULL,
  `idAdmin` int(11) DEFAULT NULL,
  PRIMARY KEY (`idReaccion`),
  KEY `idPublicacion` (`idPublicacion`),
  KEY `idPaciente` (`idPaciente`),
  KEY `idMedico` (`idMedico`),
  KEY `idAdmin` (`idAdmin`),
  CONSTRAINT `fororeaccion_ibfk_1` FOREIGN KEY (`idPublicacion`) REFERENCES `foro` (`idPublicacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fororeaccion_ibfk_2` FOREIGN KEY (`idPaciente`) REFERENCES `paciente` (`idPaciente`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fororeaccion_ibfk_3` FOREIGN KEY (`idMedico`) REFERENCES `medico` (`idMedico`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fororeaccion_ibfk_4` FOREIGN KEY (`idAdmin`) REFERENCES `administrador` (`idAdmin`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO fororeaccion VALUES("1","1","2","","");
INSERT INTO fororeaccion VALUES("2","1","3","","");
INSERT INTO fororeaccion VALUES("3","3","1","","");
INSERT INTO fororeaccion VALUES("4","3","2","","");
INSERT INTO fororeaccion VALUES("5","4","1","","");
INSERT INTO fororeaccion VALUES("6","1","","1","");
INSERT INTO fororeaccion VALUES("7","2","","2","");
INSERT INTO fororeaccion VALUES("8","3","","","1");



DROP TABLE IF EXISTS `medico`;

CREATE TABLE `medico` (
  `idMedico` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `apellidoPaterno` varchar(100) NOT NULL,
  `apellidoMaterno` varchar(100) DEFAULT NULL,
  `correo` varchar(150) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `cedulaProfesional` varchar(50) DEFAULT NULL,
  `disponibilidad` varchar(100) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `estado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idMedico`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO medico VALUES("1","Alejandro","Ruiz","Sola","dr.ruiz@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Nutrición Clínica","5512345678","CED-001","Matutino","2025-11-18 13:05:44","1");
INSERT INTO medico VALUES("2","Beatriz","Mendoza","Lara","dra.mendoza@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Cardiología","5587654321","CED-002","Vespertino","2025-11-18 13:05:44","1");
INSERT INTO medico VALUES("3","Carlos","Fuentes","Ochoa","dr.fuentes@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Medicina General","5555555555","CED-003","Ambos","2025-11-18 13:05:44","1");
INSERT INTO medico VALUES("4","Diana","Pérez","Gil","dra.perez@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Psicología","5544332211","CED-004","Matutino","2025-11-18 13:05:44","1");
INSERT INTO medico VALUES("5","Ernesto","Guevara","Rojas","dr.guevara@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Deportología","5566778899","CED-005","Vespertino","2025-11-18 13:05:44","1");
INSERT INTO medico VALUES("6","Fernanda","López","Mora","dra.lopez@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Nutrición Pediátrica","5599887766","CED-006","Ambos","2025-11-18 13:05:44","1");
INSERT INTO medico VALUES("7","Gabriel","Soto","Vargas","dr.soto@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Endocrinología","5522446688","CED-007","Matutino","2025-11-18 13:05:44","1");
INSERT INTO medico VALUES("8","Hilda","Correa","Nieto","dra.correa@healthyview.com","$2y$10$lC269lvyMq/cmJm7LMPOA.AgxJ5IoKNqZPDAQjLG8sF.nqIvoX/WO","Medicina Interna","5511335577","CED-008","Vespertino","2025-11-18 13:05:44","1");
INSERT INTO medico VALUES("9","Ignacio","Loyola","Téllez","dr.loyola@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Gastroenterología","5500998877","CED-009","Ambos","2025-11-18 13:05:44","1");
INSERT INTO medico VALUES("10","Julia","Marín","Cano","dra.marin@healthyview.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","Nutrición Deportiva","5577665544","CED-010","Matutino","2025-11-18 13:05:44","1");



DROP TABLE IF EXISTS `paciente`;

CREATE TABLE `paciente` (
  `idPaciente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `apellidoPaterno` varchar(100) NOT NULL,
  `apellidoMaterno` varchar(100) DEFAULT NULL,
  `correo` varchar(150) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `genero` enum('Masculino','Femenino','Otro') DEFAULT 'Otro',
  `peso` decimal(5,2) DEFAULT NULL,
  `estatura` decimal(4,2) DEFAULT NULL,
  `imc` decimal(5,2) DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `estado` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idPaciente`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO paciente VALUES("1","Juan","Pérez","","juan.perez@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1990-01-01","Masculino","85.50","1.75","27.92","","5511111111","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("2","Ana","López","","ana.lopez@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1995-05-15","Femenino","60.00","1.65","22.04","","5522222222","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("3","Luis","García","","luis.garcia@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1988-11-20","Masculino","95.00","1.80","29.32","","5533333333","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("4","María","Rodríguez","","maria.rodriguez@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","2000-03-10","Femenino","55.00","1.60","21.48","","5544444444","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("5","Pedro","Martínez","","pedro.martinez@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1992-07-07","Masculino","78.00","1.72","26.37","","5555555555","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("6","Sofía","Hernández","","sofia.hernandez@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1998-09-25","Femenino","68.00","1.58","27.24","","5566666666","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("7","Jorge","González","","jorge.gonzalez@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1985-12-30","Masculino","102.00","1.78","32.19","","5577777777","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("8","Lucía","Pérez","","lucia.perez@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","2002-02-14","Femenino","58.00","1.62","22.10","","5588888888","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("9","Raúl","Sánchez","","raul.sanchez@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1993-06-18","Masculino","82.00","1.76","26.47","","5599999999","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("10","Elena","Ramírez","","elena.ramirez@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1996-08-05","Femenino","63.00","1.68","22.32","","5500000000","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("11","Miguel","Torres","","miguel.torres@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1991-04-22","Masculino","88.00","1.82","26.57","","5512121212","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("12","Carmen","Flores","","carmen.flores@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1999-10-10","Femenino","70.00","1.65","25.71","","5523232323","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("13","David","Rivera","","david.rivera@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1987-01-15","Masculino","92.00","1.79","28.71","","5534343434","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("14","Laura","Gómez","","laura.g@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","2001-05-20","Femenino","52.00","1.55","21.64","","5545454545","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("15","Javier","Díaz","","javier.diaz@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1994-11-11","Masculino","80.00","1.74","26.42","","5556565656","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("16","Patricia","Vargas","","patricia.vargas@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1990-03-03","Femenino","65.00","1.63","24.46","","5567676767","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("17","Daniel","Castillo","","daniel.castillo@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1989-07-28","Masculino","86.00","1.81","26.25","","5578787878","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("18","Isabel","Morales","","isabel.morales@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1997-09-09","Femenino","60.00","1.60","23.44","","5589898989","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("19","Fernando","Ortega","","fernando.ortega@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","1995-12-12","Masculino","90.00","1.83","26.87","","5590909090","2025-11-18 13:05:44","1");
INSERT INTO paciente VALUES("20","Adriana","Reyes","","adriana.reyes@mail.com","$2y$10$VBu/GZZSAJ4odbmxKH8bnuhr.FTeLMhEhFJ3RSwNJYH/9PqHxU/Vm","2003-04-01","Femenino","56.00","1.59","22.15","","5501010101","2025-11-18 13:05:44","1");



DROP TABLE IF EXISTS `receta`;

CREATE TABLE `receta` (
  `idReceta` int(11) NOT NULL AUTO_INCREMENT,
  `idPaciente` int(11) NOT NULL,
  `idMedico` int(11) NOT NULL,
  `fechaEmision` datetime NOT NULL DEFAULT current_timestamp(),
  `resumen` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('Activa','Finalizada','Cancelada') DEFAULT 'Activa',
  PRIMARY KEY (`idReceta`),
  KEY `idPaciente` (`idPaciente`),
  KEY `idMedico` (`idMedico`),
  CONSTRAINT `receta_ibfk_1` FOREIGN KEY (`idPaciente`) REFERENCES `paciente` (`idPaciente`) ON UPDATE CASCADE,
  CONSTRAINT `receta_ibfk_2` FOREIGN KEY (`idMedico`) REFERENCES `medico` (`idMedico`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO receta VALUES("1","1","1","2025-11-08 13:05:44","Plan Hipocalórico","Reducir azúcares refinados.","Activa");
INSERT INTO receta VALUES("2","2","2","2025-11-13 13:05:44","Control Hipertensión","Monitorear presión diariamente.","Activa");



DROP TABLE IF EXISTS `recetaitem`;

CREATE TABLE `recetaitem` (
  `idItem` int(11) NOT NULL AUTO_INCREMENT,
  `idReceta` int(11) NOT NULL,
  `nombreMedicamento` varchar(200) NOT NULL,
  `dosis` varchar(100) DEFAULT NULL,
  `frecuencia` varchar(100) DEFAULT NULL,
  `duracion` varchar(50) DEFAULT NULL,
  `instrucciones` text DEFAULT NULL,
  PRIMARY KEY (`idItem`),
  KEY `idReceta` (`idReceta`),
  CONSTRAINT `recetaitem_ibfk_1` FOREIGN KEY (`idReceta`) REFERENCES `receta` (`idReceta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO recetaitem VALUES("1","1","Multivitamínico","1 tableta","Cada 24 hrs","30 días","Tomar con el desayuno");
INSERT INTO recetaitem VALUES("2","1","Omega 3","1 cápsula","Cada 12 hrs","60 días","Junto con alimentos");
INSERT INTO recetaitem VALUES("3","2","Losartán","50 mg","Cada 24 hrs","Permanente","Por la mañana");
INSERT INTO recetaitem VALUES("4","2","Amlodipino","5 mg","Cada 24 hrs","Permanente","Por la noche");



DROP TABLE IF EXISTS `seguimiento`;

CREATE TABLE `seguimiento` (
  `idSeguimiento` int(11) NOT NULL AUTO_INCREMENT,
  `idPaciente` int(11) NOT NULL,
  `idMedico` int(11) DEFAULT NULL,
  `fechaRegistro` datetime DEFAULT current_timestamp(),
  `peso` decimal(5,2) DEFAULT NULL,
  `imc` decimal(5,2) DEFAULT NULL,
  `nivelBienestar` enum('Excelente','Bueno','Regular','Malo') DEFAULT 'Regular',
  `observaciones` text DEFAULT NULL,
  PRIMARY KEY (`idSeguimiento`),
  KEY `idPaciente` (`idPaciente`),
  KEY `idMedico` (`idMedico`),
  CONSTRAINT `seguimiento_ibfk_1` FOREIGN KEY (`idPaciente`) REFERENCES `paciente` (`idPaciente`) ON UPDATE CASCADE,
  CONSTRAINT `seguimiento_ibfk_2` FOREIGN KEY (`idMedico`) REFERENCES `medico` (`idMedico`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO seguimiento VALUES("1","1","1","2025-10-19 13:05:44","90.00","29.38","Malo","Paciente con sobrepeso inicial.");
INSERT INTO seguimiento VALUES("2","1","1","2025-11-08 13:05:44","87.00","28.40","Regular","Ha bajado 3kg, buen progreso.");
INSERT INTO seguimiento VALUES("3","1","1","2025-11-18 13:05:44","85.50","27.92","Bueno","Continúa bajando, ánimo mejorado.");
INSERT INTO seguimiento VALUES("4","2","2","2025-11-13 13:05:44","60.00","22.04","Excelente","Presión estable y peso ideal.");



DROP TABLE IF EXISTS `sesion`;

CREATE TABLE `sesion` (
  `idSesion` int(11) NOT NULL AUTO_INCREMENT,
  `tipoUsuario` enum('Paciente','Medico','Administrador') NOT NULL,
  `idPaciente` int(11) DEFAULT NULL,
  `idMedico` int(11) DEFAULT NULL,
  `idAdmin` int(11) DEFAULT NULL,
  `fechaInicio` datetime NOT NULL DEFAULT current_timestamp(),
  `fechaFin` datetime DEFAULT NULL,
  `ipAcceso` varchar(45) DEFAULT NULL,
  `estado` enum('Activa','Cerrada','Fallida') DEFAULT 'Activa',
  PRIMARY KEY (`idSesion`),
  KEY `idPaciente` (`idPaciente`),
  KEY `idMedico` (`idMedico`),
  KEY `idAdmin` (`idAdmin`),
  CONSTRAINT `sesion_ibfk_1` FOREIGN KEY (`idPaciente`) REFERENCES `paciente` (`idPaciente`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sesion_ibfk_2` FOREIGN KEY (`idMedico`) REFERENCES `medico` (`idMedico`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sesion_ibfk_3` FOREIGN KEY (`idAdmin`) REFERENCES `administrador` (`idAdmin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




