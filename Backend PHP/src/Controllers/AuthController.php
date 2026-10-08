<?php
namespace App\controllers;

use App\models\bll\AuthBLL;
use App\utils\HttpUtilities;

class AuthController {
    public static function register($body) {
        if (HttpUtilities::verifyField($body, "nombre") ||[cite: 13]
            HttpUtilities::verifyField($body, "email") ||[cite: 13]
            HttpUtilities::verifyField($body, "contrasena") ||[cite: 13]
            HttpUtilities::verifyField($body, "rol")) {[cite: 13]
            return;
        }

        $rolesValidos = ['COMPRADOR', 'PROVEEDOR', 'ADMIN'];
        if (!in_array(strtoupper($body['rol']), $rolesValidos)) {
            http_response_code(400);
            echo json_encode(["msg" => "El rol debe ser COMPRADOR, PROVEEDOR o ADMIN."]);
            return;
        }

        try {
            $nuevoId = AuthBLL::registrar($body);
            $usuario = AuthBLL::obtenerPorId($nuevoId);
            http_response_code(201);
            echo json_encode([
                "msg"     => "Usuario registrado exitosamente",
                "usuario" => $usuario->toArray()
            ]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(["msg" => $e->getMessage()]);
        }
    }

    public static function login($body) {
        if (HttpUtilities::verifyField($body, "email") || HttpUtilities::verifyField($body, "contrasena")) {[cite: 13]
            return;
        }

        try {
            $authData = AuthBLL::login($body['email'], $body['contrasena']);
            if (!$authData) {
                http_response_code(401);
                echo json_encode(["msg" => "Credenciales incorrectas o usuario inactivo"]);
                return;
            }
            echo json_encode([
                "msg"  => "Inicio de sesión exitoso",
                "data" => $authData
            ]);
        } catch (\Exception $e) {
            HttpUtilities::send500($e->getMessage());[cite: 13]
        }
    }

    public static function verifyToken() {
        // Endpoint que servirá para que el API Gateway o gRPC verifique si el usuario es válido
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (empty($authHeader)) {
            http_response_code(401);
            echo json_encode(["valid" => false, "msg" => "Token no proporcionado"]);
            return;
        }

        echo json_encode(["valid" => true, "msg" => "Token válido"]);
    }
}