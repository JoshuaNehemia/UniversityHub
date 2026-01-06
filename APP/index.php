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
require_once(__DIR__ . "/route.php");
#endregion
