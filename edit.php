<?php
session_start();
// $id = $_GET['link'];
$servername = "localhost";
$USERNAME = "root";
$PASSWORD = "";
$dbname = "mydb";

$conn3 = new mysqli($servername, $USERNAME, $PASSWORD, $dbname);

$title = $title_err = "";
$body = $body_err = "";
$comp = "";

if(isset($_POST["id"]) && !empty($_POST["id"])){

    $id = $_POST["id"];

    if (empty($_POST["title"])) {  
        $title_err = "Please enter task title";  
    }
    if (empty($_POST["body"])) {  
        $body_err = "Please enter task description";  
    }
    if (empty($_POST["comp"])) {  
        $comp = "1";  
    }else{
        $comp = $_POST["comp"];
    }

    $title = $_POST['title'];
    $body = $_POST['body'];

    if(empty($title_err) && empty($body_err)){
        $sql = "UPDATE tasks SET task=?, body=?, iscompleted=? WHERE id=?";

        if($stmt = $conn3->prepare($sql)){
            $stmt->bind_param("sssi", $param_title, $param_body, $param_comp, $param_Id);

            $param_title = $title;
            $param_body = $body;
            $param_comp = $comp;
            $param_Id = $id;

            if($stmt->execute()){
                header("location: tasks.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        $stmt->close();
    }
    $conn3->close();
} else{
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id = trim($_GET["id"]);
        $sql = "SELECT * FROM tasks WHERE id = ?";

        if($stmt = $conn3->prepare($sql)){
            $stmt->bind_param("i", $param_id);
            $param_id = $id;

            if($stmt->execute()){
                $result = $stmt->get_result();

                if($result->num_rows == 1){
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $title = $row["task"];
                    $body = $row["body"];
                    $comp = $row["iscompleted"];
                    if ($comp == '1'){
                        $comp = "";
                    }
                }
            }else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        $stmt->close();
        $conn3->close();
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
        <h1>Edit Task</h1>
        <form action="<?php echo htmlspecialchars(basename($_SERVER["REQUEST_URI"])); ?>" method="post">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="<?php echo $title; ?>" placeholder="Enter Task title" class='form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>'>
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group">
                <label>Task Description</label>
                <textarea class="form-control <?php echo (!empty($body_err)) ? 'is-invalid' : ''; ?>" name="body" placeholder="Body Text"><?php echo $body; ?></textarea>
                <span class="invalid-feedback"><?php echo $body_err; ?></span>
            </div>
            <div class="form-group">
                <label>Task Completed?</label>
                <input type="text" name="comp" value="<?php echo $comp; ?>" class="form-control" placeholder="Enter done if the task is complete">
            </div>
            <input type="hidden" name="id" value="<?php echo $id?>">
            <div class="form-group">
                <input type="submit" class="btn btn-primary" style="margin-top: 1rem" value="Submit">
            </div>
        </form>
    </div>

</body>

</html>