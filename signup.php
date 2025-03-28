<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <header>
        <div class="navbar">
            <span class="title">My Friend System</span>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li class="current"><a href="signup.php">Sign-Up</a></li>
                    <li><a href="login.php">Log-In</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <?php
    require_once("settings.php");
    $email = $profileName = $password = null;

    $emailRegex = "/^[a-zA-Z0-9.]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/";
    $profileRegex = "/^[a-zA-Z ]+$/";
    $passwordRegex = "/^[a-zA-Z0-9]+$/";

    function cleaningInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    function validatingField($fieldName, $fieldValue, $regex, $errorMsg)
    {
        if(!empty($fieldValue)){
            if(!preg_match($regex,$fieldValue)){
                echo "<p style=color:red>$errorMsg</p>";
            }else{
                return $fieldValue;
            }
        }else{
            echo "<p style=color:blue>$fieldName empty!</p>";
        }
    }

    function checkingUniqueEmail($email)
    {
        global $conn, $table1;
        $sql = "SELECT friend_email FROM $table1 WHERE friend_email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $numRows = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);

        if ($numRows > 0) {
            echo "<p style=color:blue>Email already exists</p>";
            return false;
        }
        return true;
    }

    function confirmingPassword($password, $passwordConfirmation)
    {
        if ($password !== $passwordConfirmation) {
            echo "<p style=color:blue>Please ensure the passwords are identical!</p>";
            return false;
        }
        return true;
    }
    ?>

    <main>
        <div class="content">
            <h1 style="text-align:center">Registration Page</h1>
            <form action="signup.php" method="post">
            <div>
                <div class="section">
                    <p>
                        <label for="email">Email</label>
                    </p>
                </div>
                <div class="section-content">
                    <p>
                        <input name="email" value="<?php echo isset($_POST['email']) 
                                            ? htmlspecialchars($_POST['email']) 
                                            : ''; ?>">
                        <?php 
                            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                                $email = validatingField("Email", cleaningInput($_POST['email']), $emailRegex, "Invalid Email!");
                                $confirmedEmail = checkingUniqueEmail($email);
                            }
                        ?>
                    </p>
                </div>
            </div>

            <div>
                <div class="section">
                    <p>
                        <label for="profileName">Profile Name</label>
                    </p>
                </div>
                <div class="section-content">
                    <p>
                        <input name="profileName" value="<?php echo isset($_POST['profileName']) 
                                            ? htmlspecialchars($_POST['profileName']) 
                                            : ''; ?>">
                        <?php 
                            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                                $profileName = validatingField("Profile Name", cleaningInput($_POST['profileName']), $profileRegex, "Invalid profile name!");
                            }    
                        ?>
                    </p>
                </div>
            </div>

            <div>
                <div class="section">
                    <p>
                        <label for="password">Password</label>
                    </p>
                </div>
                <div class="section-content">
                    <p><input name="password" type="password">
                        <?php 
                            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                                $password = validatingField("Password", cleaningInput($_POST['password']), $passwordRegex, "Invalid password syntax!");
                            }
                        ?>
                    </p>
                </div>
            </div>

            <div>
                <div class="section">
                    <p>
                        <label for="passwordConfirmation">Confirm Password</label>
                    </p>
                </div>
                <div class="section-content">
                    <p><input type="password" name="passwordConfirmation">
                        <?php 
                            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                                $passwordsMatched = confirmingPassword($password, cleaningInput($_POST['passwordConfirmation']));
                            }
                        ?>
                    </p>
                </div>
            </div>

            <?php
                if($email && $profileName && $password && $confirmedEmail && $passwordsMatched){
                    $sql = "INSERT INTO $table1 (friend_email, password, profile_name, date_started, num_of_friends)
                            VALUES (?, ?, ?, CURDATE(), 0)";
                    $statement = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($statement, "sss", $email, $password, $profileName);
                    
                    if(mysqli_stmt_execute($statement)){
                        session_start();
                        $_SESSION['email'] = $email;
                        $_SESSION['loggedIn'] = true;
                        header("Location: friendadd.php");
                        exit();
                    }else{
                        echo "<p style=color:blue>Account creation failed: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($statement);
                }
            ?>

            <div>
                <div class="declaration">
                    <p><input type="submit" value="Register">
                        <a href="signup.php">Clear</a>
                    </p> 
                </div>
            </div>
            </form>
        </div>
    </main>
</body>