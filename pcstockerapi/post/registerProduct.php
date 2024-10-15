<?php
session_start(); 

$tipo = $_POST['tipo'] ?? '';
$caracteristicas = $_POST['caracteristicas'] ?? '';
$marca = $_POST['marca'] ?? '';
$referencia = $_POST['referencia'] ?? '';
$cantidad = $_POST['cantidad'] ?? null;
$valor = $_POST['valor'] ?? null;

// Validar que el campo tipo
if (empty($tipo)) {
    echo json_encode(['error' => 'El campo "Tipo" es obligatorio.']);
    exit();
}

// Verificacion de que el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No estás autenticado.']);
    exit();
}

// Obtener el id del proveedor autenticado
$proveedor_id = $_SESSION['proveedor_id'] ?? null;

if (empty($proveedor_id)) {
    echo json_encode(['error' => 'No se encontró el proveedor asociado al usuario.']);
    exit();
}

// Insertar el producto en la tabla productos
$stmt = $conn->prepare("INSERT INTO productos (tipo, marca, referencia, caracteristicas, cantidad, valor, proveedor_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssiis", $tipo, $marca, $referencia, $caracteristicas, $cantidad, $valor, $proveedor_id);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Producto registrado correctamente.']);
} else {
    echo json_encode(['error' => 'Error al registrar el producto: ' . $stmt->error]);
}

$conn->close(); 
?>