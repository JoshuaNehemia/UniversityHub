<?php

require_once(__DIR__ ."/boot.php");
require_once(__DIR__ ."/config.php");

function requireRole($allowedRoles = []) {
    if (!isset($_SESSION[CURRENT_ACCOUNT])) {
        echo json_encode([
            "status"=>"ERROR",
            "message"=>"Anda belum melakukan log-in",
            "route"=>"login.php"
        ]);
        exit;
    }

    if (!in_array($_SESSION[CURRENT_ACCOUNT]['jenis'], $allowedRoles)) {
        echo json_encode([
            "status"=>"FORBIDDEN",
            "message"=>"Anda tidak boleh memasuki halaman ini"
        ]);
        exit;
    }
}