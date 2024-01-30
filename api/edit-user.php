<?php
require_once '../database/connection.php';

$_POST = json_decode(file_get_contents("php://input"), true);

if (isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $id = htmlspecialchars($_POST['id']);

    if (empty($name)) {
        echo json_encode(['nameError' => 'Provide your name from PHP!']);
    } elseif (empty($email)) {
        echo json_encode(['emailError' => 'Provide your email from PHP!']);
    } else {
        $sql = "SELECT * FROM `users` WHERE `id` = $id LIMIT 1";
        $result = $conn->query($sql);
        if ($result->num_rows === 1) {
            $sql = "SELECT * FROM `users` WHERE `email` = '$email' AND `id` != $id";
            $result = $conn->query($sql);
            if ($result->num_rows === 0) {
                $sql = "UPDATE `users` SET `name` = '$name', `email` = '$email' WHERE `id` = $id";
                if ($conn->query($sql)) {
                    echo json_encode(['success' => 'Magic has been spelled!']);
                } else {
                    echo json_encode(['failure' => 'Magic has become shopper!']);
                }
            } else {
                echo json_encode(['emailError' => 'Email already exists!']);
            }
        } else {
            echo json_encode(['failure' => 'Something went wrong!']);
        }
    }
}
