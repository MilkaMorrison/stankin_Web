<?php
// db_connect.php - файл для подключения к PostgreSQL

$host = "localhost";
$port = "5432";
$dbname = "postgres";
$user = "postgres";
$password = "student";

try {
    // Создаем подключение через PDO с указанием схемы
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;options='-c search_path=atrium,public'", $user, $password);
    
    // Устанавливаем режим ошибок
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Кодировка
    $pdo->exec("SET NAMES 'utf8'");
    
    // echo "Подключение к PostgreSQL успешно!";
    
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>