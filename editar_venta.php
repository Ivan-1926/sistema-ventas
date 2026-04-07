<?php
require_once 'conexion.php';

$mensaje = '';
$tipo_mensaje = '';

// Obtener ID de la venta a editar
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $cliente = trim($_POST['cliente']);
    $producto = trim($_POST['producto']);
    $cantidad = floatval($_POST['cantidad']);
    $precio = floatval($_POST['precio']);
    $total = $cantidad * $precio;
    
    $sql = "UPDATE ventas SET cliente=?, producto=?, cantidad=?, precio=?, total=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiddi", $cliente, $producto, $cantidad, $precio, $total, $id);
    
    if ($stmt->execute()) {
        $mensaje = "✅ Venta actualizada exitosamente";
        $tipo_mensaje = "exito";
    } else {
        $mensaje = "❌ Error al actualizar: " . $stmt->error;
        $tipo_mensaje = "error";
    }
    $stmt->close();
}

// Obtener datos de la venta
$sql = "SELECT * FROM ventas WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$venta = $result->fetch_assoc();
$stmt->close();

if (!$venta) {
    die("Venta no encontrada");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>✏️ Editar Venta</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
            padding-left: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
        }

        input[readonly] {
            background: #f5f5f5;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-right: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .mensaje {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .mensaje-exito {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .mensaje-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .total-preview {
            margin-top: 10px;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Editar Venta</h1>
        
        <div class="card">
            <h2>ID Venta: <?php echo $venta['id']; ?></h2>

            <?php if ($mensaje): ?>
                <div class="mensaje mensaje-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $venta['id']; ?>">
                
                <div class="form-group">
                    <label>👤 Cliente</label>
                    <input type="text" name="cliente" value="<?php echo htmlspecialchars($venta['cliente']); ?>" required>
                </div>

                <div class="form-group">
                    <label>📦 Producto</label>
                    <input type="text" name="producto" value="<?php echo htmlspecialchars($venta['producto']); ?>" required>
                </div>

                <div class="form-group">
                    <label>🔢 Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" value="<?php echo $venta['cantidad']; ?>" min="1" required>
                </div>

                <div class="form-group">
                    <label>💰 Precio Unitario</label>
                    <input type="number" id="precio" name="precio" value="<?php echo $venta['precio']; ?>" step="0.01" min="0.01" required>
                </div>

                <div class="form-group">
                    <label>💵 Total</label>
                    <input type="text" id="total" readonly value="$<?php echo number_format($venta['total'], 2); ?>">
                </div>

                <div class="total-preview" id="preview">
                    Total calculado: $<?php echo number_format($venta['total'], 2); ?>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
                    <a href="listar_ventas.php" class="btn btn-secondary">⬅️ Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const cantidadInput = document.getElementById('cantidad');
        const precioInput = document.getElementById('precio');
        const totalInput = document.getElementById('total');
        const preview = document.getElementById('preview');

        function calcularTotal() {
            const cantidad = parseFloat(cantidadInput.value) || 0;
            const precio = parseFloat(precioInput.value) || 0;
            const total = cantidad * precio;
            totalInput.value = '$' + total.toFixed(2);
            preview.innerHTML = 'Total calculado: $' + total.toFixed(2);
        }

        cantidadInput.addEventListener('input', calcularTotal);
        precioInput.addEventListener('input', calcularTotal);
    </script>
</body>
</html>