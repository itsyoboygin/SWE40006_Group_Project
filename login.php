<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-In Page</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <header>
        <div class="navbar">
            <span class="title">My Friend System</span>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="signup.php">Sign-Up</a></li>
                    <li class="current"><a href="login.php">Log-In</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <?php
        require_once("settings.php");
        $emailInput = $pswdInput = 
            $msg1 = $msg2 = $msg3 = $msg4 = null;

        function validatingField($fieldName, $fieldValue){
            if(!empty($fieldValue)){
                return $fieldValue;
            }else{
                echo "<p style=color:red>$fieldName empty!</p>";
            }
            return null;
        }

        function cleaningInput($input){
            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input);
            return $input;
        }
    ?>

    <main>
        <h1 style="text-align:center">Log-In Page</h1>
        <div class='content'>
            <form action="login.php" method="post">
            <div>
                <div class="section">
                    <p><label for="email">Email</label></p>
                </div>
                <div class="section-content">
                    <p><input name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <?php 
                            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                                $emailInput = validatingField("Email", cleaningInput($_POST['email']));
                            }
                        ?>
                    </p>
                </div>
            </div>

            <div>
                <div class="section">
                    <p><label for="password">Password</label></p>
                </div>
                <div class="section-content">
                    <p><input type="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
                        <?php 
                            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                                $pswdInput = validatingField("Password", cleaningInput($_POST['password']));
                            }
                        ?>
                    </p>
                </div>
            </div>
            
            <div class="declaration">
                <?php
                    if($emailInput && $pswdInput){
                        $sql = "SELECT friend_email, password FROM $table1 WHERE friend_email = ?";
                        $statement = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($statement, "s", $emailInput);
                        mysqli_stmt_execute($statement);
                        mysqli_stmt_store_result($statement);

                        if(mysqli_stmt_num_rows($statement) == 0){
                            echo "<p style=color:red>Wrong email/password!</p>";
                        }else{
                            mysqli_stmt_bind_result($statement, $emailDB, $pswdDB);
                            mysqli_stmt_fetch($statement);
                            if($pswdDB === $pswdInput){
                                session_start();
                                $_SESSION['email'] = $emailInput;
                                $_SESSION['loggedIn'] = true;
                                header("Location: friendlist.php");
                                exit();
                            }else{
                                echo "<p style=color:blue>Wrong email/password!</p>";
                            }
                        }
                        mysqli_stmt_close($statement);
                    }
                ?>
            </div>

            <div class="declaration">
                <p>
                    <input type="submit" value="Log-In">
                    <a href="login.php">Clear</a>
                </p>
            </div>
            </form>
        </div>
    </main>
</body>