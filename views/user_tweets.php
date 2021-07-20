<?php
include('../connect/functions.php');
session_start();
if (!isset($_SESSION['user_id'])) {
	header('location:login.php');
}
$query = "
SELECT users.bio as 'bio',users.*, tweet.* FROM tweet
INNER JOIN users ON users.id = tweet.id_user 
WHERE users.nickname = '" . $_GET["data"] . "' 
GROUP BY tweet.id
ORDER BY tweet.id DESC
";
$statement = $connect->prepare($query);
$statement->execute();
$total_row = $statement->rowCount();
$user_id = Get_user_id($connect, $_GET["data"]);
?>
<html>

<head>
	<title>Twitter</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
	<link rel="stylesheet" href="../css/style1.css">
	<link rel="stylesheet" href="../css/dark.css">
</head>

<body>
	<div class="container">
		<?php
		include('menu.php');
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
			<?php
			if ($total_row > 0) {
				$result = $statement->fetchAll();
			?>
				<div class="col-md-9">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-6">
									<h3 class="panel-title">Tweets de <?php echo '<b>' . ' ' . $_GET["data"] . '</b>'; ?></h3>
								</div>
								<div class="col-md-6" style="text-align:right;">
									<?php
									if ($user_id != $_SESSION["user_id"]) {
										echo bouton_follow($connect, $user_id, $_SESSION["user_id"]);
									}
									?>
								</div>
							</div>
						</div>
						<div class="panel-body">
							<?php
							foreach ($result as $row) {
								$repost = 'disabled';
								if ($row['id_user'] != $_SESSION['user_id']) {
									$repost = '';
								}
								echo '
							<div class="jumbotron" style="padding:24px 30px 24px 30px">
								<div class="row">
									
									<div class="col-md-10">
										<h3><b>@<a href="/views/user_tweets.php?data=' . $row["nickname"] . '">' . $row["nickname"] . '</a></b></h3>
										<p>' . toMention($row["content"]) . '<br /><br /> 
										<button type="button" class="btn btn-link post_comment" id="' . $row["id"] . '" data-user_id="' . $row["id"] . '">' . count_comment($connect, $row["id"]) . ' Commentaires</button>
										<button type="button" class="btn btn-danger repost" data-post_id="' . $row["id"] . '" ' . $repost . '><span class="glyphicon glyphicon-retweet"></span>&nbsp;&nbsp;' . count_retweet($connect, $row["id"]) . '</button>
										<button type="button" class="btn btn-link like_button" data-post_id="' . $row["id"] . '"><span class="glyphicon glyphicon-thumbs-up"></span> Like ' . count_total_post_like($connect, $row["id"]) . '</button>
										</p>
										<div id="comment_form' . $row["id"] . '" style="display:none;">
											<span id="old_comment' . $row["id"] . '"></span>
											<div class="form-group">
												<textarea name="comment" class="form-control" id="comment' . $row["id"] . '"></textarea>
											</div>
											<div class="form-group" align="right">
												<button type="button" name="submit_comment" class="btn btn-primary btn-xs submit_comment" data-post_id="' . $row["id"] . '">Commenter</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							';
							}
							?>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4>Bio de <b><?php echo  ' ' . $_GET["data"]; ?></b> : <br> <br> <i><?php echo $row["bio"]; ?> </i><br><br></h4>
							<h3 class="panel-title">Abonnés de <b><?php echo  ' ' . $_GET["data"]; ?></b></h3>
						</div>
						<div class="panel-body">
							<?php
							$follower_query = "
						SELECT * FROM users 
							INNER JOIN link_user_follower_user_following 
							ON link_user_follower_user_following.id_following = users.id 
							WHERE link_user_follower_user_following.id_follower = '" . $user_id . "'
						";
							$statement = $connect->prepare($follower_query);

							$statement->execute();

							$follower_result = $statement->fetchAll();

							foreach ($follower_result as $follower_row) {
								echo '
							<div class="row">
							
								<div class="col-md-8">
									<h4><b>@<a href="/views/user_tweets.php?data=' . $follower_row["nickname"] . '">' . $follower_row["nickname"] . '</a></b></h4>
								</div>
							</div>
							<hr />
							';
							}
							?>
						</div>
					</div>
				</div>
			<?php
			} else {
				echo '<h3 style="text-align:center;color:red;">Aucun tweet trouvé</h3>';
			}
			?>
		</div>
	</div>
	<script src="../js/dark.js"></script>
</body>

</html>
<?php
include('../connect/jquery_ajax.php');
?>