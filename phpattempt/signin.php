<?php
session_start();

if (isset($_SESSION['user_ID'])) {
    header("Location: index.php");
    exit();
}

include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM webapp_user WHERE email = :email");
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['user_ID'] = $row['id'];
        header("Location: index.php");
        exit();
    } else {
        header("Location: signin.php?error=1");
        exit();
    }
}

$conn = null;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>While You're At It</title>
</head>
<body>
    <!-- Bootstrap Navigation bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <!-- Title -->
            <a class="navbar-brand">While You're At It</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <!-- end of bootstrap nav -->
    <div class="IndexTitle">
        <h1>LOGIN</h1>
        <br>
        <?php if (isset($_GET['error']) && $_GET['error'] == 1) : ?>
            <div class="alert alert-danger" role="alert">
                Invalid email or password.
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <!-- PHP code for handling form submission -->
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="Email" required>
            <br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <br><br>
            <button type="submit">Login</button>
        </form>
        <br><br>
        <p>Don't have an account? Sign up <a href="signup.php">here</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
