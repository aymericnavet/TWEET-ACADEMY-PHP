<?php

include('../connect/functions.php');


session_start();

if (!isset($_SESSION['user_id'])) {
	header('location:login.php');
}
$message = '';

if (isset($_POST['edit_profile'])) {

	$check_query = "
	SELECT * FROM users WHERE nickname = :nickname AND id != :id
	";
	$statement = $connect->prepare($check_query);
	$statement->execute(
		array(
			':nickname'		=>		trim($_POST["nickname"]),
			':id'		    =>		$_SESSION["user_id"]
		)
	);
	$total_row = $statement->rowCount();
	if ($total_row > 0) {
		$message = '<div class="alert alert-danger">Ce pseudo existe déjà</div>';
	} else {
		$data = array(
			':nickname'			=>	trim($_POST["nickname"]),
			':bio'				=>	trim($_POST["bio"]),
			':id'				=>	$_SESSION["user_id"]
		);
		if ($_POST['password'] != '') {
			$data[] = array(
				':password'		=>	hash_hmac('ripemd160', $password, 'vive le projet tweet_academy')
			);
			$query = '
			UPDATE users SET nickname = :nickname, password = :password, bio = :bio WHERE id = :id
			';
		} else {
			$query = '
			UPDATE users SET nickname = :nickname, bio = :bio WHERE id = :id
			';
		}
		$statement = $connect->prepare($query);
		if ($statement->execute($data)) {
			$message = '<div class="alert alert-success">Profil mis à jour :)</div>';
		}
	}
}
$query = "SELECT * FROM users WHERE id = '" . $_SESSION["user_id"] . "'";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

?>

<html>

<head>
	<title>Twitter</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
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
			<div class="col-md-3">

			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo  "<h3 class='panel-title'>Editer profil de " . $_SESSION['nickname'] . "</h3>" ?>
					</div>
					<div class="panel-body">
						<?php
						foreach ($result as $row) {
							echo $message;
						?>
							<form method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label>Pseudo</label>
									<input type="text" name="nickname" id="nickname" required class="form-control" value="<?php echo $row["nickname"]; ?>" />
								</div>
								<div class="form-group">
									<label>Mot de passe</label>
									<input type="password" name="password" id="password" class="form-control" />
								</div>


								<div class="form-group">
									<label>Bio</label>
									<textarea name="bio" id="bio" class="form-control"><?php echo $row["bio"]; ?></textarea>
								</div>
								<div class="form-group">
									<input type="submit" name="edit_profile" id="edit_profile" class="btn btn-primary" value="Sauvegarder" />
								</div>
							</form>
						<?php
						}
						?>
					</div>
				</div>
			</div>


			<div class="col-sm-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Abonnés de <b><?php echo  ' ' . $_SESSION['nickname']; ?></b></h3>
					</div>
					<div class="panel-body">
						<?php

						$follower_query = "
		SELECT * FROM users 
			INNER JOIN link_user_follower_user_following 
			ON link_user_follower_user_following.id_following = users.id 
			WHERE link_user_follower_user_following.id_follower = '" . $_SESSION['user_id'] . "'
		";
						$statement = $connect->prepare($follower_query);

						$statement->execute();

						$follower_result = $statement->fetchAll();

						foreach ($follower_result as $follower_row) {
							if ($follower_row) {
								echo '
			<div class="row">	
				<div class="col-md-4">
					<h4><b>@<a href="user_tweets.php?data=' . $follower_row["nickname"] . '">' . $follower_row["nickname"] . '</a></b></h4>
				</div>
			</div>
			<hr />
			';
							}
						}
						?>


						<h3 class="panel-title">Abonnement de <b><?php echo  ' ' . $_SESSION['nickname']; ?></b></h3>
					</div>
					<div class="panel-body">
						<?php

						$follower_query = "
		SELECT * FROM users 
			INNER JOIN link_user_follower_user_following 
			ON link_user_follower_user_following.id_follower = users.id 
			WHERE link_user_follower_user_following.id_following = '" . $_SESSION['user_id'] . "'
		";
						$statement = $connect->prepare($follower_query);

						$statement->execute();

						$follower_result = $statement->fetchAll();

						foreach ($follower_result as $follower_row) {

							if ($follower_row) {

								echo '
			<div class="row">
			
				<div class="col-md-4">
					<h4><b>@<a href="user_tweets.php?data=' . $follower_row["nickname"] . '">' . $follower_row["nickname"] . '</a></b></h4>
				</div>
			</div>
			<hr />
			';
							}
						}
						?>
					</div>
				</div>

				<script src=".
				./js/dark.js"></script>

</body>

</html>