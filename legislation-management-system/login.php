<?php
session_start();

//  REPLACE THIS WITH YOUR ACTUAL USER DATA STORAGE MECHANISM (database or file)
$users = [
    'member1' => ['password' => password_hash('memberpass', PASSWORD_DEFAULT), 'role' => 'Member of Parliament'],
    'reviewer1' => ['password' => password_hash('reviewpass', PASSWORD_DEFAULT), 'role' => 'Reviewer'],
    'admin1' => ['password' => password_hash('adminpass', PASSWORD_DEFAULT), 'role' => 'Administrator'],
];

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
        $_SESSION["username"] = $username;
        $_SESSION["role"] = $users[$username]['role'];

        // Remember Me Functionality
        if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
            // Securely set the cookie
            setcookie('remember_me', $username, time() + (86400 * 30), '/', '', true, true);

            // Encrypt password before storing (Simple Base64 + salt, not highly secure)
            $encryptedPassword = base64_encode($_POST['password'] . 'SALT'); // Add a salt to make it a bit harder to decode
            setcookie('remember_pass', $encryptedPassword, time() + (86400 * 30), '/', '', true, true);
        } else {
            // Clear the cookie if "Remember Me" is unchecked
            setcookie('remember_me', '', time() - 3600, '/', '', true, true); 
            setcookie('remember_pass', '', time() - 3600, '/', '', true, true);
        }

        header("Location: view_bill.php"); // Redirect to a welcome page
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
    <h1>Login</h1>
    <form method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo isset($_COOKIE['remember_me']) ? $_COOKIE['remember_me'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo isset($_COOKIE['remember_pass']) ? str_replace('SALT', '', base64_decode($_COOKIE['remember_pass'])) : ''; ?>">

        </div>
        <div class="form-group remember-me">  <!-- Added class for styling -->
            <input type="checkbox" id="remember" name="remember" value="on">
            <label for="remember">Remember Me</label>
        </div>
        <div class="button-group">  <!-- Added button-group div -->
            <input type="submit" value="Login">
        </div>
    </form>

    <?php if(isset($error)){echo "<p class='error'>$error</p>";} ?>
    </div>   
</body>
</html>