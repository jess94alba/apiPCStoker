<?php
session_start();
include 'config.php';

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'POST':
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'login':
                    include 'post/login.php';
                    break;
                case 'registrar':
                    include 'post/registerUser.php'; 
                    break;
                case 'registrarProducto':
                    include 'post/registerProduct.php'; 
                    break;
                case 'editar':
                    include 'post/editUser.php'; 
                    break;
                case 'editarProducto':
                    include 'post/editProduct.php'; 
                    break;
                case 'eliminarProducto':
                    include 'post/removeProduct.php'; 
                    break;
                case 'eliminarUsuario':
                    include 'post/removeUser.php'; 
                    break;
                default:
                    echo json_encode(['error' => 'Acción no válida']);
            }
        } else {
            echo json_encode(['error' => 'Acción no especificada']);
        }
        break;

    case 'GET':
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'usuarios':
                    include 'get/getUser.php';
                    break;
                case 'buscar':
                    include 'get/searchUser.php'; 
                    break;
                case 'productos':
                    include 'get/getProduct.php'; 
                    break;
                case 'buscarProducto':
                    include 'get/searchProduct.php'; 
                    break;
                case 'productosVendedor':
                    include 'get/searchProductSeller.php'; 
                    break;
                case 'buscarTodosProductos':
                    include 'get/getAllProducts.php'; 
                    break;
                default:
                    echo json_encode(['error' => 'Acción no válida']);
            }
        } else {
            echo json_encode(['error' => 'Acción no especificada']);
        }
        break;

    default:
        echo json_encode(['error' => 'Método de solicitud no permitido.']);
        break;
}

$conn->close();
?>