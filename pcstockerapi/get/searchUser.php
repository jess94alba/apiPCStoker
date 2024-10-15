<?php
$tipo = $_GET['tipo'] ?? '';
$opcion = $_GET['opcion'] ?? '';

// Validar parámetros
if (empty($tipo) || empty($opcion)) {
    echo json_encode(['error' => 'Tipo y opción son requeridos.']);
    exit();
}
// consulta según el tipo
$query = "SELECT id, name, nit, rol FROM users WHERE (rol = ? OR name LIKE ? OR nit LIKE ?)";
$stmt = $conn->prepare($query);
$searchOption = "%{$opcion}%"; 
$stmt->bind_param("sss", $tipo, $searchOption, $searchOption);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    echo json_encode($usuarios ?: ['error' => 'No se encontraron usuarios.']);
} else {
    echo json_encode(['error' => 'Error en la consulta: ' . $stmt->error]);
}
$stmt->close();
?>