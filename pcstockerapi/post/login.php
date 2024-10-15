<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, name, password, rol FROM users WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña hasheada
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['rol'] = $row['rol'];

            // Si es proveedor obtener su proveedor_id
            if ($row['rol'] == 'proveedor') {
                $stmt = $conn->prepare("SELECT id FROM proveedores WHERE user_id = ?");
                $stmt->bind_param("i", $row['id']);
                $stmt->execute();
                $stmt->bind_result($proveedor_id);
                $stmt->fetch();
                $stmt->close();
                
                $_SESSION['proveedor_id'] = $proveedor_id;
            }

            echo json_encode([
                'success' => [
                    'rol' => $row['rol'],
                    'user_id' => $row['id']
                ]
            ]);
        } else {
            echo json_encode(["error" => "Contraseña incorrecta."]);
        }
    } else {
        echo json_encode(["error" => "Usuario no encontrado."]);
    }
}

$conn->close();
?>
