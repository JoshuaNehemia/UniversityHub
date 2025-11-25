<?php

require_once(__DIR__ ."/bootstrap.php");

function requireRole($allowedRoles = []) {
    if (!isset($_SESSION['role'])) {
        echo json_encode([
            "status"=>"ERROR",
            "message"=>"Anda belum melakukan log-in",
            "route"=>"login.php"
        ]);
        exit;
    }

    if (!in_array($_SESSION['role'], $allowedRoles)) {
        echo json_encode([
            "status"=>"FORBIDDEN",
            "message"=>"Anda tidak boleh memasuki halaman ini"
        ]);
        exit;
    }
}