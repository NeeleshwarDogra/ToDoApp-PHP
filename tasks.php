<?php
session_start();

$servername = "localhost";
$USERNAME = "root";
$PASSWORD = "";
$dbname = "mydb";

$conn3 = new mysqli($servername, $USERNAME, $PASSWORD, $dbname);
$param_userid = $_SESSION['id'];
$sql = "SELECT * FROM tasks WHERE user_id = ?";
if($stmt = $conn3->prepare($sql)){
    $stmt->bind_param("s", $param_userid);

    if($stmt->execute()){
        $result = $stmt->get_result();
    }
}


?>
<!DOCTYPE html>
<html>

<head>
    <title>ToDoApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark  shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">ToDoApp</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample03"
                aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto">

                </ul>
                <div class="collapse navbar-collapse" id="navbarsExample03">
                    <ul class="navbar-nav mr-auto">
                        <?php
                    if($_SESSION['login'] == false){?>
                        <li class="nav-item ">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <?php
                    }
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php">About</a>
                        </li>
                        <?php
                    if($_SESSION['login'] == true){ ?>
                        <li class="nav-item">
                            <a class="nav-link" href="tasks.php">Tasks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="create.php">Add Task</a>
                        </li>
                        <?php
                    }
                    ?>
                    </ul>
                </div>
                <ul class="navbar-nav ms-auto">
                    <?php
                    if($_SESSION['login'] == false){?>

                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>

                    <?php
                        }else{?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['name'];?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#"><?php echo $_SESSION['email'];?></a></li>
                            <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>

                    <?php } ?>
                </ul>
            </div>
        </div>
        </div>
    </nav>
    <div class="m-4 container">
        <h1 class="text-center">Tasks</h1>
        <?php
        if($result->num_rows > 0){
            
            while($row = $result->fetch_assoc()){
                
                if($row['iscompleted'] == '1'){?>
                    <?php 
                    echo '<a href="show.php?id=' . $row['id'] . '" style="color: black; text-decoration: none">'?>
                        <div class="p-3 card m-3">
                            <h3 style="text-decoration:underline"><?php echo strtoupper($row['task']); ?></h3>
                            <h5><?php echo $row['body'] ?></h5>
                        </div>
                        </a>
            <?php
                }else{?>
                    <?php 
                    echo '<a href="show.php?id=' . $row['id']. '" style="color: black; text-decoration: none">'?>
                            <div class="p-3 card m-3" style="background-color: #02ab2c; ">
                                <h3 style="text-decoration:underline"><?php echo strtoupper($row['task']); ?></h3>
                                <h5><?php echo $row['body'] ?></h5>
                            </div>
                        </a>
            <?php
                }
            }
        }
            ?>
    </div>

</body>

</html>