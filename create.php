<?php
session_start();

$servername = "localhost";
$USERNAME = "root";
$PASSWORD = "";
$dbname = "mydb";

$conn2 = new mysqli($servername, $USERNAME, $PASSWORD, $dbname);

$title = $title_err = "";
$body = $body_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (empty($_POST["title"])) {  
        $title_err = "Please enter task title";  
    }
    if (empty($_POST["body"])) {  
        $body_err = "Please enter task description";  
    }

    $title = $_POST['title'];
    $body = $_POST['body'];
    $id = $_SESSION['id'];
    if(empty($title_err) && empty($body_err)){
        $sql = "INSERT INTO tasks (task, body, iscompleted, user_id) VALUES (?, ?, ?, ?)";

        if($stmt = $conn2->prepare($sql)){
            $stmt->bind_param("ssss", $param_title, $param_body, $param_comp, $param_userid);

            $param_title = $title;
            $param_body = $body;
            $param_comp = "1";
            $param_userid = $id;

            if($stmt->execute()){
                header("location: dashboard.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
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
        <h1>Add Task</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="" placeholder="Enter Task title" class='form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>'>
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group">
                <label>Task Description</label>
                <textarea class="form-control <?php echo (!empty($body_err)) ? 'is-invalid' : ''; ?>" name="body" placeholder="Body Text"></textarea>
                <span class="invalid-feedback"><?php echo $body_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" style="margin-top: 1rem" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" style="margin-top: 1rem" value="Reset">
            </div>
        </form>
    </div>

</body>

</html>