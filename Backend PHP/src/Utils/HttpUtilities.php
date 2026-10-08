<?php

namespace App\utils;

class HttpUtilities
{
    public static function send200($data = null, string $msg = "Operación exitosa")
    {
        http_response_code(200);
        echo json_encode([
            "status"  => 200,
            "msg"     => $msg,
            "data"    => $data
        ]);
    }

    public static function send201($data = null, string $msg = "Recurso creado")
    {
        http_response_code(201);
        echo json_encode([
            "status"  => 201,
            "msg"     => $msg,
            "data"    => $data
        ]);
    }

    public static function send400(string $msg = "Solicitud incorrecta")
    {
        http_response_code(400);
        echo json_encode([
            "status" => 400,
            "msg"    => $msg
        ]);
    }

    public static function send401(string $msg = "No autorizado")
    {
        http_response_code(401);
        echo json_encode([
            "status" => 401,
            "msg"    => $msg
        ]);
    }

    public static function send404(string $msg = "404 Not Found")
    {
        http_response_code(404);
        echo json_encode([
            "status" => 404,
            "msg"    => $msg
        ]);
    }

    public static function send405(string $msg = "Método HTTP no permitido")
    {
        http_response_code(405);
        echo json_encode([
            "status" => 405,
            "msg"    => $msg
        ]);
    }

    public static function send500(string $errorMessage = "Error interno del servidor")
    {
        http_response_code(500);
        echo json_encode([
            "status" => 500,
            "msg"    => "500 Internal Server Error",
            "error"  => $errorMessage
        ]);
    }

    public static function sendFieldError(string $nombreCampo)
    {
        http_response_code(400);
        echo json_encode([
            "status" => 400,
            "msg"    => "El campo '$nombreCampo' es obligatorio"
        ]);
    }

    public static function getJsonBody(): ?array
    {
        $body = file_get_contents('php://input');
        return json_decode($body, true) ?? [];
    }

    public static function verifyMethod(string $method): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === $method) {
            return false;
        }
        self::send405("Método {$_SERVER['REQUEST_METHOD']} no admitido. Se esperaba $method");
        return true;
    }

    public static function verifyField(?array $request, string $field): bool
    {
        if (!isset($request[$field]) || trim((string)$request[$field]) === '') {
            self::sendFieldError($field);
            return true;
        }
        return false;
    }
}