<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../include/pgsql_connection.php');
session_start();
$alert_message = '';
$alert_type = '';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Still recommend changing to password_hash()
    
    $query = "SELECT * FROM users WHERE username = $1 AND password = $2 AND type='rammasseur'";
    $result = pg_query_params($pg_conn, $query, array($username, $password));
    
    if ($result && pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);
        $_SESSION['userID'] = $user['id'];
        $_SESSION['fullname'] = $user['firstname'].' '.$user['lastname'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['type'] = $user['type'];
        header("Location: index.php");
        exit();
    } else {
        $alert_message = 'Invalid username or password.';
        $alert_type = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Setifismed</title>
    <link rel="stylesheet" href="styles/css/login.css">
    <style>
        /* Alert styles */
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 4px;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            animation: slideIn 0.3s ease-out, fadeOut 0.5s 2.5s forwards;
        }

        .alert-danger {
            background-color: #f44336;
        }

        .alert-success {
            background-color: #4CAF50;
        }

        .close-btn {
            margin-left: 15px;
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; display: none; }
        }
    </style>
</head>
<body>
<!-- Login Page -->
<div class="login-wrapper" id="loginPage">
    <?php if (!empty($alert_message)): ?>
        <div class="alert alert-<?php echo $alert_type; ?>" id="alertMessage">
            <?php echo htmlspecialchars($alert_message); ?>
            <button class="close-btn" onclick="document.getElementById('alertMessage').remove()">&times;</button>
        </div>
    <?php endif; ?>

    <div class="company-branding">
        <div class="company-logo">
            <img src="styles/images/logo.png" alt="Company Logo" id="companyLogo">
        </div>
        <div class="company-info">
            <h2>Setifismed</h2>
            <p>Your trusted medical partner</p>
        </div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <h1>Welcome back</h1>
            <p>Please enter your details to sign in</p>
        </div>

        <form class="login-form" id="loginForm" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    <span class="toggle-password" id="togglePassword">Show</span>
                </div>
            </div>
            <button name='login' type="submit" class="login-button">Sign In</button>
        </form>
    </div>
</div>
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    // Auto-hide alert after 3 seconds
    <?php if (!empty($alert_message)): ?>
        setTimeout(() => {
            const alert = document.getElementById('alertMessage');
            if (alert) alert.remove();
        }, 10000);
    <?php endif; ?>
</script>
</body>
</html>