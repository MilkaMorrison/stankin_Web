<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>Атриум - Контакты</title>
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
        <td class="nav" align="center"><a href="https://www.atrium.su/">главная</a></td>
        <td class="nav" align="center"><a href="https://www.atrium.su/about/">о нас</a></td>
        <td class="nav" align="center"><a href="https://www.atrium.su/specialty/services/">услуга</a></td>
        <td class="nav" align="center"><a href="https://www.atrium.su/contacts/">контакты</a></td>
    </tr>
</table>

<br>

<!-- Основной контент -->
<table border="0" width="900" cellpadding="5" cellspacing="0" align="center">
    <tr>
        <!-- Левое меню -->
        <td width="135" valign="top" align="center" class="left-menu">
            <a href="..\index.php">Главная</a><br>
            <a href="about_us.php">О нас</a><br>
            <a href="catalog.php">Услуги</a><br>
            <a href="contacts.php">Контакты</a><br>
        </td>

        <!-- Центральный контент -->
        <td width="635" valign="top">
            <h1>Контактная информация</h1>
            <p>Если у Вас есть вопросы, предложения или отзывы о ТРК Атриум, Вы можете легко связаться с нашей администрацией, отправив электронное письмо по адресу <b>info@engeocom.com</b> или воспользуйтесь формой обратной связи.</p>
            
            <!-- Форма обратной связи -->
            <div class="contact-form">
                <h3>Форма обратной связи</h3>
                <form method="post" action="#">
                    <input type="text" placeholder="Ваше имя" required>
                    <input type="email" placeholder="Email" required>
                    <input type="tel" placeholder="Телефон">
                    
                    <!-- Радиокнопки -->
                    <div style="margin: 10px 0;">
                        <label><input type="radio" name="gender" value="male"> Мужчина</label>
                        <label><input type="radio" name="gender" value="female"> Женщина</label>
                    </div>
                    
                    <!-- Флажки -->
                    <div style="margin: 10px 0;">
                        <label><input type="checkbox" name="newsletter"> Подписаться на рассылку</label>
                        <label><input type="checkbox" name="callback"> Заказать обратный звонок</label>
                    </div>
                    
                    <!-- Раскрывающийся список -->
                    <select style="width: 100%; margin: 8px 0; padding: 8px;" name="topic">
                        <option>Выберите тему</option>
                        <option>Вопрос по услугам</option>
                        <option>Предложение о сотрудничестве</option>
                        <option>Жалоба</option>
                        <option>Другое</option>
                    </select>
                    
                    <!-- Прокручивающееся текстовое поле -->
                    <textarea placeholder="Ваше сообщение" name="message" required></textarea>
                    
                    <!-- Переключатель -->
                    <div style="margin: 10px 0;">
                        <label><input type="radio" name="priority" value="low"> Низкий приоритет</label>
                        <label><input type="radio" name="priority" value="high"> Высокий приоритет</label>
                    </div>
                    
                    <input type="submit" value="Отправить сообщение">
                </form>
            </div>

            <hr>

            <h2>Как добраться</h2>
            <p>ТРК Атриум расположен в самом центре столицы, по адресу <b>ул. Земляной Вал, дом 33</b>, в непосредственной близости от Садового кольца и Курского вокзала.</p>
            <p>Добраться до ТРК Атриум можно с помощью метро (станции Курская или Чкаловская), МЦД (диаметры: D2, D4) или на личном автомобиле.</p>

            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1587.5136520935155!2d37.65739796418924!3d55.75705450484313!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54a8dad5d4887%3A0xdc4b5c48224c7c31!2sSarto%20Reale!5e0!3m2!1sru!2sru!4v1759680209831!5m2!1sru!2sru" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="contact-info">
                <h3>Контактные данные:</h3>
                <p><b>Телефон:</b> +7 (495) 123-45-67</p>
                <p><b>Email:</b> info@atrium.ru</p>
                <p><b>Адрес:</b> Москва, ул. Земляной Вал, д. 33</p>
                <p><b>Время работы:</b> ежедневно с 10:00 до 22:00</p>
            </div>
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