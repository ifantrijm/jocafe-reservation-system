<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logout JoCafe</title>

<style>
    body{
        margin:0;
        height:100vh;
        display:flex;
        justify-content:center;
        align-items:center;
        font-family:'Segoe UI', sans-serif;
        background: radial-gradient(circle at top, #0f172a, #020617);
        color:#fff;
    }

    .card{
        width:360px;
        background:#0b1220;
        padding:40px;
        border-radius:14px;
        text-align:center;
        box-shadow:0 0 40px rgba(0,0,0,0.6);
    }

    h2{
        color:#f59e0b;
        margin-bottom:10px;
    }

    p{
        color:#94a3b8;
        font-size:14px;
    }

    .loader{
        margin-top:20px;
        border:4px solid #1e293b;
        border-top:4px solid #f59e0b;
        border-radius:50%;
        width:30px;
        height:30px;
        animation:spin 1s linear infinite;
        margin-left:auto;
        margin-right:auto;
    }

    @keyframes spin{
        0%{transform:rotate(0deg);}
        100%{transform:rotate(360deg);}
    }
</style>
</head>

<body>
<div class="card">
    <h2>☕ JoCafe</h2>
    <p>Anda sedang logout dari sistem...</p>
    <div class="loader"></div>
</div>

<?php
// Hapus session
$_SESSION = [];
session_destroy();

// Redirect setelah 2 detik
header("Refresh:2; url=login.php");
exit;
?>
</body>
</html>