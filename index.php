<?php
include('connect/functions.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:/views/login.php');
}
?>
<html>

<head>
    <title>Twitter</title>
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <link rel="stylesheet" href="css/style1.css">
    <link rel="stylesheet" href="css/dark.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
</head>

<body>
    <div class="container">
        <?php
        include('views/menu.php');
        ?>
        <div>
            <input type="checkbox" class="checkbox" id="chk" />
            <label class="labelo" for="chk">
                <i class="fas fa-moon"></i>
                <i class="fas fa-sun"></i>
                <div class="ball"></div>
            </label>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="panel-title">Ecrivez ici</h3>
                            </div>
                            <div class="col-md-4">

                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form method="post" id="post_form">
                            <div class="form-group" id="dynamic_field">
                                <textarea name="post_content" id="post_content" maxlength="280" class="form-control" placeholder="Quoi d'neuf ?"></textarea>
                            </div>
                            <div class="form-group" style="text-align:right;">
                                <input type="hidden" name="action" value="insert" /> <!-- j'utilise cet input côté serveur (cf commentaire)  -->
                                <input type="hidden" name="post_type" id="post_type" value="text" />
                                <input type="submit" name="share_post" id="share_post" class="btn btn-primary" value="Tweeter" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tweets</h3>
                    </div>
                    <div class="panel-body">
                        <div id="post_list">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Liste de twittos</h3>
                    </div>
                    <div class="panel-body">
                        <div id="user_list"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/dark.js"></script>
</body>

</html>
<?php
include('connect/jquery_ajax.php');
?>