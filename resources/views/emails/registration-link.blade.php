<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completa tu Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #ff6b6f, #ee5a6f);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b6f, #ee5a6f);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .info-box {
            background-color: #f9f9f9;
            border-left: 4px solid #ff6b6f;
            padding: 15px;
            margin: 15px 0;
        }
        .footer {
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Bienvenido a CHEF!</h1>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $prospect->full_name }}</strong>,</p>

            <p>Tu solicitud ha sido aprobada. Ahora necesitas completar tu registro para acceder a la plataforma.</p>

            <div class="info-box">
                <p><strong>Paso siguiente:</strong></p>
                <p>Haz clic en el botón de abajo para completar tu registro. Necesitarás:</p>
                <ul>
                    <li>Crear una contraseña</li>
                    <li>Subir tu contrato firmado</li>
                    <li>Subir tu forma de pago</li>
                </ul>
            </div>

            <center>
                <a href="{{ $registrationUrl }}" class="button">Completar Registro</a>
            </center>

            <p>O copia este enlace en tu navegador:</p>
            <p style="word-break: break-all; background-color: #f9f9f9; padding: 10px; border-radius: 4px;">
                {{ $registrationUrl }}
            </p>

            <div class="info-box">
                <p><strong>⏰ Importante:</strong> Este enlace es válido por 7 días. Si no lo usas en este tiempo, deberás solicitar uno nuevo.</p>
            </div>

            <p>Si tienes preguntas, no dudes en contactarnos.</p>

            <p>¡Gracias por ser parte de CHEF!</p>
        </div>

        <div class="footer">
            <p>&copy; 2026 CHEF. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
