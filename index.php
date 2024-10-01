<?php

namespace App\Public;

use Exception;

require_once "./app/routers/routes.php";
require_once './vendor/autoload.php';

header('Content-Type: application/json; charset=UTF-8');

try {
    $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
    $end = basename($uri);
    $method = $_SERVER['REQUEST_METHOD'];

    if (!array_key_exists($end, $routes)) {
        throw new Exception("A rota não existe");
    }

    $controller_object = $routes[$end]();
    switch ($method) {
        case 'GET':
            $action = $_GET['action'] ?? null;
            if ($action === 'index_all') {
                $controller_object->index_all();
            } elseif ($action == 'index') {
                foreach ($_GET as $key => $value) {
                    $data[$key] = htmlspecialchars($value);
                }
                $id = $data['id'];
                $controller_object->index($id);
            } elseif ($action == 'verify') {
                foreach ($_GET as $key => $value) {
                    $data[$key] = htmlspecialchars($value);
                }
                $controller_object->verify($data);
            } else {
                echo json_encode(['sucess' => false, 'message' => 'A ação solicitada não existe']);
            }
            break;
        case 'POST':
            $action = $_GET['action'] ?? null;
            $data = array();
            foreach ($_POST as $key => $value) {
                $data[$key] = htmlspecialchars($value);
            }

            if ($action != 'login' && $action != 'create_login' && $action != 'update_password' && $action != 'logout') {
                session_start();

                if (!isset($_SESSION['usuario_id'])) {
                    echo json_encode(['sucess' => false, 'message' => 'Sessão inválida ou expirada']);
                    exit;
                }

                $data['autor_id'] = $_SESSION['usuario_id'];
                if ($action == 'insert') {
                    $controller_object->insert($data);
                } elseif ($action == 'delete') {
                    $controller_object->delete($data['id']);
                } elseif ($action == 'update') {
                    $controller_object->update($data);
                } else {
                    echo json_encode(['sucess' => false, 'message' => "A ação solicitada não existe"]);
                }
            } else {
                if ($action == 'create_login') {
                    $controller_object->create_login($data);
                } 
                if ($action == 'login') {
                    $controller_object->login($data);
                }

                if ($action == 'update_password') {
                    session_start();
                    if (isset($_SESSION['usuario_id'])) {
                        $data['id'] = $_SESSION['usuario_id'];
                    } elseif (isset($_SESSION['temporario_id'])) {
                        $data['id'] = $_SESSION['temporario_id'];
                        $controller_object->logout();
                    } else {
                        echo json_encode(['sucess' => false, 'message' => 'Não foi possível solicitar a alteração na senha']);
                        exit;
                    } 
                    $controller_object->update_password($data);
                }

                if ($action == 'logout') {
                    $controller_object->logout();
                }
            }
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Rota não encontrada']);
            break;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
