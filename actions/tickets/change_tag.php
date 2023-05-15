<?php

//страница для изменения статуса заявок
session_start();
require_once __DIR__ . '/../../app/requires.php';
$config = require_once __DIR__ . '/../../config/app.php';

//проверка авторизированного пользователя
if (!isset($_SESSION['user'])) {
    echo 'Error handle action';
    die();
}

$id = $_POST['id'];
$tag = $_POST['tag'];

//проверка тега
$q = $db->prepare("SELECT * FROM `ticket_tags` WHERE `id`= :id");
$q->execute(['id' => $tag]);
$tagExists = $q->fetch();
if (!$tagExists) {
    echo 'Error handle action';
    die();
}

$query = $db->prepare("SELECT * FROM `users` WHERE `id` = :id");
$query->execute(['id' => $_SESSION['user']]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ((int)$user['group_id'] === $config['admin_user_group']) {
    $query = $db->prepare("UPDATE `tickets` SET `tag_id`= :tag WHERE `id` = :id");
    $query->execute([
        'tag' => $tag,
        'id' => $id
    ]);
}

header('Location: /ticket-control.php');