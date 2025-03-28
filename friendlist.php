<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend List Page</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <header>
        <div class="navbar">
            <span class="title">My Friend System</span>
            <nav>
                <ul>
                    <li class="current"><a href="friendlist.php">Home</a></li>
                    <li><a href="friendadd.php">Add Friends</a></li>
                    <li><a href="logout.php">Log-Out</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <?php 
        session_start();
        if(!isset($_SESSION['email']) || !isset($_SESSION['loggedIn'])){
            header("Location: login.php");
            exit();
        }

        require_once("settings.php");
        $sql = "SELECT friend_id, profile_name, num_of_friends FROM $table1 WHERE friend_email = ?";
        $statement = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($statement, "s", $_SESSION["email"]);
        mysqli_stmt_execute($statement);
        $records = mysqli_stmt_get_result($statement);
        $data = mysqli_fetch_assoc($records);

        $userID = $data["friend_id"];
        $proName = $data["profile_name"];
        $friendsNum = $data["num_of_friends"];
        $sql = "SELECT f.friend_id, f.profile_name
            FROM $table1 f JOIN $table2 mf 
            ON f.friend_id = mf.friend_id1 OR f.friend_id = mf.friend_id2
            WHERE (mf.friend_id1 = ? OR mf.friend_id2 = ?) 
            AND f.friend_id != ?";
        $statement = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($statement, "iii", $userID, $userID, $userID);
        mysqli_stmt_execute($statement);
        $records = mysqli_stmt_get_result($statement);

        function removingFriends($friendID){
            global $conn, $friendsNum, $userID, $table1, $table2;

            $sql = "DELETE FROM $table2 WHERE 
                    (friend_id1 = ? AND friend_id2 = ?) 
                    OR (friend_id1 = ? AND friend_id2 = ?)";
            $statement = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($statement, "iiii", $userID, $friendID, $friendID, $userID);
            mysqli_stmt_execute($statement);
            $friendsNum--;
            $sql = "UPDATE $table1 SET num_of_friends = ? WHERE friend_id = ?";
            $statement = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($statement, "ii", $friendsNum, $userID);
            mysqli_stmt_execute($statement);

            $sql = "SELECT num_of_friends FROM $table1 WHERE friend_id = ?";
            $statement = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($statement, "i", $friendID);
            mysqli_stmt_execute($statement);
            $records = mysqli_stmt_get_result($statement);
            $data = mysqli_fetch_assoc($records);
            $updatingTheirFriendsNum = $data["num_of_friends"];
            $updatingTheirFriendsNum--;
            $sql = "UPDATE $table1 SET num_of_friends = ? WHERE friend_id = ?";
            $statement = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($statement, "ii", $updatingTheirFriendsNum, $friendID);
            mysqli_stmt_execute($statement);
        }

        if(isset($_POST["unfriend"])){
            removingFriends($_POST["friendID"]);
            header("Location: friendlist.php");
            exit();
        }
    ?>

    <main>
        <div class="header" style="text-align:center">
            <h2><?php echo $proName; ?>'s Friend List Page</h2>
            <h3>Total number of friends is <?php echo $friendsNum; ?></h3>
        </div>
        <div class="declaration">
                <?php 
                    if(mysqli_num_rows($records) > 0){
                        echo "<table>    
                                <thead>
                                    <tr>
                                        <th>Profile Name</th>
                                        <th>Action</th>
                                    </tr>
                                </head>";    
                        while($data = mysqli_fetch_assoc($records)){
                            $friendID = $data["friend_id"];
                            $friendName = $data["profile_name"];
                            echo "<tbody>
                                    <tr>
                                        <td>{$friendName}</td>
                                        <td>
                                            <form method='post' action='friendlist.php'>
                                                <input type='hidden' name='friendID' value='{$friendID}'>
                                                <input type='submit' name='unfriend' value='Unfriend'>
                                            </form>        
                                        </td>
                                    </tr>
                                  </tbody>";
                        }
                        echo "</table>";
                    }else{
                        echo "<p style=color:blue>0 friend found.</p>";
                    }
                    mysqli_stmt_close($statement);
                    mysqli_close($conn);
                ?>    
        </div>
    </main>
</body>