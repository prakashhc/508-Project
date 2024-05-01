<?php
session_start();

if (isset($_SESSION['user_ID'])) {
    header("Location: index.php");
    exit();
}

include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmpassword'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip'];
    $blg_name = $_POST['blgName'];

    // Validation checks
    if (!strpos($email, '@')) {
        $error_message = "Email address must contain '@' symbol.";
    } elseif (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password) || empty($address) || empty($city) || empty($state) || empty($zip_code)) {
        $error_message = "All fields except Building Name are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } elseif (!preg_match('/^[A-Z]{2}$/', $state)) {
        $error_message = "State must be a 2-letter abbreviation.";
    } elseif (!preg_match('/^\d{5}$/', $zip_code)) {
        $error_message = "Zip code must be a 5-digit number.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Create Building
        $building_sql = "INSERT INTO Building (address, city, state, zipCode, buildingName) VALUES ('$address', '$city', '$state', '$zip_code', '$blg_name')";
        if ($conn->query($building_sql) === TRUE) {
            $building_id = $conn->lastInsertId();

            // Create User
            $user_sql = "INSERT INTO webapp_user (email, first_name, last_name, password, building_id) VALUES ('$email', '$first_name', '$last_name', '$hashed_password', '$building_id')";
            if ($conn->query($user_sql) === TRUE) {
                $user_id = $conn->lastInsertId();
                $_SESSION['user_ID'] = $user_id;
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Error creating user: " . $conn->error;
            }
        } else {
            $error_message = "Error creating building: " . $conn->error;
        }
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
    <title>Sign Up - While You're At It</title>
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
        <h1>SIGN UP</h1>
        <br>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <h5>Personal Information</h5>
            <br>
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" placeholder="First Name" value="<?php echo isset($first_name) ? $first_name : ''; ?>" required>
            <br><br>
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" placeholder="Last Name" value="<?php echo isset($last_name) ? $last_name : ''; ?>" required>
            <br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email" value="<?php echo isset($email) ? $email : ''; ?>" required>
            <br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <br><br>
            <label for="confirmpassword">Retype Password:</label>
            <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Password" required>
            <br><br>
            <h5>Location Information</h5>
            <br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Address" value="<?php echo isset($address) ? $address : ''; ?>" required>
            <br><br>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" placeholder="City" value="<?php echo isset($city) ? $city : ''; ?>" required>
            <br><br>
            <label for="state">State:</label>
            <input type="text" id="state" name="state" placeholder="State" maxlength="2" value="<?php echo isset($state) ? $state : ''; ?>" required>
            <br><br>
            <label for="zip">Zip Code:</label>
            <input type="text" id="zip" name="zip" placeholder="Zip Code" maxlength="5" value="<?php echo isset($zip_code) ? $zip_code : ''; ?>" required>
            <br><br>
            <label for="blgName">Building Name:</label>
            <input type="text" id="blgName" name="blgName" placeholder="Building Name" value="<?php echo isset($blg_name) ? $blg_name : ''; ?>">
            <br><br>
            <button type="submit">Sign up</button>
        </form>
        <br><br>
        <p>Already have an account? Login up <a href="signin.php">here</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
