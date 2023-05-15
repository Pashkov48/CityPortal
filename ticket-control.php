<?php

session_start();

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require_once __DIR__ . "/components/head.php";
    //проверка авторизации: админ или юзер
    if (isset($_SESSION['user'])) {
        $q = $db->prepare("SELECT `group_id` FROM `users` WHERE `id`= :id");
        $q->execute(['id' => $_SESSION['user']]);
        $user = $q->fetch(PDO::FETCH_ASSOC);
        $config = require __DIR__ . '/config/app.php';
        if ((int)$user['group_id'] !== $config['admin_user_group']) {
            header('Location:/');
            die();
        }
    } ?>
    <title>Управление заявками</title>
</head>
<body class="bg-light">
<?php require_once __DIR__ . "/components/header.php" ?>
<section class="main">
    <div class="container">
        <div class="row">
            <h2 class="mb-4 pt-5 mt-5">Управление заявками</h2>
        </div>
        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Изображение</th>
                    <th scope="col">Название</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $tags = $db->query("SELECT * FROM `ticket_tags`")->fetchAll(PDO::FETCH_ASSOC);
                $tickets = $db->query("SELECT * FROM `tickets`")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($tickets as $ticket) {
                    $tagId = $ticket['tag_id'];
                    //array_fileter словно будет перебирать как форич массив и выберет тот тег, который находится в ticket
                    $tag = array_filter($tags, function ($tag) use ($tagId) {
                        return (int)$tag['id'] === (int)$tagId;
                    });
                    $tag = array_shift($tag);
                    ?>
                    <tr>
                        <td>
                            <img src="<?= $ticket['image'] ?>" width="200" alt="">
                        </td>
                        <td><?= $ticket['title'] ?></td>
                        <td><?= $ticket['description'] ?></td>
                        <td>
                            <span class="badge rounded-pill "
                                  style="background:<?= $tag['background'] ?>; color: <?= $tag['color'] ?>>">
                                <?= $tag['label'] ?>
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuLink"
                                        data-bs-toggle="dropdown" aria-expanded="false">Действия
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <form action="/actions/tickets/change_tag.php" method="post">
                                        <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                                        <input type="hidden" name="tag" value="<?= $config['success_tickets_tag'] ?>">
                                        <button type="submit" class="dropdown-item">Выполнено</button>
                                    </form>
                                    <form action="/actions/tickets/change_tag.php" method="post">
                                        <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                                        <input type="hidden" name="tag"
                                               value="<?= $config['in_progress_tickets_tag'] ?>">
                                        <button type="submit" class="dropdown-item">В процессе</button>
                                    </form>
                                    <form action="/actions/tickets/change_tag.php" method="post">
                                        <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                                        <input type="hidden" name="tag" value="<?= $config['reject_tickets_tag'] ?>">
                                        <button type="submit" class="dropdown-item">Отклонить</button>
                                    </form>
                                    <form action="/actions/tickets/remove.php" method="post">
                                        <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                                        <input type="hidden" name="management" value="true">
                                        <button type="submit" class="dropdown-item">Удалить</button>
                                    </form>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require_once __DIR__ . "/components/scripts.php" ?>
</body>
</html>
