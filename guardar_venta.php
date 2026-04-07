<?php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'ventas_db';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error conexión: ' . $conn->connect_error]);
    exit;
}

$conn->set_charset("utf8mb4");

// Recibir datos
$cliente = trim($_POST['cliente'] ?? '');
$producto = trim($_POST['producto'] ?? '');
$cantidad = floatval($_POST['cantidad'] ?? 0);
$precio = floatval($_POST['precio_unitario'] ?? 0);  // Cambiado: precio_unitario -> precio
$total = floatval($_POST['total'] ?? 0);

// Validaciones
if (empty($cliente)) {
    echo json_encode(['success' => false, 'message' => '⚠️ Cliente es obligatorio']);
    exit;
}

if (empty($producto)) {
    echo json_encode(['success' => false, 'message' => '⚠️ Producto es obligatorio']);
    exit;
}

if ($cantidad <= 0) {
    echo json_encode(['success' => false, 'message' => '⚠️ Cantidad inválida']);
    exit;
}

if ($precio <= 0) {
    echo json_encode(['success' => false, 'message' => '⚠️ Precio inválido']);
    exit;
}

if ($total <= 0) {
    echo json_encode(['success' => false, 'message' => '⚠️ Total inválido']);
    exit;
}

// Usar 'precio' en lugar de 'precio_unitario'
$sql = "INSERT INTO ventas (cliente, producto, cantidad, precio, total) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error SQL: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ssidd", $cliente, $producto, $cantidad, $precio, $total);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'message' => '✅ ¡VENTA GUARDADA EXITOSAMENTE!',
        'cliente' => $cliente,
        'producto' => $producto,
        'cantidad' => $cantidad,
        'precio' => $precio,
        'total' => $total,
        'id' => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Error al guardar: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>