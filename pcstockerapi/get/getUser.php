<?php
// Consultar todos los usuarios
$query = "SELECT id, name, nit, rol FROM users";
$result = $conn->query($query);

$usuarios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}
echo json_encode($usuarios ?: ['error' => 'No se encontraron usuarios.']);
?>
