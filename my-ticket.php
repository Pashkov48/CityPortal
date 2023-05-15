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
    <title>Мои заявки</title>
</head>
<body class="bg-light">
<?php require_once __DIR__ . "/components/header.php" ?>

<section class="main">
    <div class="container">
        <div class="row">
            <h2 class="mb-4 pt-5 mt-5">Мои заявки</h2>
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
                $query = $db->prepare("SELECT * FROM `tickets` WHERE `user_id`= :user_id");
                $query->execute(['user_id' => $_SESSION['user']]);
                $tickets = $query->fetchAll(PDO::FETCH_ASSOC);
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
                                    <form action="/actions/tickets/remove.php" method="post">
                                        <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
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
