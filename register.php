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
    $confirm_password = sanitize_input($_POST['confirm_password']);

    // Перевірка на порожні поля
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error_message = "Будь ласка, заповніть усі поля.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Паролі не співпадають.";
    } else {
        // Перевірка унікальності користувача
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Користувач з таким ім'ям вже існує.";
        } else {
            // Хешування пароля
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Вставка нового користувача
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashed_password);
            if ($stmt->execute()) {
                $success_message = "Реєстрація успішна! Тепер ви можете увійти.";
                header('Location: login.php');
                exit;
            } else {
                $error_message = "Помилка при реєстрації. Спробуйте ще раз.";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація</title>
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
        .register-container {
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
        .success-message {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <h2>Реєстрація</h2>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <input type="text" name="username" class="input-field" placeholder="Ім'я користувача" required>
            <input type="password" name="password" class="input-field" placeholder="Пароль" required>
            <input type="password" name="confirm_password" class="input-field" placeholder="Підтвердження паролю" required>
            <button type="submit" class="button">Зареєструватися</button>
        </form>
        <p>Вже є аккаунт? <a href="login.php">Увійти</a></p>
    </div>

</body>
</html>
