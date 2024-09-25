<?php
header('Content-Type: application/json');
require 'src\Controller\produtos.php';
require 'src\Controller\log.php';

$produto = new produtos();
$log = new log();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $result = $produto->getById($_GET['id']);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Produto não encontrado']);
            }
        } else if (isset($_GET['logs'])) {
            $result = $log->getAll();
            echo json_encode($result);
        } else {
            echo json_encode($produto->getAll());
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $produto->create($data);
        echo json_encode(['message' => $result]);
        break;

    case 'PUT':
        $id = $_GET['id']; // Captura o ID da URL
        $data = json_decode(file_get_contents('php://input'), true); // Captura os dados do corpo da requisição
        if ($data === null) {
            http_response_code(400);
            echo json_encode(['message' => 'Dados inválidos para a atualização.']);
            break;
        }
        $result = $produto->update($id, $data);
        echo json_encode(['message' => $result]);
        break;
    
    case 'DELETE':
        $id = $_GET['id']; // Captura o ID da URL
        $data = json_decode(file_get_contents('php://input'), true); // Captura os dados do corpo da requisição
        $userInsert = $data['userInsert'] ?? null; // Certifique-se de que está capturando o userInsert corretamente
        $result = $produto->delete($id, $userInsert);
        echo json_encode(['message' => $result]);
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método não permitido']);
        break;
}
?>
