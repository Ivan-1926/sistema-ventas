<?php
require_once 'conexion.php';

$mensaje = '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $sql = "DELETE FROM ventas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensaje = "✅ Venta eliminada exitosamente";
        $tipo = "exito";
    } else {
        $mensaje = "❌ Error al eliminar: " . $stmt->error;
        $tipo = "error";
    }
    $stmt->close();
} else {
    $mensaje = "❌ ID de venta no válido";
    $tipo = "error";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Venta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
        }
        .exito { color: #10b981; }
        .error { color: #ef4444; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="<?php echo $tipo; ?>"><?php echo $mensaje; ?></h2>
        <a href="listar_ventas.php" class="btn">⬅️ Volver al Listado</a>
    </div>
</body>
</html>