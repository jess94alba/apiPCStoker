<?php
$usuario = $_POST['Usuario'] ?? '';
$nit = $_POST['NIT'] ?? '';
$contrasena = $_POST['Contraseña'] ?? '';
$tipo = $_POST['Tipo'] ?? '';

// Verificar si todos los campos son requeridos
if (empty($usuario) || empty($nit) || empty($contrasena) || empty($tipo)) {
    echo json_encode(['error' => 'Todos los campos son requeridos.']);
    exit();
}

// Verificar si el usuario o el NIT ya existen
$stmt = $conn->prepare("SELECT * FROM users WHERE name = ? OR nit = ?");
$stmt->bind_param("ss", $usuario, $nit);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['error' => 'El nombre de usuario o el NIT ya están registrados.']);
    exit();
}

$stmt->close(); // Cerrar la consulta

// Continuar con el registro si no hay duplicados
$hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);
$rol = ($tipo === 'proveedor') ? 'proveedor' : 'vendedor';

// Insertar usuario en la tabla users
$stmt = $conn->prepare("INSERT INTO users (name, nit, password, rol) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $usuario, $nit, $hashed_password, $rol);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id; 
    $stmt->close();
    
    // Insertar en la tabla proveedores o vendedores
    $table = ($rol === 'proveedor') ? 'proveedores' : 'vendedores';
    $stmt = $conn->prepare("INSERT INTO $table (user_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Usuario registrado correctamente.']);
    } else {
        echo json_encode(['error' => "Error al registrar $rol: " . $stmt->error]);
    }
} else {
    echo json_encode(['error' => 'Error al registrar: ' . $stmt->error]);
}

$stmt->close();
?>