<?php
require_once 'conexion.php';

// Procesar eliminación desde esta misma página
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $sql = "DELETE FROM ventas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $mensaje = "✅ Venta eliminada exitosamente";
    } else {
        $mensaje = "❌ Error al eliminar";
    }
    $stmt->close();
}

// Obtener todas las ventas
$sql = "SELECT * FROM ventas ORDER BY id DESC";
$result = $conn->query($sql);
$ventas = $result->fetch_all(MYSQLI_ASSOC);

// Calcular total general
$total_general = 0;
foreach ($ventas as $venta) {
    $total_general += $venta['total'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📋 Listado de Ventas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
            padding-left: 15px;
        }

        .btn-agregar {
            background: #10b981;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-agregar:hover {
            background: #059669;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .btn-editar {
            background: #f59e0b;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
        }

        .btn-eliminar {
            background: #ef4444;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
        }

        .btn-editar:hover {
            background: #d97706;
        }

        .btn-eliminar:hover {
            background: #dc2626;
        }

        .mensaje {
            padding: 10px;
            background: #d1fae5;
            color: #065f46;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .total {
            margin-top: 20px;
            padding: 15px;
            background: #fef3c7;
            border-radius: 10px;
            text-align: right;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .acciones {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Sistema de Ventas</h1>
        
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Listado de Ventas</h2>
                <a href="index.html" class="btn-agregar">➕ Nueva Venta</a>
            </div>

            <?php if (isset($mensaje)): ?>
                <div class="mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <?php if (count($ventas) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas as $venta): ?>
                            <tr>
                                <td><?php echo $venta['id']; ?></td>
                                <td><?php echo htmlspecialchars($venta['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($venta['producto']); ?></td>
                                <td><?php echo $venta['cantidad']; ?></td>
                                <td>$<?php echo number_format($venta['precio'], 2); ?></td>
                                <td>$<?php echo number_format($venta['total'], 2); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?></td>
                                <td class="acciones">
                                    <a href="editar_venta.php?id=<?php echo $venta['id']; ?>" class="btn-editar">✏️ Editar</a>
                                    <a href="?eliminar=<?php echo $venta['id']; ?>" class="btn-eliminar" onclick="return confirm('¿Seguro que deseas eliminar esta venta?')">🗑️ Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="total">
                    💰 TOTAL GENERAL: $<?php echo number_format($total_general, 2); ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; padding: 40px;">No hay ventas registradas aún.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>