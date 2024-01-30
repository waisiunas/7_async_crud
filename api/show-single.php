<?php
require_once '../database/connection.php';

$_POST = json_decode(file_get_contents("php://input"), true);

if (isset($_POST['submit'])) {
    $id = htmlspecialchars($_POST['id']);

    $sql = "SELECT * FROM `users` WHERE `id` = $id LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['failure' => 'Something went wrong!']);
    }
}
