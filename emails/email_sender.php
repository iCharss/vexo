<?php
require 'cuerpos_emails.php';
require 'config.php';
require 'vendor/autoload.php'; // Asegúrate de tener PHPMailer instalado via Composer

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';


class EmailSender {
    private $fromEmail = 'no-repply@vexo.com.ar'; // Corregido el dominio
    private $fromName = 'Vexo';
    private $smtpHost = 'smtp.hostinger.com';
    private $smtpUsername = 'no-repply@vexo.com.ar';
    private $smtpPassword = 'Vexo2025-';
    private $smtpPort = 587;
    private $smtpSecure = 'tls';
    
    public function sendEmail($to, $subject, $body) {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true); // true para manejo de excepciones
        
        try {
            // Configuración SMTP
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUsername;
            $mail->Password = $this->smtpPassword;
            $mail->SMTPSecure = $this->smtpSecure;
            $mail->Port = $this->smtpPort;
            
            // Configuración del email
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addReplyTo('Vexo@grupoyex.com', 'Vexo');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->CharSet = 'UTF-8'; // Importante para caracteres especiales
            
            if(!$mail->send()) {
                error_log("Error al enviar email a $to: " . $mail->ErrorInfo);
                return false;
            }
            return true;
            
        } catch (Exception $e) {
            error_log("Excepción al enviar email: " . $e->getMessage());
            return false;
        }
    }
    
    // Métodos específicos para cada tipo de email
    
    public function sendClienteRegistroExitoso($email, $nombre) {
        global $email_cliente_registro_exitoso;
        $subject = "¡Bienvenido a Vexo, $nombre!";
        $body = $email_cliente_registro_exitoso($nombre);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendClienteSolicitudServicio($email, $nombre, $servicio, $codigoServicio) {
        global $email_cliente_solicitud_servicio;
        $subject = "Solicitud de servicio de $servicio recibida ";
        $body = $email_cliente_solicitud_servicio($nombre, $servicio, $codigoServicio);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendClienteOfertaRecibida($email, $nombre, $codigoServicio) {
        global $email_cliente_oferta_recibida;
        $subject = "Tienes una nueva oferta en tu solicitud de $codigoServicio.";
        $body = $email_cliente_oferta_recibida($nombre, $codigoServicio);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendClientePresupuestoAceptado($email, $nombre, $servicio, $profesionalNombre, $codigoServicio) {
        global $email_cliente_presupuesto_aceptado;
        $subject = "Presupuesto aceptado para tu solicitud de $servicio";
        $body = $email_cliente_presupuesto_aceptado($nombre, $servicio, $profesionalNombre, $codigoServicio);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendProfesionalRegistroExitoso($email, $nombre) {
        global $email_profesional_registro_exitoso;
        $subject = "Registro exitoso - Vexo Profesional";
        $body = $email_profesional_registro_exitoso($nombre);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendProfesionalValidacionEmail($email, $nombre, $codigoValidacion) {
        global $email_profesional_validacion_email;
        $subject = "Valida tu email - Vexo";
        $body = $email_profesional_validacion_email($nombre, $codigoValidacion);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendProfesionalCuentaAprobada($email, $nombre) {
        global $email_profesional_cuenta_aprobada;
        $subject = "¡Tu cuenta Vexo ha sido aprobada!";
        $body = $email_profesional_cuenta_aprobada($nombre);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendProfesionalNuevoPedido($email, $nombre, $servicio) {
        global $email_profesional_nuevo_pedido;
        $subject = "Nueva solicitud de trabajo: $servicio";
        $body = $email_profesional_nuevo_pedido($nombre, $servicio);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendProfesionalPresupuestoAceptado($email, $nombre, $servicio, $clienteNombre, $codigoServicio) {
        global $email_profesional_presupuesto_aceptado;
        $subject = "¡Tu presupuesto para $servicio fue aceptado!";
        $body = $email_profesional_presupuesto_aceptado($nombre, $servicio, $clienteNombre, $codigoServicio);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendPasswordReset($email, $nombre, $resetLink) {
        global $email_recuperar_contrasena;
        $subject = "Recupera tu contraseña - Vexo";
        $body = $email_recuperar_contrasena($nombre, $resetLink);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendClientePagoExitoso($email, $nombre, $pedidoId, $monto, $servicio) {
        global $email_cliente_pago_exitoso;
        $subject = "¡Pago exitoso - Pedido #$pedidoId | $servicio";
        $body = $email_cliente_pago_exitoso($nombre, $pedidoId, $monto, $servicio);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendProfesionalPagoRecibido($email, $nombre, $pedidoId, $monto, $servicio) {
        global $email_profesional_pago_recibido;
        $subject = "Pago recibido - Pedido #$pedidoId | $servicio";
        $body = $email_profesional_pago_recibido($nombre, $pedidoId, $monto, $servicio);
        return $this->sendEmail($email, $subject, $body);
    }
    

    public function sendClienteMensaje($email, $nombre, $servicio, $profesionalNombre, $codigoPedido, $pedidoId) {
        global $email_cliente_nuevo_mensaje;
        $subject = "Nuevo mensaje sobre tu pedido de $servicio | #$pedidoId";
        $body = $email_cliente_nuevo_mensaje($nombre, $servicio, $profesionalNombre, $codigoPedido, $pedidoId);
        return $this->sendEmail($email, $subject, $body);
    }
    
    public function sendProfesionalMensaje($email, $nombre, $servicio, $clienteNombre, $codigoPedido, $pedidoId) {
        global $email_profesional_nuevo_mensaje;
        $subject = "Nuevo mensaje sobre el pedido de $servicio | #$pedidoId";
        $body = $email_profesional_nuevo_mensaje($nombre, $servicio, $clienteNombre, $codigoPedido, $pedidoId);
        return $this->sendEmail($email, $subject, $body);
    }
}