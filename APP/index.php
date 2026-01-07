<?php
#region HEADER
//header("Content-Type: application/json");
#endregion

#region BOOT
require_once(__DIR__ . "/boot.php");
#endregion
#region MIDDLEWARE
require_once(__DIR__ . "/MIDDLEWARE/AuthMiddleware.php");
#endregion

#region ROUTE
try {
    require_once(__DIR__ . "/route.php");
} catch (Exception $e) {
    $response = array(
        "status" => "failed",
        "message" => $e->getMessage());

} finally {
    echo json_encode($response);
}
#endregion
