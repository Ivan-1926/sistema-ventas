<!DOCTYPE html>
<html>
<head>
    <title>Test Conexión</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .ok { background: #d4edda; color: #155724; padding: 15px; margin: 10px 0; border-radius: 8px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; margin: 10px 0; border-radius: 8px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #007bff; color: white; }
    </style>
</head>
<body>
    <h1>🔌 Prueba de Conexión - caso01</h1>

<?php
require_once 'conexion.php';

if ($conn->ping()) {
    echo '<div class="ok">✅ Conexión exitosa a MySQL/MariaDB</div>';
    echo '<div class="ok">📊 Base de datos: ventas_db</div>';
    
    // Verificar tabla
    $result = $conn->query("SHOW TABLES LIKE 'ventas'");
    
    if ($result->num_rows > 0) {
        echo '<div class="ok">✅ Tabla "ventas" existe</div>';
        
        // Mostrar ventas
        $ventas = $conn->query("SELECT * FROM ventas ORDER BY id DESC");
        
        if ($ventas->num_rows > 0) {
            echo "<h2>📝 Ventas registradas:</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Cliente</th><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Total</th><th>Fecha</th></tr>";
            while($row = $ventas->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['cliente']}</td>";
                echo "<td>{$row['producto']}</td>";
                echo "<td>{$row['cantidad']}</td>";
                echo "<td>\${$row['precio_unitario']}</td>";
                echo "<td>\${$row['total']}</td>";
                echo "<td>{$row['fecha']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo '<div class="error">⚠️ No hay ventas registradas aún</div>';
        }
    } else {
        echo '<div class="error">❌ Tabla "ventas" NO existe. Ejecuta el SQL.</div>';
    }
} else {
    echo '<div class="error">❌ Error de conexión</div>';
}

$conn->close();
?>
</body>
</html>