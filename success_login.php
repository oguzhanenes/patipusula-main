<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiPusula | Giriş</title>
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
                    <form method="get">
                        <h1>Giriş <span style="color: green;">başarılı.</span></h1>
                        <?php
                        session_start();
                        if(isset($_SESSION["user"]) && $_SESSION["user"] == "yes") {
                            if(isset($_SESSION["username"])) {
                                echo "<p>Oturum başlatılıyor.</p>"; 
                                echo "<img style='height: 90px; width: 160px;' src='../img/loading.gif'><br>";
                                echo "Lütfen bekleyin.";
                                header("refresh:2;url=posts.php");
                            } else {
                                echo "<h1>Hoşgeldiniz!</h1>";
                            }
                        } else {
                            exit;
                        }
                        ?>
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
