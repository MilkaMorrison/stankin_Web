<?php
session_start();
require_once 'db_connect.php';

try {
    // Получаем услуги из базы данных
    $query = "SELECT * FROM atrium.services WHERE available = true ORDER BY name";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $services = [];
    $error = "Ошибка загрузки услуг: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>Атриум - Услуги</title>
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

<!-- Основной контент -->
<table border="0" width="900" cellpadding="5" cellspacing="0" align="center">
    <tr>
        <!-- Левое меню -->
        <td width="260" valign="top" align="center" class="left-menu">
            <a href="../index.php">Главная</a><br>
            <a href="about_us.php">О нас</a><br>
            <a href="catalog.php">Услуги</a><br>
            <a href="contacts.php">Контакты</a><br>
        </td>

        <!-- Центральный контент -->
        <td width="510" valign="top">
            <h1 align="center">Услуги</h1>
            
            <?php if (isset($error)): ?>
                <div style="color: red; text-align: center;"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <table width="100%" align="center">
                <?php
                $counter = 0;
                foreach ($services as $service): 
                    if ($counter % 2 == 0) echo '<tr>';
                ?>
                    <td align="center">
                        <figure>
                            <div class="image-container">
                                <a href="<?php echo $service['image']; ?>" target="_blank" title="Открыть изображение полном размере">
                                <img class="zoom-image" src="<?php echo $service['image']; ?>" alt="<?php echo $service['name']; ?>" width="200" height="220">
                                </a>
                            </div>
                            <figcaption>
                                <div class="product-info">
                                    <a href="service_detail.php?id=<?php echo $service['id']; ?>"><?php echo htmlspecialchars($service['name']); ?></a>
                                    <span class="price">от <?php echo number_format($service['price'], 0, ',', ' '); ?> ₽</span> 
                                </div>
                            </figcaption>
                        </figure>
                    </td>
                <?php 
                    $counter++;
                    if ($counter % 2 == 0) echo '</tr>';
                endforeach; 
                // Закрываем строку если нечетное количество услуг
                if ($counter % 2 != 0) echo '</tr>';
                ?>
            </table>
        </td>

        <!-- Правая колонка -->
        <td width="130" valign="top" align="center" class="right-menu">
            <a href="https://www.atrium.su/" target="_blank" title="Перейти на официальный сайт Атриум">
                <img src="../Картинки/banner1.jpg" alt="banner1" width="120" height="100">
            </a>
            <a href="https://www.atrium.su/" target="_blank" title="Перейти на официальный сайт Атриум">
                <img src="../Картинки/banner2.png" alt="banner2" width="120" height="100">
            </a>
            <a href="https://www.atrium.su/" target="_blank" title="Перейти на официальный сайт Атриум">
                <img src="../Картинки/logo.png" alt="logo" width="120" height="100">
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

<!--
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>Атриум - Услуги</title>
</head>
<body>
-- Шапка
<table border="0" width="900" cellpadding="0" cellspacing="0" align="center" class="header">
    <tr>
        <td width="150" align="center">
            <img src="..\Картинки\logo.png" alt="Логотип Атриум">
        </td>
        <td align="center"><h1>Атриум</h1></td>
        <td class="form" width="270" align="right">
            <a href="auth.html" class="login-btn">Войти</a>
        </td>
    </tr>
</table>

-- Навигация
<table border="0" width="900" cellpadding="5" cellspacing="0" align="center">
    <tr>
        <td class="nav" align="center"><a href="https://www.atrium.su/">главная</a></td>
        <td class="nav" align="center"><a href="https://www.atrium.su/about/">о нас</a></td>
        <td class="nav" align="center"><a href="https://www.atrium.su/specialty/services/">услуга</a></td>
        <td class="nav" align="center"><a href="https://www.atrium.su/contacts/">контакты</a></td>
    </tr>
</table>

<br>

-- Основной контент
<table border="0" width="900" cellpadding="5" cellspacing="0" align="center">
    <tr>
        -- Левое меню 
        <td width="260" valign="top" align="center" class="left-menu">
            <a href="..\index.html">Главная</a><br>
            <a href="about_us.html">О нас</a><br>
            <a href="catalog.html">Услуги</a><br>
            <a href="contacts.html">Контакты</a><br>
        </td>

        -- Центральный контент 
        <td width="510" valign="top">
            <h1 align="center">Услуги</h1>
            <table width="100%" align="center">
                <tr>
                    <td align="center">
                        <figure>
                            <div class="image-container">
                                <a href="..\Картинки\brow_up.jpeg" target="_blank" title="Открыть изображение полном размере">
                                <img class="zoom-image" src="..\Картинки\brow_up.jpeg" alt="brow_up" width="200" height="220">
                                </a>
                            </div>
                            <figcaption>
                                <div class="product-info">
                                    <a href="brow_up.html">Brow Up!</a>
                                    <span class="price">от 800 ₽</span>
                                </div>
                            </figcaption>
                        </figure>
                    </td>
                    <td align="center">
                        <figure>
                            <div class="image-container">
                                <a href="..\Картинки\face_fit.jpg" target="_blank" title="Открыть изображение полном размере">
                                <img class="zoom-image" src="..\Картинки\face_fit.jpg" alt="face_fit" width="200" height="220">
                                </a>
                            </div>
                            <figcaption>
                                <div class="product-info">
                                    <a href="face_fit.html">Face Fit</a>
                                    <span class="price">от 1 500 ₽</span>
                                </div>
                            </figcaption>
                        </figure>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <figure>
                            <div class="image-container">
                                <a href="..\Картинки\hawaii.png" target="_blank" title="Открыть изображение полном размере">
                                <img class="zoom-image" src="..\Картинки\hawaii.png" alt="hawaii" width="200" height="150">
                                </a>
                            </div>
                            <figcaption>
                                <div class="product-info">
                                    <a href="https://www.atrium.su/specialty/services/">Hawaii Fitboxing</a>
                                    <span class="price">от 700 ₽</span>
                                </div>
                            </figcaption>
                        </figure>
                    </td>
                    <td align="center">
                        <figure>
                            <div class="image-container">
                                <a href="..\Картинки\nikko.jpg" target="_blank" title="Открыть изображение полном размере">
                                <img class="zoom-image" src="..\Картинки\nikko.jpg" alt="nikko" width="200" height="150">
                                </a>
                            </div>
                            <figcaption>
                                <div class="product-info">
                                    <a href="https://www.atrium.su/specialty/services/">NIKKO</a>
                                    <span class="price">от 2 000 ₽</span>
                                </div>
                            </figcaption>
                        </figure>
                    </td>
                </tr>
            </table>
        </td>

        -- Правая колонка 
        <td width="130" valign="top" align="center" class="right-menu">
            <a href="https://www.atrium.su/" target="_blank" title="Перейти на официальный сайт Атриум">
                <img src="../Картинки/banner1.jpg" alt="banner1" width="120" height="100">
            </a>
            <a href="https://www.atrium.su/" target="_blank" title="Перейти на официальный сайт Атриум">
                <img src="../Картинки/banner2.png" alt="banner2" width="120" height="100">
            </a>
            <a href="https://www.atrium.su/" target="_blank" title="Перейти на официальный сайт Атриум">
                <img src="../Картинки/logo.png" alt="logo" width="120" height="100">
            </a>
        </td>
    </tr>
</table>

-- Футер
<footer class="footer">
    <div class="footer-content">
        <h3>Атриум</h3>
        <p>Большой современный торговый центр с четырьмя этажами: магазины, рестораны, кинотеатр.</p>
        <p class="footer-copyright">© 2025 ТРЦ Атриум. Все права защищены.</p>
    </div>
</footer>

</body>
</html> -->