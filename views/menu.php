<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Twitter</title>
	<link rel="stylesheet" href="./css/style1.css">
	<link rel="stylesheet" href="./css/dark.css">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<nav class="navbar navbar-expand navbar-light bg-white">
	<div class="container">
		<div class="collapse navbar-collapse">
			<ul class="navbar-nav">
				<li class="nav-item active">
					<a href="../index.php" class="nav-link">
						<svg viewBox="0 0 24 24">
							<g>
								<path d="M22.46 7.57L12.357 2.115c-.223-.12-.49-.12-.713 0L1.543 7.57c-.364.197-.5.652-.303 1.017.135.25.394.393.66.393.12 0 .243-.03.356-.09l.815-.44L4.7 19.963c.214 1.215 1.308 2.062 2.658 2.062h9.282c1.352 0 2.445-.848 2.663-2.087l1.626-11.49.818.442c.364.193.82.06 1.017-.304.196-.363.06-.818-.304-1.016zm-4.638 12.133c-.107.606-.703.822-1.18.822H7.36c-.48 0-1.075-.216-1.178-.798L4.48 7.69 12 3.628l7.522 4.06-1.7 12.015z"></path>
								<path d="M8.22 12.184c0 2.084 1.695 3.78 3.78 3.78s3.78-1.696 3.78-3.78-1.695-3.78-3.78-3.78-3.78 1.696-3.78 3.78zm6.06 0c0 1.258-1.022 2.28-2.28 2.28s-2.28-1.022-2.28-2.28 1.022-2.28 2.28-2.28 2.28 1.022 2.28 2.28z"></path>
							</g>
						</svg>
					</a>
				</li>
				<li class="nav-item" id="notif" style="position:relative;">
					<a href="" class="nav-link" data-toggle="dropdown">
						<svg viewBox="0 0 24 24">
							<g>
								<path d="M21.697 16.468c-.02-.016-2.14-1.64-2.103-6.03.02-2.532-.812-4.782-2.347-6.335C15.872 2.71 14.01 1.94 12.005 1.93h-.013c-2.004.01-3.866.78-5.242 2.174-1.534 1.553-2.368 3.802-2.346 6.334.037 4.33-2.02 5.967-2.102 6.03-.26.193-.366.53-.265.838.102.308.39.515.712.515h4.92c.102 2.31 1.997 4.16 4.33 4.16s4.226-1.85 4.327-4.16h4.922c.322 0 .61-.206.71-.514.103-.307-.003-.645-.263-.838zM12 20.478c-1.505 0-2.73-1.177-2.828-2.658h5.656c-.1 1.48-1.323 2.66-2.828 2.66zM4.38 16.32c.74-1.132 1.548-3.028 1.524-5.896-.018-2.16.644-3.982 1.913-5.267C8.91 4.05 10.397 3.437 12 3.43c1.603.008 3.087.62 4.18 1.728 1.27 1.285 1.933 3.106 1.915 5.267-.024 2.868.785 4.765 1.525 5.896H4.38z">
								</path>
							</g>
						</svg>
					</a>

					<?php
					$total_notification = Count_notification($connect, $_SESSION["user_id"]);

					if ($total_notification > 0) {
						echo '<span class="label label-danger" id="total_notification">' . $total_notification . '</span>';
					}


					?>
					<ul class="dropdown-menu">
						<?php
						echo Load_notification($connect, $_SESSION["user_id"]);

						?>

				</li>
			</ul>
		</div>
		<ul class="navbar-nav d-none d-md-block">
			<li>
				<form action="/views/search_results.php" method="GET">
					<input type="text" name="search" id="search" class="form-control form-control-sm rounded-pill search border-0 px-3 w-100 searchbar" placeholder="Rechercher..." autocomplete="off" style="height:30px;" />
				</form>
			</li>
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user icon" aria-hidden="true"></i></a>
				<ul class="dropdown-menu">
					<li><a href="/views/profile.php">Profil</a></li>
					<li><a href="/views/logout.php">Se déconnecter</a></li>
				</ul>
			</li>
		</ul>
	</div>
</nav>