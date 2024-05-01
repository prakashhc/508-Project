<?php
include 'connection.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_ID'];
$sql = "SELECT first_name FROM webapp_user WHERE id = '$user_id'";
$result = $conn->query($sql);
if ($result->rowCount() > 0) {
    $row = $result->fetch();
    $first_name = $row['first_name'];
} else {
    header("Location: signin.php");
    exit();
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
    <h1>Welcome, <?php echo $first_name; ?></h1>
    <!-- Rest of the HTML code -->
</body>
</html>
