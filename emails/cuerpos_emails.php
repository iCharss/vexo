<?php
// Plantillas de emails para clientes
$email_cliente_registro_exitoso = function($nombre) {
    $year = date('Y');
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Registro Exitoso - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>¡Bienvenido a Vexo!</h1>
        </div>
        
        <div class="content">
            <p>Hola '. $nombre.',</p>
            <p>¡Gracias por registrarte en Vexo! Ahora puedes solicitar servicios y encontrar profesionales calificados para resolver tus problemas en el hogar.</p>
            <p style="text-align: center;">
                <a href="https://vexo.com.ar/perfil" class="button">Acceder a mi cuenta</a>
            </p>
            <p>Si no realizaste este registro, por favor ignora este mensaje.</p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    return $html;
};

$email_cliente_solicitud_servicio = function($nombre, $servicio, $codigoServicio) {
    $year = date('Y');
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Solicitud de Servicio Recibida - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .code { font-size: 24px; font-weight: bold; color: #4CAF50; text-align: center; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>Solicitud de Servicio Recibida</h1>
        </div>
        
        <div class="content">
            <p>Hola '. $nombre.',</p>
            <p>Hemos recibido tu solicitud de servicio correctamente. Nuestros profesionales en '. $servicio .  ' están siendo notificados y pronto recibirás ofertas.</p>
            
            <p>Tu código de servicio es:</p>
            <div class="code"> vexo_'.$codigoServicio.'</div>
            
            <p>Puedes revisar el estado de tu solicitud en cualquier momento desde tu perfil.</p>
            <p>Gracias por confiar en Vexo.</p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    return $html;
};

$email_cliente_oferta_recibida = function($nombre, $codigoServicio) {
    $year = date('Y');
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>¡Tienes una nueva oferta! - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>¡Tienes una nueva oferta!</h1>
        </div>
        
        <div class="content">
            <p>Hola '. $nombre.',</p>
            <p>Un profesional ha enviado una oferta para tu solicitud de servicio de <strong> '.$codigoServicio.'</strong>.</p>
            
            <p style="text-align: center;">
                <a href="https://vexo.com.ar/pedidos" class="button">Ver oferta</a>
            </p>
            
            <p>Tienes 48 horas para aceptar o rechazar la oferta antes de que expire.</p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    return $html;
};

$email_cliente_presupuesto_aceptado = function($nombre, $servicio, $profesionalNombre, $codigoServicio) {
    $year = date('Y');
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Presupuesto Aceptado - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .info-box { background-color: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>¡Presupuesto Aceptado!</h1>
        </div>
        
        <div class="content">
            <p>Hola '. $nombre.',</p>
            <p>Has aceptado el presupuesto para tu solicitud de servicio de <strong> '.$servicio.'</strong>.</p>
            
            <p>Tu código de servicio es:</p>
            <div class="code"> '.$codigoServicio.'</div>

            <div class="info-box">
                <p><strong>Profesional asignado:</strong> '.$profesionalNombre.'</p>
                <p>El profesional se pondrá en contacto contigo para coordinar los detalles.</p>
            </div>
            
            <p>Recuerda que puedes seguir el progreso del servicio desde tu perfil en Vexo.</p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    return $html;
};

// Plantillas de emails para profesionales
$email_profesional_registro_exitoso = function($nombre) {
    $year = date('Y');
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Registro Exitoso - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>¡Registro Exitoso!</h1>
        </div>
        
        <div class="content">
            <p>Hola '. $nombre.',</p>
            <p>Gracias por registrarte como profesional en Vexo. Tu solicitud está siendo revisada por nuestro equipo.</p>
            <p>Recibirás un correo electrónico una vez que tu cuenta haya sido aprobada.</p>
            <p>Este proceso puede tardar hasta 48 horas hábiles.</p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    return $html;
};

$email_profesional_validacion_email = function($nombre, $codigoValidacion) {
    $year = date('Y');
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Valida tu email - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .code { font-size: 24px; font-weight: bold; color: #4CAF50; text-align: center; margin: 20px 0; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>Valida tu dirección de email</h1>
        </div>
        
        <div class="content">
            <p>Hola '. $nombre.',</p>
            <p>Para completar tu registro como profesional en Vexo, por favor valida tu dirección de email usando el siguiente código:</p>
            
            <div class="code"> '.$codigoValidacion.'</div>
            
            <p>Haz clic en el siguiente botón para validar automáticamente:</p>
            <p style="text-align: center;">
                <a href="https://vexo.com.ar/validar-email?codigo='.$codigoValidacion.'" class="button">Validar mi email</a>
            </p>
            
            <p>Si no solicitaste este registro, por favor ignora este mensaje.</p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    return $html;
};

$email_profesional_cuenta_aprobada = function($nombre) {
    $year = date('Y');
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Cuenta Aprobada - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>¡Tu cuenta ha sido aprobada!</h1>
        </div>
        
        <div class="content">
            <p>Hola '. $nombre.',</p>
            <p>Nos complace informarte que tu cuenta de profesional en Vexo ha sido aprobada.</p>
            <p>Ahora puedes comenzar a recibir solicitudes de servicios y ofertar por ellas.</p>
            
            <p style="text-align: center;">
                <a href="https://vexo.com.ar/solicitudes" class="button">Ver solicitudes disponibles</a>
            </p>
            
            <p>Bienvenido a la comunidad Vexo!</p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    return $html;
};

$email_profesional_nuevo_pedido = function($nombre, $servicio) {
    $year = date('Y');
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Nueva Solicitud de Servicio - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>¡Nueva solicitud de servicio!</h1>
        </div>
        
        <div class="content">
            <p>Hola '. $nombre.',</p>
            <p>Hay una nueva solicitud de trabajo para <strong> '.$servicio.' </strong> que coincide con tus categorías profesionales.</p>
            
            <p style="text-align: center;">
                <a href="https://vexo.com.ar/solicitudes" class="button">Ver solicitud</a>
            </p>
            
            <p>Recuerda que las primeras ofertas tienen mayor visibilidad. ¡No esperes demasiado!</p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    return $html;
};

$email_profesional_presupuesto_aceptado = function($nombre, $servicio, $clienteNombre, $codigoServicio) {
    $year = date('Y');
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>¡Tu presupuesto fue aceptado! - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .info-box { background-color: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>¡Presupuesto Aceptado!</h1>
        </div>
        
        <div class="content">
            <p>Hola '. $nombre.',</p>
            <p>El cliente <strong> '.$clienteNombre.'</strong> ha aceptado tu presupuesto para el servicio de <strong> '.$codigoServicio.'</strong>.</p>
            
            <p>Tu código de servicio es:</p>
            <div class="code"> '.$codigoServicio.'</div>

            <div class="info-box">
                <p>Por favor, ponte en contacto con el cliente para coordinar los detalles del servicio.</p>
                <p>Recuerda que puedes ver los datos de contacto del cliente en la sección de pedidos en curso.</p>
            </div>
            
            <p style="text-align: center;">
                <a href="https://vexo.com/perfil" class="button">Ver detalles del servicio</a>
            </p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    return $html;
};

$email_recuperar_contrasena = function($nombre, $resetLink) {
    $year = date('Y');
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Recupera tu contraseña - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .info-box { background-color: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
                <h1>Recupera tu contraseña</h1>
            </div>
            <div class="content">
                <p>Hola '.$nombre.' ,</p>
                <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en Vexo.</p>
                <p>Haz clic en el siguiente enlace para crear una nueva contraseña:</p>
                <p style="text-align: center; margin: 30px 0;">
                    <a href="'.$resetLink.'" class="button">Restablecer contraseña</a>
                </p>
                <p>Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
                <p>El enlace expirará en 1 hora.</p>
            </div>
            <div class="footer">
                <p>© ' . date('Y') . ' Vexo. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>';
    return $html;
};

// En cuerpos_email.php
$email_cliente_pago_exitoso = function($nombre, $pedidoId, $monto, $servicio) {
    $year = date('Y');
    $montoFormateado = number_format($monto, 2, ',', '.');
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Pago Exitoso - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .success-icon { color: #4CAF50; font-size: 50px; text-align: center; }
            .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #4CAF50; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>¡Pago exitoso!</h1>
        </div>
        
        <div class="content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <p>Hola '. $nombre.',</p>
            <p>Hemos recibido tu pago por el pedido #'.$pedidoId.'. A continuación los detalles:</p>
            
            <div class="info-box">
                <p><strong>Número de pedido:</strong> #'.$pedidoId.'</p>
                <p><strong>Monto pagado:</strong> $'.$montoFormateado.'</p>
                <p><strong>Tipo de servicio:</strong> '.$servicio.'</p>
                <p><strong>Estado:</strong> Pago aprobado</p>
            </div>
            
            <p>Si tienes alguna duda sobre tu pedido, no dudes en contactarnos.</p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    return $html;
};

$email_profesional_pago_recibido = function($nombre, $pedidoId, $monto, $servicio) {
    $year = date('Y');
    $montoFormateado = number_format($monto, 2, ',', '.');
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Pago Recibido - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #4CAF50; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>¡Pago recibido!</h1>
        </div>
        
        <div class="content">
            <p>Hola '. $nombre.',</p>
            <p>El cliente ha realizado el pago por el pedido #'.$pedidoId.'. A continuación los detalles:</p>
            
            <div class="info-box">
                <p><strong>Número de pedido:</strong> #'.$pedidoId.'</p>
                <p><strong>Monto recibido:</strong> $'.$montoFormateado.'</p>
                <p><strong>Tipo de servicio:</strong> '.$servicio.'</p>
                <p><strong>Estado:</strong> Pago aprobado</p>
            </div>
            
            <p>Recuerda que el pago se acreditará en tu cuenta según los términos de Vexo.</p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    return $html;
};

// En cuerpos_email.php
$email_cliente_nuevo_mensaje = function($nombre, $servicio, $profesionalNombre, $codigoPedido, $pedidoId) {
    $year = date('Y');
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Nuevo Mensaje - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #4CAF50; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>¡Tienes un nuevo mensaje!</h1>
        </div>
        
        <div class="content">
            <p>Hola '.$nombre.',</p>
            <p>Has recibido un nuevo mensaje sobre tu pedido de servicio:</p>
            
            <div class="info-box">
                <p><strong>Servicio:</strong> '.$servicio.'</p>
                <p><strong>Profesional:</strong> '.$profesionalNombre.'</p>
                <p><strong>Código de pedido:</strong> #'.$codigoPedido.'</p>
            </div>
            
            <p>Ingresa a la plataforma para ver el mensaje completo y responder.</p>
            
            <p style="text-align: center;">
                <a href="https://vexo.com.ar/chat?pedido_id='.$pedidoId.'" class="button">Ver mensaje</a>
            </p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    
    return $html;
};

$email_profesional_nuevo_mensaje = function($nombre, $servicio, $clienteNombre, $codigoPedido, $pedidoId) {
    $year = date('Y');
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Nuevo Mensaje - Vexo</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .logo { max-width: 150px; }
            .content { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
            .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #4CAF50; }
            .button { display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="https://vexo.com.ar/public/img/vexo-mail.png" alt="Vexo Logo" class="logo">
            <h1>¡Tienes un nuevo mensaje!</h1>
        </div>
        
        <div class="content">
            <p>Hola '.$nombre.',</p>
            <p>Has recibido un nuevo mensaje sobre el pedido de servicio:</p>
            
            <div class="info-box">
                <p><strong>Servicio:</strong> '.$servicio.'</p>
                <p><strong>Cliente:</strong> '.$clienteNombre.'</p>
                <p><strong>Código de pedido:</strong> #'.$codigoPedido.'</p>
            </div>
            
            <p>Ingresa a la plataforma para ver el mensaje completo y responder.</p>
            
            <p style="text-align: center;">
                <a href="https://vexo.com.ar/chat?pedido_id='.$pedidoId.'" class="button">Ver mensaje</a>
            </p>
        </div>
        
        <div class="footer">
            <p>© '.$year.' Vexo. Todos los derechos reservados.</p>
        </div>
    </body>
    </html>';
    
    return $html;
};