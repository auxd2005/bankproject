<?php
require 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_number = $_POST['account_number'];
    $balance = $_POST['balance'];

    $sql = "UPDATE Accounts SET balance = ? WHERE account_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $balance, $account_number); // 'd' for double (balance), 'i' for integer (account_number)

    if ($stmt->execute()) {
        $message = "Баланс оновлено успішно!";
    } else {
        $message = "Помилка: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оновлення балансу рахунку</title>
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

        .container {
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

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
        }

        .message.success {
            background-color: #4CAF50;
            color: white;
        }

        .message.error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Оновлення балансу рахунку</h2>

        <form method="POST">
            <div class="form-group">
                <label for="account_number">Номер рахунку:</label>
                <input type="number" id="account_number" name="account_number" required>
            </div>

            <div class="form-group">
                <label for="balance">Новий баланс:</label>
                <input type="number" id="balance" name="balance" required>
            </div>

            <div class="form-group">
                <button type="submit">Оновити</button>
            </div>
        </form>

        <?php if ($message != ""): ?>
            <div class="message <?php echo ($message == "Баланс оновлено успішно!") ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
