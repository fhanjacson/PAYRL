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
$today = date("Y-m-d"); 
$tomorrow = date("Y-m-d", strtotime($today . '+ 1 days'));

echo(password_hash("user", PASSWORD_BCRYPT));


$dbconnection->close();
?>



</div>
<div class="col"></div>
</div>
<script type="text/javascript" src="vendor/jquery/jquery-3.3.1.slim.min"></script>	
<script type="text/javascript" src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>