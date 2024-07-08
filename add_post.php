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

$user_id_sql = "SELECT user_id FROM users WHERE username='$username'";
$user_id_result = $connect->query($user_id_sql);
$rowx = $user_id_result->fetch_assoc();
$take_user_id = $rowx["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $anim_type = $_POST["anim_type"];
    $anim_color = $_POST["anim_color"];
    $anim_age = $_POST["anim_age"];
    $anim_parent_status = $_POST["anim_parent_status"];
    $anim_city = $_POST["anim_city"];
    $anim_street = $_POST["anim_street"];
    $anim_status_level = $_POST["anim_status_level"];
    $full_address = $_POST["full_address"];
    $anim_health_status = $_POST["anim_health_status"];
    $photo = $_POST["photo_upload"]; # Fotoğraf linki.

    if(empty($anim_type) || empty($anim_color) ||empty($anim_age) ||
    empty($anim_parent_status) ||empty($anim_city) ||empty($anim_street) ||
    empty($anim_status_level) ||empty($full_address) ||empty($anim_health_status) ||
    empty($photo)){
        $n_error_message = "Tüm alanları doldurmalısınız.";
    }

    if (!isset($n_error_message)) {

        $add_photo_sql = "INSERT INTO table_photos_and_videos (photo1) VALUES (?)";
        $add_photo_stmt = mysqli_stmt_init($connect);
        $prepare_stmt = mysqli_stmt_prepare($add_photo_stmt, $add_photo_sql);
        if($prepare_stmt){
            mysqli_stmt_bind_param($add_photo_stmt, "s", $photo);
            mysqli_stmt_execute($add_photo_stmt);
            $take_photo_id = mysqli_insert_id($connect);
        }

        $add_location_sql = "INSERT INTO table_location_information (city, street, full_address) VALUES (?, ?, ?)";
        $add_location_stmt = mysqli_stmt_init($connect);
        $prepare_stmt = mysqli_stmt_prepare($add_location_stmt, $add_location_sql);
        if($prepare_stmt){
            mysqli_stmt_bind_param($add_location_stmt, "sss", $anim_city, $anim_street, $full_address);
            mysqli_stmt_execute($add_location_stmt);
            $take_location_id = mysqli_insert_id($connect);
        }

        $add_location_sql = "INSERT INTO table_animal_information (type, age, color, health_status, parent_status, status_level, location_id, photo_id, user_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $add_location_stmt = mysqli_stmt_init($connect);
        $prepare_stmt = mysqli_stmt_prepare($add_location_stmt, $add_location_sql);
        if($prepare_stmt){
            mysqli_stmt_bind_param($add_location_stmt, "sssssssss", $anim_type, $anim_age, $anim_color, $anim_health_status, $anim_parent_status, $anim_status_level,
            $take_location_id, $take_photo_id, $take_user_id);
            mysqli_stmt_execute($add_location_stmt);
        }
        $y_error_message = "İlan başarıyla eklendi! İlanlar sayfasına dönün.";
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiPusula | İlan Ekle</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="stylesheet" type="text/css"  href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://app.simplefileupload.com/buckets/41082854b6911ed697a563d54088e473.js"></script>

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
        <h4 class="pb-4 border-bottom">İlan Ekleme</h4>
        <hr>
        <form action="add_post.php" method="post" class="add-post">
            <div class="py-2">
                <div class="row py-2">
                    <div class="col-md-6">
                        <label for="statuss">Hayvanın Türü</label>
                        <select id="statuss" name="anim_type" class="bg-light form-control">
                            <option value="Kedi">Kedi</option>
                            <option value="Köpek">Köpek</option>
                        </select>
                    </div>
                    <div class="col-md-6 pt-md-0 pt-3">
                        <label for="Hrenk">Hayvanın Rengi</label>
                        <input type="text" id="Hrenk" name="anim_color" class="bg-light form-control">
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-md-6">
                    <label for="statuss">Hayvanın Yaşı (Tahmini)</label>
                        <select id="statuss" name="anim_age" class="bg-light form-control">
                            <option value="Yavru">Yavru</option>
                            <option value="Orta">Orta</option>
                            <option value="Yaşlı">Yaşlı</option>
                        </select>
                    </div>
                    <div class="col-md-6 pt-md-0 pt-3">
                        <label for="statuss">Sahiplik Durumu</label>
                        <select id="statuss" name="anim_parent_status" class="bg-light form-control">
                            <option value="Sahibi var">Sahibi var</option>
                            <option value="Sahipsiz">Sahipsiz</option>
                        </select>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-md-6">
                        <label for="Hil">Hayvanın Yaşadığı İl</label>
                        <input type="text" id="Hil" name="anim_city" class="bg-light form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="Hilce">Hayvanın Yaşadığı İlçe</label>
                        <input type="text" id="Hilce" name="anim_street" class="bg-light form-control">
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-md-6">
                        <label for="statuss">Sağlık Durumu</label>
                        <select id="statuss" name="anim_status_level" class="bg-light form-control">
                            <option value="Normal-İlan">Normal İlan</option>
                            <option value="Acil">Acil İlan</option>
                        </select>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-md-12">
                        <label for="Hadres">Adres</label>
                        <textarea rows="5" id="Saciklama" name="full_address" style="width:100%;resize:vertical;"></textarea>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-md-12">
                        <label for="Hadres">Açıklama</label>
                        <textarea rows="5" id="Saciklama" name="anim_health_status" style="width:100%;resize:vertical;"></textarea>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-md-12">
                    <label>Fotoğraf Ekle</label>
                    <input id="uploader-preview-here-371" class="simple-file-upload" data-width="50" data-height="50" type="hidden" value="" name="photo_upload" data-accepted="image/*"></div>
                </div>
                <hr>
                <div class="py-3 pb-4 border-bottom">
                    <button class="btn btn-primary mr-3" type="submit">Yayınla</button>
                    <button class="btn border button" type="reset">Sıfırla</button>
                </div>
                <div class="d-sm-flex align-items-center pt-3" id="deactivate">
                    <br>
                    <div>
                        <span class="glyphicon glyphicon-info-sign"></span>
                        <b>Bilgilendirme</b>
                        <p>İlan Bilgilerinin güncelliği ve doğruluğu hakkında emin olduktan sonra yayınlamayı unutmayın.
                        </p>
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
    <script src="https://app.simplefileupload.com/buckets/.js"></script>
    <script type="text/javascript" src="js/jquery.1.11.1.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/main.js"></script>


</body>
</html>
