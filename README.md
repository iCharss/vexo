# Vexo

**Vexo** es una plataforma web desarrollada en PHP que conecta personas que necesitan servicios para el hogar y tecnología con profesionales calificados. Permite gestionar solicitudes, presupuestos, pagos online y comunicación entre clientes y profesionales, todo desde una interfaz moderna y segura.

## 🚀 Demo en producción

Puedes ver Vexo funcionando en: [https://vexo.com.ar](https://vexo.com.ar)

---

## Tabla de Contenidos

- [Características](#características)
- [Tecnologías](#tecnologías)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Uso](#uso)
- [Integraciones](#integraciones)
- [Licencia](#licencia)

---

## Características

- Registro y login de clientes y profesionales (incluye Google OAuth).
- Validación de email y aprobación de profesionales.
- Gestión de servicios y pedidos (solicitudes, presupuestos, aceptación, cancelación).
- Chat interno entre cliente y profesional.
- Sistema de valoraciones y testimonios.
- Gestión de pagos online con MercadoPago (Checkout Pro).
- Panel de administración para gestión de usuarios y servicios.
- Notificaciones por email (registro, ofertas, mensajes, pagos, etc).
- Responsive design y animaciones modernas.

---

## Tecnologías

- **Backend:** PHP 8.x, [FlightPHP](https://flightphp.com/) (micro-framework)
- **Frontend:** HTML5, CSS3, JavaScript (vanilla)
- **Base de datos:** MySQL
- **Emails:** PHPMailer, plantillas HTML personalizadas
- **Pagos:** MercadoPago SDK v3
- **Otros:** Google OAuth, Monolog (logging), Composer (autoloader y dependencias)

---

## Estructura del Proyecto

```
/
├── config.php                # Configuración de base de datos
├── index.php                 # Entry point y rutas principales (FlightPHP)
├── composer.json             # Dependencias PHP
├── public/                   # Archivos públicos (CSS, JS, imágenes)
│   ├── assets/               # JS y CSS
│   ├── docs/                 # Documentos subidos por usuarios
│   └── img/                  # Imágenes y avatares
├── emails/                   # Plantillas y lógica de envío de emails
│   ├── cuerpos_emails.php
│   └── email_sender.php
├── views/                    # Vistas PHP (header, footer, páginas, etc)
├── vendor/                   # Dependencias instaladas por Composer
└── README.md                 
```

## Uso

- Accede a la web y regístrate como cliente o profesional.
- Los clientes pueden solicitar servicios, recibir presupuestos y chatear con profesionales.
- Los profesionales pueden ver solicitudes, enviar ofertas y gestionar trabajos.
- El administrador puede aprobar profesionales y gestionar usuarios y servicios desde `/admin`.

---

## Integraciones

- **MercadoPago:** Pagos online seguros, integración con notificaciones IPN.
- **PHPMailer:** Envío de emails transaccionales con plantillas HTML.
- **Google OAuth:** Login rápido y seguro para usuarios.
- **Monolog:** Logging avanzado de errores y eventos.

---

## Licencia

Este proyecto es privado y su código fuente no está autorizado para uso comercial ni distribución sin permiso del autor.

---

## Autor

Desarrollado por Carlos Rodriguez para [vexo.com.ar](https://vexo.com.ar).

