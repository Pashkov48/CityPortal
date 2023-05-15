<?php

//информация с какого домена перешли, дирректория и много другой информации
//метод передачи
//если перейти со страницы выхода, то будет метод передачи POST
//если просто по ссылке - GET
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    unset($_SESSION['user']);
    session_destroy();
    header('Location: /login.php');
} else {
    echo 'Error handle action';
    die();
}
