<?php
session_start();
require_once __DIR__ . '/../../app/requires.php';

$email = $_POST['email'];
$password = $_POST['password'];

$error = false;
$fields = [
    'email' => [
        'auth_error' => false,
        'email_not_exist' => ''
    ],
    'password' => [
        'auth_error' => false,
        'incorrect_password' => ''
    ]
];


//если парроли совпадают идем на след шаг иначе так же не воши
//вошли-создаем сессию
//ищем пользователя по почте
$query = $db->prepare("SELECT * FROM `users` WHERE `email`= :email");
$query->execute(['email' => $email]);
$user = $query->fetch(PDO::FETCH_ASSOC);

//если пользователя нет - ошибка
if (!$user) {
    $fields['email']['auth_error'] = true;
    $fields['email']['not_exist'] = 'Пользователь с таким e-mail не существует';
    $_SESSION['fields'] = $fields;
    header('Location: /login.php');
} elseif (!password_verify($password, $user['password'])) {
    //проверяем пароль, хешируя его
    $fields['password']['auth_error'] = true;
    $fields['password']['incorrect_password'] = 'Неверный пароль';
    $_SESSION['fields'] = $fields;
    header('Location: /login.php');
} else {
    //сохраняем id для сессии
    $_SESSION['user'] = $user['id'];
    header('Location: /');
}






