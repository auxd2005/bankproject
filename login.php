<?php
session_start();
require 'db.php';  

// Функція санітарної очистки
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$error_message = ""; // Оголошуємо змінну для повідомлення про помилку

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Санітарна очистка та перевірка введених даних
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    // Перевірка на порожні поля
    if (empty($username) || empty($password)) {
        $error_message = "Будь ласка, введіть ім'я користувача та пароль.";
    } else {
        // Підготовлений запит для уникнення SQL Injection
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];  // Зберігаємо id користувача в сесії
                $_SESSION['username'] = $username;  // Зберігаємо ім'я користувача в сесії
                header('Location: index.php'); // Перенаправляємо на головну сторінку після успішного логіну
                exit;  // Завершуємо виконання коду
            } else {
                $error_message = "Невірний пароль.";
            }
        } else {
            $error_message = "Користувача з таким ім'ям не знайдено.";
        }

        // Закриваємо запит
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Логін</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Вхід в систему</h2>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <input type="text" name="username" class="input-field" placeholder="Ім'я користувача" required>
            <input type="password" name="password" class="input-field" placeholder="Пароль" required>
            <button type="submit" class="button">Увійти</button>
        </form>
        <p>Ще не зареєстровані? <a href="register.php">Зареєструватися</a></p>
    </div>

</body>
</html>
