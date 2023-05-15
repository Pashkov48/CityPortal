<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php require_once __DIR__ . "/components/head.php" ?>
    <title>CityPortal</title>
</head>
<body class="bg-light">
<?php require_once __DIR__ . "/components/header.php" ?>
<section class="main">
    <div class="container">
        <div class="row">
            <h2 class="mb-4 pt-5 mt-5">Заявки</h2>
        </div>
        <div class="row">
            <?php

            if (isset($_GET['q'])) {
                $q = $db->prepare("SELECT * FROM `tickets` WHERE `title` LIKE :q ORDER BY `id` DESC ");
                $q->execute(['q' => "%{$_GET['q']}%"]);
                $tickets = $q->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $tickets = $db->query("SELECT * FROM `tickets` ORDER BY `id` DESC ")->fetchAll(PDO::FETCH_ASSOC);
            }

            if (empty($tickets)) {
                ?>
                <div class="alert alert-warning" role="alert">
                    По вашему запросу ничего не найдено!
                </div>
                <?php
            }

            $tags = $db->query("SELECT * FROM `ticket_tags`")->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tickets as $ticket) {
                $tagId = $ticket['tag_id'];
                //array_filter словно будет перебирать как форич массив и выберет тот тег, который находится в ticket
                $tag = array_filter($tags, function ($tag) use ($tagId) {
                    return (int)$tag['id'] === (int)$tagId;
                });
                $tag = array_shift($tag);
                ?>

                <div class="card mb-3">
                    <img src="<?= $ticket['image'] ?>" class="img-fluid" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?= $ticket['title'] ?>
                            <span class="badge"
                                  style="background:<?= $tag['background'] ?>; color: <?= $tag['color']; ?>">
                                <?= $tag['label'] ?>
                            </span>
                        </h5>
                        <p class="card-text"><?= $ticket['description'] ?></p>
                        <p class="card-text"><small class="text-muted">Добавлено: <?= $ticket['created_at'] ?></small>
                        </p>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . "/components/scripts.php" ?>
</body>
</html>