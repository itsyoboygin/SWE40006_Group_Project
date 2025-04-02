<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
</head>
<body>
    <header>
        <div class="navbar">
            <span class="title">My Friends System</span>
            <nav>
                <ul>
                    <li class="current"><a href="index.php">Home</a></li>
                    <li><a href="signup.php">Sign-Up</a></li>
                    <li><a href="login.php">Log-In</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <h1 style="text-align:center">Project Index</h1>
        <div class="content">
            <div>
                <div class="section">
                    <p>Name: </p>
                </div>
                <div class="section-content">
                    <p style="font-weight:bold">Vu Bao Phuc Do</p>
                </div>
            </div>

            <div>
                <div class="section">
                    <p>Student ID: </p>
                </div>
                <div class="section-content">
                    <p style="font-weight:bold">103847381</p>
                </div>
            </div>

            <div>
                <div class="section">
                    <p>Email: </p>
                </div>
                <div class="section-content">
                    <p><a href="mailto:103847381@student.swin.edu.au">103847381@student.swin.edu.au</a></p>
                </div>
            </div>
        </div>
        
        <div class="content">
            <div>
                <div class="declaration">
                    <p class="highlight">I declare that this assignment is my individual work. I have not worked
                    collaboratively, nor have I copied from any other student’s work or from any other source.</p>
                </div>
            </div>

            <div class="declaration">

                <?php
                    require_once("settings.php");
                    $sql1 = "CREATE TABLE IF NOT EXISTS $table1 (
                        friend_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        friend_email VARCHAR(50) NOT NULL,
                        password VARCHAR(20) NOT NULL,
                        profile_name VARCHAR(20) NOT NULL,
                        date_started DATE NOT NULL,
                        num_of_friends INT UNSIGNED NOT NULL
                    )";
                    if(mysqli_query($conn, $sql1)){
                        echo "<p style=color:green>Table '$table1' created successfully.\n</p>";
                    }else{
                        echo "<p style-color:blue>Table '$table1' creation failed: " . mysqli_error($conn) . "\n</p>";
                    }

                    $sql2 = "CREATE TABLE IF NOT EXISTS $table2 (
                        friend_id1 INT NOT NULL,
                        friend_id2 INT NOT NULL
                    )";
                    if(mysqli_query($conn, $sql2)){
                        echo "<p style=color:green>Table '$table2' created successfully.\n</p>";
                    }else{
                        echo "<p style-color:blue>Table '$table2' creation failed: " . mysqli_error($conn) . "\n</p>";
                    }

                    $emptyCheck1 = "SELECT * FROM $table1";
                    $records1 = mysqli_num_rows(mysqli_query($conn, $emptyCheck1));
                    if($records1 > 0){
                        echo "<p style=color:blue>Data already existed in table $table1.\n</p>";
                    }else{
                        $inserting1 = "INSERT INTO $table1 (friend_email, password, profile_name, date_started, num_of_friends) VALUES
                            ('friend1@gmail.com', 'pswd1', 'Marvin Gaye', '2024-01-09', 4),
                            ('friend2@gmail.com', 'pswd2', 'Al Green', '2024-01-11', 4),
                            ('friend3@gmail.com', 'pswd3', 'Prince', '2024-01-19', 4),
                            ('friend4@gmail.com', 'pswd4', 'George Benson', '2024-01-29', 4),
                            ('friend5@gmail.com', 'pswd5', 'Luther Vandross', '2024-02-05', 4),
                            ('friend6@gmail.com', 'pswd6', 'El Debarge', '2024-02-09', 4),
                            ('friend7@gmail.com', 'pswd7', 'James Brown', '2024-03-18', 4),
                            ('friend8@gmail.com', 'pswd8', 'George Clinton', '2024-03-25', 4),
                            ('friend9@gmail.com', 'pswd9', 'Dangelo', '2024-04-09', 4),
                            ('friend10@gmail.com', 'pswd10', 'Stevie Wonder', '2024-04-17', 4)";
                        if(mysqli_query($conn, $inserting1)){
                            echo "<p style=color:green>Sample records inserted successfully into table $table1.\n</p>";
                        }else{
                            echo "<p style=color:blue>Data insertion failed: " . mysqli_error($conn) . "\n</p>";
                        }
                    }

                    $emptyCheck2 = "SELECT * FROM $table2";
                    $records2 = mysqli_num_rows(mysqli_query($conn, $emptyCheck2));
                    if ($records2 > 0) {
                        echo "<p style=color:blue>Data already existed in table $table2.\n</p>";
                    } else {
                        $inserting2 = "INSERT INTO myfriends (friend_id1, friend_id2) VALUES
                            (1, 6),
                            (2, 7),
                            (3, 8),
                            (4, 9),
                            (5, 10),
                            (6, 5),
                            (7, 4),
                            (8, 3),
                            (9, 2),
                            (10, 1),
                            (1, 5),
                            (2, 4),
                            (3, 2),
                            (4, 1),
                            (5, 3),
                            (6, 10),
                            (7, 9),
                            (8, 7),
                            (9, 6),
                            (10, 8)";
                        if (mysqli_query($conn, $inserting2)) {
                            echo "<p style=color:green>Sample records inserted successfully into table $table2.</p>";
                        } else {
                            echo "<p style=color:blue>Data insertion failed: " . mysqli_error($conn) . "</p>";
                        }
                    }
                    
                ?>
            </div>
        </div>
    </main>
</body>
</html>