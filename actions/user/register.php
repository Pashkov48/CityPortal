<?php
//регистрация
session_start();

require_once __DIR__ . '/../../app/requires.php';
$config = require_once __DIR__ . '/../../config/app.php';

$email = $_POST['email'];
$name = $_POST['name'];
$dob = $_POST['dob'];
$password = $_POST['password'];
$passwordConfirmation = $_POST['password_confirmation'];

$error = false;
$fields = [
    'email' => [
        'value' => $email,
        'error' => false,
        'empty' => '',
        'incorrectly_email' => '',
        'email_exist' => ''

    ],
    'name' => [
        'value' => $name,
        'error' => false,
        'empty' => ''
    ],
    'dob' => [
        'value' => $dob,
        'error' => false,
        'empty' => '',
    ],
    'password' => [
        'error' => false,
        'empty' => '',
        'pass_different' => ''
    ],
    'passwordConfirmation' => [
        'error' => false,
        'empty' => '',

    ]
];

//валидация email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $fields['email']['error'] = true;
    $fields['email']['incorrectly_email'] = "E-mail введен неверно";
    $error = true;

}

if (empty($name)) {
    $fields['name']['error'] = true;
    $fields['name']['empty'] = "Заполните поле";
    $error = true;
}

if (empty($dob)) {
    $fields['dob']['error'] = true;
    $fields['dob']['empty'] = "Заполните поле";
    $error = true;
}

if (empty($password)) {
    $fields['password']['error'] = true;
    $fields['password']['empty'] = "Заполните поле";
    $error = true;
}

if ($password !== $passwordConfirmation) {
    $fields['password']['error'] = true;
    $fields['password']['pass_different'] = "Пароли не совпадают";
    $error = true;
}
if (empty($passwordConfirmation)) {
    $fields['password']['error'] = true;
    $fields['passwordConfirmation']['empty'] = "Заполните поле";
    $error = true;
}

//проверка на сущестующий email
//ищем пользователя по почте
$query = $db->prepare("SELECT * FROM `users` WHERE `email`= :email");
$query->execute(['email' => $email]);
$user = $query->fetch(PDO::FETCH_ASSOC);

//если пользователь уже существует - ошибка
if ($user) {
    $fields['email']['email_exist'] = "Пользователь с таким e-mail уже существует";
    $error = true;
}

if ($error) {
    $_SESSION['fields'] = $fields;
    header('Location: /register.php');
}

//запрос на добавление пользователя с защитой от инъекции
//в БД у email свойство уникальности, которой не даст добавить 2 записи с одинаковым email
$query = $db->prepare("INSERT INTO `users`(email, name, dob, password, group_id) VALUES (:email, :name, :dob, :password, :group_id)");

try {
    $query->execute([
        'email' => $email,
        'name' => $name,
        'dob' => $dob,
        'password' => password_hash($password, PASSWORD_DEFAULT), //хешируем пароль чтобы был закодирован в БД
        'group_id' => $config['default_user_group']
    ]);
    $_SESSION['successful_registration'] = true;
    header('Location: /login.php');
} catch (\PDOException $exception) {
    echo $exception->getMessage();
    header('Location: /register.php');
}
