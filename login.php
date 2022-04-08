<?php
session_start();
$_SESSION['login'] = false;
$servername = "localhost";
$USERNAME = "root";
$PASSWORD = "";
$dbname = "mydb";

$conn = new mysqli($servername, $USERNAME, $PASSWORD, $dbname);

$password = $password_err = "";
$email = $email_err = "";
$valid_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    //Validate email
    if (empty($_POST["email"])) {  
        $email_err = "Please enter an email";  
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    }

    $email = $_POST["email"];
    $password = $_POST["password"];
    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";

    if($stmt = $conn->prepare($sql)){
            
        $stmt->bind_param("s", $param_email);
        $param_email = trim($_POST["email"]);
            
        if($stmt->execute()){
            $stmt->store_result();
            $stmt->bind_result($ID, $NAME, $EMAIL, $PASSWORD);
            $stmt->fetch();
            if(password_verify($password, $PASSWORD)){
                $_SESSION['name'] = $NAME;
                $_SESSION['id'] = $ID;
                $_SESSION['email'] = $EMAIL;
                $_SESSION['login'] = true;
                header("location: dashboard.php");
            }
            else{
                $valid_err = "The email and password do not match";
            }
        }
    }
    
    // Close connection
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
    <div class="container text-center" style="width:25rem; margin-top: 2rem; background-color: #e9ecef;
    border-radius: .3rem; padding: 2rem 1rem;">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php
            if(!empty($valid_err)){?>
            <div class="alert alert-danger" role="alert">
                <?php echo $valid_err; ?>
            </div>
            <?php
            }
            ?>
            <div class="form-group ">
                <label>Email</label>
                <input type="email" name="email"
                    class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value=""
                    placeholder="Enter email">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password"
                    class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value=""
                    placeholder="Enter Password">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" style="margin-top: 1rem" value="Login">
                <input type="reset" class="btn btn-secondary ml-2" style="margin-top: 1rem" value="Reset">
            </div>
        </form>
    </div>
</body>

</html>