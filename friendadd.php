<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Friend Page</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <header>
        <div class="navbar">
            <span class="title">My Friend System</span>
            <nav>
                <ul>
                    <li><a href="friendlist.php">Home</a></li>
                    <li class="current"><a href="friendadd.php">Add Friends</a></li>
                    <li><a href="logout.php">Log-Out</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <?php 
        session_start();
        if(!isset($_SESSION["email"]) || !isset($_SESSION["loggedIn"])){
            header("Location: login.php");
        }
        
        require_once("settings.php");
        $sql = "SELECT friend_id, profile_name, num_of_friends FROM $table1
                WHERE friend_email = ?";
        $statement = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($statement, "s", $_SESSION['email']);
        mysqli_stmt_execute($statement);
        $record = mysqli_stmt_get_result($statement);
        $data = mysqli_fetch_assoc($record);

        $userID = $data['friend_id'];
        $proName = $data["profile_name"];
        $friendsNum = $data["num_of_friends"];

        function addingFriends($friendID){
            global $conn, $userID, $friendsNum, $table1, $table2;
            $sql = "INSERT INTO $table2 (friend_id1, friend_id2) VALUES (?, ?)";
            $statement = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($statement, "ii", $userID, $friendID);
            mysqli_stmt_execute($statement);
            $friendsNum++;
            $sql = "UPDATE $table1 SET num_of_friends = ? WHERE friend_id = ?";
            $statement = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($statement, "ii", $friendsNum, $userID);
            mysqli_stmt_execute($statement);

            $sql = "SELECT num_of_friends FROM $table1 WHERE friend_id = ?";
            $statement = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($statement, "i", $friendID);
            mysqli_stmt_execute($statement);
            $record = mysqli_stmt_get_result($statement);
            $data = mysqli_fetch_assoc($record);
            $theirFriendsNum = $data["num_of_friends"];
            $theirFriendsNum++;
            $sql = "UPDATE $table1 SET num_of_friends = ? WHERE friend_id = ?";
            $statement = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($statement, "ii", $theirFriendsNum, $friendID);
            mysqli_stmt_execute($statement);
        }

        if (isset($_POST["addingFriends"])) {
            addingFriends($_POST["friendID"]);
            header("Location: friendadd.php");
            exit();
        }

        $sql = "SELECT COUNT(t1.friend_id) 
            FROM $table1 t1 WHERE t1.friend_id != ? AND t1.friend_id NOT IN 
            (SELECT t2.friend_id1 FROM $table2 t2 WHERE t2.friend_id2 = ?)
            AND t1.friend_id NOT IN 
            (SELECT t2.friend_id2 FROM $table2 t2 WHERE t2.friend_id1 = ?)";
        $statement = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($statement, "iii", $userID, $userID, $userID);
        mysqli_stmt_execute($statement);
        $record = mysqli_stmt_get_result($statement);
        $data = mysqli_fetch_assoc($record);

        $sql = "SELECT t1.friend_id, t1.profile_name
                FROM $table1 t1 WHERE t1.friend_id != ? AND t1.friend_id NOT IN 
                    (SELECT t2.friend_id1 FROM $table2 t2 WHERE t2.friend_id2 = ?)
                AND t1.friend_id NOT IN 
                    (SELECT t2.friend_id2 FROM $table2 t2 WHERE t2.friend_id1 = ?)";
        $statement = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($statement, "iii", $userID, $userID, $userID);
        mysqli_stmt_execute($statement);
        $record = mysqli_stmt_get_result($statement);
    ?>

    <main>
        <div class="header" style="text-align:center">
            <h2>
                <?php echo $proName; ?>'s Add Friends Page
            </h2>
            <h3>Total number of friends is <?php echo $friendsNum; ?></h3>
        </div>
        <div class="declaration" style="align-items: center;">
            <?php
            if(mysqli_num_rows($record) > 0){
                echo "<table>
                        <thead><tr><th>Profile Name</th><th>Mutual Friends</th><th>Action</th></tr></thead>";
                while($data = mysqli_fetch_assoc($record)){
                    $friendID = $data["friend_id"];
                    $friendName = $data["profile_name"];

                    $sqlNew = "SELECT friend_id, COUNT(*) AS mutual_friends
                            FROM $table1 AS t1 JOIN $table2 AS t2
                            ON (t1.friend_id = t2.friend_id1 AND t2.friend_id2 = {$data['friend_id']})
                            OR (t1.friend_id = t2.friend_id2 AND t2.friend_id1 = {$data['friend_id']})
                            WHERE t1.friend_id != ? AND t1.friend_id IN 
                            (SELECT friend_id1 FROM $table2 WHERE friend_id2 = $userID 
                            UNION SELECT friend_id2 FROM $table2 WHERE friend_id1 = $userID)";
                    $statement = mysqli_prepare($conn, $sqlNew);
                    mysqli_stmt_bind_param($statement, "i", $userID);
                    mysqli_stmt_execute($statement);
                    $recordUpdated = mysqli_stmt_get_result($statement);
                    $data = mysqli_fetch_assoc($recordUpdated);
                    $mutualFriends = $data["mutual_friends"];

                    echo "<tbody>
                        <tr>
                            <td>{$friendName}</td>
                            <td>{$mutualFriends} mutual friends</td>
                            <td>
                            <form method='post' action='friendadd.php'>
                                <input type='hidden' name='friendID' value='{$friendID}'>
                                <input type='submit' name='addingFriends' value='Add as friend'>
                            </form>
                            </td>
                        </tr>
                        </tbody>";
                }
                echo "</table>";
            } else {
                echo "<p style=color:blue>No friend to add.</p>";
            }

            mysqli_stmt_close($statement);
            mysqli_close($conn);
            ?>
            
        </div>
    </main>
</body>
</html>