<?php
require_once 'Страницы/db_connect.php';

try {
    // Простой запрос для проверки
    $stmt = $pdo->query("SELECT version() as postgres_version");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Подключение успешно!<br>";
    echo "Версия PostgreSQL: " . $result['postgres_version'];
    
} catch (PDOException $e) {
    echo "Ошибка запроса: " . $e->getMessage();
}
?>