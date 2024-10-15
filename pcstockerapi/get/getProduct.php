<?php
session_start();
include 'config.php';

// Obtener el ID del proveedor autenticado
$proveedor_id = $_SESSION['proveedor_id'] ?? null;

if (!$proveedor_id) {
    echo json_encode(['error' => 'Proveedor no autenticado.']);
    exit();
}

if ($action === 'productos') {
    // Obtener productos del proveedor
    $query = "SELECT * FROM productos WHERE proveedor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $proveedor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    // Devolver productos
    echo json_encode($productos);
} else {
    echo json_encode(['error' => 'Acción no válida']);
}
?>
