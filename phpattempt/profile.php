<?php
session_start();

if (isset($_SESSION['user_id'])) {
    include 'connection.php';

    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $new_password = $_POST['password'];

        $sql = "UPDATE webapp_user SET first_name = '$first_name', last_name = '$last_name'";
        if (!empty($new_password)) {
            $hashed_password = hash('sha256', $new_password);
            $sql .= ", password = '$hashed_password'";
        }
        $sql .= " WHERE id = '$user_id'";

        if ($conn->query($sql) === TRUE) {
            $building_sql = "SELECT * FROM Building WHERE id = (SELECT building_id FROM webapp_user WHERE id = '$user_id')";
            $building_result = $conn->query($building_sql);
            if ($building_result->num_rows > 0) {
                $building_row = $building_result->fetch_assoc();
                $building_id = $building_row['id'];
                $address = $_POST['address'];
                $city = $_POST['city'];
                $state = $_POST['state'];
                $zipCode = $_POST['zip'];

                $update_building_sql = "UPDATE Building SET address = '$address', city = '$city', state = '$state', zipCode = '$zipCode' WHERE id = '$building_id'";
                $conn->query($update_building_sql);
            }
            $success_message = "Your settings have been updated successfully.";
        } else {
            $error_message = "Error updating settings: " . $conn->error;
        }
    } else {
        $sql = "SELECT * FROM webapp_user WHERE id = '$user_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = $row;
        }
    }

    $conn->close();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styleDeliver.css">
    <title>While You're At It</title>
</head>
<body>
    <!-- Bootstrap Navigation bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <!-- Title -->
            <a class="navbar-brand" href="#">While You're At It</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/index">Home</a>
                    </li>
                    <!-- Deliver -->
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/deliver">Deliver</a>
                    </li>
                    <!-- Order -->
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/order">Order</a>
                    </li>
                    <!-- Profile -->
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/profile">Profile</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <a href="/signout/" class="btn btn-outline-success">Logout</a>
                </form>
            </div>
        </div>
    </nav>
    <!-- end of bootstrap nav -->
    <body>
        <h1>Settings</h1>
        <?php if (isset($success_message)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php elseif (isset($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo isset($user) ? $user['first_name'] : ''; ?>"><br>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo isset($user) ? $user['last_name'] : ''; ?>"><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password"><br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo isset($user['building_id']) ? $user['building']['address'] : ''; ?>"><br>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?php echo isset($user['building_id']) ? $user['building']['city'] : ''; ?>"><br>
            <label for="state">State:</label>
            <input type="text" id="state" name="state" value="<?php echo isset($user['building_id']) ? $user['building']['state'] : ''; ?>"><br>
            <label for="zip">Zip Code:</label>
            <input type="text" id="zip" name="zip" value="<?php echo isset($user['building_id']) ? $user['building']['zipCode'] : ''; ?>"><br>
            <input type="submit" value="Update">
        </form>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
?>