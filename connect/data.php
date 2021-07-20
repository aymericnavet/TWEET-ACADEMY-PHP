<?php

include('functions.php');
session_start();


if (isset($_POST['action'])) {

	$output = '';

	if ($_POST['action'] == 'insert')  // j'ai créee un input type hidden pour pouvoir reverifier si le formulaire est bien envoyé et ainsi, je peux englober
	// toute mes conditions dans une seule condition (if isset($_POST['action]))) et pouvoir déclarer ma variable '$output' qu'une seule fois
	{

		$tweet_info = toHashtag($_POST['post_content']);

		$data = array(
			':id_user'		=>	$_SESSION["user_id"],
			':content'		=>	$tweet_info[0],
			':date'     	=>	date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:s'))),
			':id_hashtag'   => $tweet_info[1]
		);
		$query = "
		INSERT INTO tweet
		(id_user, content, date,id_hashtag) 
		VALUES (:id_user, :content, :date,:id_hashtag)
		";

		$statement = $connect->prepare($query);
		$statement->execute($data);

		$notification_query = "
		SELECT id_following FROM link_user_follower_user_following 
		WHERE id_following = '" . $_SESSION["user_id"] . "'
		";

		$statement = $connect->prepare($notification_query);

		$statement->execute();

		$notification_result = $statement->fetchAll();

		foreach ($notification_result as $notification_row) {
			$notification_text = '<b>' . Get_user_name($connect, $_SESSION["user_id"]) . '</b> a partagé un nouveau tweet';

			$insert_query = "
			INSERT INTO notifications 
			(notification_receiver_id, notification_text, read_notification) 
			VALUES ('" . $notification_row['id_following'] . "', '" . $notification_text . "', 'no')
			";

			$statement = $connect->prepare($insert_query);

			$statement->execute();
		}
	}
	if ($_POST['action'] == 'fetch_post') {  // Récupération de mes posts (tweets)
		$query = "
		SELECT tweet.id as 'tweet_id', tweet.* , users.*  FROM tweet 
		INNER JOIN users ON users.id = tweet.id_user  
		LEFT JOIN link_user_follower_user_following ON link_user_follower_user_following.id_follower = tweet.id_user    
		WHERE link_user_follower_user_following.id_following  = '" . $_SESSION["user_id"] . "' OR tweet.id_user = '" . $_SESSION["user_id"] . "' 
		GROUP BY tweet.id 
		ORDER BY tweet.id DESC
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$total_row = $statement->rowCount();
		if ($total_row > 0) {
			foreach ($result as $row) {

				$repost = 'disabled';
				if ($row['id'] != $_SESSION['user_id']) {
					$repost = '';
				}

				$output .= '
				<div class="jumbotron" style="padding:24px 30px 24px 30px">
					<div class="row">
					
						<div class="col-md-8">
							<h3><b><b>@<a href="/views/user_tweets.php?data=' . $row["nickname"] . '">' . $row["nickname"] . '</a></h3>
							<p id="' . $row['tweet_id'] . '">' . toMention($row["content"]) . '<br /><br /> 
							<button type="button" class="btn btn-link post_comment" id="' . $row['tweet_id'] . '" data-user_id="' . $row["id_user"] . '">' . count_comment($connect, $row['tweet_id']) . ' Commenaires</button>
							<button type="button" class="btn btn-danger repost" data-post_id="' . $row['tweet_id'] . '" ' . $repost . '><span class="glyphicon glyphicon-retweet"></span>&nbsp;&nbsp;' . count_retweet($connect, $row['tweet_id']) . '</button>


							<button type="button" class="btn btn-link like_button" data-post_id="' . $row['tweet_id'] . '"><span class="glyphicon glyphicon-thumbs-up"></span> Like ' . total_likes($connect, $row['tweet_id']) . '</button>
							<br><br><span style="font-size:15px;font-style:italic;">' . $row['date'] . '</span>	 


							</p>
							<div id="comment_form' . $row['tweet_id'] . '" style="display:none;">
								<span id="old_comment' . $row['tweet_id'] . '"></span>
								<div class="form-group">
									<textarea name="comment" class="form-control" id="comment' . $row['tweet_id'] . '"></textarea>
								</div>
								<div class="form-group" align="right">
								
									<button type="button" name="submit_comment" class="btn btn-primary btn-xs submit_comment">Commenter</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				';
			}
		} else {
			$output = '<h4 style="color:red;">Aucun tweet trouvé</h4>';
		}

		echo $output;
	}
	if ($_POST['action'] == 'fetch_user') { // liste de mes utilisateurs
		$query = "
		SELECT * FROM users 
		WHERE id != '" . $_SESSION["user_id"] . "' 
		ORDER BY id DESC 
		LIMIT 15
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();

		if ($result) {
			foreach ($result as $row) {
				$queryNumberFollower = "SELECT COUNT(id_following) FROM link_user_follower_user_following WHERE id_follower=" . $row['id'];
				$stmt = $connect->prepare($queryNumberFollower);
				$stmt->execute();
				$resultat = $stmt->fetchAll();
				//nombre de followers
				$output .= '
			<div class="row">
			
				<div class="col-md-8">
					<h4><b>@<a href="/views/user_tweets.php?data=' . $row["nickname"] . '">' . $row["nickname"] . '</a></b></h4>
					' . bouton_follow($connect, $row["id"], $_SESSION["user_id"]) . '
					<span class="label label-success"> ' . $resultat[0][0] . ' Abonnés</span>
				</div>
			</div>
			<hr />
			';
			}
			echo $output;
		}
	}
	if ($_POST['action'] == 'follow') { // Insertion de follow dans ma ddb
		$query = "
		INSERT INTO link_user_follower_user_following 
		(id_follower,id_following) 
		VALUES ('" . $_POST["id_follower"] . "', '" . $_SESSION["user_id"] . "')
		";
		$statement = $connect->prepare($query);
		if ($statement->execute()) {

			$notification_text = '<b>' . Get_user_name($connect, $_SESSION["user_id"]) . '</b> vous a follow.';

			$insert_query = "
			INSERT INTO notifications 
			(notification_receiver_id, notification_text, read_notification) 
			VALUES ('" . $_POST["id_follower"] . "', '" . $notification_text . "', 'no')
			";

			$statement = $connect->prepare($insert_query);
			$statement->execute();
		}
	}
	if ($_POST['action'] == 'unfollow') {  // supression de follow dans ma ddb

		$query = "
		DELETE FROM link_user_follower_user_following  
		WHERE id_follower = '" . $_POST["id_follower"] . "' 
		AND id_following = '" . $_SESSION["user_id"] . "'
		";
		$statement = $connect->prepare($query);
		if ($statement->execute()) {
			$sub_query = "SELECT COUNT(id_following) FROM link_user_follower_user_following WHERE id_follower=" . $row['id'];

			$statement = $connect->prepare($sub_query);
			$statement->execute();

			$notification_text = '<b>' . Get_user_name($connect, $_SESSION["user_id"]) . '</b> vous a unfollow mdr.';

			$insert_query = "
			INSERT INTO notifications 
			(notification_receiver_id, notification_text, read_notification) 
			VALUES ('" . $_POST["id_follower"] . "', '" . $notification_text . "', 'no')
			";
			$statement = $connect->prepare($insert_query);

			$statement->execute();
		}
	}
	if ($_POST["action"] == 'submit_comment') {  // envoi de commentaire sous un tweet
		$comment_info = toHashtag($_POST['comment']);

		$data = array(
			':id_tweet'		=>	$_POST["tweet_id"],
			':id_user'		=>	$_SESSION["user_id"],
			':content'		=>	$comment_info[0],
			':date'	=>	date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')))
		);
		$query = "
		INSERT INTO comments 
		(id_tweet, id_user, content, date) 
		VALUES (:id_tweet, :id_user, :content, :date)
		";
		$statement = $connect->prepare($query);
		$statement->execute($data);

		$notification_query = "
		SELECT id_user, content FROM tweet 
		WHERE id = '" . $_POST["tweet_id"] . "'
		";

		$statement = $connect->prepare($notification_query);

		$statement->execute();

		$notification_result = $statement->fetchAll();

		foreach ($notification_result as $notification_row) {
			$notification_text = '<b>' . Get_user_name($connect, $_SESSION["user_id"]) . '</b> a commenté votre tweet - "' . strip_tags(substr($notification_row["content"], 0, 30)) . '..."';

			$insert_query = "
			INSERT INTO notifications 
			(notification_receiver_id, notification_text, read_notification) 
			VALUES ('" . $notification_row['id_user'] . "', '" . $notification_text . "', 'no')
			";

			$statement = $connect->prepare($insert_query);
			$statement->execute();
		}
	}
	if ($_POST["action"] == "fetch_comment") { // récuperation du commentaire
		$query = "
		SELECT * FROM comments 
		INNER JOIN users 
		ON users.id = comments.id_user 
		WHERE id_tweet = '" . $_POST["tweet_id"] . "' 
		ORDER BY comments.id ASC
		";
		$statement = $connect->prepare($query);
		$output = '';
		if ($statement->execute()) {
			$result = $statement->fetchAll();
			foreach ($result as $row) {

				$output .= '
				<div class="row" style="margin-left:5%;">
	
					<div class="col-md-10" style="margin-top:16px; padding-left:0">
						<b>@<a href="/views/user_tweets.php?data=' . $row["nickname"] . '">' . $row["nickname"] . '</a></b><br />
						' . $row["content"] . '
						
					</div>
				</div>
				<br />
				';
			}
		}
		echo $output;
	}

	if ($_POST['action'] == 'retweet') {			 // RETWEETS


		$query = "
		SELECT * FROM link_user_tweet
		WHERE id_tweet = '" . $_POST['tweet_id'] . "'
		AND id_user = " . $_SESSION['user_id'];

		$statement = $connect->prepare($query);
		$statement->execute();
		$total = $statement->fetchAll(PDO::FETCH_ASSOC);
		$already_retweeted = false;
		if (count($total) > 0) {
			foreach ($total[0] as $value) {
				if ($value == $_SESSION['user_id']) {
					$already_retweeted = true;
					echo 'Vous avez déjà retweeté ça';
				}
			}
		}

		if ($already_retweeted == false) {

			$query1 = "
			INSERT INTO link_user_tweet 
			(id_tweet, id_user) 
			VALUES ('" . $_POST['tweet_id'] . "', '" . $_SESSION["user_id"] . "')
			";
			$statement = $connect->prepare($query1);

			if ($statement->execute()) {
				$query2 = "
				SELECT * FROM tweet 
				WHERE id = '" . $_POST['tweet_id'] . "'
				";

				$statement = $connect->prepare($query2);
				if ($statement->execute()) {

					$result = $statement->fetchAll();
					$post_content = '';
					if (count($result) > 0) {

						$post_content .= $result[0]['content'];
					}

					$query3 = "
					INSERT INTO tweet
					(id_user, content, date) 
					VALUES (?, ?, ?)
					";

					$statement = $connect->prepare($query3);
					if ($statement->execute(array($_SESSION["user_id"], $post_content, date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')))))) {

						$notification_query = "
						SELECT id_user, content FROM tweet 
						WHERE id = '" . $_SESSION['user_id'] . "'
						";

						$statement = $connect->prepare($notification_query);

						$statement->execute();

						$notification_result = $statement->fetchAll();

						foreach ($notification_result as $notification_row) {
							$notification_text = '<b>' . Get_user_name($connect, $_SESSION["user_id"]) . '</b> a retweet votre post - "' . strip_tags(substr($notification_row["content"], 0, 30)) . '..."';

							$insert_query = "
							INSERT INTO notifications 
							(notification_receiver_id, notification_text, read_notification) 
							VALUES ('" . $notification_row['id_user'] . "', '" . $notification_text . "', 'no')
							";
							$statement = $connect->prepare($insert_query);
							$statement->execute();
						}

						echo 'Retweet effectué avec succès';
					}
				}
			}
		}
	}
	if ($_POST["action"] == "like") { 			// LIKES
		$query = "
		SELECT * FROM likes 
		WHERE id_tweet = '" . $_POST["tweet_id"] . "' 
		AND id_user = '" . $_SESSION["user_id"] . "'
		";
		$statement = $connect->prepare($query);
		$statement->execute();

		$total_row = $statement->rowCount();

		if ($total_row > 0) {
			echo 'Vous avez déjà liké ce tweet';
		} else {
			$insert_query = "
			INSERT INTO likes 
			(id_user, id_tweet) 
			VALUES ('" . $_SESSION["user_id"] . "', '" . $_POST["tweet_id"] . "')
			";

			$statement = $connect->prepare($insert_query);

			$statement->execute();

			$notification_query = "
			SELECT id_user, content FROM tweet
			WHERE id = '" . $_POST["tweet_id"] . "'
			";

			$statement = $connect->prepare($notification_query);

			$statement->execute();

			$notification_result = $statement->fetchAll();

			foreach ($notification_result as $notification_row) {
				$notification_text = '
				<b>' . Get_user_name($connect, $_SESSION["user_id"]) . '</b> a liké votre post - "' . strip_tags(substr($notification_row['content'], 0, 30)) . '..."
				';

				$insert_query = "
				INSERT INTO notifications 
					(notification_receiver_id, notification_text, read_notification) 
					VALUES ('" . $notification_row['id_user'] . "', '" . $notification_text . "', 'no')
				";

				$statement = $connect->prepare($insert_query);
				$statement->execute();
			}

			echo 'Like bien pris en compte';
		}
	}
	if ($_POST["action"] == "like_user_list") { // récupération des noms des utilisateurs qui ont liké un post
		$query = "
		SELECT * FROM likes 
		INNER JOIN users 
		ON users.id = likes.id_user 
		WHERE likes.id_tweet = '" . $_POST["post_id"] . "'
		";

		$statement = $connect->prepare($query);

		$statement->execute();

		$result = $statement->fetchAll();

		foreach ($result as $row) {
			$output .= '
			<p>' . $row["nickname"] . '</p>
			';
		}

		echo $output;
	}
	if ($_POST["action"] == "update_notification_status") {
		$query = "
		UPDATE notifications 
		SET read_notification = 'yes' 
		WHERE notification_receiver_id = '" . $_SESSION["user_id"] . "'
		";

		$statement = $connect->prepare($query);
		$statement->execute();
	}
}
