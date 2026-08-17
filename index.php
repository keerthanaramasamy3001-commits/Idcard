<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Enterprise Universal ID Card Management System</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Bootstrap Icons -->
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



/* Main Card */

.glass-card{

position:relative;
z-index:10;

width:650px;
padding:45px;

text-align:center;

border-radius:35px;

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

.brand-mark{

display:flex;
justify-content:center;
align-items:center;

margin-bottom:25px;

}


/* Logo Icon */

.brand-mark i{

font-size:85px;

color:white;

text-shadow:
0 0 20px #a855f7,
0 0 40px #a855f7,
0 0 60px #9333ea;

}



/* Heading */

h1{

color:white;

font-size:42px;

line-height:1.4;

font-weight:800;

margin-bottom:35px;

}



/* Button */

.btn{

display:inline-block;

padding:16px 42px;

text-decoration:none;

font-size:20px;

font-weight:700;

border-radius:18px;

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

box-shadow:
0 10px 25px
rgba(168,85,247,.35);

}


.btn-primary:hover{

transform:
translateY(-5px);

box-shadow:
0 0 35px
rgba(168,85,247,.60);

}


.btn-primary i{

margin-right:8px;

}



/* Responsive */

@media(max-width:768px){


.glass-card{

width:90%;

padding:30px;

}


h1{

font-size:28px;

}


.brand-mark i{

font-size:60px;

}


.btn{

font-size:18px;

padding:14px 30px;

}

}


</style>

</head>

<body>


<div class="glass-card">

<!-- Logo -->

<div class="brand-mark">

<i class="bi bi-person-vcard-fill"></i>

</div>


<!-- Heading -->

<h1>

Enterprise Universal ID<br>
Card Management System

</h1>


<!-- Login Button -->

<a href="login.php" class="btn btn-primary">

<i class="bi bi-shield-lock-fill"></i>

Admin Login

</a>


</div>


</body>
</html>