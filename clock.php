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
include('payrl_db.php');

function createBox($textTitleTag, $textTitle, $textContent) {
    switch ($textTitleTag) {
        case "Error":
        $border = "danger";
        break;
        case "Success":
        $border = "success";
        break;
        case "Error":
        $border = "danger";
        break;
        default:
        $border = "primary";
    }            
    $BoxTemplate = '<div class="card border-' . $border . ' mb-3 shadow-lg">';
    $BoxTemplate .= '<div class="card-header font-weight-bold">';
    $BoxTemplate .= '[' . $textTitleTag . '] ' . $textTitle;
    $BoxTemplate .= '</div><div class="card-body"><blockquote class="blockquote mb-0"><p>';
    $BoxTemplate .= $textContent;
    $BoxTemplate .= '</p><br/><a href="/" class="btn btn-primary">Back to Home Page</a><footer class="blockquote-footer">Be Productive!</footer></blockquote></div></div>';
    return $BoxTemplate;
}

if ((!empty($_POST["employeeID"])) && (!empty($_POST["employeePassword"])) && (!empty($_POST["ClockRadio"]))) {
    
    $employeeID = $_POST["employeeID"];
    $employeePassword = $_POST["employeePassword"];
    $employeePasswordHash = $employeePassword;
    $clocktype = $_POST["ClockRadio"];  
    $statement = mysqli_stmt_init($dbconnection);
    
    if (mysqli_stmt_prepare($statement, "SELECT * FROM `employee` WHERE Employee_status = 'Active' and Employee_ID = ?")) {
        mysqli_stmt_bind_param($statement, "s", $employeeID);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        mysqli_stmt_close($statement);
        if (mysqli_num_rows($result) > 1) {
            echo(createBox("Error", "More than one account have the same Employee ID", "If you're Database Admin, enable primary key for Employee ID"));
        }
        elseif (mysqli_num_rows($result) == 0) {
            echo(createBox("Error", "Employee ID: " . $employeeID . " not found!", "Ask HR to add your account into the system"));
        }
        elseif (mysqli_num_rows($result) == 1) {
            $employee_row = mysqli_fetch_array($result, MYSQLI_BOTH);

            if (password_verify($employeePassword, $employee_row["Employee_PasswordHash"])) {
                $statement = mysqli_stmt_init($dbconnection);

                if ($clocktype == "clockin" || $clocktype == "clockout"){

                    if (mysqli_stmt_prepare($statement, "INSERT INTO clock (Employee_Index, Clock_Type) VALUES (?,?)")) {
                        mysqli_stmt_bind_param($statement, "is", $employee_row["Employee_Index"], $clocktype);
                        mysqli_stmt_execute($statement);
                        $lastid =  mysqli_insert_id($dbconnection);

                        if (mysqli_affected_rows($dbconnection) == 1) {
                            $statement = mysqli_stmt_init($dbconnection);

                            if (mysqli_stmt_prepare($statement, "SELECT * FROM `clock` WHERE Clock_ID = ?")) {
                                mysqli_stmt_bind_param($statement, "s", $lastid);                
                                mysqli_stmt_execute($statement);                
                                $result = mysqli_stmt_get_result($statement);
                                mysqli_stmt_close($statement);

                                if (mysqli_num_rows($result) == 1) {
                                    $clock_row = mysqli_fetch_array($result, MYSQLI_BOTH);
                                    $clockMessage = 'Welcome, %s %s.<br/>
                                    Successfully clocked at: %s<br/>
                                    Clock ID: %s';
                                    $formattedClockMessage = sprintf($clockMessage, $employee_row["Employee_FirstName"], $employee_row["Employee_LastName"], $clock_row["Clock_DateTime"], $lastid);
                                    echo(createBox("Success","Employee ID: " . $employeeID . " , Clocked!", $formattedClockMessage));
                                }
                            }
                            
                        } else {
                            echo("Something Wrong! Insert Process Fail or Value Inserted Twice!");
                        }                        
                    }
                } else {
                    echo("Clock Value given was not expected!");
                }
            } else {
                echo(createBox("Error", "Employee ID: " . $employeeID . " , Wrong Password!", "Enter your  correct password, or ask HR to reset your password"));
            }
        }
    } else {
        echo ("abc");
    }
    mysqli_close($dbconnection);
} else {
    echo ("The Form contains empty values!"); 
    mysqli_close($dbconnection);
}
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

<!-- 
// See the password_hash() example to see where this came from.
// $hash = password_hash("admin", PASSWORD_BCRYPT);
// echo($hash);
// echo('<br/>');
// if (password_verify('admin', $hash)) {
    //     echo 'Password is valid!';
    // } else {
        //     echo 'Invalid password.';
        // }
        // if ($dbconnection->connect_error) {
            //     die("Connection failed: " . $dbconnection->connect_error);
            // } else {
                //     $statement = $dbconnection->prepare("SELECT * FROM `employee` WHERE Employee_ID = ?");
                //     $statement->bind_param("s", $employeeID);
                //     $statement->execute();
                //     $result = $statement->get_result();    
                //     if ($result->num_rows == 1) {
                    //         $employeerow = $result->fetch_array(MYSQLI_ASSOC);
                    //         //echo ("Employee_Index: " . $row["Employee_Index"]. " - Employee_ID: " . $row["Employee_ID"]. " - Employee_Password: " . $row["Employee_PasswordHash"]. " - Employee Full Name: ". $row["Employee_FirstName"] . " " . $row["Employee_LastName"]);
                    //         //echo ("<br>");
                    //         if (($employeerow["Employee_ID"] == $employeeID) && ($employeerow["Employee_PasswordHash"] == $employeePasswordHash)) {
                        //             try {
                            
                            //                 $statement = $dbconnection->prepare("INSERT INTO clock (Employee_Index) VALUES (?)");
                            //                 $statement->bind_param("i", $employee_Index);
                            //                 $employee_Index = $employeerow["Employee_Index"];
                            //                 $statement->execute();
                            //                 $lastid = $statement->insert_id;
                            //                 $statement->close();
                            //                 $statement = $dbconnection->prepare("SELECT * from clock where Clock_ID = ?");
                            //                 $statement->bind_param('s',$lastid);
                            //                 $statement->execute();
                            //                 $result = $statement->get_result();
                            //                 if ($result->num_rows == 1) {
                                //                     $clockrow = $result->fetch_array(MYSQLI_ASSOC);
                                //                     echo ('<div class="card border-success mb-3 shadow-lg">');
                                //                     echo ('<div class="card-header font-weight-bold">');
                                //                     echo ("[" . $lastid . "]" . " Data Successfully Inserted");
                                //                     echo ('</div><div class="card-body"><blockquote class="blockquote mb-0"><p>');
                                //                     echo ('Welcome, ' . $employeerow["Employee_FirstName"] . ' ' . $employeerow["Employee_LastName"]);
                                //                     echo ('<br/>');
                                //                     echo ('Clocked at: ' . $clockrow["Clock_DateTime"]);
                                //                     echo ('<br/>');
                                //                     echo ('<a href="/" class="btn btn-primary">Back to Home Page</a>');
                                //                     echo ('</p><footer class="blockquote-footer">Be Productive!</footer></blockquote></div></div>');
                                //                 }
                                //             }
                                //             catch (PDOException $e) {
                                    //                 echo ("Error: " . $e->getMessage());
                                    //             }
                                    //         } else {
                                        //             echo ('<div class="card border-danger mb-3 shadow-lg">');
                                        //             echo ('<div class="card-header font-weight-bold">');
                                        //             echo ("[Error]");
                                        //             echo ('</div><div class="card-body"><blockquote class="blockquote mb-0"><p>');
                                        //             echo ("Incorrect Username or Password!");
                                        //             echo ('<br/>');
                                        //             echo ('<a href="/" class="btn btn-primary">Back to Home Page</a>');
                                        //             echo ('</p><footer class="blockquote-footer">Be Productive!</footer></blockquote></div></div>');
                                        
                                        //         }            
                                        //     } elseif ($result->num_rows > 1) {
                                            //         echo ('<div class="card border-danger mb-3 shadow-lg">');
                                            //         echo ('<div class="card-header font-weight-bold">');
                                            //         echo ("[Error]");
                                            //         echo ('</div><div class="card-body"><blockquote class="blockquote mb-0"><p>');
                                            //         echo ("More than one Employee_ID found! Check database primary key status");
                                            //         echo ('<br/>');
                                            //         echo ('<a href="/" class="btn btn-primary">Back to Home Page</a>');
                                            //         echo ('</p><footer class="blockquote-footer">Be Productive!</footer></blockquote></div></div>');
                                            
                                            //     } elseif ($result->num_rows == 0) {
                                                //         echo ('<div class="card border-danger mb-3 shadow-lg">');
                                                //         echo ('<div class="card-header font-weight-bold">');
                                                //         echo ("[Error]");
                                                //         echo ('</div><div class="card-body"><blockquote class="blockquote mb-0"><p>');
                                                //         echo ("Employee_ID not Found");
                                                //         echo ('<br/>');
                                                //         echo ('<a href="/" class="btn btn-primary">Back to Home Page</a>');
                                                //         echo ('</p><footer class="blockquote-footer">Be Productive!</footer></blockquote></div></div>');
                                                
                                                //     } else {
                                                    //         echo ('<div class="card border-danger mb-3 shadow-lg">');
                                                    //         echo ('<div class="card-header font-weight-bold">');
                                                    //         echo ("[Error]");
                                                    //         echo ('</div><div class="card-body"><blockquote class="blockquote mb-0"><p>');
                                                    //         echo ("Something went wrong");
                                                    //         echo ('<br/>');
                                                    //         echo ('<a href="/" class="btn btn-primary">Back to Home Page</a>');
                                                    //         echo ('</p><footer class="blockquote-footer">Be Productive!</footer></blockquote></div></div>');
                                                    //     }    
                                                    //     $statement->close();       
                                                    // }
                                                    // $dbconnection->close(); -->