<?php
header('Content-Type: application/json');

// Obtener el ID del producto a actualizar
$id_producto = $_POST['id'] ?? null; 
$cantidad = $_POST['cantidad'] ?? null;
$tipo = $_POST['tipo'] ?? null;
$marca = $_POST['marca'] ?? null;
$referencia = $_POST['referencia'] ?? null;
$caracteristicas = $_POST['caracteristicas'] ?? null;
$valor = $_POST['valor'] ?? null;

// ID del producto no sea nulo
if (is_null($id_producto)) {
    echo json_encode(['error' => 'Debe proporcionar el ID del producto para actualizar.']);
    exit();
}

// Preparar la consulta 
$campos = [];
$valores = [];
$tipos = "";

// Campos a actualizar
if (!is_null($cantidad)) {
    $campos[] = 'cantidad = ?';
    $valores[] = $cantidad;
    $tipos .= 'i'; 
}
if (!is_null($tipo)) {
    $campos[] = 'tipo = ?';
    $valores[] = $tipo;
    $tipos .= 's'; 
}
if (!is_null($marca)) {
    $campos[] = 'marca = ?';
    $valores[] = $marca;
    $tipos .= 's';
}
if (!is_null($referencia)) {
    $campos[] = 'referencia = ?';
    $valores[] = $referencia;
    $tipos .= 's';
}
if (!is_null($caracteristicas)) {
    $campos[] = 'caracteristicas = ?';
    $valores[] = $caracteristicas;
    $tipos .= 's';
}
if (!is_null($valor)) {
    $campos[] = 'valor = ?';
    $valores[] = $valor;
    $tipos .= 'i'; 
}

// Verificar que al menos un campo fue proporcionado
if (empty($campos)) {
    echo json_encode(['error' => 'Debe proporcionar al menos un campo para actualizar el producto.']);
    exit();
}

// Generar la consulta SQL
$sql = "UPDATE productos SET " . implode(", ", $campos) . " WHERE id = ?";
$valores[] = $id_producto;

// Preparar la declaración
$stmt = $conn->prepare($sql);
$stmt->bind_param($tipos . 'i', ...$valores); 

// Ejecutar la consulta
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => 'Producto actualizado correctamente.']);
    } 
    else {
        echo json_encode(['error' => 'No se encontraron cambios en el producto.']);
    }
} else {
    echo json_encode(['error' => 'Error al actualizar producto: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>