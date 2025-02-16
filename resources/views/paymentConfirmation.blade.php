<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pago</title>
</head>
<body>
    <p>Su pago por <strong>{{ number_format($payment->amount, 2) }} USD</strong> con referencia <strong>{{ $payment->reference }}</strong> ha sido aprobado con éxito.</p>
    <p>Gracias por su confianza.</p>
</body>
</html>
