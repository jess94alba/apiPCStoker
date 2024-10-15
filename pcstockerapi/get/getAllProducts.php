<?php
session_start();

// Consulta para recuperar todos los productos con el nombre del proveedor asociado
$sql = "SELECT p.tipo, p.cantidad, p.marca, p.referencia, p.caracteristicas, p.valor, u.name AS proveedor
        FROM productos p
        JOIN proveedores pr ON p.proveedor_id = pr.id
        JOIN users u ON pr.user_id = u.id";

if ($stmt = $conn->prepare($sql)) {
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $productos = [];
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        // Devolver los resultados 
        echo json_encode(['productos' => $productos, 'error' => null]);
    } else {
        echo json_encode(['productos' => [], 'error' => 'No hay productos registrados']);
    }

    $stmt->close();
} else {
    echo json_encode(['productos' => [], 'error' => 'Error en la consulta: ' . htmlspecialchars($conn->error)]);
}

$conn->close();
?>