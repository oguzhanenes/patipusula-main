<?php

    $db_host = "eu-cluster-west-01.k8s.cleardb.net";
    $db_user = "b219de9e0d450c";
    $db_pass = "15e85a48";
    $db_name = "heroku_d8205ed99c08299";
    
    $connect = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die("database connection error!");