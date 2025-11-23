<?php
session_start();
require_once 'db_connect.php';

// Получаем ID услуги из URL
$service_id = $_GET['id'] ?? 1;

try {
    // Получаем данные об услуге
    $query = "SELECT * FROM atrium.services WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $service_id);
    $stmt->execute();
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        die("Услуга не найдена");
    }

    // Получаем отзывы для этой услуги
    $query = "SELECT r.*, u.username 
              FROM atrium.reviews r 
              JOIN atrium.users u ON r.user_id = u.id 
              WHERE r.service_id = :service_id 
              ORDER BY r.created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':service_id', $service_id);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Считаем средний рейтинг
    $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as reviews_count 
              FROM atrium.reviews 
              WHERE service_id = :service_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':service_id', $service_id);
    $stmt->execute();
    $rating_info = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Ошибка загрузки данных: " . $e->getMessage());
}

// Обработка добавления отзыва
if ($_POST && isset($_POST['message']) && isset($_SESSION['user_id'])) {
    try {
        $query = "INSERT INTO atrium.reviews (user_id, service_id, message, rating, priority) 
                  VALUES (:user_id, :service_id, :message, :rating, :priority)";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':service_id', $service_id);
        $stmt->bindParam(':message', $_POST['message']);
        $stmt->bindParam(':rating', $_POST['rating']);
        $stmt->bindParam(':priority', $_POST['priority']);
        
        if ($stmt->execute()) {
            header("Location: service_detail.php?id=" . $service_id . "&success=1");
            exit();
        }
    } catch (PDOException $e) {
        $review_error = "Ошибка при добавлении отзыва: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title><?php echo htmlspecialchars($service['name']); ?> - Атриум</title>
    <style>
        .review-card {
            border: 1px solid #e0e0e0;
            padding: 20px;
            margin: 15px 0;
            border-radius: 10px;
            background: #fafafa;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .review-author {
            font-weight: bold;
            color: #2c3e50;
        }
        .review-rating {
            color: #f39c12;
            font-size: 16px;
        }
        .review-date {
            color: #7f8c8d;
            font-size: 12px;
        }
        .review-priority {
            background: #e74c3c;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin-left: 10px;
        }
        .review-message {
            color: #34495e;
            line-height: 1.5;
            margin-top: 10px;
        }
        .fixed-width-textarea {
            width: 100%;
            max-width: 100%;
            min-width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<!-- Шапка -->
<table border="0" width="900" cellpadding="0" cellspacing="0" align="center" class="header">
    <tr>
        <td width="150" align="center">
            <img src="../Картинки/logo.png" alt="Логотип Атриум">
        </td>
        <td align="center"><h1>Атриум</h1></td>
        <td class="form" width="270" align="right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Добро пожаловать, <?php echo $_SESSION['username']; ?>!</span>
                <a href="logout.php" class="login-btn">Выйти</a>
            <?php else: ?>
                <a href="auth.php" class="login-btn">Войти</a>
            <?php endif; ?>
        </td>
    </tr>
</table>

<!-- Навигация -->
<table border="0" width="900" cellpadding="5" cellspacing="0" align="center">
    <tr>
        <td class="nav" align="center"><a href="../index.php">главная</a></td>
        <td class="nav" align="center"><a href="about_us.php">о нас</a></td>
        <td class="nav" align="center"><a href="catalog.php">услуги</a></td>
        <td class="nav" align="center"><a href="contacts.php">контакты</a></td>
    </tr>
</table>

<br>

<!-- Основной контент - УЗКИЕ БОКОВЫЕ МЕНЮ -->
<table border="0" width="900" cellpadding="5" cellspacing="0" align="center">
    <tr>
        <!-- Левое меню - СДЕЛАЛ УЖЕ -->
        <td width="120" valign="top" align="center" class="left-menu">
            <a href="../index.php">Главная</a><br>
            <a href="about_us.php">О нас</a><br>
            <a href="catalog.php">Услуги</a><br>
            <a href="contacts.php">Контакты</a><br>
        </td>

        <!-- Центральный контент - ШИРЕ -->
        <td width="660" valign="top">
            <!-- Информация об услуге -->
            <div style="text-align: center;">
                <img src="<?php echo $service['image']; ?>" alt="<?php echo $service['name']; ?>" width="320" height="340" style="border-radius: 10px;">
                <h1><?php echo htmlspecialchars($service['name']); ?></h1>
                
                <!-- Рейтинг -->
                <?php if ($rating_info['avg_rating']): ?>
                    <div style="margin: 10px 0;">
                        <strong>Рейтинг: </strong>
                        <?php echo number_format($rating_info['avg_rating'], 1); ?> ★ 
                        (<?php echo $rating_info['reviews_count']; ?> отзывов)
                    </div>
                <?php endif; ?>
                
                <div class="price" style="font-size: 24px; color: #e74c3c; margin: 10px 0;">
                    от <?php echo number_format($service['price'], 0, ',', ' '); ?> ₽
                </div>
            </div>

            <h3>Описание</h3>
            <p class="detailed-description"><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>

            <hr>

            <!-- Форма добавления отзыва -->
            <h3>Оставить отзыв</h3>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_GET['success'])): ?>
                    <div style="color: green; margin: 10px 0; padding: 10px; background: #e6ffe6; border-radius: 5px;">
                        ✅ Отзыв успешно добавлен!
                    </div>
                <?php endif; ?>
                <?php if (isset($review_error)): ?>
                    <div style="color: red; margin: 10px 0; padding: 10px; background: #ffe6e6; border-radius: 5px;">
                        ❌ <?php echo $review_error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="post">
                    <!-- Текстовое поле с фиксированной шириной -->
                    <textarea name="message" placeholder="Ваш отзыв..." rows="4" class="fixed-width-textarea" required></textarea>
                    
                    <div style="margin: 10px 0; display: flex; gap: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px;">Оценка:</label>
                            <select name="rating" required style="padding: 8px; border-radius: 5px;">
                                <option value="5">5 ★ - Отлично</option>
                                <option value="4">4 ★ - Хорошо</option>
                                <option value="3">3 ★ - Нормально</option>
                                <option value="2">2 ★ - Плохо</option>
                                <option value="1">1 ★ - Ужасно</option>
                            </select>
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 5px;">Приоритет:</label>
                            <select name="priority" style="padding: 8px; border-radius: 5px;">
                                <option value="low">Низкий</option>
                                <option value="medium">Средний</option>
                                <option value="high">Высокий</option>
                            </select>
                        </div>
                    </div>
                    
                    <input type="submit" value="Добавить отзыв" style="padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">
                </form>
            <?php else: ?>
                <p>Чтобы оставить отзыв, <a href="auth.php">войдите в систему</a>.</p>
            <?php endif; ?>

            <hr>

            <!-- Список отзывов -->
            <h3>Отзывы</h3>
            <?php if (count($reviews) > 0): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div>
                                <span class="review-author"><?php echo htmlspecialchars($review['username']); ?></span>
                                <span class="review-rating"><?php echo str_repeat('★', $review['rating']); ?></span>
                                <?php if ($review['priority'] != 'low'): ?>
                                    <span class="review-priority"><?php echo $review['priority']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="review-date">
                                <?php echo date('d.m.Y H:i', strtotime($review['created_at'])); ?>
                            </div>
                        </div>
                        <div class="review-message">
                            <?php echo nl2br(htmlspecialchars($review['message'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 30px; color: #7f8c8d;">
                    <p>Пока нет отзывов. Будьте первым!</p>
                </div>
            <?php endif; ?>
        </td>

        <!-- Правая колонка - СДЕЛАЛ УЖЕ -->
        <td width="120" valign="top" align="center" class="right-menu">
            <a href="https://www.atrium.su/" target="_blank">
                <img src="../Картинки/banner1.jpg" alt="banner1" width="110" height="90" style="margin: 5px 0;">
            </a>
            <a href="https://www.atrium.su/" target="_blank">
                <img src="../Картинки/banner2.png" alt="banner2" width="110" height="90" style="margin: 5px 0;">
            </a>
            <a href="https://www.atrium.su/" target="_blank">
                <img src="../Картинки/logo.png" alt="logo" width="110" height="90" style="margin: 5px 0;">
            </a>
        </td>
    </tr>
</table>

<!-- Футер -->
<footer class="footer">
    <div class="footer-content">
        <h3>Атриум</h3>
        <p>Большой современный торговый центр с четырьмя этажами: магазины, рестораны, кинотеатр.</p>
        <p class="footer-copyright">© 2025 ТРЦ Атриум. Все права защищены.</p>
    </div>
</footer>
</body>
</html>