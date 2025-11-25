<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // "user_input" matches the name="..." in the HTML input
    $input = isset($_POST['user_input']) ? $_POST['user_input'] : '';

    // Do your logic here (save to DB, etc.)
    $s = print_r($_POST);
    // Return a response for the console.log
    echo "Received: " . $s;
}
?>