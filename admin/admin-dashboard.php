<?php
session_start();

if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: /Activity1/public/access-denied.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Data Table</title>
    <link rel="stylesheet" href="../public/global.css">
    <link rel="stylesheet" href="admin-dashboard.css">
    <style>
        table,
        th,
        td {
            border: 1px solid #4c7aaf;
            /* Adds borders to the table, headers, and data cells */
            border-collapse: collapse;
            /* Collapses borders into a single line */
            padding: 8px;
            /* Adds space between cell content and borders */
            text-align: left;
            /* Aligns text to the left */
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="navbar">
            <div class="left">
                <h2>Employee-Account Management Dashboard</h2>
                <h4>Admin: </h4>
            </div>
            <div class="right">
                <button type="button" class="logout" id="logout-btn">Logout</button>
            </div>
        </div>
        <div class="content">
            <div class="top">
                <div class="employee-form">
                    <div class="modal-head">
                        <h3>Employee-Account Data Entry Form</h3>
                    </div>
                    <form id="employee-form">
                        <p class="form-section">Employee Information</p>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required><br><br>

                        <div class="wrap">
                            <div class="inner-wrap">
                                <label for="address">Address:</label>
                                <input type="text" id="address" name="address" required>
                            </div>
                            <div class="inner-wrap">
                                <label for="birthday">Birthday</label>
                                <input type="date" id="birthday" name="birthday" required>
                            </div>
                        </div><br>

                        <div class="wrap">
                            <div class="inner-wrap">
                                <label for="position">Position:</label>
                                <select id="position" name="position" required>
                                    <option value="" disabled selected>Select position</option>
                                    <option value="Trainee">Trainee</option>
                                    <option value="Personnel">Personnel</option>
                                    <option value="Analyst">Analyst</option>
                                    <option value="Developer">Developer</option>
                                    <option value="Project leader">Project leader</option>
                                    <option value="Manager">Manager</option>
                                    <option value="CEO">CEO</option>
                                    <option value="Support">Support</option>
                                </select>
                            </div>
                            <div class="inner-wrap">
                                <label for="department">Department:</label>
                                <select id="department" name="department" required>
                                    <option value="" disabled selected>Select department</option>
                                    <option value="Human Resources">Human Resources</option>
                                    <option value="Frontend">Frontend</option>
                                    <option value="Backend">Backend</option>
                                    <option value="QA">QA</option>
                                    <option value="Research">Research</option>
                                    <option value="Logistics">Logistics</option>
                                    <option value="Administration">Administration</option>
                                </select>
                            </div>
                        </div><br>

                        <p class="form-section">Account Information</p>
                        <div class="wrap">
                            <div class="inner-wrap">
                                <label for="username">Username:</label>
                                <input type="text" id="username" name="username" required>
                            </div>
                            <div class="inner-wrap">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                        </div><br <label for="role">User Account Role:</label>
                        <select id="role" name="role" required>
                            <option value="" disabled selected>Select role</option>
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                        </select>
                        <br><br>

                        <button type="submit" class="btn">Submit</button>
                    </form>
                </div>
                <div class="employee-table">
                <div class="modal-head">
                    <h3>Employee & Account Data</h3>
                    <button type="button" class="refresh">Refresh</button>
                </div>
                <table id="employee-account-data">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Username</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        <script src="admin-dashboard.js"></script>
        <script src="logout.js"></script>
</body>

</html>