<?php

require_once(__DIR__ ."/../../APP/MODELS/akun.php");

use MODELS\Akun;

$admin = new Akun("admin2","admin2","ADMIN");
$admin->akunCreate("password",null,null);