<?php
namespace App\models\dto;

class UsuarioDTO {
    public $id;
    public $nombre;
    public $email;
    public $telefono;
    public $ciudad;
    public $rol;
    public $activo;

    public function toArray(): array {
        return [
            "id"       => (int)$this->id,
            "nombre"   => $this->nombre,
            "email"    => $this->email,
            "telefono" => $this->telefono,
            "ciudad"   => $this->ciudad,
            "rol"      => $this->rol,
            "activo"   => (bool)$this->activo
        ];
    }
}