<?php
$id = $_POST['id'] ?? ''; 
$usuario = $_POST['Usuario'] ?? '';
$nit = $_POST['NIT'] ?? '';
$contrasena = $_POST['Contraseña'] ?? '';
$rol = $_POST['Rol'] ?? '';

// Validar ID del usuario
if (empty($id)) {
    echo json_encode(['error' => 'El ID es obligatorio.']);
    exit();
}
// Consultar si se debe actualizar la contraseña
if (!empty($contrasena)) {
    $hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET name = ?, nit = ?, password = ?, rol = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $usuario, $nit, $hashed_password, $rol, $id);
} else {
    $stmt = $conn->prepare("UPDATE users SET name = ?, nit = ?, rol = ? WHERE id = ?");
    $stmt->bind_param("sssi", $usuario, $nit, $rol, $id);
}

if ($stmt->execute()) {
    echo json_encode(['success' => 'Usuario actualizado correctamente.']);
} else {
    echo json_encode(['error' => 'Error al actualizar usuario: ' . $stmt->error]);
}
$stmt->close();
?>