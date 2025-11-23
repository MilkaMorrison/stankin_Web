<?php
session_start();
// Очищаем все переменные сессии
$_SESSION = array();
// Уничтожаем сессию
session_destroy();
// Перенаправляем на страницу входа
header("Location: auth.php");
exit();
?>