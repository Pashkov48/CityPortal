<?php
//страница для добавления заявок
session_start();
require_once __DIR__ . '/../../app/requires.php';
$config = require_once __DIR__ . '/../../config/app.php';

//проверка авторизированного пользователя
if (!isset($_SESSION['user'])) {
    echo 'Error handle action';
    die();
}

$title = $_POST['title'];
$description = $_POST['description'];
$image = $_FILES['image'];

//валидация
$error = false;
$alerts_fields = [
    'title' => [
        'value' => $title,
        'error' => false,
        'empty_input' => ''
    ],
    'image' => [
        'error' => false,
        'empty_input' => '',
        'big_size' => ''
    ],
    'description' => [
        'value' => $description,
        'error' => false,
        'empty_input' => ''
    ]
];

if (empty($title)) {
    $error = true;
    $alerts_fields['title']['error'] = true;
    $alerts_fields['title']['empty_input'] = 'Заполните поле';
}

if (empty($description)) {
    $error = true;
    $alerts_fields['description']['error'] = true;
    $alerts_fields['description']['empty_input'] = 'Заполните поле';
}

$max_size = 6;
$size = $image['size'] / 1000000;

if ($image['name'] === "") {
    $error = true;
    $alerts_fields['image']['error'] = true;
    $alerts_fields['image']['empty_input'] = 'Загрузите фотографию';
} elseif ($size > $max_size) {
    $error = true;
    $alerts_fields['image']['error'] = true;
    $alerts_fields['image']['big_size'] = 'Размер файла больше 25Мб!';
}

if ($error) {
    $_SESSION['$alerts_fields'] = $alerts_fields;
    header('Location: /add-ticket.php');
    die();
}

$path = __DIR__ . '/../../uploads';
$filename = uniqid() . '-' . $image['name'];

// если нет папки по указанному пути, то создаем папку
if (!is_dir($path)) {
    mkdir($path);
}

//загружаем файл
move_uploaded_file($image['tmp_name'], "$path/$filename");

$query = $db->prepare("INSERT INTO `tickets`( `title`, `description`, `image`, `tag_id`, `user_id`) VALUES (:title, :description, :image, :tag_id, :user_id)");

try {
    $query->execute([
        'title' => $title,
        'description' => $description,
        'image' => "uploads/$filename",
        'tag_id' => $config['default_tickets_tag'],
        'user_id' => $_SESSION['user']
    ]);
    header('Location: /my-ticket.php');
} catch (\PDOException $exception) {
    echo $exception->getMessage();
    die();
}
