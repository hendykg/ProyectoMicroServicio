<?php

use App\controllers\AuthController;
use App\utils\HttpUtilities;

$controller = $_REQUEST["controller"] ?? "auth";[cite: 7]
$action     = $_REQUEST["action"] ?? "login";[cite: 7]

if ($controller === "auth") {
    switch ($action) {
        case "register":
            if (HttpUtilities::verifyMethod("POST")) return;[cite: 7, 13]
            $body = HttpUtilities::getJsonBody();[cite: 7, 13]
            AuthController::register($body);
            return;

        case "login":
            if (HttpUtilities::verifyMethod("POST")) return;[cite: 7, 13]
            $body = HttpUtilities::getJsonBody();[cite: 7, 13]
            AuthController::login($body);
            return;
    }
}

HttpUtilities::send404();[cite: 8, 13]