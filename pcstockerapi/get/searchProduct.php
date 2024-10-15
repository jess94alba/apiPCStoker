<?php
session_start();
include 'config.php';

// Obtener el ID del proveedor autenticado
$proveedor_id = $_SESSION['proveedor_id'] ?? null;

if (!$proveedor_id) {
    echo json_encode(['error' => 'Proveedor no autenticado.']);
    exit();
}

// Parámetros de búsqueda
$tipo = $_GET['tipo'] ?? '';
$opcion = $_GET['opcion'] ?? '';

// Validar parámetros
if (empty($tipo) || empty($opcion)) {
    echo json_encode(['error' => 'Tipo y opción son requeridos.']);
    exit();
}

// Consulta para buscar productos del proveedor según tipo, marca o referencia
$query = "SELECT * FROM productos WHERE proveedor_id = ? AND ($tipo LIKE ?)";
$stmt = $conn->prepare($query);
$searchOption = "%{$opcion}%";
$stmt->bind_param("is", $proveedor_id, $searchOption);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    echo json_encode($productos ?: ['error' => 'No se encontraron productos.']);
} else {
    echo json_encode(['error' => 'Error en la consulta: ' . $stmt->error]);
}

$stmt->close();
?>