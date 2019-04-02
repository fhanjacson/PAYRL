<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Clock in/out</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
<link rel="manifest" href="images/favicon/site.webmanifest">
<style type="text/css">
body { 
    background-image: url("/images/background.jpg");
    background-position: center center;
    background-repeat:  no-repeat;
    background-attachment: fixed;
    background-size:  cover;
    background-color: #999;
}
</style>
</head>
<body>
<div class="fj-Navigation-Bar p-3">
<nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
<a class="navbar-brand font-weight-bold h4" href="/">PAYRL</a>
<div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
<div class="navbar-nav ">
<a class="nav-item nav-link font-weight-bold h4 active" href="/">Home <span class="sr-only">(current)</span></a>
<a class="nav-item nav-link font-weight-bold h4" href="admin.php">Admin</a>
<a class="nav-item nav-link font-weight-bold h4" href="summary.php">Summary</a>
<a class="nav-item nav-link font-weight-bold h4" href="references.html">Credits</a>
</div>
</div>
</nav>
</div>

<div class="fj-Content">
<div class="container">
<div class="row">
<div class="col"></div>
<div class="col-8">

<?php
$servername = "localhost";
$dbname = "payrl_db";
$dbusername = "root";
$dbpassword = "";

$employeeID = $_POST["employeeID"];
$employeePassword = $_POST["employeePassword"];
$employeePasswordHash = $employeePassword;
// echo ("Employee ID from form: " . $employeeID);
// echo ("<br/>");
// echo ("Employee Password from form: " . $employeePassword);
// echo ("<br/>");

$dbconnection = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($dbconnection->connect_error) {
    die("Connection failed: " . $dbconnection->connect_error);
} else {
    $statement = $dbconnection->prepare("SELECT * FROM `employee` WHERE Employee_ID = ?");
    $statement->bind_param("s", $employeeID);
    $statement->execute();
    $result = $statement->get_result();    
    if ($result->num_rows == 1) {
        $employeerow = $result->fetch_array(MYSQLI_ASSOC);
        //echo ("Employee_Index: " . $row["Employee_Index"]. " - Employee_ID: " . $row["Employee_ID"]. " - Employee_Password: " . $row["Employee_PasswordHash"]. " - Employee Full Name: ". $row["Employee_FirstName"] . " " . $row["Employee_LastName"]);
        //echo ("<br>");
        if (($employeerow["Employee_ID"] == $employeeID) && ($employeerow["Employee_PasswordHash"] == $employeePasswordHash)) {
            try {
                
                $statement = $dbconnection->prepare("INSERT INTO clock (Employee_Index) VALUES (?)");
                $statement->bind_param("i", $employee_Index);
                $employee_Index = $employeerow["Employee_Index"];
                $statement->execute();
                $lastid = $statement->insert_id;
                $statement->close();
                $statement = $dbconnection->prepare("SELECT * from clock where Clock_ID = ?");
                $statement->bind_param('s',$lastid);
                $statement->execute();
                $result = $statement->get_result();
                if ($result->num_rows == 1) {
                    $clockrow = $result->fetch_array(MYSQLI_ASSOC);
                    echo ('<div class="card border-success mb-3 shadow-lg">');
                    echo ('<div class="card-header font-weight-bold">');
                    echo ("[" . $lastid . "]" . " Data Successfully Inserted");
                    echo ('</div><div class="card-body"><blockquote class="blockquote mb-0"><p>');
                    echo ('Welcome, ' . $employeerow["Employee_FirstName"] . ' ' . $employeerow["Employee_LastName"]);
                    echo ('<br/>');
                    echo ('Clocked at: ' . $clockrow["Clock_DateTime"]);
                    echo ('<br/>');
                    echo ('<a href="/" class="btn btn-primary">Back to Home Page</a>');
                    echo ('</p><footer class="blockquote-footer">Be Productive!</footer></blockquote></div></div>');
                }
            }
            catch (PDOException $e) {
                echo ("Error: " . $e->getMessage());
            }
        } else {
            echo ('<div class="card border-danger mb-3 shadow-lg">');
            echo ('<div class="card-header font-weight-bold">');
            echo ("[Error]");
            echo ('</div><div class="card-body"><blockquote class="blockquote mb-0"><p>');
            echo ("Incorrect Username or Password!");
            echo ('<br/>');
            echo ('<a href="/" class="btn btn-primary">Back to Home Page</a>');
            echo ('</p><footer class="blockquote-footer">Be Productive!</footer></blockquote></div></div>');

        }            
    } elseif ($result->num_rows > 1) {
        echo ('<div class="card border-danger mb-3 shadow-lg">');
        echo ('<div class="card-header font-weight-bold">');
        echo ("[Error]");
        echo ('</div><div class="card-body"><blockquote class="blockquote mb-0"><p>');
        echo ("More than one Employee_ID found! Check database primary key status");
        echo ('<br/>');
        echo ('<a href="/" class="btn btn-primary">Back to Home Page</a>');
        echo ('</p><footer class="blockquote-footer">Be Productive!</footer></blockquote></div></div>');

    } elseif ($result->num_rows == 0) {
        echo ('<div class="card border-danger mb-3 shadow-lg">');
        echo ('<div class="card-header font-weight-bold">');
        echo ("[Error]");
        echo ('</div><div class="card-body"><blockquote class="blockquote mb-0"><p>');
        echo ("Employee_ID not Found");
        echo ('<br/>');
        echo ('<a href="/" class="btn btn-primary">Back to Home Page</a>');
        echo ('</p><footer class="blockquote-footer">Be Productive!</footer></blockquote></div></div>');
        
    } else {
        echo ('<div class="card border-danger mb-3 shadow-lg">');
        echo ('<div class="card-header font-weight-bold">');
        echo ("[Error]");
        echo ('</div><div class="card-body"><blockquote class="blockquote mb-0"><p>');
        echo ("Something went wrong");
        echo ('<br/>');
        echo ('<a href="/" class="btn btn-primary">Back to Home Page</a>');
        echo ('</p><footer class="blockquote-footer">Be Productive!</footer></blockquote></div></div>');
    }    
    $statement->close();       
}
$dbconnection->close();
?>



</div>
<div class="col"></div>
</div>
<script type="text/javascript" src="vendor/jquery/jquery-3.3.1.slim.min"></script>	
<script type="text/javascript" src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    setTimeout(() => {
        window.location = '/';
    }, 5000);
    
</script>
</body>
</html>