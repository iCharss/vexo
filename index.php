<?php
// Configuración de errores más robusta
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/php_errors.log');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

// Verificar si podemos escribir en los logs
if (!is_writable(__DIR__.'/php_errors.log')) {
    file_put_contents(__DIR__.'/php_errors.log', '');
    chmod(__DIR__.'/php_errors.log', 0666);
}

// Configuración de sesión más robusta
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);
ini_set('session.gc_maxlifetime', 14400); // 4 horas
ini_set('session.cookie_lifetime', 0); // Hasta que se cierre el navegador

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función mejorada de logging
function log_message($message, $level = 'INFO') {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$level] $message\n";
    
    // Escribir en archivo
    error_log($logMessage, 3, __DIR__.'/php_errors.log');
    
    // También en el log del sistema si es desarrollo
    if (getenv('ENVIRONMENT') === 'development') {
        error_log($logMessage);
    }
}

require 'vendor/autoload.php';
require 'config.php';
require 'emails/email_sender.php'; // Asegúrate de que la ruta sea correcta

use flight\Engine;

$app = new Engine();

// Middleware para registrar peticiones (útil para debugging)
/*$app->before('start', function() use ($app) {
    log_message("Inicio de petición: {$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']}");
    log_message("Session ID: " . session_id());
    log_message("Session data: " . json_encode($_SESSION));
}); */


// Configuración de rutas
$app->route('/', function() use ($app) {
    $app->render('header', ['title' => 'Vexo - Soluciones para tu vida']);
    $app->render('home');
    $app->render('footer');
});

$app->route('/nosotros', function() use ($app) {
    $app->render('header', ['title' => 'Vexo - Soluciones para tu vida']);
    $app->render('nosotros');
    $app->render('footer');
});

// Ruta para servicios (versión corregida y mejorada)
$app->route('/servicios', function() use ($app, $pdo) {
    try {
        // Obtener servicios
        $stmt = $pdo->query("SELECT * FROM servicios");
        $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Para cada servicio, obtener sus opciones si tiene
        foreach ($servicios as &$servicio) {
            if ($servicio['tiene_opciones']) {
                $stmt = $pdo->prepare("SELECT * FROM servicio_opciones WHERE servicio_id = ?");
                $stmt->execute([$servicio['id']]);
                $servicio['opciones'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        
        // Renderizar vista con los datos
        $app->render('header.php', ['title' => 'Vexo - Nuestros Servicios']);
        $app->render('servicios.php', ['servicios' => $servicios]);
        $app->render('footer.php');
        
    } catch (PDOException $e) {
        error_log("Error PDO en /servicios: " . $e->getMessage());
        $app->halt(500, 'Error al cargar los servicios desde la base de datos');
    } catch (Exception $e) {
        error_log("Error general en /servicios: " . $e->getMessage());
        $app->halt(500, 'Error al procesar la solicitud');
    }
});

// API de servicios (para JSON)
$app->route('/api/servicios', function() use ($pdo) {
    header('Content-Type: application/json');
    try {
        $stmt = $pdo->query("SELECT * FROM servicios");
        $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($servicios);
    } catch (PDOException $e) {
        error_log("Error en /api/servicios: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener servicios']);
    }
});

$app->route('/contacto', function() use ($app) {
    $app->render('header', ['title' => 'Vexo - Contacto']);
    $app->render('contacto');
    $app->render('footer');
});

$app->route('/registro-exitoso', function() use ($app) {
    $app->render('header', ['title' => 'Vexo - Contacto']);
    $app->render('registro-exitoso');
    $app->render('footer');
});

/*pedidos*/
$app->route('/pedidos', function() use ($app) {
    if (!isset($_SESSION['user'])) {
        $app->redirect('/');
        return;
    }
    
    $app->render('header', ['title' => 'Vexo - Mis pedidos']);
    $app->render('pedidos');
    $app->render('footer');
});

// Ruta para eliminar un pedido directamente
$app->route('DELETE /api/pedidos/@id/cancelar', function($id) use ($app, $pdo) {
    header('Content-Type: application/json');
    
    // Verificar sesión y que el pedido pertenezca al usuario
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }
    
    try {
        // Verificar que el pedido pertenece al usuario
        $stmt = $pdo->prepare("SELECT cliente_username FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$pedido || $pedido['cliente_username'] !== $_SESSION['user']['username']) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }
        
        // Eliminar el pedido
        $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true, 'message' => 'Pedido eliminado correctamente']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar pedido']);
    }
});

// Endpoint para valoraciones
$app->route('POST /api/pedidos/valorar', function() use ($app, $pdo) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['error' => 'Debes iniciar sesión']);
        return;
    }
    
    try {
        $pedido_id = $_POST['pedido_id'] ?? null;
        $profesional_username = $_POST['profesional_username'] ?? null;
        $precio = $_POST['precio'] ?? null;
        $valoracion = $_POST['valoracion'] ?? null;
        $testimonio = $_POST['testimonio'] ?? null;
        
        // Validaciones
        if (!$pedido_id || !$profesional_username || !$valoracion || !$testimonio) {
            throw new Exception('Datos incompletos');
        }
        
        if ($valoracion < 1 || $valoracion > 5) {
            throw new Exception('Valoración inválida');
        }
        
        // Verificar que el pedido pertenece al cliente y está finalizado
        $stmt = $pdo->prepare("
            SELECT 1 FROM pedidos 
            WHERE id = ? AND cliente_username = ? AND estado = 'completado'
        ");
        $stmt->execute([$pedido_id, $_SESSION['user']['username']]);
        
        if (!$stmt->fetch()) {
            throw new Exception('Pedido no válido para valoración');
        }
        
        // Verificar que no existe ya una valoración para este pedido
        $stmt = $pdo->prepare("
            SELECT 1 FROM pedidos_realizados 
            WHERE pedido_id = ? AND cliente_username = ?
        ");
        $stmt->execute([$pedido_id, $_SESSION['user']['username']]);
        
        if ($stmt->fetch()) {
            throw new Exception('Ya has valorado este servicio');
        }
        
        // Insertar valoración
        $stmt = $pdo->prepare("
            INSERT INTO pedidos_realizados 
            (pedido_id, profesional_username, cliente_username, precio, testimonio, valoracion, fecha_realizacion)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $pedido_id,
            $profesional_username,
            $_SESSION['user']['username'],
            $precio,
            $testimonio,
            $valoracion
        ]);
        
        // Actualizar promedio de valoraciones del profesional
        $stmt = $pdo->prepare("
            UPDATE users 
            SET valoracion_promedio = (
                SELECT AVG(valoracion) 
                FROM pedidos_realizados 
                WHERE profesional_username = ?
            )
            WHERE username = ?
        ");
        $stmt->execute([$profesional_username, $profesional_username]);
        
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        error_log("Error al guardar valoración: " . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// API para obtener detalles de un pedido (versión mejorada con manejo de errores)
$app->route('GET /api/pedidos/@id', function($id) use ($app, $pdo) {
    header('Content-Type: application/json');
    
    try {
        // Validar que el ID sea numérico
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de pedido inválido']);
            return;
        }

        // Consulta principal del pedido con información del profesional si está asignado
        $stmt = $pdo->prepare("
            SELECT 
                p.*, 
                s.categoria as nombre_servicio,
                s.tiene_opciones,
                u.nombre as profesional_nombre,
                u.apellido as profesional_apellido,
                u.telefono as profesional_telefono,
                u.foto_perfil as profesional_foto,
                (
                    SELECT j.oferta 
                    FROM JSON_TABLE(
                        p.ofertas, 
                        '$[*]' COLUMNS(
                            username VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci PATH '$.username',
                            oferta DECIMAL(10,2) PATH '$.oferta'
                        )
                    ) as j 
                    WHERE j.username = p.profesional_username
                    LIMIT 1
                ) as profesional_oferta
            FROM pedidos p
            LEFT JOIN servicios s ON p.categoria_id = s.id
            LEFT JOIN users u ON p.profesional_username = u.username
            WHERE p.id = ?
        ");
        
        if (!$stmt->execute([$id])) {
            throw new PDOException('Error al ejecutar la consulta principal');
        }
        
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$pedido) {
            http_response_code(404);
            echo json_encode(['error' => 'Pedido no encontrado']);
            return;
        }
        
        // Obtener información de las opciones seleccionadas
        $opcionesSeleccionadas = json_decode($pedido['opciones_seleccionadas'] ?? '[]', true);
        $opcionesInfo = [];
        $fleteInfo = null;
        
        // Verificar si es un flete (tiene_opciones = 2)
        if ($pedido['tiene_opciones'] === 2 && $opcionesSeleccionadas === ["000"]) {
            // Obtener datos del flete
            $stmt = $pdo->prepare("
                SELECT inicio, fin, kilometros_totales
                FROM servicio_agregar 
                WHERE id = ?
            ");
            
            if (!$stmt->execute([$id])) {
                throw new PDOException('Error al obtener datos del flete');
            }
            
            $fleteInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            log_message("datos del flete $id: ") . print_r($fleteInfo, true);
        }
        elseif (!empty($opcionesSeleccionadas)) {
            // Obtener información de opciones normales
            $placeholders = implode(',', array_fill(0, count($opcionesSeleccionadas), '?'));
            
            $stmt = $pdo->prepare("
                SELECT id, nombre 
                FROM servicio_opciones 
                WHERE id IN ($placeholders)
            ");
            
            if (!$stmt->execute($opcionesSeleccionadas)) {
                throw new PDOException('Error al obtener opciones del servicio');
            }
            
            $opcionesInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Preparar respuesta final
        $response = [
            'success' => true,
            'pedido' => array_merge($pedido, [
                'opciones_info' => $opcionesInfo,
                'flete_info' => $fleteInfo
            ])
        ];
        
        echo json_encode($response);
        
    } catch (PDOException $e) {
        error_log("Error al obtener pedido $id: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Error interno al procesar la solicitud']);
    } catch (Exception $e) {
        error_log("Error general al obtener pedido $id: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
});

// API para obtener presupuestos de un pedido
$app->route('GET /api/pedidos/@id/presupuestos', function($id) use ($app, $pdo) {
    header('Content-Type: application/json');
    
    try {
        // Obtener el pedido
        $stmt = $pdo->prepare("SELECT ofertas FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$pedido) {
            echo json_encode(['error' => 'Pedido no encontrado']);
            return;
        }
        
        $ofertas = json_decode($pedido['ofertas'], true) ?? [];
        $result = ['ofertas' => []];
        
        // Obtener información de cada profesional que hizo oferta
        foreach ($ofertas as $oferta) {
            $stmt = $pdo->prepare("SELECT u.username, u.nombre, s.categoria, u.apellido, u.foto_perfil
            FROM users u
            JOIN usuario_servicios us ON  u.username = us.usuario_username
            JOIN servicios s ON us.servicio_id = s.id
            WHERE username = ?");
            $stmt->execute([$oferta['username']]);
            $profesional = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($profesional) {
                $result['ofertas'][] = [
                    'profesional' => $profesional,
                    'monto' => $oferta['oferta'],
                    'descripcion' => $oferta['descripcion'] ?? '',
                    'fecha_propuesta' => $oferta['fecha_propuesta'] ?? ''
                ];
            }
        }
        
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener presupuestos']);
    }
});

// API para actualizar la fecha de un pedido
$app->route('POST /api/pedidos/@id/actualizar-fecha', function($id) use ($app, $pdo) {
    header('Content-Type: application/json');
    
    // Verificar sesión y que el pedido pertenezca al usuario
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }
    
    $data = $app->request()->data;
    $nuevaFecha = $data['fecha_necesidad'] ?? null;
    
    try {
        // Verificar que el pedido pertenece al usuario y está pendiente
        $stmt = $pdo->prepare("SELECT cliente_username, estado FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$pedido || $pedido['cliente_username'] !== $_SESSION['user']['username']) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }
        
        if ($pedido['estado'] !== 'pendiente') {
            echo json_encode(['success' => false, 'message' => 'Solo se pueden editar pedidos pendientes']);
            return;
        }
        
        // Validar fecha
        if (!$nuevaFecha) {
            echo json_encode(['success' => false, 'message' => 'Fecha no válida']);
            return;
        }
        
        // Actualizar fecha
        $stmt = $pdo->prepare("UPDATE pedidos SET fecha_necesidad = ? WHERE id = ?");
        $stmt->execute([$nuevaFecha, $id]);
        
        echo json_encode(['success' => true, 'message' => 'Fecha actualizada']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar fecha']);
    }
});

// API para aceptar una oferta
$app->route('POST /api/pedidos/@id/aceptar-oferta', function($id) use ($app, $pdo) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }
    
    $data = $app->request()->data;
    $ofertaId = $data['ofertaId'] ?? null;
    
    try {
        // Verificar que el pedido pertenece al usuario
        $stmt = $pdo->prepare("SELECT cliente_username, ofertas FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$pedido || $pedido['cliente_username'] !== $_SESSION['user']['username']) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }
        
        // Decodificar ofertas
        $ofertas = json_decode($pedido['ofertas'], true) ?? [];
        
        // Verificar que el índice de oferta es válido
        if ($ofertaId === null || !isset($ofertas[$ofertaId])) {
            echo json_encode(['success' => false, 'message' => 'Oferta no válida']);
            return;
        }
        
        // Obtener la oferta seleccionada
        $oferta = $ofertas[$ofertaId];
        
        // Actualizar pedido con profesional asignado y fecha propuesta
        $stmt = $pdo->prepare("
            UPDATE pedidos 
            SET estado = 'en_curso', 
                profesional_username = ?,
                fecha_necesidad = ?
            WHERE id = ?
        ");
        
        // Usar la fecha_propuesta de la oferta o mantener la existente si no hay
        $fechaNecesidad = !empty($oferta['fecha_propuesta']) ? $oferta['fecha_propuesta'] : $pedido['fecha_necesidad'];
        
        $stmt->execute([
            $oferta['username'],
            $fechaNecesidad,
            $id
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Oferta aceptada']);

        $emailSender = new EmailSender();

        try{
            // Obtener datos del cliente
            $stmt = $pdo->prepare("
                SELECT u.email as cliente_email, u.nombre as cliente_nombre, 
                    p.profesional_username, up.email as profesional_email, 
                    up.nombre as profesional_nombre, p.codigo, s.categoria
                FROM pedidos p
                JOIN users u ON p.cliente_username = u.username
                JOIN users up ON p.profesional_username = up.username
                JOIN servicios s ON p.categoria_id = s.id
                WHERE p.id = ?
            ");
            $stmt->execute([$id]);
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
                
            // Email al cliente
            $emailSender->sendClientePresupuestoAceptado(
                $datos['cliente_email'],
                $datos['cliente_nombre'],
                $datos['categoria'],
                $datos['profesional_nombre'],
                $datos['codigo']
            );
            
            // Email al profesional
            $emailSender->sendProfesionalPresupuestoAceptado(
                $datos['profesional_email'],
                $datos['profesional_nombre'],
                $datos['categoria'],
                $datos['cliente_nombre'],
                $datos['codigo']
            );
        } catch (Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
        }
    } catch (PDOException $e) {
        error_log("Error al aceptar oferta: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al aceptar oferta']);
    }
});

// API para cancelar un pedido
$app->route('POST /api/pedidos/@id/cancelar', function($id) use ($app, $pdo) {
    header('Content-Type: application/json');
    
    // Verificar sesión y que el pedido pertenezca al usuario
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }
    
    try {
        // Verificar que el pedido pertenece al usuario
        $stmt = $pdo->prepare("SELECT cliente_username, estado FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$pedido || $pedido['cliente_username'] !== $_SESSION['user']['username']) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }
        
        // Solo permitir cancelar pedidos pendientes
        if ($pedido['estado'] !== 'pendiente') {
            echo json_encode(['success' => false, 'message' => 'Solo se pueden cancelar pedidos pendientes']);
            return;
        }
        
        // Actualizar estado
        $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'cancelado' WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true, 'message' => 'Pedido cancelado']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al cancelar pedido']);
    }
});

// API para obtener información de un profesional
$app->route('GET /api/profesionales/@username', function($username) use ($app, $pdo) {
    header('Content-Type: application/json');
    
    try {
        $stmt = $pdo->prepare("SELECT username, nombre, apellido, telefono, foto_perfil FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $profesional = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($profesional) {
            echo json_encode($profesional);
        } else {
            echo json_encode(['error' => 'Profesional no encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener profesional']);
    }
});

$app->route('POST /pedidos/nuevo', function() use ($app, $pdo) {
    header('Content-Type: application/json');
    
    log_message('Inicio de solicitud POST /pedidos/nuevo');
    log_message('Datos POST: ' . print_r($_POST, true));
    log_message('Datos FILES: ' . print_r($_FILES, true));
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['error' => 'Debes iniciar sesión para realizar esta acción']);
        return;
    }
    
    if ($_SESSION['user']['rol'] != '1') {
        echo json_encode(['error' => 'Solo los clientes pueden solicitar servicios']);
        return;
    }

    try {
        // Obtener dirección del usuario
        $stmt = $pdo->prepare("SELECT localidad FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['user']['username']]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (empty($userData['localidad'])) {
            echo json_encode(['error' => 'Debes configurar tu dirección en tu perfil primero']);
            return;
        }

        // Procesar archivo subido
        $archivoPath = null;
        if (isset($_FILES['prueba']) && $_FILES['prueba']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/public/img/prueba/';
            
            // Crear directorio si no existe
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Validar tipo de archivo
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/quicktime'];
            $fileType = mime_content_type($_FILES['prueba']['tmp_name']);
            
            if (!in_array($fileType, $allowedTypes)) {
                echo json_encode(['error' => 'Tipo de archivo no permitido. Solo imágenes (JPEG, PNG, GIF) y videos (MP4, MOV)']);
                return;
            }
            
            // Validar tamaño (máximo 10MB)
            if ($_FILES['prueba']['size'] > 10 * 1024 * 1024) {
                echo json_encode(['error' => 'El archivo es demasiado grande. Máximo 10MB']);
                return;
            }
            
            // Generar nombre único
            $extension = pathinfo($_FILES['prueba']['name'], PATHINFO_EXTENSION);
            $filename = 'prueba_'. $_SESSION['user']['username'] . '.' . $extension;
            $destino = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['prueba']['tmp_name'], $destino)) {
                $archivoPath = $filename;
            } else {
                echo json_encode(['error' => 'Error al subir el archivo']);
                return;
            }
        }
        
        $codigo = 'Vexo_' . bin2hex(random_bytes(3));
        $opcionesSeleccionadas = isset($_POST['esFlete']) ? ["000"] : ($_POST['opciones_seleccionadas'] ?? null);

        // Insertar pedido
        $stmt = $pdo->prepare("INSERT INTO pedidos 
            (cliente_username, categoria_id, tipo_trabajo, direccion, fecha_necesidad, opciones_seleccionadas, prueba, codigo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        
        $stmt->execute([
            $_SESSION['user']['username'],
            $_POST['categoria_id'],
            $_POST['tipo_trabajo'],
            $userData['localidad'],
            $_POST['fecha_necesidad'] ?? null,
            json_encode($opcionesSeleccionadas),
            $archivoPath,
            $codigo
        ]);
        
        $servicio = $_POST['categoria_id'];
        $pedidoId = $pdo->lastInsertId();
        
        if(isset($_POST['esFlete']) && $_POST['esFlete'] == '1'){
            $stmt = $pdo->prepare("INSERT INTO servicio_agregar (id, servicio_id, inicio, fin, kilometros_totales) VALUES (?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $pedidoId,
                $servicio,
                $_POST['puntoPartida'],
                $_POST['puntoFinal'],
                $_POST['kilometros']
            ]);
        }
        
        echo json_encode([
            'success' => true, 
            'pedido_id' => $pdo->lastInsertId(),
            'archivo' => $archivoPath
        ]);

        try {
            
            $emailSender = new EmailSender();
            $stmt = $pdo->prepare("SELECT p.id, u.email, u.nombre, se.categoria FROM users u 
            JOIN pedidos p ON u.username = p.cliente_username
            JOIN servicios se ON p.categoria_id = se.id WHERE p.id = ?");
            $stmt->execute([$pdo->lastInsertId()]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            $emailSender->sendClienteSolicitudServicio(
                $cliente['email'],
                $cliente['nombre'],
                $cliente['categoria'],
                $cliente['id']
            );

            //enviar mail a profesionales con esta categoria
            $stmt = $pdo->prepare("SELECT u.email, u.nombre, se.categoria FROM users u 
            JOIN usuario_servicios s ON u.username = s.usuario_username
            JOIN servicios se ON s.servicio_id = se.id WHERE s.servicio_id = ?");
            $stmt->execute([$_POST['categoria_id']]);
            $profesionales = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($profesionales as $profesional) {
                $emailSender->sendProfesionalNuevoPedido(
                    $profesional['email'],
                    $profesional['nombre'],
                    $profesional['categoria']
                );
            }
            } catch (Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
        }
    } catch (PDOException $e) {
        log_message('error PDO: ' . $e->getMessage());
        error_log("Error al crear pedido: " . $e->getMessage());
        echo json_encode(['error' => 'Error al crear pedido']);
    }
});

// Rutas de autenticación
$app->route('/login/cliente', function() use ($app) {
    if (isset($_SESSION['user'])) {
        $app->redirect('/');
        return;
    }

    $app->render('header', ['title' => 'Vexo - Login Cliente']);
    $app->render('login-cliente');
    $app->render('footer');
});

$app->route('/login/profesional', function() use ($app) {
    if (isset($_SESSION['user'])) {
        $app->redirect('/');
        return;
    }

    $app->render('header', ['title' => 'Vexo - Login Profesional']);
    $app->render('login-profesional');
    $app->render('footer');
});

$app->route('/registro/cliente', function() use ($app) {
    if (isset($_SESSION['user'])) {
        $app->redirect('/');
        return;
    }

    $app->render('header', ['title' => 'Vexo - Registro Cliente']);
    $app->render('registro-cliente');
    $app->render('footer');
});

$app->route('/registro/profesional', function() use ($app) {
    if (isset($_SESSION['user'])) {
        $app->redirect('/');
        return;
    }

    $app->render('header', ['title' => 'Vexo - Registro Profesional']);
    $app->render('registro-profesional');
    $app->render('footer');
});

// Ruta para el perfil (redirige según el tipo de usuario)
$app->route('/perfil', function() use ($app) {
    if (!isset($_SESSION['user'])) {
        $app->redirect('/');
        return;
    }
    
    if ($_SESSION['user']['rol'] == '1') {
        $app->render('header', ['title' => 'Vexo - Perfil de Cliente']);
        $app->render('perfil-cliente');
        $app->render('footer');
    } else {
        $app->render('header', ['title' => 'Vexo - Perfil de Profesional']);
        $app->render('perfil-profesional');
        $app->render('footer');
    }
});

// Ruta para actualizar foto de perfil
$app->route('POST /api/actualizar-foto-perfil', function() use ($app, $pdo) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }
    
    // Verificar que se subió un archivo
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'No se subió ninguna imagen']);
        return;
    }
    
    // Validar tipo de archivo
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = mime_content_type($_FILES['avatar']['tmp_name']);
    
    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Formato de imagen no válido. Solo JPG, PNG o GIF']);
        return;
    }
    
    // Validar tamaño (máximo 2MB)
    if ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'La imagen es demasiado grande (máximo 2MB)']);
        return;
    }
    
    try {
        // Directorio de uploads
        $uploadDir = __DIR__ . '/public/img/profiles/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generar nombre único
        $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $_SESSION['user']['username'] . '_' . uniqid() . '.' . $extension;
        $destino = $uploadDir . $filename;
        
        // Mover el archivo
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destino)) {
            // Eliminar la foto anterior si no es la default
            $oldPhoto = $pdo->prepare("SELECT foto_perfil FROM users WHERE username = ?");
            $oldPhoto->execute([$_SESSION['user']['username']]);
            $oldPhotoPath = $oldPhoto->fetchColumn();
            
            if ($oldPhotoPath && $oldPhotoPath !== 'default.jpg' && file_exists($uploadDir . $oldPhotoPath)) {
                unlink($uploadDir . $oldPhotoPath);
            }
            
            // Actualizar base de datos
            $stmt = $pdo->prepare("UPDATE users SET foto_perfil = ? WHERE username = ?");
            $stmt->execute([$filename, $_SESSION['user']['username']]);
            
            // Actualizar sesión
            $_SESSION['user']['foto_perfil'] = $filename;
            
            echo json_encode([
                'success' => true,
                'message' => 'Foto de perfil actualizada',
                'newPhoto' => $filename
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
        }
    } catch (PDOException $e) {
        error_log("Error al actualizar foto de perfil: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al actualizar foto de perfil']);
    }
});

// Ruta para actualizar perfil del cliente
$app->route('POST /api/actualizar-perfil', function() use ($app, $pdo) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }
    
    $data = $app->request()->data;
    
    // Validar datos
    $requiredFields = ['nombre', 'apellido', 'telefono', 'direccion', 'localidad'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
            return;
        }
    }
    
    // Validar formato de teléfono
    if (!preg_match('/^[0-9\s+-]{8,15}$/', $data['telefono'])) {
        echo json_encode(['success' => false, 'message' => 'Teléfono no válido']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET 
            nombre = ?, 
            apellido = ?, 
            telefono = ?, 
            direccion = ?,
            localidad = ?
            WHERE username = ?");
        
        $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['telefono'],
            $data['direccion'],
            $data['localidad'],
            $_SESSION['user']['username']
        ]);
        
        // Actualizar datos en sesión
        $_SESSION['user']['nombre'] = $data['nombre'];
        $_SESSION['user']['apellido'] = $data['apellido'];
        $_SESSION['user']['telefono'] = $data['telefono'];
        $_SESSION['user']['direccion'] = $data['direccion'];
        $_SESSION['user']['localidad'] = $data['localidad'];
        
        echo json_encode(['success' => true]);
        
    } catch (PDOException $e) {
        error_log("Error al actualizar perfil: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al actualizar perfil']);
    }
});

// Ruta para actualizar perfil del profesional
$app->route('GET /api/profesional/datos-edicion', function() use ($app, $pdo) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['error' => 'No autorizado']);
        return;
    }
    
    try {
        // Obtener datos básicos del profesional
        $stmt = $pdo->prepare("SELECT nombre, apellido, telefono, direccion, nro_matricula, localidad FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['user']['username']]);
        $profesional = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$profesional) {
            echo json_encode(['error' => 'Profesional no encontrado']);
            return;
        }
        
        // Obtener todos los servicios disponibles
        $servicios = $pdo->query("SELECT id, categoria FROM servicios")->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener servicios del profesional
        $stmt = $pdo->prepare("SELECT servicio_id FROM usuario_servicios WHERE usuario_username = ?");
        $stmt->execute([$_SESSION['user']['username']]);
        $serviciosProfesional = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        
        // Marcar qué servicios están seleccionados
        foreach ($servicios as &$servicio) {
            $servicio['seleccionado'] = in_array($servicio['id'], $serviciosProfesional);
        }
        
        echo json_encode([
            'profesional' => $profesional,
            'servicios' => $servicios
        ]);
        
    } catch (PDOException $e) {
        error_log("Error al obtener datos de edición: " . $e->getMessage());
        echo json_encode(['error' => 'Error al obtener datos']);
    }
});

// Ruta para actualizar perfil del profesional
$app->route('POST /api/actualizar-perfil-pro', function() use ($app, $pdo) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }
    
    $username = $_SESSION['user']['username'];
    $data = $app->request()->data;
    $files = $app->request()->files;
    
    // Validar datos básicos
    $requiredFields = ['nombre', 'apellido', 'telefono', 'direccion', 'localidad'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos básicos son requeridos']);
            return;
        }
    }
    
    // Validar formato de teléfono
    if (!preg_match('/^[0-9\s+-]{8,15}$/', $data['telefono'])) {
        echo json_encode(['success' => false, 'message' => 'Teléfono no válido']);
        return;
    }
    
    try {
        $pdo->beginTransaction();
        
        // 1. Actualizar datos básicos del profesional
        $stmt = $pdo->prepare("UPDATE users SET 
            nombre = ?, 
            apellido = ?, 
            telefono = ?, 
            direccion = ?,
            nro_matricula = COALESCE(?, nro_matricula),
            localidad = ?
            WHERE username = ?");
        
        $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['telefono'],
            $data['direccion'],
            $data['nro_matricula'] ?? null,
            $data['localidad'],
            $username
        ]);
        
        // 2. Manejar documento si se subió uno
        if (!empty($files['documento']) && $files['documento']['error'] === UPLOAD_ERR_OK) {
            $documento = $files['documento'];
            
            // Validar tipo de archivo
            $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
            if (!in_array($documento['type'], $allowedTypes)) {
                throw new Exception('Formato de documento no permitido. Solo PDF, JPG o PNG');
            }
            
            // Validar tamaño (máximo 5MB)
            if ($documento['size'] > 5 * 1024 * 1024) {
                throw new Exception('El documento es demasiado grande. Máximo 5MB');
            }
            
            // Generar nombre único
            $extension = pathinfo($documento['name'], PATHINFO_EXTENSION);
            $filename = 'doc_' . $username . '_' . time() . '.' . $extension;
            $uploadDir = __DIR__ . '/public/docs/';
            
            if (!move_uploaded_file($documento['tmp_name'], $uploadDir . $filename)) {
                throw new Exception('Error al subir el documento');
            }
            
            // Actualizar en base de datos
            $stmt = $pdo->prepare("UPDATE users SET documento = ? WHERE username = ?");
            $stmt->execute([$filename, $username]);
        }
        
        // 3. Actualizar servicios del profesional
        $servicios = json_decode($data['servicios'] ?? '[]', true) ?? [];
        
        // Eliminar todos los servicios actuales
        $stmt = $pdo->prepare("DELETE FROM usuario_servicios WHERE usuario_username = ?");
        $stmt->execute([$username]);
        
        // Insertar los nuevos servicios seleccionados
        if (!empty($servicios)) {
            $stmt = $pdo->prepare("INSERT INTO usuario_servicios (usuario_username, servicio_id) VALUES (?, ?)");
            foreach ($servicios as $servicio_id) {
                $stmt->execute([$username, $servicio_id]);
            }
        }
        
        $pdo->commit();
        
        // Actualizar datos en sesión
        $_SESSION['user']['nombre'] = $data['nombre'];
        $_SESSION['user']['apellido'] = $data['apellido'];
        $_SESSION['user']['telefono'] = $data['telefono'];
        $_SESSION['user']['direccion'] = $data['direccion'];
        $_SESSION['user']['localidad'] = $data['localidad'];
        
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error al actualizar perfil profesional: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
});

// Ruta para cerrar sesión
$app->route('/logout', function() use ($app) {
    session_destroy();
    $app->redirect('/');
});

// Manejar el envío del formulario
$app->route('POST /enviar-mensaje', function() use ($app) {
    // Verificar si es una solicitud AJAX
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
              
    try {
        // Obtener y limpiar los datos del formulario
        $nombre   = trim($app->request()->data->name);
        $email    = trim($app->request()->data->email);
        $telefono = trim($app->request()->data->phone);
        $servicio = trim($app->request()->data->service);
        $mensaje  = trim($app->request()->data->message);

        // Validar campos obligatorios
        if (empty($nombre) || empty($email) || empty($mensaje)) {
            echo json_encode([
                'success' => false,
                'message' => 'Todos los campos obligatorios deben completarse.'
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success'=> false,
                'message'=> 'Email invalido'
            ]);
            return;
        }
        
        // Crear el cuerpo del email
        $emailBody = "
            <h2>Nuevo mensaje de contacto</h2>
            <p><strong>Nombre:</strong> {$nombre}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Teléfono:</strong> {$telefono}</p>
            <p><strong>Servicio:</strong> {$servicio}</p>
            <p><strong>Mensaje:</strong></p>
            <p>{$mensaje}</p>
            <hr>
            <p>Este mensaje fue enviado desde el formulario de contacto del sitio web.</p>
        ";
        
        // Configurar y enviar el email
        $mailer = new EmailSender();
        $result = $mailer->sendEmail(
            'Vexo@grupoyex.com', // Destinatario
            "Nuevo mensaje de contacto: {$servicio}", // Asunto
            $emailBody // Cuerpo del mensaje
        );
        
        if ($result) {
            if ($isAjax) {
                echo json_encode([
                    'success' => true,
                    'message' => '¡Mensaje enviado correctamente!'
                ]);
        } else {
            throw new Exception('No se pudo enviar el mensaje.');
        }
        
        return;
    }

    } catch (Exception $e) {
        error_log("Error en contacto: " . $e->getMessage());

        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }

    return;
});

$app->route('POST /auth/login', function() use ($app, $pdo) {
    header('Content-Type: application/json');
    
    try {
        // Verificar datos recibidos
        if (empty($_POST['username1']) || empty($_POST['password'])) {
            throw new Exception('Usuario y contraseña son requeridos');
        }

        $username = $_POST['username1'];
        $password = $_POST['password'];
        $tipo = $_POST['tipo1'] ?? null; // 'cliente' o 'profesional'

        // Buscar usuario por username o email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception('Usuario no encontrado');
        }

        // Verificar contraseña
        if (!password_verify($password, $user['password'])) {
            throw new Exception('Contraseña incorrecta');
        }

        // Determinar tipo de usuario
        $userType = ($user['rol'] == '1') ? 'cliente' : 'profesional';
        
        // Si el usuario es admin (rol 3), ignorar la validación de tipo
        if ($user['rol'] != '3' && $tipo && $tipo !== $userType) {
            throw new Exception('Usa el login correcto para tu tipo de cuenta');
        }

        // Verificar si profesional está aprobado (excepto para admins)
        if ($user['rol'] == '2' && !$user['alta'] && $user['rol'] != '3') {
            throw new Exception('Tu cuenta profesional está pendiente de aprobación');
        }

        // Establecer sesión
        $_SESSION['user'] = [
            'username' => $user['username'],
            'email' => $user['email'],
            'nombre' => $user['nombre'],
            'rol' => $user['rol'],
            'tipo' => ($user['rol'] == '3') ? 'admin' : $userType, // Agregamos tipo admin
            'direccion' => $user['direccion'] ?? '',
            'telefono' => $user['telefono'] ?? '',
            'foto_perfil' => $user['foto_perfil'] ?? 'default.jpg'
        ];

        // Determinar redirección
        $redirect = '/perfil';
        if ($user['rol'] == '3') {
            $redirect = '/admin';
        }

        // Respuesta exitosa
        echo json_encode([
            'success' => true,
            'redirect' => $redirect,
            'userType' => ($user['rol'] == '3') ? 'admin' : $userType
        ]);

    } catch (Exception $e) {
        error_log("Error en login: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
});

// Ruta para solicitar recuperación
$app->post('/auth/forgot-password', function() use ($app, $pdo) {
    $request = json_decode(file_get_contents('php://input'), true);
    $email = filter_var($request['email'], FILTER_SANITIZE_EMAIL);

    // Verificar si el email existe
    $stmt = $pdo->prepare("SELECT username, nombre FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $app->json(['success' => false, 'message' => 'No existe una cuenta con este email']);
        return;
    }

    // Generar token único
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Guardar token en la base de datos
    $stmt = $pdo->prepare("INSERT INTO password_reset_tokens (username, token, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$user['username'], $token, $expiresAt]);

    // Enviar email con el enlace
    $resetLink = "https://vexo.com.ar/recuperar-contrasena?token=$token";
    $emailSender = new EmailSender();
    $emailSender->sendPasswordReset($email, $user['nombre'], $resetLink);

    $app->json(['success' => true, 'message' => 'Se ha enviado un enlace de recuperación a tu email']);
});

// Ruta para mostrar formulario de nueva contraseña
$app->route('/recuperar-contrasena', function() use ($app) {
    $token = $_GET['token'] ?? '';
    $app->render('recuperar-contrasena.php', ['token' => $token]);
});

// Ruta para procesar el cambio de contraseña
$app->post('/auth/reset-password', function() use ($app, $pdo) {
    $request = json_decode(file_get_contents('php://input'), true);
    $token = $request['token'];
    $newPassword = $request['password'];

    // Validar token
    $stmt = $pdo->prepare("SELECT username FROM password_reset_tokens WHERE token = ? AND used = 0 AND expires_at > NOW()");
    $stmt->execute([$token]);
    $tokenData = $stmt->fetch();

    if (!$tokenData) {
        $app->json(['success' => false, 'message' => 'El enlace no es válido o ha expirado']);
        return;
    }

    // Actualizar contraseña
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->execute([$hashedPassword, $tokenData['username']]);

    // Marcar token como usado
    $stmt = $pdo->prepare("UPDATE password_reset_tokens SET used = 1 WHERE token = ?");
    $stmt->execute([$token]);

    $app->json(['success' => true, 'message' => 'Contraseña actualizada correctamente']);
});

// Ruta para login con Google)
$app->route('/auth/google', function() use ($app) {
    $client = new Google_Client();
    //$client->setClientId('-');
    //$client->setClientSecret('-');
    $client->setRedirectUri('https://vexo.com.ar/auth/google/callback');
    $client->addScope('email');
    $client->addScope('profile');
    
    $authUrl = $client->createAuthUrl();
    $app->redirect($authUrl);
});

$app->route('/auth/google/callback', function() use ($app, $pdo) {
    $client = new Google_Client();
    //$client->setClientId('-');
    //$client->setClientSecret('-');
    $client->setRedirectUri('https://vexo.com.ar/auth/google/callback');
    
    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);
        
        $oauth = new Google_Service_Oauth2($client);
        $googleUser = $oauth->userinfo->get();
        
        // Obtener datos de Google
        $email = $googleUser->getEmail();
        $nombreCompleto = $googleUser->getName();
        $googleId = $googleUser->getId();
        $avatar = $googleUser->getPicture();
        
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR google_id = ?");
        $stmt->execute([$email, $googleId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user && $user['rol'] == '2'){
            $app->redirect('/login/cliente?error=profesionales_deben_usar_login_normal');
            return;
        }
        
        // Separar nombre y apellido
        $nombreParts = explode(' ', $nombreCompleto, 2);
        $nombre = $nombreParts[0];
        $apellido = count($nombreParts) > 1 ? $nombreParts[1] : '';
        
        // Nueva función para manejar el avatar
        $foto_perfil = 'default.jpg'; // Valor por defecto
        
        if ($avatar) {
            try {
                // 1. Descargar la imagen
                $imageData = file_get_contents($avatar);
                
                if ($imageData !== false) {
                    // 2. Determinar tipo de imagen
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->buffer($imageData);
                    $extension = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/gif' => 'gif'
                    ][$mime] ?? 'jpg';
                    
                    // 3. Guardar localmente
                    $uploadDir = __DIR__ . '/public/img/profiles/';
                    $filename = 'google_' . md5($googleId) . '.' . $extension;
                    
                    if (file_put_contents($uploadDir . $filename, $imageData)) {
                        $foto_perfil = $filename;
                    }
                }
            } catch (Exception $e) {
                error_log("Error descargando avatar de Google: " . $e->getMessage());
            }
        }
        
        // Estructura de datos - debe coincidir con el registro normal
        $data = [
            'username' => $email,
            'password' => null,
            'rol' => '1', // Siempre cliente con Google
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'telefono' => null,
            'direccion' => null,
            'foto_perfil' => $foto_perfil,
            'alta' => 1, // Alta inmediata para clientes
            'email_validado' => 1, // Email validado por Google
            'google_id' => $googleId,
            'nro_matricula' => null,
            'documento' => null
        ];
        
        
        if (!$user) {
            // Insertar nuevo usuario
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            
            $stmt = $pdo->prepare("INSERT INTO users ($columns) VALUES ($placeholders)");
            $stmt->execute(array_values($data));
            
            // Obtener el usuario recién creado
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Enviar email de bienvenida
            try {
                $emailSender = new EmailSender();
                $emailSender->sendClienteRegistroExitoso($email, $nombre);
            } catch (Exception $e) {
                error_log("Error enviando email de bienvenida: " . $e->getMessage());
            }
        
        } else {
            // Actualizar usuario existente
            $updateData = [
                'google_id' => $googleId
            ];
            
            if ($user['rol'] === 1){
                $sql = "UPDATE users SET google_id = ? WHERE email = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$googleId, $email]);
            }
        }
        
        // Verificar si el usuario está activo (alta = 1)
        if ($user['alta'] != 1) {
            $app->redirect('/login?error=cuenta_no_habilitada');
            return;
        }
        
        // Iniciar sesión - DEBE COINCIDIR CON EL REGISTRO NORMAL
        $_SESSION['user'] = [
            'username' => $user['username'],
            'email' => $user['email'],
            'nombre' => $user['nombre'],
            'apellido' => $user['apellido'],
            'rol' => $user['rol'],
            'foto_perfil' => $user['foto_perfil'],
            'alta' => $user['alta'],
            'email_validado' => $user['email_validado']
        ];
        
        
        $app->redirect('/perfil');
    } else {
        $app->redirect('/login?error=google_failed');
    }
});

$app->route('POST /auth/register', function() use ($app, $pdo) {   
    header('Content-Type: application/json');

    $tipo = $_POST['tipo'];
    
    // Validar que al menos un servicio fue seleccionado para profesionales
    if ($tipo == 'profesional' && (empty($_POST['servicios']) || !is_array($_POST['servicios']))) {
        echo json_encode([
            'success' => false,
            'message' => 'Debes seleccionar al menos un servicio',
            'errors' => ['servicios' => 'Debes seleccionar al menos un servicio']
        ]);
        return;
    }
    
    // Validar que se subió documento para profesionales
    if ($tipo == 'profesional' && empty($_FILES['documento']['name'])) {
        // Verificar si el checkbox "no tengo matrícula" está marcado
        if (!isset($_POST['no_matricula']) || $_POST['no_matricula'] != 'on') {
            echo json_encode([
                'success' => false,
                'message' => 'El documento que acredita tu profesión es obligatorio',
                'errors' => ['documento' => 'Debes subir un documento']
            ]);
            return;
        } else {
            // Si no tiene matrícula, guardar como null
            $data['nro_matricula'] = null;
            $data['documento'] = null;
        }
    }

    $data = [
        'username' => $_POST['username'],
        'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
        'rol' => $tipo == 'cliente' ? '1' : '2',
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'email' => $_POST['email'],
        'telefono' => $_POST['telefono'],
        'direccion' => $_POST['direccion'],
        'localidad' => $_POST['localidad'],
        'foto_perfil' => 'default.jpg',
        'alta' => $tipo == 'cliente' ? 1 : 0,
        'email_validado' => $tipo == 'cliente' ? 1 : 0
    ];

    // Manejar foto de perfil para clientes (opcional) y profesionales (obligatorio)
    if ($tipo == 'profesional' && empty($_FILES['foto_perfil']['name'])) {
        echo json_encode([
            'success' => false,
            'message' => 'La foto de perfil es obligatoria para profesionales',
            'errors' => ['foto_perfil' => 'La foto de perfil es obligatoria']
        ]);
        return;
    }
    
    if (!empty($_FILES['foto_perfil']['name'])) {
        $uploadDir = __DIR__ . '/public/img/profiles/';
        $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array(strtolower($ext), $allowed)) {
            echo json_encode([
                'success' => false,
                'message' => 'Formato de imagen no válido',
                'errors' => ['foto_perfil' => 'Formatos permitidos: JPG, PNG, GIF']
            ]);
            return;
        }
        
        if ($_FILES['foto_perfil']['size'] > 2000000) {
            echo json_encode([
                'success' => false,
                'message' => 'La imagen es demasiado grande (máx 2MB)',
                'errors' => ['foto_perfil' => 'La imagen no debe superar 2MB']
            ]);
            return;
        }
        
        $filename = 'profile_' . uniqid() . '.' . $ext;
        
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $uploadDir . $filename)) {
            $data['foto_perfil'] = $filename;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al subir la foto de perfil']);
            return;
        }
    }
    
    // Validar y manejar documento para profesionales (ahora obligatorio)
    if ($tipo == 'profesional' && !empty($_FILES['documento']['name'])) {
        $data['nro_matricula'] = $_POST['nro_matricula'];
        
        // Validar tipo de documento
        $allowedDocs = ['pdf', 'jpg', 'jpeg', 'png'];
        $docExt = strtolower(pathinfo($_FILES['documento']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($docExt, $allowedDocs)) {
            echo json_encode([
                'success' => false,
                'message' => 'Formato de documento no válido',
                'errors' => ['documento' => 'Formatos permitidos: PDF, JPG, PNG']
            ]);
            return;
        }
        
        // Validar tamaño del documento (5MB máximo)
        if ($_FILES['documento']['size'] > 5000000) {
            echo json_encode([
                'success' => false,
                'message' => 'El documento es demasiado grande (máx 5MB)',
                'errors' => ['documento' => 'El documento no debe superar 5MB']
            ]);
            return;
        }
        
        $uploadDir = __DIR__ . '/public/docs/';
        $docname = 'doc_' . uniqid() . '.' . $docExt;
        
        if (!move_uploaded_file($_FILES['documento']['tmp_name'], $uploadDir . $docname)) {
            echo json_encode(['success' => false, 'message' => 'Error al subir el documento']);
            return;
        }
        
        // Guardar nombre del documento en la base de datos
        $data['documento'] = $docname;
    } elseif ($tipo == 'profesional' && (isset($_POST['no_matricula']) && $_POST['no_matricula'] == 'on')) {
        $data['nro_matricula'] = null;
        $data['documento'] = null;
    }
    
    try {
        // Verificar si el username ya existe
        $stmt = $pdo->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->execute([$data['username']]);
        if ($stmt->fetch()) {
            echo json_encode([
                'success' => false,
                'message' => 'El nombre de usuario ya está en uso',
                'errors' => ['username' => 'El nombre de usuario ya está en uso']
            ]);
            return;
        }
        
        // Verificar si el email ya existe
        $stmt = $pdo->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            echo json_encode([
                'success' => false,
                'message' => 'El email ya está registrado',
                'errors' => ['email' => 'El email ya está registrado']
            ]);
            return;
        }
        
        // Insertar usuario
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $pdo->prepare("INSERT INTO users ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        
        // Si es profesional, insertar servicios seleccionados
        if ($tipo == 'profesional') {
            $servicios = $_POST['servicios'];
            $username = $data['username'];
            
            $stmt = $pdo->prepare("INSERT INTO usuario_servicios (usuario_username, servicio_id) VALUES (?, ?)");
            
            foreach ($servicios as $servicio_id) {
                // Validar que el servicio existe
                $check = $pdo->prepare("SELECT id FROM servicios WHERE id = ?");
                $check->execute([$servicio_id]);
                
                if ($check->fetch()) {
                    $stmt->execute([$username, $servicio_id]);
                }
            }
        }
        
        // Iniciar sesión automáticamente
        $_SESSION['user'] = $data;
        
        echo json_encode([
            'success' => true,
            'message' => $tipo == 'cliente' 
                ? 'Registro exitoso! Redirigiendo...' 
                : 'Registro exitoso! Tu cuenta será revisada por un administrador.',
            'redirect' => $tipo == 'cliente' ? '/perfil' : '/registro-exitoso']);
            
        $emailSender = new EmailSender();

         // Intentar enviar email pero no romper si falla
        try {
            $emailSender = new EmailSender();
            if ($tipo == 'cliente') {
                $emailSender->sendClienteRegistroExitoso($data['email'], $data['nombre']);
            } else {
                $codigoValidacion = bin2hex(random_bytes(3)); // Generar código de validación
                $emailSender->sendProfesionalRegistroExitoso($data['email'], $data['nombre']);
                $emailSender->sendProfesionalValidacionEmail($data['email'], $data['nombre'], $codigoValidacion);
                
                // Guardar el código en la base de datos para validación posterior
                $stmt = $pdo->prepare("UPDATE users SET codigo_validacion = ? WHERE username = ?");
                $stmt->execute([$codigoValidacion, $data['username']]);
            }
        } catch (Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
        }
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
    }

});

// Ruta para verificar disponibilidad de username
$app->route('GET /auth/check-username', function() use ($app, $pdo) {
    $username = $_GET['username'] ?? '';
    
    try {
        $stmt = $pdo->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->execute([$username]);
        echo json_encode(['available' => !$stmt->fetch()]);
    } catch (PDOException $e) {
        echo json_encode(['available' => false]);
    }
});

// Obtener opciones de un servicio específico (versión adaptada)
$app->route('GET /servicios/@id/opciones', function($id) use ($app, $pdo) {
    header('Content-Type: application/json');
    try {
        $stmt = $pdo->prepare("SELECT * FROM servicio_opciones WHERE servicio_id = ?");
        $stmt->execute([$id]);
        $opciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($opciones);
    } catch (PDOException $e) {
        error_log("Error al obtener opciones: " . $e->getMessage());
        echo json_encode(['error' => 'Error al obtener opciones']);
    }
});

$app->route('GET /servicios/@id/info', function($id) use ($pdo) {
    header('Content-Type: application/json');
    
    try {
        $stmt = $pdo->prepare("SELECT id, categoria, tiene_opciones FROM servicios WHERE id = ?");
        $stmt->execute([$id]);
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($service) {
            echo json_encode($service);
        } else {
            echo json_encode(['error' => 'Servicio no encontrado']);
        }
    } catch (PDOException $e) {
        error_log("Error al obtener servicio: " . $e->getMessage());
        echo json_encode(['error' => 'Error al obtener información del servicio']);
    }
});

// Verificar sesión (versión corregida)
$app->route('GET /auth/check-session', function() use ($app) {
    // Configurar cabeceras primero
    header('Content-Type: application/json');
    
    // Iniciar sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $response = [
        'loggedIn' => false,
        'userType' => null
    ];
    
    try {
        if (isset($_SESSION['user'])) {
            $response['loggedIn'] = true;
            
            // Verificar estructura de $_SESSION['user']
            if (isset($_SESSION['user']['rol'])) {
                $response['userType'] = ($_SESSION['user']['rol'] == '1') ? 'cliente' : 'profesional';
            } elseif (isset($_SESSION['user']['tipo'])) {
                $response['userType'] = $_SESSION['user']['tipo'];
            } else {
                // Si no tiene rol ni tipo, asumimos cliente por compatibilidad
                $response['userType'] = 'cliente';
            }
        }
        
        echo json_encode($response);
    } catch (Exception $e) {
        error_log("Error en check-session: " . $e->getMessage());
        echo json_encode([
            'error' => 'Error al verificar sesión',
            'details' => $e->getMessage()
        ]);
    }
});

// Obtener dirección del usuario (versión adaptada)
$app->route('GET /user/address', function() use ($app, $pdo) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['error' => 'Acceso no autorizado']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT localidad FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['user']['username']]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userData && !empty($userData['localidad'])) {
            echo json_encode(['success' => true, 'localidad' => $userData['localidad']]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Localidad no configurada']);
        }
    } catch (PDOException $e) {
        error_log("Error al obtener la localidad: " . $e->getMessage());
        echo json_encode(['error' => 'Error al obtener dirección']);
    }
});

// Ruta para la vista de solicitudes para profesionales
$app->route('/solicitudes', function() use ($app, $pdo) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != '2') {
        $app->redirect('/login/profesional');
        return;
    }

    $user = $_SESSION['user'];
    
    try {
        // Obtener los servicios del profesional
        $stmt = $pdo->prepare("
            SELECT s.id, s.categoria 
            FROM usuario_servicios us
            JOIN servicios s ON us.servicio_id = s.id
            WHERE us.usuario_username = ?
        ");
        $stmt->execute([$user['username']]);
        $serviciosProfesional = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener IDs de servicios para filtrar
        $servicioIds = array_column($serviciosProfesional, 'id');
        
        if (empty($servicioIds)) {
            $pedidos = [];
        } else {
            // Crear placeholders para la consulta IN
            $placeholders = implode(',', array_fill(0, count($servicioIds), '?'));
            
            // Versión compatible con MySQL 5.7+ (sin funciones JSON avanzadas)
            $stmt = $pdo->prepare("
                SELECT p.*, s.categoria as nombre_servicio, 
                       IFNULL(LENGTH(p.ofertas) - LENGTH(REPLACE(p.ofertas, '\"username\"', '\"x\"')) / LENGTH('\"username\"'), 0) as num_ofertas,
                       IF(p.ofertas LIKE CONCAT('%\"', ?, '\"%'), 1, 0) as ya_oferto
                FROM pedidos p
                JOIN servicios s ON p.categoria_id = s.id
                WHERE p.categoria_id IN ($placeholders)
                AND p.estado = 'pendiente'
                ORDER BY p.created_at DESC
            ");
            
            // Agregar username dos veces (para el LIKE y para los placeholders)
            $params = array_merge([$user['username']], $servicioIds);
            $stmt->execute($params);
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Procesar los resultados para contar ofertas correctamente
            foreach ($pedidos as &$pedido) {
                // Contar ofertas de forma más precisa
                if (!empty($pedido['ofertas'])) {
                    $ofertas = json_decode($pedido['ofertas'], true);
                    $pedido['num_ofertas'] = is_array($ofertas) ? count($ofertas) : 0;
                } else {
                    $pedido['num_ofertas'] = 0;
                }
            }
            unset($pedido); // Romper la referencia
        }
        
        $app->render('header.php', ['title' => 'Vexo - Solicitudes Disponibles']);
        $app->render('solicitudes.php', [
            'pedidos' => $pedidos,
            'serviciosProfesional' => $serviciosProfesional
        ]);
        $app->render('footer.php');
        
    } catch (PDOException $e) {
        error_log("Error en /solicitudes: " . $e->getMessage());
        $app->halt(500, 'Error al cargar las solicitudes');
    }
});

$app->route('POST /api/pedidos/@id/enviar-oferta', function($id) use ($app, $pdo) {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }
    
    $data = $app->request()->data;
    $username = $_SESSION['user']['username'];
    
    try {
        // Verificar que el profesional tiene permiso para ofertar en este servicio
        $stmt = $pdo->prepare("
            SELECT 1 
            FROM pedidos p
            JOIN usuario_servicios us ON p.categoria_id = us.servicio_id
            WHERE p.id = ? AND us.usuario_username = ?
        ");
        $stmt->execute([$id, $username]);
        
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'No tienes permiso para ofertar en este servicio']);
            return;
        }
        
        // Obtener las ofertas actuales
        $stmt = $pdo->prepare("SELECT ofertas FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Decodificar ofertas existentes o crear array vacío
        $ofertas = !empty($pedido['ofertas']) ? json_decode($pedido['ofertas'], true) : [];
        
        // Verificar si ya ofertó
        foreach ($ofertas as $oferta) {
            if (isset($oferta['username']) && $oferta['username'] === $username) {
                echo json_encode(['success' => false, 'message' => 'Ya has enviado una oferta para este pedido']);
                return;
            }
        }
        
        // Crear nueva oferta
        $nuevaOferta = [
            'username' => $username,
            'oferta' => $data['oferta'],
            'descripcion' => $data['descripcion'],
            'fecha_propuesta' => $data['fecha_propuesta'],
            'fecha_oferta' => date('Y-m-d H:i:s')
        ];
        
        // Agregar nueva oferta
        $ofertas[] = $nuevaOferta;
        
        // Actualizar el campo ofertas
        $stmt = $pdo->prepare("UPDATE pedidos SET ofertas = ? WHERE id = ?");
        $stmt->execute([json_encode($ofertas), $id]);
        
        echo json_encode(['success' => true, 'message' => 'Oferta enviada correctamente']);

        try{
            $emailSender = new EmailSender();

            $stmt = $pdo->prepare("SELECT u.email, u.nombre, s.categoria FROM pedidos p 
            JOIN users u ON p.cliente_username = u.username 
            JOIN servicios s ON p.categoria_id = s.id WHERE p.id = ?");
            $stmt->execute([$id]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                $emailSender->sendClienteOfertaRecibida(
                    $cliente['email'],
                    $cliente['nombre'],
                    $cliente['categoria'] 
                );
            }
        } catch (Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
        }
        
    } catch (PDOException $e) {
        error_log("Error al enviar oferta: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al enviar oferta']);
    }
});

// API para finalizar un trabajo
$app->route('POST /api/pedidos/@id/finalizar', function($id) use ($app, $pdo) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }
    
    $data = $app->request()->data;
    $username = $_SESSION['user']['username'];
    
    try {
        // Verificar que el pedido está asignado a este profesional
        $stmt = $pdo->prepare("SELECT 1 FROM pedidos WHERE id = ? AND profesional_username = ? AND estado = 'en_curso'");
        $stmt->execute([$id, $username]);
        
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'No tienes permiso para finalizar este trabajo']);
            return;
        }
        
        // Actualizar estado del pedido
        $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'completado' WHERE id = ?");
        $stmt->execute([$id]);
        
        // Registrar en pedidos_realizados (si es necesario)
        $stmt = $pdo->prepare("
            INSERT INTO pedidos_realizados 
            (pedido_id, profesional_username, comentarios, fecha_finalizacion)
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE comentarios = VALUES(comentarios)
        ");
        $stmt->execute([$id, $username, $data['comentarios'] ?? null]);
        
        echo json_encode(['success' => true, 'message' => 'Trabajo finalizado correctamente']);
        
    } catch (PDOException $e) {
        error_log("Error al finalizar trabajo: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error al finalizar trabajo']);
    }
});


$app->route('GET /validar-email', function() use ($app, $pdo) {
    $codigo = $_GET['codigo'] ?? '';
    
    if (empty($codigo)) {
        $app->render('mensaje-generico', [
            'titulo' => 'Error de Validación',
            'mensaje' => 'No se proporcionó un código de validación válido.',
            'tipo' => 'error',
            'autoCerrar' => true
        ]);
        return;
    }

    try {
        // Buscar al profesional por el código de validación
        $stmt = $pdo->prepare("SELECT username, nombre, email, alta, email_validado FROM users WHERE codigo_validacion = ? AND rol = '2'");
        $stmt->execute([$codigo]);
        $profesional = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$profesional) {
            $app->render('mensaje-generico', [
                'titulo' => 'Código Inválido',
                'mensaje' => 'El código de validación no es correcto o ya fue utilizado.',
                'tipo' => 'error',
                'autoCerrar' => true
            ]);
            return;
        }

        // Si el profesional ya está validado
        if ($profesional['email_validado']) {
            $app->render('mensaje-generico', [
                'titulo' => 'Email Ya Validado',
                'mensaje' => 'Este email ya fue validado anteriormente.',
                'tipo' => 'info',
                'autoCerrar' => true
            ]);
            return;
        }

        // Marcar el email como validado
        $stmt = $pdo->prepare("UPDATE users SET email_validado = 1, codigo_validacion = NULL WHERE username = ?");
        $stmt->execute([$profesional['username']]);

        // Mostrar mensaje de éxito
        $app->render('mensaje-generico', [
            'titulo' => '¡Email Validado!',
            'mensaje' => 'Gracias por validar tu dirección de email. Tu cuenta está pendiente de aprobación por nuestro equipo.',
            'tipo' => 'success',
            'autoCerrar' => true
        ]);

    } catch (PDOException $e) {
        error_log("Error al validar email: " . $e->getMessage());
        $app->render('mensaje-generico', [
            'titulo' => 'Error del Sistema',
            'mensaje' => 'Ocurrió un error al procesar tu validación. Por favor intenta nuevamente más tarde.',
            'tipo' => 'error',
            'autoCerrar' => true
        ]);
    }
});

/*ADMIN*/

$app->route('/admin/logout', function() use ($app) {
    unset($_SESSION['user']);
    $app->redirect('/');
});

$app->route('/admin', function() use ($app, $pdo) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != '3') {
        $app->redirect('/');
        return;
    }
    
    try {
        // Obtener clientes
        $clientes = $pdo->query("SELECT username, nombre, apellido, email, telefono, direccion, created_at FROM users WHERE rol = '1' ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener profesionales
        $profesionales = $pdo->query("SELECT username, nombre, apellido, email, telefono, direccion, foto_perfil, documento, nro_matricula, alta, created_at FROM users WHERE rol = '2' ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener servicios
        $servicios = $pdo->query("SELECT id, categoria, descripcion FROM servicios")->fetchAll(PDO::FETCH_ASSOC);

        // Obtener chats
        $chats = $pdo->query("
            SELECT mc.pedido_id,
                   u1.nombre AS cliente,
                   u2.nombre AS profesional,
                   (SELECT mensaje FROM mensajes_chat m2 WHERE m2.pedido_id = mc.pedido_id ORDER BY m2.fecha_envio DESC LIMIT 1) AS ultimo_mensaje
            FROM mensajes_chat mc
            INNER JOIN pedidos p ON mc.pedido_id = p.id
            INNER JOIN users u1 ON p.cliente_username = u1.username
            INNER JOIN users u2 ON p.profesional_username = u2.username
            GROUP BY mc.pedido_id
            ORDER BY MAX(mc.fecha_envio) DESC")->fetchAll(PDO::FETCH_ASSOC);
        
        $app->render('admin', [
            'clientes' => $clientes,
            'profesionales' => $profesionales,
            'servicios' => $servicios,
            'chats' => $chats
        ]);
        
    } catch (PDOException $e) {
        $app->halt(500, 'Error: ' . $e->getMessage());
    }
});

// API para administradores
$app->route('POST /admin/actualizar-profesional', function() use ($app, $pdo) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != '3') {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }
    
    $data = $app->request()->data;
    $username = $data['username'] ?? '';
    $alta = $data['alta'] ?? 0;
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET alta = ? WHERE username = ?");
        $stmt->execute([$alta, $username]);
        
        // Si se está dando de alta, enviar email de confirmación
        if ($alta) {
            $stmt = $pdo->prepare("SELECT email, nombre FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $profesional = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($profesional) {
                $emailSender = new EmailSender();
                $emailSender->sendProfesionalCuentaAprobada($profesional['email'], $profesional['nombre']);
            }
        }
        
        echo json_encode(['success' => true]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar']);
    }
});

$app->route('POST /admin/eliminar-usuario', function() use ($app, $pdo) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != '3') {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        return;
    }
    
    $data = $app->request()->data;
    $username = $data['username'] ?? '';
    
    try {
        // Primero verificar si es profesional para eliminar sus relaciones
        $stmt = $pdo->prepare("SELECT rol FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            return;
        }
        
        if ($user['rol'] == '2') {
            // Eliminar relaciones de servicios del profesional
            $stmt = $pdo->prepare("DELETE FROM usuario_servicios WHERE usuario_username = ?");
            $stmt->execute([$username]);
        }
        
        // Eliminar el usuario
        $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        echo json_encode(['success' => true]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar']);
    }
});

$app->route('/admin/chat/@pedido_id', function($pedido_id) use ($app, $pdo) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != '3') {
        $app->redirect('/admin/login');
        return;
    }

    $stmt = $pdo->prepare("SELECT * FROM mensajes_chat WHERE pedido_id = ? ORDER BY fecha_envio ASC");
    $stmt->execute([$pedido_id]);
    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Opcional: información adicional del pedido o usuarios
    $app->render('admin-chat-ver', ['mensajes' => $mensajes, 'pedido_id' => $pedido_id]);
});


$app->route('GET /admin/get-user-data', function() use ($app, $pdo) {
    header('Content-Type: application/json');

    // Verificar autenticación
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'error' => 'No autenticado']);
        return;
    }

    if ($_SESSION['user']['rol'] != '3') {
        echo json_encode(['success' => false, 'error' => 'No autorizado']);
        return;
    }

    $request = Flight::request();
    $username = $request->query->username ?? '';
    $tipo = strtolower(trim($request->query->tipo ?? ''));

    if (empty($username) || empty($tipo)) {
        echo json_encode(['success' => false, 'error' => 'Parámetros incompletos']);
        return;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
            return;
        }

        $response = [
            'success' => true,
            'data' => [
                'nombre' => $usuario['nombre'].' '.$usuario['apellido'],
                'username' => $usuario['username'],
                'email' => $usuario['email'],
                'telefono' => $usuario['telefono'] ?? 'N/A',
                'direccion' => $usuario['direccion'] ?? 'N/A',
                'localidad' => $usuario['localidad'] ?? 'N/A',
                'foto_perfil' => '/public/img/profiles/'.($usuario['foto_perfil'] ?? 'default.jpg'),
                'registro' => date('d/m/Y H:i', strtotime($usuario['created_at']))
            ],
            'profesional' => []
        ];

        if ($tipo === 'profesional') {
            $response['profesional'] = [
                'estado' => $usuario['alta'] ? 'Activo' : 'Pendiente',
                'matricula' => $usuario['nro_matricula'] ?? null,
                'documento' => $usuario['documento'] ? [
                    'url' => '/public/docs/'.$usuario['documento'],
                    'ext' => strtoupper(pathinfo($usuario['documento'], PATHINFO_EXTENSION))
                ] : null
            ];

            $stmt = $pdo->prepare("
                SELECT s.categoria 
                FROM usuario_servicios us
                JOIN servicios s ON us.servicio_id = s.id
                WHERE us.usuario_username = ?
            ");
            $stmt->execute([$username]);
            $servicios = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $response['profesional']['servicios'] = $servicios ?: [];
        }

        echo json_encode($response);

    } catch (PDOException $e) {
        log_message("Error en get-user-data: " . $e->getMessage()); // <-- usa tu función de logging
        http_response_code(500); // para que sea más claro
        echo json_encode(['success' => false, 'error' => 'Error en el servidor. Consulta el log.']);
    }
});

// Obtener todos los servicios
$app->route('GET /admin/servicios', function() use ($app, $pdo) {
    $stmt = $pdo->query("SELECT * FROM servicios ORDER BY categoria");
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // En un framework MVC real, aquí renderizarías la vista con los servicios
    echo json_encode(['success' => true, 'data' => $servicios]);
});

// Obtener un servicio por ID
$app->route('GET /admin/servicios/obtener/@id', function($id) use ($app, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
    $stmt->execute([$id]);
    $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($servicio) {
        echo json_encode(['success' => true, 'data' => $servicio]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Servicio no encontrado']);
    }
});

// Agregar nuevo servicio
$app->route('POST /admin/servicios/agregar', function() use ($app, $pdo) {
    $data = [
        'categoria' => $_POST['categoria'],
        'descripcion' => $_POST['descripcion'] ?? null
    ];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO servicios (categoria, descripcion) VALUES (:categoria, :descripcion)");
        $stmt->execute($data);
        
        echo json_encode([
            'success' => true,
            'message' => 'Servicio agregado correctamente',
            'id' => $pdo->lastInsertId()
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al agregar servicio: ' . $e->getMessage()
        ]);
    }
});

// Editar servicio
$app->route('POST /admin/servicios/editar', function() use ($app, $pdo) {
    $data = [
        'id' => $_POST['id'],
        'categoria' => $_POST['categoria'],
        'descripcion' => $_POST['descripcion'] ?? null
    ];
    
    try {
        $stmt = $pdo->prepare("UPDATE servicios SET categoria = :categoria, descripcion = :descripcion WHERE id = :id");
        $stmt->execute($data);
        
        echo json_encode([
            'success' => true,
            'message' => 'Servicio actualizado correctamente'
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar servicio: ' . $e->getMessage()
        ]);
    }
});

// Eliminar servicio
$app->route('DELETE /admin/servicios/eliminar/@id', function($id) use ($app, $pdo) {
    try {
        // Iniciar transacción
        $pdo->beginTransaction();
        
        // 1. Eliminar opciones relacionadas en servicio_opciones
        $stmt = $pdo->prepare("DELETE FROM servicio_opciones WHERE servicio_id = ?");
        $stmt->execute([$id]);
        
        // 2. Eliminar relaciones con profesionales
        $stmt = $pdo->prepare("DELETE FROM usuario_servicios WHERE servicio_id = ?");
        $stmt->execute([$id]);
        
        // 3. Finalmente eliminar el servicio
        $stmt = $pdo->prepare("DELETE FROM servicios WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            $pdo->commit();
            echo json_encode([
                'success' => true,
                'message' => 'Servicio eliminado correctamente'
            ]);
        } else {
            $pdo->rollBack();
            echo json_encode([
                'success' => false,
                'message' => 'Servicio no encontrado'
            ]);
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Error al eliminar servicio: ' . $e->getMessage()
        ]);
    }
});


//CHAT
$app->route('GET /api/chat/@pedido_id/mensajes', function($pedido_id) use ($app, $pdo) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['error' => 'No autorizado']);
        return;
    }
    
    try {
        // Verificar acceso al chat
        $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ? AND 
            (cliente_username = ? OR profesional_username = ? OR ? = (SELECT username FROM users WHERE rol = '3' LIMIT 1))");
        $stmt->execute([
            $pedido_id, 
            $_SESSION['user']['username'], 
            $_SESSION['user']['username'],
            $_SESSION['user']['username']
        ]);
        
        if (!$stmt->fetch()) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }
        
        // Obtener mensajes
        $stmt = $pdo->prepare("
            SELECT m.*, u.nombre, u.foto_perfil 
            FROM mensajes_chat m
            JOIN users u ON m.remitente_username = u.username
            WHERE m.pedido_id = ?
            ORDER BY m.fecha_envio ASC
        ");
        $stmt->execute([$pedido_id]);
        $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Marcar mensajes como leídos si es el receptor
        if ($_SESSION['user']['rol'] != '3') { // Admin ve todo sin marcar como leído
            $pdo->prepare("UPDATE mensajes_chat SET leido = TRUE 
                WHERE pedido_id = ? AND remitente_username != ?")
                ->execute([$pedido_id, $_SESSION['user']['username']]);
        }
        
        echo json_encode(['mensajes' => $mensajes]);
        
    } catch (PDOException $e) {
        error_log("Error al obtener mensajes: " . $e->getMessage());
        echo json_encode(['error' => 'Error al obtener mensajes']);
    }
});

$app->route('POST /api/chat/@pedido_id/mensajes', function($pedido_id) use ($app, $pdo) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'error' => 'No autorizado']);
        $app->redirect('/');
        return;
    }
    
    $data = $app->request()->data;
    $mensaje = trim($data['mensaje'] ?? '');
    
    if (empty($mensaje)) {
        echo json_encode(['success' => false, 'error' => 'Mensaje vacío']);
        return;
    }
    
    try {
        // Verificar acceso al chat y estado del pedido
        $stmt = $pdo->prepare("SELECT estado FROM pedidos WHERE id = ? AND 
            (cliente_username = ? OR profesional_username = ? OR ? = (SELECT username FROM users WHERE rol = '3' LIMIT 1))");
        $stmt->execute([
            $pedido_id, 
            $_SESSION['user']['username'], 
            $_SESSION['user']['username'],
            $_SESSION['user']['username']
        ]);
        
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$pedido) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        if ($pedido['estado'] === 'cancelado' || $pedido['estado'] === 'completado') {
            echo json_encode(['success' => false, 'error' => 'No se puede enviar mensajes en pedidos '.$pedido['estado']]);
            return;
        }
        
        // Insertar mensaje
        $stmt = $pdo->prepare("INSERT INTO mensajes_chat 
            (pedido_id, remitente_username, mensaje) 
            VALUES (?, ?, ?)");
        $stmt->execute([
            $pedido_id,
            $_SESSION['user']['username'],
            $mensaje
        ]);
        
        $emailSender = new EmailSender();
        
        // Obtener datos del pedido para enviar los mails
        $stmt = $pdo->prepare("SELECT p.id, s.categoria, p.codigo, 
                c.username AS cliente_username, c.nombre AS cliente_nombre, c.email AS cliente_email,
                pr.username AS profesional_username, pr.nombre AS profesional_nombre, pr.email AS profesional_email
            FROM pedidos p
            JOIN servicios s ON p.categoria_id = s.id
            JOIN users c ON p.cliente_username = c.username
            JOIN users pr ON p.profesional_username = pr.username
            WHERE p.id = ?");
        $stmt->execute([$pedido_id]);
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($info) {
            // Detectar si el mensaje fue del cliente o profesional
            $esCliente = $_SESSION['user']['username'] === $info['cliente_username'];
            $esProfesional = $_SESSION['user']['username'] === $info['profesional_username'];
        
            if ($esCliente) {
                $emailSender->sendProfesionalMensaje(
                    $info['profesional_email'],
                    $info['profesional_nombre'],
                    $info['categoria'],
                    $info['cliente_nombre'],
                    $info['codigo'],
                    $info['id']
                );
            } elseif ($esProfesional) {
                $emailSender->sendClienteMensaje(
                    $info['cliente_email'],
                    $info['cliente_nombre'],
                    $info['categoria'],
                    $info['profesional_nombre'],
                    $info['codigo'],
                    $info['id']
                );
            }
        }
        
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        
    } catch (PDOException $e) {
        error_log("Error al enviar mensaje: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Error al enviar mensaje']);
    }
});

// Ruta para la vista de chat
$app->route('/chat', function() use ($app, $pdo) {
    if (!isset($_SESSION['user'])) {
        header('Location: /');
        exit;
    }

    $pedido_id = $_GET['pedido_id'] ?? null;

    if (!$pedido_id) {
        header('Location: /pedidos');
        exit;
    }

    // Verificar que el usuario tiene permiso para ver este chat
    try {
        $stmt = $pdo->prepare("SELECT 
            p.id, 
            p.cliente_username, 
            p.profesional_username,
            s.categoria as nombre_servicio,
            p.codigo
        FROM pedidos p
        JOIN servicios s ON p.categoria_id = s.id
        WHERE p.id = ? AND 
        (p.cliente_username = ? OR p.profesional_username = ? OR ? = (SELECT username FROM users WHERE rol = '3' LIMIT 1))");
        
        $stmt->execute([
            $pedido_id, 
            $_SESSION['user']['username'], 
            $_SESSION['user']['username'],
            $_SESSION['user']['username']
        ]);
        
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$pedido) {
            header('Location: /pedidos');
            exit;
        }
    } catch (PDOException $e) {
        error_log("Error verificando acceso al chat: " . $e->getMessage());
        header('Location: /pedidos');
        exit;
    }

    $app->render('header', ['title' => 'Vexo - Chat']);
    $app->render('chat', ['pedido' => $pedido]);
    $app->render('footer');
});

$app->route('/terminos-y-condiciones', function() use ($app) {
    $app->render('terminos.php', [
        'fecha_actualizacion' => '05/06/2025'
    ]);
});

$app->route('/pautas-de-privacidad', function() use ($app) {
    $app->render('pautas.php', [
        'fecha_actualizacion' => '05/06/2025'
    ]);
});

/*MERCADO PAGO*/
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

// Configuración de MercadoPago
MercadoPagoConfig::setAccessToken("APP_USR-7026707268599038-060913-9b11091155959087d2ff752ec531cb64-355052126");

$app->route('POST /api/pedidos/@id/crear-pago', function($id) use ($app, $pdo) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user'])) {
        echo json_encode(['error' => 'Debes iniciar sesión']);
        return;
    }

    try {
        // Obtener información del pedido y la oferta aceptada
        $stmt = $pdo->prepare("
            SELECT 
                p.*,
                s.categoria as nombre_servicio,
                (
                    SELECT jt.oferta
                    FROM JSON_TABLE(
                        p.ofertas, 
                        '$[*]' COLUMNS(
                            username VARCHAR(50) PATH '$.username',
                            oferta DECIMAL(10,2) PATH '$.oferta'
                        )
                    ) AS jt
                    WHERE jt.username COLLATE utf8mb4_unicode_ci = p.profesional_username COLLATE utf8mb4_unicode_ci
                    LIMIT 1
                ) AS monto
            FROM pedidos p
            LEFT JOIN servicios s ON p.categoria_id = s.id
            WHERE p.id = ? AND p.cliente_username = ?
        ");
        $stmt->execute([$id, $_SESSION['user']['username']]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido || !$pedido['profesional_username']) {
            echo json_encode(['error' => 'Pedido no encontrado o sin profesional asignado']);
            return;
        }

        $monto = (float)$pedido['monto'];
        if ($monto <= 0) {
            echo json_encode(['error' => 'Monto inválido']);
            return;
        }

        // Crear preferencia de pago
        $client = new PreferenceClient();
        $preference = $client->create([
            "items" => array(
                array(
                    "title" => "Servicio de ".$pedido['nombre_servicio'],
                    "quantity" => 1,
                    "unit_price" => $monto,
                    "currency_id" => "ARS",
                    "description" => substr($pedido['tipo_trabajo'], 0, 200)
                )
            ),
            "payer" => array(
                "name" => $_SESSION['user']['nombre'],
                "email" => $_SESSION['user']['email']
            ),
            "payment_methods" => array(
                "installments" => 1,
                "default_installments" => 1
            ),
            "external_reference" => $id,
            "notification_url" => "https://vexo.com.ar/notificacion-pago",
            "back_urls" => array(
                "success" => "https://vexo.com.ar/pago/success",
                "failure" => "https://vexo.com.ar/pago/error",
                "pending" => "https://vexo.com.ar/pago/pending"
            ),
            "auto_return" => "all"
        ]);

        // Verificar si ya existe un pago para este pedido
        $stmt = $pdo->prepare("SELECT id FROM pagos WHERE pedido_id = ?");
        $stmt->execute([$id]);
        $pagoExistente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($pagoExistente) {
            // Actualizar registro existente
            $stmt = $pdo->prepare("
                UPDATE pagos SET 
                    mp_preference_id = ?, 
                    monto = ?,
                    mp_status = 'pending',
                    fecha_actualizacion = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$preference->id, $monto, $pagoExistente['id']]);
            $pago_id = $pagoExistente['id'];
        } else {
            // Crear nuevo registro
            $stmt = $pdo->prepare("
                INSERT INTO pagos (
                    pedido_id, 
                    mp_preference_id, 
                    monto,
                    mp_status,
                    fecha_creacion,
                    fecha_actualizacion
                ) VALUES (?, ?, ?, 'pending', NOW(), NOW())
            ");
            $stmt->execute([$id, $preference->id, $monto]);
            $pago_id = $pdo->lastInsertId();
        }

        echo json_encode([
            'success' => true,
            'preference_id' => $preference->id,
            'init_point' => $preference->init_point,
            'pago_id' => $pago_id
        ]);

    } catch (Exception $e) {
        error_log("Error al crear pago: " . $e->getMessage());
        echo json_encode(['error' => 'Error al procesar el pago: ' . $e->getMessage()]);
    }
});

// Endpoint para notificaciones de MercadoPago (versión mejorada)
$app->route('POST|GET /notificacion-pago', function() use ($app, $pdo) {
    // Capturamos datos desde POST o GET
    $payment_id = $_POST['data']['id'] ?? $_POST['id'] ?? $_GET['id'] ?? null;
    $topic = $_POST['topic'] ?? $_GET['topic'] ?? null;

    try {

        $payment_client = new PaymentClient();
        $payment = $payment_client->get($payment_id);

        $pago = null;
        
        // Intento 1: Buscar por payment_id
        if (!$pago) {
            $stmt = $pdo->prepare("SELECT * FROM pagos WHERE mp_payment_id = ?");
            $stmt->execute([$payment_id]);
            $pago = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($pago) error_log("Encontrado por mp_payment_id");
        }

        // Intento 2: Buscar por preference_id (order->id)
        if (!$pago && isset($payment->order->id)) {
            $stmt = $pdo->prepare("SELECT * FROM pagos WHERE mp_preference_id = ?");
            $stmt->execute([$payment->order->id]);
            $pago = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($pago) error_log("Encontrado por mp_preference_id");
        }

        // Intento 3: Buscar por external_reference (pedido_id)
        if (!$pago && isset($payment->external_reference)) {
            $stmt = $pdo->prepare("SELECT * FROM pagos WHERE pedido_id = ?");
            $stmt->execute([$payment->external_reference]);
            $pago = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($pago) error_log("Encontrado por external_reference");
        }

        if (!$pago) {
            error_log("Pago no encontrado en BD. Datos recibidos: " . print_r($payment, true));
            throw new Exception('Pago no encontrado en la base de datos');
        }

        // Actualizar estado del pago
        $stmt = $pdo->prepare("
            UPDATE pagos SET 
                mp_payment_id = ?,
                mp_status = ?,
                metodo_pago = ?,
                fecha_actualizacion = NOW()
            WHERE id = ?
        ");
        $stmt->execute([
            $payment_id,
            $payment->status,
            $payment->payment_type_id ?? null,
            $pago['id']
        ]);

        // Si el pago está aprobado, marcar pedido como finalizado
        if ($payment->status === 'approved') {
            $pdo->prepare("UPDATE pedidos SET estado = 'completado', pago_id = ? WHERE id = ?")
                ->execute([$pago['id'], $pago['pedido_id']]);

            // Enviar notificaciones
            $emailSender = new EmailSender();
            
            // Obtener datos para los emails
            $stmt = $pdo->prepare("
                SELECT p.*, u.email as cliente_email, u.nombre as cliente_nombre,
                       prof.email as profesional_email, prof.nombre as profesional_nombre,
                       s.categoria as nombre_servicio
                FROM pedidos p
                JOIN users u ON p.cliente_username = u.username
                JOIN users prof ON p.profesional_username = prof.username
                JOIN servicios s ON p.categoria_id = s.id
                WHERE p.id = ?
            ");
            $stmt->execute([$pago['pedido_id']]);
            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($pedido) {
                // Enviar email al cliente
                $emailSender->sendClientePagoExitoso(
                    $pedido['cliente_email'],
                    $pedido['cliente_nombre'],
                    $pedido['id'],
                    $pago['monto'],
                    $pedido['nombre_servicio']
                );

                // Enviar email al profesional
                $emailSender->sendProfesionalPagoRecibido(
                    $pedido['profesional_email'],
                    $pedido['profesional_nombre'],
                    $pedido['id'],
                    $pago['monto'],
                    $pedido['nombre_servicio']
                );
            }
        }

        http_response_code(200);
        echo "OK";
    } catch (Exception $e) {
        error_log("Error en notificación de pago: " . $e->getMessage());
        http_response_code(500);
        echo "Error";
    }
});

// Páginas de redirección
$app->route('GET /pago/success', function() use ($app, $pdo) {
    $pedido_id = $_GET['external_reference'] ?? null;
    if ($pedido_id) {
        $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
        $stmt->execute([$pedido_id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    $app->render('header', ['title' => 'Vexo - Pago']);
    $app->render('pago_exitoso', ['pedido' => $pedido ?? null]);
    $app->render('footer');
});

$app->route('GET /pago/error', function() use ($app) {
    $app->render('header', ['title' => 'Vexo - Pago']);
    $app->render('pago_error');
    $app->render('footer');
});

$app->route('GET /pago/pending', function() use ($app) {
    $app->render('header', ['title' => 'Vexo - Pago']);
    $app->render('pago_pendiente');
    $app->render('footer');
});

// Iniciar la aplicación
$app->start();