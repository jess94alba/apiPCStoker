<?php
session_start();
include 'config.php';

// Obtener los parámetros de búsqueda
$tipo = $_GET['tipo'] ?? '';
$opcion = $_GET['opcion'] ?? '';

// Validar parámetros
if (empty($tipo) || empty($opcion)) {
    echo json_encode(['error' => 'Tipo y opción son requeridos.']);
    exit();
}

// Validar que el tipo sea uno de los permitidos
$tipos_permitidos = ['tipo', 'marca', 'referencia']; 
if (!in_array($tipo, $tipos_permitidos)) {
    echo json_encode(['error' => 'Tipo no válido.']);
    exit();
}

// Consulta para buscar productos según tipo, marca o referencia
$query = "SELECT p.*, u.name AS proveedor
          FROM productos p
          JOIN proveedores pr ON p.proveedor_id = pr.id
          JOIN users u ON pr.user_id = u.id
          WHERE $tipo LIKE ?";
$stmt = $conn->prepare($query);
$searchOption = "%{$opcion}%";
$stmt->bind_param("s", $searchOption);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    echo json_encode(['productos' => $productos ?: [], 'error' => null]);
} else {
    echo json_encode(['error' => 'Error en la consulta: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
