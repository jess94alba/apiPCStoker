<?php

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        
        // Consulta para eliminar el usuario
        $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        // Ejecutar la consulta y verificar el resultado
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al eliminar el producto.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'ID no especificado.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método de solicitud no permitido.']);
}

$conn->close();
?>
