<?php
namespace App\models\bll;

use App\models\dal\Connection;
use App\models\dto\UsuarioDTO;
use PDO;

class AuthBLL {
    public static function registrar(array $datos): int {
        $con = new Connection();

        // 1. Verificar si el email ya existe
        $checkSql = "SELECT id FROM usuarios WHERE email = :email LIMIT 1";
        $stmtCheck = $con->queryWithParams($checkSql, ["email" => $datos["email"]]);
        if ($stmtCheck->rowCount() > 0) {
            throw new \Exception("El correo electrónico ya se encuentra registrado.");
        }

        // 2. Obtener el ID del rol
        $rolSql = "SELECT id FROM roles WHERE nombre = :rol LIMIT 1";
        $stmtRol = $con->queryWithParams($rolSql, ["rol" => strtoupper($datos["rol"])]);
        $rol = $stmtRol->fetch(PDO::FETCH_ASSOC);
        if (!$rol) {
            throw new \Exception("Rol no válido.");
        }

        // 3. Hashear contraseña e insertar
        $hashPass = password_hash($datos["contrasena"], PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nombre, email, contrasena, telefono, ciudad, rol_id) 
                VALUES (:nombre, :email, :pass, :tel, :ciudad, :rol_id)";

        $con->queryWithParams($sql, [
            "nombre" => $datos["nombre"],
            "email"  => $datos["email"],
            "pass"   => $hashPass,
            "tel"    => $datos["telefono"] ?? null,
            "ciudad" => $datos["ciudad"] ?? null,
            "rol_id" => $rol["id"]
        ]);

        return (int)$con->getLastInsertedId();
    }

    public static function login(string $email, string $password): ?array {
        $con = new Connection();
        $sql = "SELECT u.id, u.nombre, u.email, u.contrasena, u.telefono, u.ciudad, r.nombre AS rol 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.email = :email AND u.activo = 1 LIMIT 1";

        $stmt = $con->queryWithParams($sql, ["email" => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || !password_verify($password, $row["contrasena"])) {
            return null;
        }

        // Generación de Token de sesión (simulado o base para JWT)
        $token = bin2hex(random_bytes(32));

        return [
            "usuario" => [
                "id"       => (int)$row["id"],
                "nombre"   => $row["nombre"],
                "email"    => $row["email"],
                "telefono" => $row["telefono"],
                "ciudad"   => $row["ciudad"],
                "rol"      => $row["rol"]
            ],
            "token" => $token
        ];
    }

    public static function obtenerPorId(int $id): ?UsuarioDTO {
        $con = new Connection();
        $sql = "SELECT u.id, u.nombre, u.email, u.telefono, u.ciudad, u.activo, r.nombre AS rol 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.id = :id LIMIT 1";

        $stmt = $con->queryWithParams($sql, ["id" => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $dto = new UsuarioDTO();
        $dto->id       = $row["id"];
        $dto->nombre   = $row["nombre"];
        $dto->email    = $row["email"];
        $dto->telefono = $row["telefono"];
        $dto->ciudad   = $row["ciudad"];
        $dto->rol      = $row["rol"];
        $dto->activo   = $row["activo"];
        return $dto;
    }
}