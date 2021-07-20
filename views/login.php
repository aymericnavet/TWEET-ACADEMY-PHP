<?php

include('../connect/functions.php');

session_start();

$message = '';

if (isset($_SESSION['user_id'])) {
	header('location:../index.php');
}

if (isset($_POST["login"])) {
	$query = "
	SELECT * FROM users
  		WHERE nickname = :nickname
	";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':nickname' => $_POST['nickname']
		)
	);
	$count = $statement->rowCount();
	if ($count > 0) {
		$result = $statement->fetchAll();
		foreach ($result as $row) {
			if (hash_hmac('ripemd160', $_POST['password'], 'vive le projet tweet_academy') == $row['password']) {
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['nickname'] = $row['nickname'];
				header('location:../index.php');
			} else {
				$message = '<label>Mauvais mot de passe, réessayez ;)</label>';
			}
		}
	} else {
		$message = '<label>Mauvais pseudo, réessayez ;)</labe>';
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
		<h3 style="text-align:center">Twitter</a></h3><br />
		<br />
		<div class="panel panel-default">
			<div class="panel-heading">Connexion</div>
			<div class="panel-body">
				<form method="post">
					<p class="text-danger"><?php echo $message; ?></p>
					<div class="form-group">
						<label>Entrez votre pseudo</label>
						<input type="text" name="nickname" class="form-control" required />
					</div>
					<div class="form-group">
						<label>Entrez votre mot de passe</label>
						<input type="password" name="password" class="form-control" required />
					</div>
					<div class="form-group">
						<input type="submit" name="login" class="btn btn-info" value="Se connecter" />
					</div>
					<div style="text-align:center">
						<a href="register.php">S'inscrire</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
