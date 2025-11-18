<?php
/**
 * Modelo para todas las acciones del Médico.
 * Interactúa con las tablas 'cita', 'paciente', 'seguimiento', 'receta', 'actividad', etc.
 */

class MedicoModel {
    private $connection;

    // Constructor: recibe la conexión
    public function __construct($connection) {
        $this->connection = $connection;
    }

    /**
     * Obtiene la lista de citas de un médico específico
     * @return array
     */
    public function getCitasMedico($idMedico) {
        $sql = "SELECT
                    c.idCita,
                    c.fechaHora,
                    c.motivo,
                    c.duracionMin,
                    c.estado,
                    CONCAT(p.nombre, ' ', p.apellidoPaterno) as pacienteNombre,
                    p.idPaciente,
                    p.telefono
                FROM cita c
                JOIN paciente p ON c.idPaciente = p.idPaciente
                WHERE c.idMedico = ?
                ORDER BY c.fechaHora ASC";

        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("i", $idMedico);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            return [];
        }
    }
    
    /**
     * Obtiene los datos resumidos para el dashboard del médico.
     * @return array
     */
    public function getDashboardData($idMedico) {
        $hoy_inicio = date('Y-m-d 00:00:00');
        $hoy_fin = date('Y-m-d 23:59:59');

        $sql = "SELECT
                    c.idCita,
                    c.fechaHora,
                    c.motivo,
                    c.estado,
                    CONCAT(p.nombre, ' ', p.apellidoPaterno) as pacienteNombre
                FROM cita c
                JOIN paciente p ON c.idPaciente = p.idPaciente
                WHERE c.idMedico = ?
                AND c.fechaHora BETWEEN ? AND ?
                AND c.estado = 'Programada'
                ORDER BY c.fechaHora ASC
                LIMIT 5";

        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("iss", $idMedico, $hoy_inicio, $hoy_fin);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            return [];
        }
    }

    /**
     * Obtiene una lista única de pacientes asociados a un médico
     * MODIFICADO: Ahora incluye el peso inicial y el peso más reciente (para mostrar ).
     * @return array
     */
    public function getPacientesDelMedico($idMedico) {
        // Usamos DISTINCT para obtener cada paciente solo una vez
        $sql = "SELECT DISTINCT
                    p.idPaciente,
                    p.nombre,
                    p.apellidoPaterno,
                    p.correo,
                    p.telefono,
                    p.peso AS pesoActual, -- El peso más reciente guardado en la tabla paciente
                    
                    -- Subconsulta para obtener el PRIMER peso registrado
                    (SELECT s1.peso 
                     FROM seguimiento s1 
                     WHERE s1.idPaciente = p.idPaciente 
                     ORDER BY s1.fechaRegistro ASC 
                     LIMIT 1) AS pesoInicial
                     
                FROM paciente p
                JOIN cita c ON p.idPaciente = c.idPaciente
                WHERE c.idMedico = ?
                ORDER BY p.apellidoPaterno ASC, p.nombre ASC";

        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("i", $idMedico);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            return [];
        }
    }

    /**
     * Obtiene los datos de perfil de un paciente específico.
     * @return array|null
     */
    public function getDatosPacienteParaMedico($idPaciente) {
        // MODIFICADO: Añadido 'imc'
        $sql = "SELECT idPaciente, nombre, apellidoPaterno, apellidoMaterno, correo, 
                       fechaNacimiento, genero, telefono, 
                       peso, estatura, imc, diagnostico 
                FROM paciente 
                WHERE idPaciente = ? 
                LIMIT 1";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("i", $idPaciente);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            return null;
        }
    }

    /**
     * Obtiene el historial de seguimientos de un paciente.
     * @return array
     */
    public function getHistorialSeguimiento($idPaciente) {
        $sql = "SELECT fechaRegistro, peso, imc, nivelBienestar, observaciones 
                FROM seguimiento 
                WHERE idPaciente = ? 
                ORDER BY fechaRegistro DESC";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("i", $idPaciente);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            return [];
        }
    }

    /**
     * Inserta un nuevo registro de seguimiento en el historial del paciente (RFN-11).
     * @return bool
     */
    public function registrarSeguimiento($idPaciente, $idMedico, $peso, $imc, $nivelBienestar, $observaciones) {
        $sql = "INSERT INTO seguimiento (idPaciente, idMedico, peso, imc, nivelBienestar, observaciones) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("iiddss", $idPaciente, $idMedico, $peso, $imc, $nivelBienestar, $observaciones);
            $exito = $statement->execute();
            $statement->close();
            return $exito;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    /**
     * Actualiza el peso, estatura e imc en la tabla principal del paciente.
     * @return bool
     */
    public function updatePacientePesoEstatura($idPaciente, $peso, $estatura, $imc) {
        // MODIFICADO: Añadido 'imc'
        $sql = "UPDATE paciente SET 
                    peso = ?, 
                    estatura = ?,
                    imc = ?
                WHERE idPaciente = ?";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("dddi", $peso, $estatura, $imc, $idPaciente);
            $exito = $statement->execute();
            $statement->close();
            return $exito;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    /**
     * Actualiza el estado de una cita (para el Médico).
     * @return bool
     */
    public function actualizarEstadoCita($idCita, $idMedico, $nuevoEstado) {
        
        if ($nuevoEstado != 'Completada' && $nuevoEstado != 'NoAsistida') {
            return false; 
        }
 
        $sql = "UPDATE cita SET estado = ? WHERE idCita = ? AND idMedico = ?";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("sii", $nuevoEstado, $idCita, $idMedico);
            $exito = $statement->execute();
            $filasAfectadas = $statement->affected_rows;
            $statement->close();
            return ($exito && $filasAfectadas > 0);
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    /**
     * Obtiene el historial de recetas asignadas a un paciente.
     * @return array
     */
    public function getRecetasDelPaciente($idPaciente) {
        $sql = "SELECT idReceta, fechaEmision, resumen, observaciones, estado 
                FROM receta 
                WHERE idPaciente = ? 
                ORDER BY fechaEmision DESC";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("i", $idPaciente);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            return [];
        }
    }

    /**
     * Crea un nuevo registro de receta para un paciente.
     * @return bool
     */
    public function crearReceta($idPaciente, $idMedico, $resumen, $observaciones) {
        $sql = "INSERT INTO receta (idPaciente, idMedico, resumen, observaciones) 
                VALUES (?, ?, ?, ?)";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("iiss", $idPaciente, $idMedico, $resumen, $observaciones);
            $exito = $statement->execute();
            $statement->close();
            return $exito;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    /**
     * Obtiene los datos de una receta específica.
     * Importante: Valida que la receta pertenezca al médico que la solicita.
     * @return array|null
     */
    public function getRecetaEspecifica($idReceta, $idMedico) {
        $sql = "SELECT r.idReceta, r.resumen, r.observaciones, r.estado, r.idPaciente,
                       CONCAT(p.nombre, ' ', p.apellidoPaterno) as pacienteNombre
                FROM receta r
                JOIN paciente p ON r.idPaciente = p.idPaciente
                WHERE r.idReceta = ? AND r.idMedico = ?
                LIMIT 1";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("ii", $idReceta, $idMedico);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            return null;
        }
    }

    /**
     * Obtiene todos los ítems (medicamentos/indicaciones) de una receta.
     * @return array
     */
    public function getItemsDeReceta($idReceta) {
        $sql = "SELECT idItem, nombreMedicamento, dosis, frecuencia, duracion, instrucciones
                FROM recetaItem
                WHERE idReceta = ?
                ORDER BY idItem ASC";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("i", $idReceta);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            return [];
        }
    }

    /**
     * Agrega un nuevo ítem (medicamento) a una receta existente.
     * @return bool
     */
    public function agregarItemReceta($idReceta, $nombre, $dosis, $frecuencia, $duracion, $instrucciones) {
        $sql = "INSERT INTO recetaItem (idReceta, nombreMedicamento, dosis, frecuencia, duracion, instrucciones)
                VALUES (?, ?, ?, ?, ?, ?)";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("isssss", $idReceta, $nombre, $dosis, $frecuencia, $duracion, $instrucciones);
            $exito = $statement->execute();
            $statement->close();
            return $exito;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    /**
     * Elimina un ítem de una receta.
     * Valida que el médico sea el dueño de la receta a la que pertenece el ítem.
     * @return bool
     */
    public function eliminarItemReceta($idItem, $idMedico) {
        $sql = "DELETE ri FROM recetaItem ri
                JOIN receta r ON ri.idReceta = r.idReceta
                WHERE ri.idItem = ? AND r.idMedico = ?";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("ii", $idItem, $idMedico);
            $exito = $statement->execute();
            $filasAfectadas = $statement->affected_rows;
            $statement->close();
            return ($exito && $filasAfectadas > 0);
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    // --- INICIO DE NUEVAS FUNCIONES (Gestión de Actividades - RFN-07) ---

    /**
     * Obtiene el catálogo de actividades (creadas por el Admin).
     * @return array
     */
    public function getActividadesDisponibles() {
        $sql = "SELECT idActividad, nombre, tipo FROM actividad ORDER BY tipo, nombre";
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene el historial de actividades asignadas a un paciente.
     * @return array
     */
    public function getActividadesDelPaciente($idPaciente) {
        $sql = "SELECT 
                    ap.idAsignacion,
                    ap.fechaAsignacion,
                    ap.fechaInicio,
                    ap.fechaFin,
                    ap.,
                    ap.estado,
                    a.nombre as actividadNombre,
                    a.tipo as actividadTipo
                FROM actividadPaciente ap
                JOIN actividad a ON ap.idActividad = a.idActividad
                WHERE ap.idPaciente = ?
                ORDER BY ap.estado ASC, ap.fechaAsignacion DESC";
        
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("i", $idPaciente);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            return [];
        }
    }

    /**
     * Asigna una nueva actividad a un paciente.
     * @return bool
     */
    public function asignarActividadPaciente($idPaciente, $idMedico, $idActividad, $fechaAsignacion, $fechaInicio, $fechaFin, $observaciones) {
        // Asegurarse de que las fechas vacías se guarden como NULL
        $fechaInicio = !empty($fechaInicio) ? $fechaInicio : null;
        $fechaFin = !empty($fechaFin) ? $fechaFin : null;

        $sql = "INSERT INTO actividadPaciente (idPaciente, idMedico, idActividad, fechaAsignacion, fechaInicio, fechaFin, observaciones) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $statement = $this->connection->prepare($sql);
            // i, i, i, s, s, s, s
            $statement->bind_param("iiissss", $idPaciente, $idMedico, $idActividad, $fechaAsignacion, $fechaInicio, $fechaFin, $observaciones);
            $exito = $statement->execute();
            $statement->close();
            return $exito;
        } catch (mysqli_sql_exception $e) {
            // Puedes loggear el error si quieres: error_log($e->getMessage());
            return false;
        }
    }
    // --- FIN DE NUEVAS FUNCIONES ---

    // --- FUNCIONES PARA DASHBOARD (ESTADÍSTICAS) ---
    public function getEstadisticasMedico($idMedico) {
        $stats = [];
        
        // 1. Total de Pacientes únicos atendidos (basado en citas)
        $sqlPacientes = "SELECT COUNT(DISTINCT idPaciente) as total FROM cita WHERE idMedico = ?";
        $stmt = $this->connection->prepare($sqlPacientes);
        $stmt->bind_param("i", $idMedico);
        $stmt->execute();
        $stats['totalPacientes'] = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        // 2. Citas Pendientes (Futuras)
        $sqlCitas = "SELECT COUNT(*) as total FROM cita WHERE idMedico = ? AND estado = 'Programada' AND fechaHora >= NOW()";
        $stmt = $this->connection->prepare($sqlCitas);
        $stmt->bind_param("i", $idMedico);
        $stmt->execute();
        $stats['citasPendientes'] = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();
        
        // 3. Recetas Emitidas en el último mes
        $sqlRecetas = "SELECT COUNT(*) as total FROM receta WHERE idMedico = ? AND fechaEmision >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        $stmt = $this->connection->prepare($sqlRecetas);
        $stmt->bind_param("i", $idMedico);
        $stmt->execute();
        $stats['recetasMes'] = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();

        return $stats;
    }

    // --- FUNCIONES DE PERFIL (MÉDICO) ---

    /**
     * Obtiene los datos del perfil del médico logueado.
     */
    public function getDatosMedico($idMedico) {
        $sql = "SELECT nombre, apellidoPaterno, apellidoMaterno, correo, 
                       telefono, especialidad, cedulaProfesional, disponibilidad 
                FROM medico 
                WHERE idMedico = ? 
                LIMIT 1";
        try {
            $statement = $this->connection->prepare($sql);
            $statement->bind_param("i", $idMedico);
            $statement->execute();
            $result = $statement->get_result();
            $statement->close();
            return $result->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            return null;
        }
    }

    /**
     * Actualiza los datos del perfil del médico.
     */
    public function updatePerfilMedico($idMedico, $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $correo, $password) {
        try {
            // Si el usuario escribió una contraseña nueva, la hasheamos y actualizamos todo
            if (!empty($password)) {
                $pass_hash = password_hash($password, PASSWORD_BCRYPT);
                $sql = "UPDATE medico SET 
                            nombre = ?, apellidoPaterno = ?, apellidoMaterno = ?, 
                            telefono = ?, correo = ?, passwordHash = ? 
                        WHERE idMedico = ?";
                $statement = $this->connection->prepare($sql);
                $statement->bind_param("ssssssi", $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $correo, $pass_hash, $idMedico);
            } else {
                // Si no, solo actualizamos los datos personales
                $sql = "UPDATE medico SET 
                            nombre = ?, apellidoPaterno = ?, apellidoMaterno = ?, 
                            telefono = ?, correo = ? 
                        WHERE idMedico = ?";
                $statement = $this->connection->prepare($sql);
                $statement->bind_param("sssssi", $nombre, $apellidoPaterno, $apellidoMaterno, $telefono, $correo, $idMedico);
            }
            
            $exito = $statement->execute();
            $statement->close();
            return $exito;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    // --- FUNCIONES DE FORO (MÉDICO) ---

    /**
     * Obtiene todas las publicaciones para el médico.
     * Verifica si EL MÉDICO actual ya reaccionó a cada una.
     */
    public function getPublicacionesForo($idMedicoActual) {
        $sql = "SELECT 
                    f.idPublicacion, f.titulo, f.contenido, f.imagenURL, f.fechaPublicacion,
                    CASE 
                        WHEN f.idPaciente IS NOT NULL THEN CONCAT(p.nombre, ' ', p.apellidoPaterno)
                        WHEN f.idMedico IS NOT NULL THEN CONCAT('Dr. ', m.nombre, ' ', m.apellidoPaterno)
                        WHEN f.idAdmin IS NOT NULL THEN CONCAT(a.nombre, ' (Admin)')
                        ELSE 'Usuario Desconocido'
                    END as autorNombre,
                    
                    CASE 
                        WHEN f.idPaciente IS NOT NULL THEN 'Paciente'
                        WHEN f.idMedico IS NOT NULL THEN 'Medico'
                        WHEN f.idAdmin IS NOT NULL THEN 'Admin'
                        ELSE 'Desconocido'
                    END as autorRol,
                    COUNT(DISTINCT fr.idReaccion) as totalReacciones,
                    MAX(CASE WHEN fr.idMedico = ? THEN 1 ELSE 0 END) as usuarioYaReacciono
                    
                FROM foro f
                LEFT JOIN paciente p ON f.idPaciente = p.idPaciente
                LEFT JOIN medico m ON f.idMedico = m.idMedico
                LEFT JOIN administrador a ON f.idAdmin = a.idAdmin
                LEFT JOIN foroReaccion fr ON f.idPublicacion = fr.idPublicacion
                
                GROUP BY f.idPublicacion
                ORDER BY f.fechaPublicacion DESC
                LIMIT 50";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("i", $idMedicoActual);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            return [];
        }
    }

    /**
     * Inserta una publicación hecha por un MÉDICO.
     */
    public function insertarPublicacionForo($idMedico, $titulo, $contenido, $imagenURL) {
        // Nota: insertamos en 'idMedico', dejando 'idPaciente' como NULL
        $sql = "INSERT INTO foro (idMedico, titulo, contenido, imagenURL) VALUES (?, ?, ?, ?)";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("isss", $idMedico, $titulo, $contenido, $imagenURL);
            $exito = $stmt->execute();
            $stmt->close();
            return $exito;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    /**
     * Gestiona el Like del MÉDICO.
     */
    public function alternarReaccion($idPublicacion, $idMedico) {
        // 1. Intentar borrar (si ya existe el like de este médico)
        $sql_del = "DELETE FROM foroReaccion WHERE idPublicacion = ? AND idMedico = ?";
        try {
            $stmt = $this->connection->prepare($sql_del);
            $stmt->bind_param("ii", $idPublicacion, $idMedico);
            $stmt->execute();
            $filas = $stmt->affected_rows;
            $stmt->close();

            // 2. Si no borró nada, es que no existía -> Insertamos
            if ($filas == 0) {
                $sql_ins = "INSERT INTO foroReaccion (idPublicacion, idMedico) VALUES (?, ?)";
                $stmt2 = $this->connection->prepare($sql_ins);
                $stmt2->bind_param("ii", $idPublicacion, $idMedico);
                $stmt2->execute();
                $stmt2->close();
            }
            return true;
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }
}
?>