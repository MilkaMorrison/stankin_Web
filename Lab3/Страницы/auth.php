<?php
session_start();
require_once 'db_connect.php';

$error = '';
$success = '';

// Определяем, какая форма отправлена
$is_login_form = ($_POST && isset($_POST['username']) && isset($_POST['password']) && !isset($_POST['confirm_password']));
$is_register_form = ($_POST && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password']));

// Обработка ВХОДА
if ($is_login_form) {
    try {
        $query = "SELECT id, username, password FROM atrium.users WHERE email = :email OR username = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $_POST['username']);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($_POST['password'], $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                header("Location: catalog.php?message=login_success");
                exit();
            } else {
                $error = 'Неверный пароль!';
            }
        } else {
            $error = 'Пользователь не найден!';
        }
    } catch (PDOException $e) {
        $error = 'Ошибка авторизации: ' . $e->getMessage();
    }
}

// Обработка РЕГИСТРАЦИИ
if ($is_register_form) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $error = 'Пароли не совпадают!';
    } else {
        try {
            $query = "INSERT INTO atrium.users 
                     (fullname, email, username, password, phone, gender, country, newsletter, additional_info) 
                     VALUES 
                     (:fullname, :email, :username, :password, :phone, :gender, :country, :newsletter, :additional_info)";
            
            $stmt = $pdo->prepare($query);
            
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt->bindParam(':fullname', $_POST['fullname']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->bindParam(':username', $_POST['username']);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':phone', $_POST['phone']);
            $stmt->bindParam(':gender', $_POST['gender']);
            $stmt->bindParam(':country', $_POST['country']);
            $newsletter = isset($_POST['newsletter']) ? 1 : 0;
            $stmt->bindParam(':newsletter', $newsletter);
            $stmt->bindParam(':additional_info', $_POST['additional_info']);
            
            if ($stmt->execute()) {
                $success = 'Регистрация прошла успешно! Теперь вы можете войти.';
                $_POST = array();
            }
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'unique constraint') !== false) {
                $error = 'Пользователь с таким email или логином уже существует!';
            } else {
                $error = 'Ошибка регистрации: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>Атриум - Вход и регистрация</title>
    <style>
        .error-message {
            color: red;
            background: #ffe6e6;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid red;
        }
        .success-message {
            color: green;
            background: #e6ffe6;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid green;
        }
        .user-info {
            display: inline-block;
            margin-right: 15px;
            color: #333;
            font-weight: bold;
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
                <span class="user-info"><?php echo $_SESSION['username']; ?></span>
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
        <td width="130" valign="top" align="center" class="left-menu">
            <a href="../index.php">Главная</a><br>
            <a href="about_us.php">О нас</a><br>
            <a href="catalog.php">Услуги</a><br>
            <a href="contacts.php">Контакты</a><br>
        </td>

        <!-- Центральный контент -->
        <td width="640" valign="top">
            <h1 align="center">Вход и регистрация</h1>
            
            <!-- Если пользователь уже вошел -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="success-message">
                    <p>Вы уже вошли в систему как <strong><?php echo $_SESSION['username']; ?></strong></p>
                    <p><a href="catalog.php">Перейти к услугам</a> или <a href="logout.php">выйти</a></p>
                </div>
            <?php else: ?>
            
                <!-- Показываем сообщения об ошибках/успехе -->
                <?php if ($error): ?>
                    <div class="error-message">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Форма авторизации -->
                <div class="auth-form">
                    <h2>Вход в систему</h2>
                    <form method="post">
                        <input type="text" name="username" placeholder="Логин или Email" required 
                               value="<?php echo isset($_POST['username']) && !isset($_POST['confirm_password']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        <input type="password" name="password" placeholder="Пароль" required>
                        
                        <div style="margin: 10px 0;">
                            <label><input type="checkbox" name="remember"> Запомнить меня</label>
                        </div>
                        
                        <input type="submit" value="Войти">
                        
                        <div style="text-align: center; margin-top: 15px;">
                            <p>Нет аккаунта? <a href="#registration">Зарегистрируйтесь</a></p>
                        </div>
                    </form>
                </div>

                <hr id="registration">

                <!-- Форма регистрации -->
                <div class="auth-form">
                    <h2>Регистрация нового пользователя</h2>
                    <form method="post">
                        <input type="text" name="fullname" placeholder="ФИО" required 
                               value="<?php echo isset($_POST['fullname']) && isset($_POST['confirm_password']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                        <input type="email" name="email" placeholder="Email" required 
                               value="<?php echo isset($_POST['email']) && isset($_POST['confirm_password']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <input type="text" name="username" placeholder="Логин" required 
                               value="<?php echo isset($_POST['username']) && isset($_POST['confirm_password']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        <input type="password" name="password" placeholder="Пароль" required>
                        <input type="password" name="confirm_password" placeholder="Подтвердите пароль" required>
                        <input type="tel" name="phone" placeholder="Телефон"
                               value="<?php echo isset($_POST['phone']) && isset($_POST['confirm_password']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        
                        <div style="margin: 10px 0;">
                            <label>
                                <input type="radio" name="gender" value="male" required 
                                       <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'male' && isset($_POST['confirm_password'])) ? 'checked' : ''; ?>> 
                                Мужской
                            </label>
                            <label>
                                <input type="radio" name="gender" value="female" required
                                       <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'female' && isset($_POST['confirm_password'])) ? 'checked' : ''; ?>> 
                                Женский
                            </label>
                        </div>
                        
                        <select name="country" style="width: 100%; margin: 8px 0; padding: 8px;">
                            <option value="">Выберите страну</option>
                            <option value="ru" <?php echo (isset($_POST['country']) && $_POST['country'] == 'ru' && isset($_POST['confirm_password'])) ? 'selected' : ''; ?>>Россия</option>
                            <option value="kz" <?php echo (isset($_POST['country']) && $_POST['country'] == 'kz' && isset($_POST['confirm_password'])) ? 'selected' : ''; ?>>Казахстан</option>
                            <option value="by" <?php echo (isset($_POST['country']) && $_POST['country'] == 'by' && isset($_POST['confirm_password'])) ? 'selected' : ''; ?>>Беларусь</option>
                            <option value="other" <?php echo (isset($_POST['country']) && $_POST['country'] == 'other' && isset($_POST['confirm_password'])) ? 'selected' : ''; ?>>Другая</option>
                        </select>
                        
                        <div style="margin: 15px 0;">
                            <h3>Загрузка документов</h3>
                            <div style="margin: 8px 0;">
                                <label>Дополнительный документ:</label>
                                <input type="file" name="additional_doc" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                            <div style="margin: 8px 0;">
                                <label>Фото профиля:</label>
                                <input type="file" name="profile_photo" accept=".jpg,.jpeg,.png">
                            </div>
                        </div>
                        
                        <div style="margin: 10px 0;">
                            <label>
                                <input type="checkbox" name="agree_terms" required
                                       <?php echo (isset($_POST['agree_terms']) && isset($_POST['confirm_password'])) ? 'checked' : ''; ?>> 
                                Я согласен с <a href="../Документы/Правила_пользования.pdf">правилами пользования</a>
                            </label><br>
                            <label>
                                <input type="checkbox" name="agree_privacy" required
                                       <?php echo (isset($_POST['agree_privacy']) && isset($_POST['confirm_password'])) ? 'checked' : ''; ?>> 
                                Я согласен на обработку персональных данных
                            </label><br>
                            <label>
                                <input type="checkbox" name="newsletter"
                                       <?php echo (isset($_POST['newsletter']) && isset($_POST['confirm_password'])) ? 'checked' : ''; ?>> 
                                Подписаться на новостную рассылку
                            </label>
                        </div>
                        
                        <textarea name="additional_info" placeholder="Дополнительная информация" rows="4"><?php echo (isset($_POST['additional_info']) && isset($_POST['confirm_password'])) ? htmlspecialchars($_POST['additional_info']) : ''; ?></textarea>
                        
                        <input type="submit" value="Зарегистрироваться">
                    </form>
                </div>
            <?php endif; ?>
        </td>

        <!-- Правая колонка -->
        <td width="130" valign="top" align="center" class="right-menu">
            <a href="https://www.atrium.su/" target="_blank">
                <img src="../Картинки/banner1.jpg" alt="banner1" width="120" height="100">
            </a>
            <a href="https://www.atrium.su/" target="_blank">
                <img src="../Картинки/banner2.png" alt="banner2" width="120" height="100">
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