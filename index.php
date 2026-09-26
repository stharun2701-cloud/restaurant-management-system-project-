<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cuisine Cloud</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Orbitron, sans-serif;
}

body{
height:100vh;
display:flex;
justify-content:center;
align-items:center;
overflow:hidden;
background:black;
color:white;
}

/* Animated gaming gradient */
body::before{
content:"";
position:absolute;
width:200%;
height:200%;
background:linear-gradient(45deg, #869159, #fcfcf7, #889377, #898f4d);
animation:bgMove 8s linear infinite;
opacity:2;
}

@keyframes bgMove{
200%{transform:translate(-25%,-25%)}
50%{transform:translate(25%,25%)}
100%{transform:translate(-25%,-25%)}
}

/* Main Box */
.loader-box{
position:relative;
text-align:center;
z-index:2;
}

/* 3D rotating logo */
.logo3d{
width:200px;
height:200px;
margin:auto;
perspective:1000px;
}

.logo3d img{
width:100%;
animation:rotate3d 6s linear infinite;
filter:drop-shadow(0 0 20px #040404);
}

.title span{
      color: var(--red);
}

@keyframes rotate3d{
0%{transform:rotateY(0deg)}
100%{transform:rotateY(360deg)}
}

/* Game style title */
.title{
margin-top:20px;
font-size:28px;
letter-spacing:4px;
text-shadow:
0 0 10px #000000,
0 0 20px #000000,
0 0 40px #000000;
animation:pulse 2s infinite;
}

@keyframes pulse{
0%{opacity:1}
50%{opacity:0.6}
100%{opacity:1}
}

/* Loading Bar */

.progress{
width:260px;
height:8px;
background:#111;
border-radius:10px;
margin:30px auto;
overflow:hidden;
}

.bar{
height:100%;
width:0%;
background:linear-gradient(90deg, #f6ff00, #fc2c02);
animation:loading 5s linear forwards;
}

@keyframes loading{
0%{width:0}
100%{width:100%}
}

/* Particles */

.particle{
position:absolute;
width:20zpx;
height:20px;
background: #d28383;
border-radius:100%;
animation:float 6s linear infinite;
}

@keyframes float{
0%{
transform:translateY(100vh);
opacity:0;
}
50%{
opacity:1;
}
100%{
transform:translateY(-10vh);
opacity:0;
}
}

</style>

<script>

/* redirect after animation */

setTimeout(function(){
window.location.href="home.php";
},6000);

/* create particles */

window.onload=function(){

for(let i=0;i<40;i++){

let p=document.createElement("div");
p.className="particle";

p.style.left=Math.random()*100+"%";
p.style.animationDuration=(Math.random()*5+3)+"s";

document.body.appendChild(p);

}

}

</script>

</head>

<body>

<div class="loader-box">

<div class="logo3d">
<img src="project images/logo1.png">
</div>

<div class="title">
Cuisine <span>Cloud</span>
</div>

<div class="progress">
<div class="bar"></div>
</div>

</div>

</body>
</html>