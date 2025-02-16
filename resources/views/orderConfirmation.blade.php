<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Envío</title>
</head>
<body>
    <p>Estimado {{ $order->user->name }},</p>
    <p>Su orden ha sido enviada a la dirección de la agencia Zoom {{$order->address}}.</p>
    <p>Gracias por su compra.</p>
</body>
</html>
