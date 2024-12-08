<?php
session_start();  
require 'db.php';

// Перевірка, чи користувач авторизований
if (!isset($_SESSION['user_id'])) {
    // Якщо користувач не авторизований, перенаправляємо на сторінку логіну
    header('Location: login.php');
    exit;  // Зупиняє виконання подальшого коду
}

// Отримання даних про клієнтів з бази даних
$sql = "SELECT nickname, name, date_of_birth FROM Clients";
$result = $conn->query($sql);

// Перевірка, чи користувач натиснув кнопку виходу
if (isset($_POST['logout'])) {
    // Завершення сесії
    session_destroy();
    header('Location: login.php');  // Перенаправлення на сторінку логіну
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Таблиці бази даних</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        h1 {
            text-align: center;
            padding: 20px;
            background-color: #4CAF50;
            color: white;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        .logout-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>Таблиці бази даних</h1>

    <!-- Виведення таблиці Clients -->
    <h2>Клієнти</h2>
    <table>
        <tr>
            <th>Нікнейм</th>
            <th>Ім'я</th>
            <th>Дата народження</th>
        </tr>
        <?php
        $sql = "SELECT nickname, name, date_of_birth FROM Clients";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['nickname']) . "</td><td>" . htmlspecialchars($row['name']) . "</td><td>" . htmlspecialchars($row['date_of_birth']) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Немає клієнтів для відображення</td></tr>";
        }
        ?>
    </table>

    <!-- Виведення таблиці Accounts -->
    <h2>Рахунки</h2>
    <table>
        <tr>
            <th>Номер рахунку</th>
            <th>ID користувача</th>
            <th>Баланс</th>
        </tr>
        <?php
        $sql = "SELECT account_number, user_id, balance FROM Accounts";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['account_number']) . "</td><td>" . htmlspecialchars($row['user_id']) . "</td><td>" . htmlspecialchars($row['balance']) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Немає рахунків для відображення</td></tr>";
        }
        ?>
    </table>

    <!-- Виведення таблиці Employees -->
    <h2>Співробітники</h2>
    <table>
        <tr>
            <th>Ім'я</th>
            <th>Прізвище</th>
            <th>Відділ</th>
        </tr>
        <?php
        $sql = "SELECT name, surname, department FROM Employees";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['name']) . "</td><td>" . htmlspecialchars($row['surname']) . "</td><td>" . htmlspecialchars($row['department']) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Немає співробітників для відображення</td></tr>";
        }
        ?>
    </table>

    <!-- Виведення таблиці Loan_Agreements -->
    <h2>Кредитні договори</h2>
    <table>
        <tr>
            <th>ID користувача</th>
            <th>Сума кредиту</th>
            <th>Ідентифікатор співробітника</th>
        </tr>
        <?php
        $sql = "SELECT user_id, loan_amount, employee_id FROM Loan_Agreements";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['user_id']) . "</td><td>" . htmlspecialchars($row['loan_amount']) . "</td><td>" . htmlspecialchars($row['employee_id']) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Немає кредитних договорів для відображення</td></tr>";
        }
        ?>
    </table>

    <!-- Виведення таблиці Loan_Applications -->
    <h2>Заявки на кредити</h2>
    <table>
        <tr>
            <th>ID користувача</th>
            <th>Сума кредиту</th>
            <th>Ідентифікатор співробітника</th>
        </tr>
        <?php
        $sql = "SELECT user_id, loan_amount, employee_id FROM Loan_Applications";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['user_id']) . "</td><td>" . htmlspecialchars($row['loan_amount']) . "</td><td>" . htmlspecialchars($row['employee_id']) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Немає заявок на кредити для відображення</td></tr>";
        }
        ?>
    </table>

    <!-- Виведення таблиці Transactions -->
    <h2>Транзакції</h2>
    <table>
        <tr>
            <th>ID транзакції</th>
            <th>Номер рахунку</th>
            <th>Сума</th>
        </tr>
        <?php
        $sql = "SELECT transaction_id, account_number, amount FROM Transactions";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['transaction_id']) . "</td><td>" . htmlspecialchars($row['account_number']) . "</td><td>" . htmlspecialchars($row['amount']) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Немає транзакцій для відображення</td></tr>";
        }
        ?>
    </table>

    <!-- Список доступних дій -->
    <h2>Дії</h2>
    <ul>
        <li><a href="add.php">Додати клієнта</a></li>
        <li><a href="delete.php">Видалити клієнта</a></li>
        <li><a href="update.php">Змінити рахунок</a></li>
        <li><a href="filter.php">Фільтр транзакцій</a></li>
    </ul>

    <!-- Кнопка виходу -->
    <form method="POST">
        <button type="submit" name="logout" class="logout-btn">Вийти</button>
    </form>

    <?php
   
    $conn->close();
    ?>

</body>
</html>