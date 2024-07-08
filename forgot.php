<?php 
session_start();

if(isset($_POST['username']) && isset($_POST['password'])){
    $username = $_POST["username"];
    $g_password = $_POST["password"];
    require_once "db_conn.php";
    $sql = "SELECT username, password FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if(empty($username) || empty($g_password)){
        $errorMessage = "Kullanıcı adı veya şifre girmediniz!";
    }else{
        $verify = password_verify( $g_password, $user["password"]);
    if ($user){
        if($verify){
            session_start();
            $_SESSION["user"] = "yes";
            $_SESSION["username"] = $username;
            header("Location: success_login.php");
            die();
        }
        else{
            $errorMessage = "Kullanıcı adı veya şifre hatalı!";
        }
    }else {
        $errorMessage = "Kullanıcı adı veya şifre hatalı!";
    }
    }
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiPusula | Kullanıcı Adı Veya Şifremi Unuttum</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" type="text/css"  href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
</head>
<body data-spy="scroll" data-target=".navbar-fixed-top">
    
    <nav id="menu" class="nav navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand page-scroll" href="index.php">
                    <img src= "img/logo.png" alt=""></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                <li><a href="about.php" class="page-scroll">Hakkımızda</a></li>
                    <li><a href="contact.php" class="page-scroll">Bize Ulaşın</a></li>
                    <li><a href="login.php" class="page-scroll">Giriş</a></li>
                    <li><a href="register.php" class="page-scroll register-btn" >Kayıt Ol</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="login-bg">
        <div class="overlay">
            <div class="container">
                <div class="row">
                        <form action="login.php" method="post">
                            <div class="illustration"><i class="ionicons ion-ios-loop"></i></div>
                            <h1>Kullanıcı Adı Veya Şifre Yenileme</h1>
                            <h6>Kullanıcı adınızı veya şifrenizi yenilemek için <br>e-postanıza gelen link üzerindeki yönergeleri takip etmeniz gerekmektedir.</h6>
                            <div class="form-group"><input class="form-control" type="email" name="email" placeholder="E-posta"></div>
                            <div class="form-group"><input class="header-button" type="submit" value="Yenileme Linki Gönder"></div>
                        </form>
                </div>
            </div>
        </div>
    </div>

    <section id="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <p>Copyright © 2024 | PatiPusula - Tüm hakları saklıdır.</p>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript" src="js/jquery.1.11.1.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>
