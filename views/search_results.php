<?php
require_once '../connect/functions.php';
session_start();
if (isset($_GET['search']) && $_GET['search'] !== '') {
    // recherche htag/tweets
    $data = [];
    $data_tweet = [];
    $queryHtag = 'SELECT content FROM tweet WHERE content LIKE "%' . $_GET['search'] . '%"';
    $statement = $connect->prepare($queryHtag);
    $statement->execute();
    $resultHtag = $statement->fetchAll();
    foreach ($resultHtag as $row) {
        $data_tweet[] = $row["content"];
    }
    // recherche utilisateurs 
    $query = "
    SELECT nickname FROM users
    WHERE nickname LIKE '%" . $_GET['search'] . "%'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $data[] = $row["nickname"];
    }
} else {
    header('location:../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="../css/dark.css" rel="stylesheet">
    <link href="../css/style1.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <title>Twitter</title>
</head>

<body>
    <header>
        <?php include('menu.php') ?>
    </header>
    <nav>
        <a href="#" id="togg1">Tweet</a>
        <a href="#" id="togg2">utilisateur</a>
        <div class="animation start-home"></div>
    </nav>
    <div class="container">
        <div class="col-xl-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div id="d1">
                            <ul>
                                <?php
                                if (empty($data_tweet)) {
                                    echo "0 tweet(s) pour: " . $_GET['search'];
                                } else {
                                    foreach ($data_tweet as $value) {
                                        echo "<li>Tweet : " . $value . "</li>";
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        <div id="d2" style="display:none;">
                            <ul>
                                <?php
                                if (empty($data)) {
                                    echo "0 utilisateur(s) pour: " . $_GET['search'];
                                } else {
                                    foreach ($data as $value) {
                                        echo '<li>Utilisateur : <a href="user_tweets.php?data=' . $value . '">' . $value . '</a></li>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>

                        <script type="text/javascript" src="../js/sr.js"></script>
                        <script type="text/javascript" src="../js/dark.js"></script>

</body>

</html>