<?php 
session_start();
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = "Misafir";
    header("Location: login.php");
}
require_once "db_conn.php";

if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: login.php");
    exit;
}

$id = $_GET["id"];
if (is_null($id)) header("location:posts.php");

$sql = "SELECT * FROM table_animal_information AS a
                JOIN table_location_information AS l ON a.location_id = l.location_id
                JOIN table_photos_and_videos AS p ON a.photo_id = p.photo_id
                JOIN users AS u ON a.user_id = u.user_id
                WHERE animal_id = $id";
$result = $connect->query($sql)->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiPusula | Detaylar</title>
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
<body data-spy="scroll" data-target=".navbar-fixed-top">
    
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


    <div id="content-details">
        <div id="animal-info">
            <div class="details">
                <?php echo "<img src='" . $result['photo1'] . "' class='anim-photo' alt='...''>";?>
                <div class="user-line">
                    <div class="user-holder">
                        <img src="img/user.png" alt="profile photo">
                        <?php echo "<span>" . $result['firstname'] . " " . $result['lastname'] . "</span>" ?>
                    </div>
                    <div class="city-holder">
                        <span><i class='fa fa-map-marker'></i></span>
                        <?php echo "<span>" . $result['city'] . ", " . $result['street'] . "</span>"; ?>
                    </div>
                </div>

                <div class="description-holder">
                    <h4><strong>Açıklama</strong></h4>
                    <?php echo "<p>" . $result['health_status'] . "</p>"; ?>
                </div>
            </div>
            <div class="info">
                <div class="general-info">
                    <h5>Hayvan Bilgileri</h5>
                    <div class="info-holder">
                        <div class="info-line">
                            <span class="header">Tür:</span>
                            <?php echo '<span class="content">' . $result['type'] . '</span>'; ?>
                        </div>
                        <div class="info-line">
                            <span class="header">Cinsiyet:</span>
                            <!-- TODO: Cinsiyet Ekle -->
                            <?php echo '<span class="content">' . $result['type'] . '</span>'; ?>
                        </div>
                        <div class="info-line">
                            <span class="header">Yaş:</span>
                            <?php echo '<span class="content">' . $result['age'] . '</span>'; ?>
                        </div>
                        <div class="info-line">
                            <span class="header">Renk:</span>
                            <?php echo '<span class="content">' . $result['color'] . '</span>'; ?>
                        </div>
                        <div class="info-line">
                            <span class="header">İl:</span>
                            <?php echo '<span class="content">' . $result['city'] . '</span>'; ?>
                        </div>
                        <div class="info-line">
                            <span class="header">İlçe:</span>
                            <?php echo '<span class="content">' . $result['street'] . '</span>'; ?>
                        </div>
                    </div>
                </div>
                <div class="contact-info">
                    <h5>İletişim Bilgileri</h5>
                    <div class="info-line">
                        <span class="header">Telefon:</span>
                        <?php echo '<span class="content">' . $result['tel_num'] . '</span>'; ?>
                    </div>
                </div>
                <div class="location-info">
                    <h5>Adres Bilgileri</h5>
                    <?php echo "<p>" . $result['full_address'] . "</p>"; ?>
                    <iframe width="100%" height="350" src="https://www.openstreetmap.org/export/embed.html?bbox=29.090509414672855%2C41.13248004615992%2C29.098325371742252%2C41.135918361404904&amp;layer=mapnik"></iframe><br /><small><a href="https://www.openstreetmap.org/#map=18/41.13420/29.09442">Daha Büyük Haritayı Göster</a></small>
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

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js"></script>
    <script type="text/javascript" src="js/jquery.1.11.1.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/main.js"></script>

    <script>
        $(document).ready(function(){
            $('.account-btn').click(function(){
                $(this).next('.dropdown-menu').toggleClass('show');
            });
        });
    </script>
</body>
</html>
