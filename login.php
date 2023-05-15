<?php

session_start();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require_once __DIR__ . "/components/head.php" ?>
    <title>Вход</title>
</head>
<body class="bg-light">

<?php
require_once __DIR__ . "/components/header.php";
?>

<div class="container">
    <h1 class="mb-4 pt-5 mt-5">Авторизация</h1>
    <?php
    //выводит сообщение о успешной регистрации
    if (isset($_SESSION['successful_registration'])) {
        ?>
        <div class="alert alert-success" style="margin: 20px" role="alert">
            Вы успешно зарегистрированы
        </div>
        <?php
        //очищаем сессию, т.к. она останется
        unset($_SESSION['successful_registration']);
    }
    ?>

    <?php
    //выводит ошибку при неправильной заполнении полей
    if (isset($_SESSION['fields'])) {
        ?>
        <div class="alert alert-danger" style="margin: 20px" role="alert">
            Ошибка авторизации
        </div>
        <?php
        //очищаем ошибку, т.к. она останется
        $fields = $_SESSION['fields'];
        unset($_SESSION['fields']);
    }
    ?>
    <form method="post" action="/actions/user/login.php">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email</label>
            <input type="email" name="email"
                   class="form-control <?= $fields['email']['auth_error'] ? 'is-invalid' : '' ?>"
                   id="exampleInputEmail1"
                   aria-describedby="emailHelp">
            <span class="text-danger"><?= $fields['email']['not_exist'] ?? '' ?></span>
            <div id="emailHelp" class="form-text">Мы никому никогда не передадим вашу электронную почту</div>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Пароль</label>
            <input type="password"
                   name="password"
                   class="form-control <?= $fields['password']['auth_error'] ? 'is-invalid' : '' ?>"
                   id="exampleInputPassword1">
            <span class="text-danger"><?= $fields['password']['incorrect_password'] ?? '' ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
</div>
<?php require_once __DIR__ . "/components/scripts.php" ?>
</body>
</html>
