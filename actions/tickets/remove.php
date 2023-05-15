<?php
//страница для удаления заявок
session_start();
require_once __DIR__ . '/../../app/requires.php';
$config = require_once __DIR__ . '/../../config/app.php';

//проверка авторизированного пользователя
if (!isset($_SESSION['user'])) {
    echo 'Error handle action';
    die();
}

$id = $_POST['id'];
$management = $_POST['management'];


//запрос на получение всех заявок авторизированного пользователя
$query = $db->prepare("SELECT user_id FROM `tickets` WHERE `id` = :id");
$query->execute(['id' => $id]);
$ticket = $query->fetch(PDO::FETCH_ASSOC);

//получение текущего пользователя
$user = false;
if (isset($_SESSION['user'])) {
    $query = $db->prepare("SELECT * FROM `users` WHERE `id` = :id");
    $query->execute(['id' => $_SESSION['user']]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
}
//проверка id
if ($ticket['user_id'] !== $_SESSION['user'] && (int)$user['group_id'] !== $config['admin_user_group']) {
    echo 'Error handle action';
    die();
}
//удаление заявки
$query = $db->prepare("DELETE FROM `tickets` WHERE `id` = :id");
$query->execute(['id' => $id]);

if ((int)$user['group_id'] === $config['admin_user_group'] && (bool)$management === true) {
    header('Location: /ticket-control.php');
} else {
    header('Location: /my-ticket.php');
}
