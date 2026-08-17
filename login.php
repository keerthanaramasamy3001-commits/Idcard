<?php
session_start();

$error='';

if($_SERVER['REQUEST_METHOD']=='POST')
{

$adminid=trim($_POST['adminid']);
$password=trim($_POST['password']);

if($adminid=="admin" && $password=="admin123")
{

$_SESSION['logged_in']=true;

header("Location:dashboard.php");
exit;

}
else{

$error="Invalid Admin ID or Password.";

}

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Login</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{

min-height:100vh;
display:flex;
justify-content:center;
align-items:center;

font-family:'Poppins',sans-serif;

background:#05010f;
overflow:hidden;

}


/* Background Glow */

body::before{

content:'';
position:absolute;

width:500px;
height:500px;

background:#7c3aed;

filter:blur(250px);

opacity:.45;

top:-100px;
right:-100px;

}


body::after{

content:'';
position:absolute;

width:400px;
height:400px;

background:#9333ea;

filter:blur(220px);

opacity:.30;

bottom:-100px;
left:-100px;

}



/* Login Card */

.card{

position:relative;
z-index:10;

width:450px;

}


.glass-card{

padding:40px;

border-radius:30px;

background:
linear-gradient(
135deg,
rgba(15,15,25,.95),
rgba(90,40,170,.85)
);

border:2px solid
rgba(255,255,255,.20);

backdrop-filter:blur(20px);

box-shadow:
0 0 40px rgba(168,85,247,.40),
0 20px 60px rgba(0,0,0,.60);

}


/* Logo */

.logo{

text-align:center;
margin-bottom:15px;

}


.logo i{

font-size:65px;
color:white;

text-shadow:
0 0 20px #a855f7,
0 0 40px #a855f7;

}



/* Heading */

h2{

text-align:center;

color:white;

font-size:34px;

font-weight:800;

margin-bottom:30px;

}


/* Label */

.form-field{

margin-bottom:20px;

}


.form-field label{

display:block;

margin-bottom:8px;

color:white;

font-weight:600;

}


/* Input */

.form-field input{

width:100%;

padding:15px;

border:none;

outline:none;

border-radius:15px;

font-size:16px;

background:
rgba(255,255,255,.90);

}


.form-field input:focus{

box-shadow:
0 0 20px
rgba(168,85,247,.70);

}


/* Button */

.btn{

width:100%;

padding:15px;

border:none;

cursor:pointer;

border-radius:15px;

font-size:18px;

font-weight:700;

transition:.4s;

}


/* Login Button */

.btn-primary{

background:
linear-gradient(
135deg,
#6d28d9,
#06b6d4
);

color:white;

}


.btn-primary:hover{

transform:
translateY(-3px);

box-shadow:
0 0 30px
rgba(168,85,247,.70);

}


/* Error Message */

.error{

padding:12px;

margin-bottom:20px;

border-radius:15px;

text-align:center;

background:#ffe4e4;

color:red;

font-weight:600;

}


/* Responsive */

@media(max-width:768px){

.card{

width:90%;

}

h2{

font-size:28px;

}

}

</style>

</head>

<body>


<div class="card">

<div class="glass-card login-card">


<div class="logo">

<i class="bi bi-shield-lock-fill"></i>

</div>


<h2>Admin Login</h2>


<?php if($error!=''){ ?>

<div class="error">

<?php echo $error; ?>

</div>

<?php } ?>


<form method="POST">


<div class="form-field">

<label>Admin ID</label>

<input type="text"
name="adminid"
placeholder="Enter Admin ID"
required>

</div>


<div class="form-field">

<label>Admin Password</label>

<input type="password"
name="password"
placeholder="Enter Password"
required>

</div>


<button class="btn btn-primary">

<i class="bi bi-box-arrow-in-right"></i>

&nbsp; Login

</button>


</form>

</div>

</div>


</body>
</html>