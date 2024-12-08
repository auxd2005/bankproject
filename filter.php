<?php
require 'db.php';

$transactions = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $minAmount = $_POST['min_amount'];
    $filterEnabled = isset($_POST['filter_enabled']);

    $sql = "SELECT transaction_id, account_number, amount FROM Transactions";
    if ($filterEnabled) {
        $sql .= " WHERE amount >= ?";
    }

    $stmt = $conn->prepare($sql);

    if ($filterEnabled) {
        $stmt->bind_param("d", $minAmount);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $transactions = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Фільтрація транзакцій</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 500px;
            max-width: 100%;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }

        input[type="number"] {
            padding: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="checkbox"] {
            margin: 10px 0;
        }

        button {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
            font-size: 14px;
            color: #555;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        td {
            background-color: #fff;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Фільтрація транзакцій</h2>
        
        <form method="POST">
            <label for="min_amount">Мінімальна сума транзакції:</label>
            <input type="number" id="min_amount" name="min_amount" required>
            
            <label for="filter_enabled">
                <input type="checkbox" id="filter_enabled" name="filter_enabled"> Увімкнути фільтр
            </label>
            
            <button type="submit">Фільтрувати</button>
        </form>

        <h3>Результати</h3>
        <table>
            <tr>
                <th>ID Транзакції</th>
                <th>ID Рахунку</th>
                <th>Сума</th>
            </tr>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                    
                    <td>
                        <?php
                        $accountNumber = $transaction['account_number'];
                        if (strlen($accountNumber) > 2) {
                            echo str_repeat('*', strlen($accountNumber) - 2) . substr($accountNumber, -2);
                        } else {
                            echo $accountNumber;
                        }
                        ?>
                    </td>
                    
                    <td><?php echo htmlspecialchars($transaction['amount']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
