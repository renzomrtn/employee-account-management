<?php
session_start();

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'User') {
    header("Location: /Activity1/public/access-denied.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baseline Activity</title>
    <link rel="stylesheet" href="../public/global.css">
</head>

<body>
    <div class="wrapper">
        <div class="navbar">
            <div class="left">
                <h2>Employee Dashboard</h2>
                <h4>User: </h4>
            </div>
            <div class="right">
                <button type="button" class="logout" id="logout-btn">Logout</button>
            </div>
        </div>
        <div class="content">
            <div class="welcome">
                <h1>Welcome to the Employee Dashboard</h1>
                <p>This is the employee dashboard.</p>
            </div>
        </div>
    </div>
    <script src="logout.js"></script>
</body>

</html>