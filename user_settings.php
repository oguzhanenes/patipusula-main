<?php 
session_start();
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = "Misafir";
    header("Location: login.php");
}

if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: login.php");
    exit;
}

require_once "db_conn.php";
$sql = "SELECT * FROM users WHERE (username = '$username')";
$result = $connect->query($sql)->fetch_assoc(); # DB'den gelen bilgiler.
$db_user_id = $result["user_id"];
$db_username = $result["username"];
$db_email = $result["email"];
$db_tel_num = $result["tel_num"];

if(isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["tel_num"]) && isset($_POST["username"]) && isset($_POST["email"])) {
    # input içindeki veriler alındı.
    $n_firstname = $_POST["firstname"];
    $n_lastname = $_POST["lastname"]; 
    $n_tel_num = $_POST["tel_num"]; 
    $n_username = $_POST["username"]; 
    $n_email = $_POST["email"];

    if(empty($n_firstname) || empty($n_lastname) || empty($n_tel_num) || empty($n_username) || empty($n_email)){
        $n_error_message = "Tüm alanları doldurmalısınız.";
    }
    elseif (!filter_var($n_email, FILTER_VALIDATE_EMAIL)){
        $n_error_message = "Geçersiz bir email adresi girdiniz.";
    }

    $n_sql_check_email = "SELECT users.email FROM users WHERE email=?";
    $n_stmt_check_email = mysqli_stmt_init($connect);
    if(mysqli_stmt_prepare($n_stmt_check_email, $n_sql_check_email)){
        mysqli_stmt_bind_param($n_stmt_check_email, "s", $n_email);
        mysqli_stmt_execute($n_stmt_check_email);
        mysqli_stmt_store_result($n_stmt_check_email);
        if(mysqli_stmt_num_rows($n_stmt_check_email) > 0){
            if($n_email == $db_email){
                $y_error_message = "Kendi E-Posta adresinizi girdiniz.";       
            }
            else{
                $n_error_message = "E-posta kullanımda.";
            }
        }
    }

    $n_sql_check_username = "SELECT users.username FROM users WHERE username=?";
    $n_stmt_check_username = mysqli_stmt_init($connect);
    if(mysqli_stmt_prepare($n_stmt_check_username, $n_sql_check_username)){
        mysqli_stmt_bind_param($n_stmt_check_username, "s", $n_username);
        mysqli_stmt_execute($n_stmt_check_username);
        mysqli_stmt_store_result($n_stmt_check_username);
        if(mysqli_stmt_num_rows($n_stmt_check_username) > 0){
            if($n_username == $db_username){
                $y_error_message = "Kendi Kullanıcı Adınızı girdiniz.";
            }
            else{
                $n_error_message = "Bu kullanıcı adı daha önceden alınmış.";
            }
        }
    }

    $n_sql_check_tel_num = "SELECT users.tel_num FROM users WHERE tel_num=?";
    $n_stmt_check_tel_num = mysqli_stmt_init($connect);
    if(mysqli_stmt_prepare($n_stmt_check_tel_num, $n_sql_check_tel_num)){
        mysqli_stmt_bind_param($n_stmt_check_tel_num, "s", $n_tel_num);
        mysqli_stmt_execute($n_stmt_check_tel_num);
        mysqli_stmt_store_result($n_stmt_check_tel_num);
        if(mysqli_stmt_num_rows($n_stmt_check_tel_num) > 0){
            if($n_tel_num == $db_tel_num){
                $y_error_message = "Kendi telefon numaranızı girdiniz.";
            }
            else{
                $n_error_message = "Bu telefon numarası sisteme kayıtlı.";
            }
        }
    }

    if(!isset($n_error_message)){
        $update_sql = "UPDATE users SET username=?, email=?, firstname=?, lastname=?, tel_num=? WHERE user_id=?";
        $n_stmt = mysqli_stmt_init($connect);
        if(mysqli_stmt_prepare($n_stmt, $update_sql)){
            mysqli_stmt_bind_param($n_stmt, "sssssi", $n_username, $n_email, $n_firstname, $n_lastname, $n_tel_num, $db_user_id);
            if(mysqli_stmt_execute($n_stmt)){
                $_SESSION['username'] = $n_username;
                session_write_close();
                session_start();
                mysqli_close($connect);
                $y_error_message = "İşlem başarılı!";
            } else{
                $n_error_message = "Güncelleme başarısız!";
            }
        }
        else{
            $n_error_message = "Hata oluştu!";
        }
    }

}


if(isset($_POST["curr_pass"]) && isset($_POST["new_pass"]) && isset($_POST["new_pass2"])){
    $curr_pass = $_POST["curr_pass"];
    $new_pass = $_POST["new_pass"];
    $new_pass2 = $_POST["new_pass2"];

    if(empty($curr_pass) || empty($new_pass) || empty($new_pass2)){
        $n2_error_message = "Tüm alanları doldurmalısınız.";
    }
    elseif ($new_pass != $new_pass2){
        $n2_error_message = "Yeni şifreler uyuşmuyor.";
    }
    elseif (strlen($new_pass) < 8){
        $n2_error_message = "Şifreniz en az 8 karakter olmalıdır.";
    }

    require_once "db_conn.php";
    $sql_check_curr_pass = "SELECT users.password FROM users WHERE user_id = $db_user_id";
    $result_pass = mysqli_query($connect, $sql_check_curr_pass);
    $rowx2 = mysqli_fetch_array($result_pass);
    $db_pass = $rowx2["password"];

    if(password_verify($curr_pass, $db_pass)){
        #Mevcut şifre doğru girildi.
        if(!isset($n_error_message)){
            $new_pass_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $update_pass_sql = "UPDATE users SET password=? WHERE user_id=?";
            $n_stmt_pass = mysqli_stmt_init( $connect );
            if(mysqli_stmt_prepare($n_stmt_pass, $update_pass_sql)){
                mysqli_stmt_bind_param($n_stmt_pass, "si", $new_pass_hash, $db_user_id);
                if(mysqli_stmt_execute($n_stmt_pass)){
                    mysqli_close($connect);
                    $y2_error_message = "İşlem başarılı!";
                }
            }
        }
    }
    else {
        $n2_error_message = "Mevcut şifreyi yanlış girdiniz.";
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiPusula | Ayarlar</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" type="text/css"  href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

</head>
<body data-target=".navbar-fixed-top">
    
    <nav id="menu" class="nav navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#dropMenu"> <span class="sr-only">Menüyü Aç</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand page-scroll">
                    <img src="img/logo.png" alt="">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="dropMenu">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="add_post.php" class="account-btn" type="button"><span class="glyphicon glyphicon-plus"></span> İlan Ekle</a></li>
                    <li><a href="posts.php" class="page-scroll">İlanlar</a></li>
                    <li>
                        <div class="dropdown">
                            <a href="#" class="account-btn" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="glyphicon glyphicon-user"></span>
                                <strong><?php echo $username; ?></strong>
                                <span class="glyphicon glyphicon-chevron-down"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="my_posts.php">İlanlarım</a>
                                <a class="dropdown-item" href="user_settings.php">Ayarlar</a>
                                <a class="dropdown-item" href="?logout=1">Çıkış</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="wrapper bg-white mt-sm-5">
        <h4 class="pb-4 border-bottom">Kullanıcı Hesap Ayarları</h4>
        <hr>
        <form method="post" class="user-settings" action="user_settings.php">
        <div class="py-2">
            <div class="row py-2">
                <div class="col-md-6">
                    <label for="firstname">İsim</label>
                    <?php 
                        echo "<input name='firstname' type='text' class='bg-light form-control' value=" . $result['firstname'] .">"; 
                    ?>
                </div>
                <div class="col-md-6 pt-md-0 pt-3">
                    <label for="lastname">Soyisim</label>
                    <?php 
                        echo "<input name='lastname' type='text' class='bg-light form-control' value=" . $result['lastname'] .">"; 
                    ?>
                </div>
            </div>
            <div class="row py-2">
                <div class="col-md-6">
                    <label for="email">E-Posta</label>
                    <?php 
                        echo "<input name='email' type='text' class='bg-light form-control' value=" . $result['email'] .">"; 
                    ?>
                </div>
                <div class="col-md-6 pt-md-0 pt-3">
                    <label for="username">Kullanıcı Adı</label>
                    <?php 
                        echo "<input name='username' type='text' class='bg-light form-control' value=" . $result['username'] .">"; 
                    ?>
                </div>
            </div>
            <div class="row py-2">
                <div class="col-md-6">
                <label for="phone">Telefon Numarası</label>
                <?php 
                        echo "<input name='tel_num' type='tel' class='bg-light form-control' value=" . $result['tel_num'] .">"; 
                    ?>
                </div>
            </div>
            <hr>
            <div class="py-3 pb-4 border-bottom">
                <button class="btn btn-primary mr-3" type="submit">Değişiklikleri Kaydet</button>
                <button class="btn border button" href="posts.php">İlanlar Sayfasına Dön</button>
            </div>
            <div class="d-sm-flex align-items-center pt-3" id="deactivate">
            <br>
            <div>
            <span class="glyphicon glyphicon-info-sign"></span>
                    <b>Bilgilendirme</b>
                    <p>Bilgilerinizi güncellediğiniz takdirde, eski bilgilerinize geri çeviremezsiniz.
                        Gerekirse eski bilgilerinizi yedeklemeniz önerilir.</p>
                        <?php if(isset($n_error_message)) { ?>
                            <div class="error_message"><?php echo $n_error_message; ?></div>
                        <?php } else if(isset($y_error_message)) { ?>
                            <div class="cong_message"><?php echo $y_error_message; ?></div>
                        <?php } ?>
                </div>
            </div>
            </div>
        </form>
    </div>

    <div class="wrapper bg-white mt-sm-5">
        <h4 class="pb-4 border-bottom">Şifre Değiştirme</h4>
        <hr>
        <form method="post" class="update_pass" action="user_settings.php">
            <div class="py-2">
                <div class="row py-2">
                    <div class="col-md-6">
                        <label for="password">Mevcut Şifre</label>
                        <input name="curr_pass" type="password" class="bg-light form-control">
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-md-6">
                        <label for="new-password">Yeni Şifre</label>
                        <input name="new_pass" type="password" class="bg-light form-control">
                    </div>
                    <div class="col-md-6 pt-md-0 pt-3">
                        <label for="new-password2">Yeni Şifre Tekrar</label>
                        <input name="new_pass2" type="password" class="bg-light form-control">
                    </div>
                </div>
                <hr>
                <div class="py-3 pb-4 border-bottom">
                    <button class="btn btn-primary mr-3">Şifreyi Güncelle</button>
                    <button class="btn border button" href="posts.php">İlanlar Sayfasına Dön</button>
                </div>
                <div class="d-sm-flex align-items-center pt-3" id="deactivate">
                <br>
                <div>
                <span class="glyphicon glyphicon-info-sign"></span>
                        <b>Bilgilendirme</b>
                        <p>Güvenlik nedeniyle şifrenizi kimseyle paylaşmayın.</p>
                        <?php if(isset($n2_error_message)) { ?>
                            <div class="error_message"><?php echo $n2_error_message; ?></div>
                        <?php } else if(isset($y2_error_message)) { ?>
                            <div class="cong_message"><?php echo $y2_error_message; ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <br><br><br>

    <section id="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <p>Copyright © 2024 | PatiPusula - Tüm hakları saklıdır.</p>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.1.11.1.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>
