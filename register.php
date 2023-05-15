<?php

session_start();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require_once __DIR__ . "/components/head.php" ?>
    <title>Регистрация</title>
</head>
<body class="bg-light">

<?php
require_once __DIR__ . "/components/header.php";
?>

<div class="container">
    <h1 class="mb-4 pt-5 mt-5">Регистрация</h1>
    <?php
    //выводит ошибку при неправильной заполнении полей
    if (isset($_SESSION['fields'])) {
        ?>
        <div class="alert alert-danger" style="margin: 20px" role="alert">
            Проверьте правильность введенных полей!
        </div>

        <?php
        //очищаем ошибку, т.к. она останется
        $fields = $_SESSION['fields'];
        unset($_SESSION['fields']);
    }
    ?>

    <form method="post" action="/actions/user/register.php">
        <div class="mb-3">
            <label for="formGroupExampleInput1" class="form-label">E-mail</label>
            <input type="email"
                   value="<?= $fields['email']['value'] ?? '' ?>"
                   name="email"
                   class="form-control <?= $fields['email']['error'] ? 'is-invalid' : '' ?>"
                   id="formGroupExampleInput1"
            >
            <span class="text-danger"><?= $fields['email']['incorrectly_email'] ?? '' ?></span>
            <span class="text-danger"><?= $fields['email']['email_exist'] ?? '' ?></span>
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput2" class="form-label">ФИО</label>
            <input type="text"
                   value="<?= $fields['name']['value'] ?? '' ?>"
                   name="name"
                   class="form-control <?= $fields['name']['error'] ? 'is-invalid' : '' ?>"
                   id="formGroupExampleInput2"
            >
            <span class="text-danger"><?= $fields['name']['empty'] ?? '' ?></span>
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput3" class="form-label">Дата рождения</label>
            <input type="date"
                   value="<?= $fields['dob']['value'] ?? '' ?>"
                   name="dob"
                   class="form-control <?= $fields['dob']['error'] ? 'is-invalid' : '' ?>"
                   id="formGroupExampleInput3"
                   aria-describedby="emailHelp"
            >
            <span class="text-danger"><?= $fields['dob']['empty'] ?? '' ?></span>
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput4" class="form-label">Пароль</label>
            <input type="password"
                   name="password"
                   class="form-control <?= $fields['password']['error'] ? 'is-invalid' : '' ?>"
                   id="formGroupExampleInput4"
            >
            <span class="text-danger"><?= $fields['password']['empty'] ?? '' ?></span>
            <span class="text-danger"><?= $fields['password']['pass_different'] ?? '' ?></span>
        </div>
        <div class="mb-3">
            <label for="formGroupExampleInput5" class="form-label">Подтверждение пароля</label>
            <input type="password"
                   name="password_confirmation"
                   class="form-control <?= $fields['password']['error'] ? 'is-invalid' : '' ?>"
                   id="formGroupExampleInput5"
            >
            <span class="text-danger"><?= $fields['passwordConfirmation']['empty'] ?? '' ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Создать аккаунт</button>
    </form>
</div>
<?php require_once __DIR__ . "/components/scripts.php" ?>
</body>
</html>
