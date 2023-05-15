<?php

session_start();

//провека для доступа добавления заявок и личного кабинета
if (!isset($_SESSION['user'])) {
    header('Location: /login.php');
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require_once __DIR__ . "/components/head.php" ?>
    <title>Добавить заявку</title>
</head>
<body class="bg-light">
<?php require_once __DIR__ . "/components/header.php" ?>
<div class="container">
    <h1 class="mb-4 pt-5 mt-5">Добавить заявку</h1>
    <?php
    if (isset($_SESSION['$alerts_fields'])) {
        $alerts_fields = $_SESSION['$alerts_fields'];
        ?>
        <div class="alert alert-danger" style="margin: 20px" role="alert">
            Проверьте правильность введенных полей!
        </div>

        <?php
        unset($_SESSION['$alerts_fields']);
    }
    ?>
    <form action="actions/tickets/store.php" method="post" enctype="multipart/form-data">
        <!--        enctype="multipart/form-data"-->
        <!--        для загрузки фото-->
        <div class="mb-3 ">
            <label for="exampleFormControlInput1" class="form-label">Тема заявки</label>
            <input type="text"
                   name="title"
                   class="form-control <?= $alerts_fields['title']['error'] ? "is-invalid" : ""; ?>"
                   id="exampleFormControlInput1"
                   value="<?= $alerts_fields['title']['value'] ?? ''; ?>">
            <span class="text-danger"><?= $alerts_fields['title']['empty_input'] ?? '' ?></span>
        </div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Изображение</label>
            <input class="form-control <?= $alerts_fields['image']['error'] ? 'is-invalid' : ''; ?>" name="image"
                   type="file" id="formFile">
            <span class="text-danger"><?= $alerts_fields['image']['empty_input'] ?? '' ?></span>
            <span class="text-danger"><?= $alerts_fields['image']['big_size'] ?? '' ?></span>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Описание</label>
            <textarea class="form-control <?= $alerts_fields['description']['error'] ? 'is-invalid' : '' ?>"
                      name="description"
                      id="exampleFormControlTextarea1"
                      rows="3"><?= $alerts_fields['description']['value'] ?? ""; ?></textarea>
            <span class="text-danger"><?= $alerts_fields['description']['empty_input'] ?? '' ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Добавить заявку</button>
    </form>
</div>
<?php require_once __DIR__ . "/components/scripts.php" ?>
</body>
</html>