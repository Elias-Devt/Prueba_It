<?php
include 'db.php';
include 'validacion.php'; 

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'crear':
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $correo = $_POST['correo'];

        if (!Validacion::validarnombre($nombre)) {
            echo json_encode(['status' => 'error', 'message' => 'Nombre inválido.']);
            exit;
        }

        if (!Validacion::validarapellidos($apellidos)) {
            echo json_encode(['status' => 'error', 'message' => 'Apellidos inválidos.']);
            exit;
        }

        if (!Validacion::validarcorreo($correo)) {
            echo json_encode(['status' => 'error', 'message' => 'Correo electrónico inválido.']);
            exit;
        }


        $stc = $conn->prepare("INSERT INTO usuarios (nombre, apellidos, correo) VALUES (:nombre, :apellidos, :correo)");
        $stc->execute(['nombre' => $nombre, 'apellidos' => $apellidos, 'correo' => $correo]);
        echo json_encode(['status' => 'success']);
        break;

    case 'obtener':
        $stc = $conn->prepare("SELECT * FROM usuarios");
        $stc->execute();
        $result = $stc->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
        break;

    case 'eliminar':
        $id = $_POST['id'];

        if (!Validacion::validarid($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
            exit;
        }

        $stc = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
        $stc->execute(['id' => $id]);
        echo json_encode(['status' => 'success']);
        break;

    case 'editar':
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $correo = $_POST['correo'];

        if (!Validacion::validarid($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
            exit;
        }

        if (!Validacion::validarnombre($nombre)) {
            echo json_encode(['status' => 'error', 'message' => 'Nombre inválido.']);
            exit;
        }

        if (!Validacion::validarapellidos($apellidos)) {
            echo json_encode(['status' => 'error', 'message' => 'Apellidos inválidos.']);
            exit;
        }

        if (!Validacion::validarcorreo($correo)) {
            echo json_encode(['status' => 'error', 'message' => 'Correo electrónico inválido.']);
            exit;
        }
        $stc = $conn->prepare("UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, correo = :correo WHERE id = :id");
        $stc->execute(['nombre' => $nombre, 'apellidos' => $apellidos, 'correo' => $correo, 'id' => $id]);
        echo json_encode(['status' => 'success']);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida.']);
        break;
}
?>
