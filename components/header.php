<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Основная навигация">
    <div class="container-fluid">
        <a class="navbar-brand" href="/"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">City
                    Portal</font></font></a>
        <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse"
                aria-label="Переключить навигацию">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
            <?php
            $user = false;
            if (isset($_SESSION['user'])) {
                $query = $db->prepare("SELECT * FROM `users` WHERE `id` = :id");
                $query->execute(['id' => $_SESSION['user']]);
                $user = $query->fetch(PDO::FETCH_ASSOC);
            }
            ?>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../"><font
                                style="vertical-align: inherit;"><font
                                    style="vertical-align: inherit;">Заявки</font></font></a>
                </li>
                <?php
                if ($user) {
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown"
                           aria-expanded="false"><font style="vertical-align: inherit;"><font
                                        style="vertical-align: inherit;">Мои заявки</font></font></a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown01">
                            <li><a class="dropdown-item" href="/add-ticket.php"><font
                                            style="vertical-align: inherit;"><font
                                                style="vertical-align: inherit;">Добавить</font></font></a></li>
                            <li><a class="dropdown-item" href="/my-ticket.php"><font
                                            style="vertical-align: inherit;"><font
                                                style="vertical-align: inherit;">Мои заявки</font></font></a></li>
                        </ul>
                    </li>
                    <?php
                }
                $config = require __DIR__ . '/../config/app.php';

                if ($user && (int)$user['group_id'] === $config['admin_user_group']) {
                    //доступ к управлению заявками администратору
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/ticket-control.php"><font style="vertical-align: inherit;"><font
                                        style="vertical-align: inherit;">Управление заявками</font></font></a>
                    </li>
                    <?php
                }
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown"
                       aria-expanded="false"><font style="vertical-align: inherit;"><font
                                    style="vertical-align: inherit;">
                                <?= !$user ? 'Аккаунт' : $user['name'] ?>
                            </font></font></a>
                    <ul class="dropdown-menu" aria-labelledby="dropdown01">
                        <?php
                        if (!$user) {
                            ?>
                            <li><a class="dropdown-item" href="/login.php"><font style="vertical-align: inherit;"><font
                                                style="vertical-align: inherit;">Вход</font></font></a></li>
                            <li><a class="dropdown-item" href="/register.php"><font
                                            style="vertical-align: inherit;"><font
                                                style="vertical-align: inherit;">Регистрация</font></font></a></li>
                            <?php
                        } else {
                            //чтобы злоумышленники не могли навредить, необходимо выход из учетки
                            //делать через пост форму
                            ?>
                            <form action="/actions/user/logout.php" method="post">
                                <button type="submit" class="dropdown-item">Выход</button>
                            </form>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
            </ul>
            <form class="d-flex" action="/" method="get">
                <input class="form-control me-2" name="q" value="<?= $_GET['q'] ?? '' ?>" type="search"
                       placeholder="Поиск" aria-label="Поиск">
                <button class="btn btn-outline-success" type="submit"><font style="vertical-align: inherit;"><font
                                style="vertical-align: inherit;">Поиск</font></font></button>
            </form>
        </div>
    </div>
</nav>


