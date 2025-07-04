<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .mensaje-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            max-width: 500px;
            width: 90%;
            text-align: center;
        }
        .mensaje-titulo {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }
        .mensaje-texto {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }
        .success { border-top: 4px solid #4CAF50; }
        .error { border-top: 4px solid #f44336; }
        .info { border-top: 4px solid #2196F3; }
        .contador {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .modal-logo {
            text-align: center;
        }
        .modal-logo .logo {
            height: 100px;
            animation: logoPulse 2s ease-in-out infinite;
        }

        @keyframes logoPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
    </style>
</head>
<body>
    <div class="mensaje-container <?= $tipo ?>">
        <div class="modal-logo">
            <img src="/public/img/wefix-f.webp" alt="WeFix Logo" class="logo">
        </div>
        <h1 class="mensaje-titulo"><?= $titulo ?></h1>
        <p class="mensaje-texto"><?= $mensaje ?></p>
        
        <?php if (isset($autoCerrar) && $autoCerrar): ?>
            <div class="contador">Esta página se cerrará automáticamente en <span id="segundos">10</span> segundos...</div>
            <script>
                let segundos = 10;
                const contador = document.getElementById('segundos');
                
                const intervalo = setInterval(() => {
                    segundos--;
                    contador.textContent = segundos;
                    
                    if (segundos <= 0) {
                        clearInterval(intervalo);
                        window.close();
                    }
                }, 1000);
            </script>
        <?php endif; ?>
    </div>
</body>
</html>