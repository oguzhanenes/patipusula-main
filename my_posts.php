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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiPusula | İlanlarım</title>
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

    <section id="content">
        <div class="other-posts">
            <h2 style="font-family: 'Space Grotesk', sans-serif;">İlanlarım</h2>
            <div class="other-post-holder">
                <?php

                    $user_id_sql = "SELECT user_id FROM users WHERE username='$username'";
                    $user_id_result = $connect->query($user_id_sql);
                    $rowx = $user_id_result->fetch_assoc();
                    $search_user_id = $rowx["user_id"];

                    $sql = "SELECT * FROM users JOIN table_animal_information ON users.user_id = table_animal_information.user_id JOIN table_photos_and_videos ON
                    table_animal_information.photo_id = table_photos_and_videos.photo_id JOIN 
                    table_location_information ON table_animal_information.location_id = table_location_information.location_id WHERE (users.user_id = '$search_user_id')";
                    $result = $connect -> query($sql);
                    if($result -> num_rows > 0) {
                        while( $row = $result -> fetch_assoc() ) {
                            echo "<div class='post-card' data-id=" . $row["animal_id"] . ">";
                            echo "<img src='". $row['photo1'] ."'>";
                            echo "<div class='card-description'>" . $row['health_status'] . "</div>";
                            echo "<div class='location-holder'>";
                            echo "<span><i class='fa fa-map-marker'></i></span>";
                            echo "<span class='location'>" . $row['city'] ."</span>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                    else {
                        echo "<h3>Hiçbir ilan bulunamadı.</h3>";
                    }
                ?>

            </div>
        </div>
    </section>

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

    <script>
        const cards = document.querySelectorAll('.post-card');
        cards.forEach((card) => {
            card.addEventListener('click', (e) => {
                window.location.href =  `details.php?id=${e.target.dataset.id}`;
            })
        })
    </script>

</body>
</html>
