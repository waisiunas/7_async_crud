<?php
require_once '../database/connection.php';

$_POST = json_decode(file_get_contents("php://input"), true);

if (isset($_POST['submit'])) {
    $id = htmlspecialchars($_POST['id']);

    $sql = "SELECT * FROM `users` WHERE `id` = $id LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $sql = "DELETE from`users` WHERE `id` = $id";
        if ($conn->query($sql)) {
            echo json_encode(['success' => 'Magic has been spelled!']);
        } else {
            echo json_encode(['failure' => 'Magic has become shopper!']);
        }
    } else {
        echo json_encode(['failure' => 'Something went wrong!']);
    }
}
