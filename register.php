<?php
session_start();

if(isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["tel_num"]) && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $tel_num = $_POST["tel_num"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password2 = $_POST["password-repeat"];
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if(empty($firstname) || empty($lastname) || empty($tel_num) || empty($username) || empty($email) || empty($password) || empty($password2) || empty($password2)){
        $error_message = "Tüm alanları doldurmalısınız.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error_message = "Geçersiz bir email adresi girdiniz.";
    } elseif (strlen($password) < 8){
        $error_message = "Şifreniz en az 8 karakter olmalıdır.";
    } elseif ($password != $password2){
        $error_message = "Şifreler uyuşmuyor.";
    }

    require_once "db_conn.php";
    $sql_check_email = "SELECT * FROM users WHERE email=?";
    $stmt_check_email = mysqli_stmt_init($connect);
    if(mysqli_stmt_prepare($stmt_check_email, $sql_check_email)){
        mysqli_stmt_bind_param($stmt_check_email, "s", $email);
        mysqli_stmt_execute($stmt_check_email);
        mysqli_stmt_store_result($stmt_check_email);
        if(mysqli_stmt_num_rows($stmt_check_email) > 0){
        $error_message = "E-posta kullanımda.";
        }
    } 

    $sql_check_username = "SELECT * FROM users WHERE username=?";
    $stmt_check_username = mysqli_stmt_init($connect);
    if(mysqli_stmt_prepare($stmt_check_username, $sql_check_username)){
        mysqli_stmt_bind_param($stmt_check_username, "s", $username);
        mysqli_stmt_execute($stmt_check_username);
        mysqli_stmt_store_result($stmt_check_username);
        if(mysqli_stmt_num_rows($stmt_check_username) > 0){
            $error_message = "Bu kullanıcı adı daha önceden alınmış.";
        }
    }

    $sql_check_tel_num = "SELECT * FROM users WHERE tel_num=?";
    $stmt_check_tel_num = mysqli_stmt_init($connect);
    if(mysqli_stmt_prepare($stmt_check_tel_num, $sql_check_tel_num)){
        mysqli_stmt_bind_param($stmt_check_tel_num, "s", $tel_num);
        mysqli_stmt_execute($stmt_check_tel_num);
        mysqli_stmt_store_result($stmt_check_tel_num);
        if(mysqli_stmt_num_rows($stmt_check_tel_num) > 0){
            $error_message = "Bu telefon numarası sisteme kayıtlı.";
        }
    }

    if(!isset($error_message)) {
        $sql = "INSERT INTO users (username, email, password, firstname, lastname, tel_num) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($connect);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
        if ($prepareStmt){
            mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $passwordHash, $firstname, $lastname, $tel_num);
            mysqli_stmt_execute($stmt);
            sleep(1);
            header("Location: success_register.php");
        }else {
            die("Hata!");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiPusula | Kayıt Ol</title>
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

    <div class="register-bg">
        <div class="overlay">
            <div class="container">
                <div class="row">
                        <form action="register.php" method="POST">
                            <div class="illustration"><i class="icon ion-ios-personadd-outline"></i></div>
                            <h1>Kayıt Ol</h1>
                            <div class="form-group"><input class="form-control" type="firstname" name="firstname" placeholder="Ad"></div>
                            <div class="form-group"><input class="form-control" type="lastname" name="lastname" placeholder="Soyad"></div>
                            <div class="form-group"><input class="form-control" type="username" name="username" placeholder="Kullanıcı Adı"></div>
                            <div class="form-group"><input class="form-control" type="tel_num" name="tel_num" placeholder="Telefon Numarası"></div>
                            <div class="form-group"><input class="form-control" type="email" name="email" placeholder="E-posta"></div>
                            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Şifre"></div>
                            <div class="form-group"><input class="form-control" type="password" name="password-repeat" placeholder="Şifre Tekrar"></div>
                            <div class="form-group"><input class="header-button" type="submit" value="Tamamla"></div>
                            <a href="login.php" class="forgot">Zaten bir hesabınız mı var? Giriş yapın.</a>
                            <?php if(isset($error_message)) { ?>
                                <div class="error_message"><?php echo $error_message; ?></div>
                            <?php } ?>
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
