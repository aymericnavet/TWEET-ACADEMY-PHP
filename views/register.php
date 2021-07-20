<?php

include('../connect/functions.php');

session_start();

$message = '';

if (isset($_SESSION['user_id'])) {
	header('location:index.php');
}

if (isset($_POST['register'])) {

	$nickname = trim($_POST["nickname"]);
	$password = trim($_POST["password"]);
	$email = trim($_POST['email']);
	$birthday = $_POST['birthday'];

	$check_query = "
	SELECT * FROM  users 
	WHERE nickname = :nickname
	";
	$statement = $connect->prepare($check_query);
	$check_data = array(
		':nickname'		=>	$nickname
	);
	if ($statement->execute($check_data)) {
		if ($statement->rowCount() > 0) {
			$message .= '<p><label>Pseudo déjà pris</label></p>';
		} else {
			if (empty($nickname)) {
				$message .= '<p><label>Met un pseudo non ?</label></p>';
			}
			if (empty($password)) {
				$message .= '<p><label>Met un mot de passe frero ? ça va pas ?</label></p>';
			} else {
				if ($password != $_POST["confirm_password"]) {
					$message .= '<p><label>Ta ecris un mot de passe différent de celui que ta tapé avant... :facepalm: </label></p>';
				}
			}
			if ($message == '') {

				$data = array(
					':nickname'		=>	$nickname,
					':password'		=>	hash_hmac('ripemd160', $password, 'vive le projet tweet_academy'),
					':email' => $email,
					':birthday' => $birthday
				);

				$query = "
				INSERT INTO users 
				(nickname, password,email,birthday) 
				VALUES (:nickname, :password,:email,:birthday)
				";

				$statement = $connect->prepare($query);

				if ($statement->execute($data)) {
					$message = '<label>Inscription effectuée avec succès !</label>';
				}
			}
		}
	}
}

?>

<html>

<head>
	<title>Twitter</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>
	<div class="container">
		<br />

		<h3 style="text-align:center;">Twitter</a></h3><br />
		<br />
		<div class="panel panel-default">
			<div class="panel-heading">S'inscrire</div>
			<div class="panel-body">
				<form method="post">
					<span class="text-danger"><?php echo $message; ?></span>
					<div class="form-group">
						<label>Choisissez un pseudo</label>
						<input type="text" name="nickname" class="form-control" />
					</div>
					<div class="form-group">
						<label>Indiquez votre date de naissance</label>
						<input type="date" name="birthday" class="form-control" />
					</div>
					<div class="form-group">
						<label>Indiquez votre adresse e-mail</label>
						<input type="email" name="email" class="form-control" />
					</div>
					<div class="form-group">
						<label>Choisissez un mot de passe</label>
						<input type="password" name="password" id="password" class="form-control" />
					</div>
					<div class="form-group">
						<label>Re-entrez votre mot de passe</label>
						<input type="password" name="confirm_password" id="confirm_password" class="form-control" />
					</div>
					<div class="form-group">
						<input type="submit" name="register" class="btn btn-info" value="S'inscrire" />
					</div>
					<div style="text-align:center;">
						<a href="login.php">Se connecter</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>

</html>