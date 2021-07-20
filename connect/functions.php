<?php
$connect = new PDO("mysql:host=localhost;dbname=tweet-academy-bdd", "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

function Count_notification($connect, $receiver_id)
{
	$query = "
	SELECT COUNT(notification_id) as total 
	FROM notifications 
	WHERE notification_receiver_id = '" . $receiver_id . "' 
	AND read_notification = 'no'
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	foreach ($result as $row) {
		return $row["total"];
	}
}

function Load_notification($connect, $receiver_id)
{
	$query = "
	SELECT * FROM notifications
	WHERE notification_receiver_id = '" . $receiver_id . "' 
	ORDER BY notification_id DESC
	";
	$statement = $connect->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	$total_row = $statement->rowCount();

	$output = '';

	if ($total_row > 0) {
		foreach ($result as $row) {
			$output .= '<li><a href="#">' . $row["notification_text"] . '</a></li>';
		}
	}
	return $output;
}

function toHashtag($content)
{
	global $connect;
	if ($content) {

		$exploded = explode(' ', $content);
		$countEx = count($exploded);
		for ($i = 0; $i <=  $countEx; $i++) {
			if (substr($exploded[$i], 0, 1) == '#') {
				$exploded[$i] = "<a href='/views/search_results.php?search=" . substr($exploded[$i], 1) . "'>" . $exploded[$i] . "</a>";
			}
		}

		$finalContent = implode(' ', $exploded);

		if ($finalContent) {
			$queryHtag = 'INSERT INTO hashtags (content) VALUES (?)';
			$statement = $connect->prepare($queryHtag);
			$statement->execute(array($finalContent));
			$inserted = $connect->lastInsertId();

			return [$finalContent, $inserted];
		}
	}
}

function toMention($content)
{
	global $connect;
	if ($content) {
		$exploded = explode(' ', $content);
		$countEx = count($exploded);
		for ($i = 0; $i < $countEx; $i++) {
			if (substr($exploded[$i], 0, 1) == '@') {
				$exploded[$i] = "<a href='/views/user_tweets.php?data=" . substr($exploded[$i], 1) . "'>" . $exploded[$i] . "</a>";
			}
		}

		$finalContent = implode(' ', $exploded);

		return $finalContent;
	}
}


function Get_user_name($connect, $user_id)
{
	$query = "
	SELECT nickname FROM users
	WHERE id = '" . $user_id . "'
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	foreach ($result as $row) {
		return $row["nickname"];
	}
}

function count_retweet($connect, $post_id)
{
	$query = "
	SELECT * FROM link_user_tweet
	WHERE id_tweet = '" . $post_id . "'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

function count_comment($connect, $post_id)
{
	$query = "
	SELECT * FROM comments 
	WHERE id_tweet = '" . $post_id . "'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

function bouton_follow($connect, $sender_id, $receiver_id)  // bouton follow 
{
	$query = "
	SELECT * FROM link_user_follower_user_following
	WHERE id_follower = '" . $sender_id . "' 
	AND id_following = '" . $receiver_id . "'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$total_row = $statement->rowCount();
	$output = '';
	if ($total_row > 0) {
		$output = '<button type="button" name="follow_button" class="btn btn-warning action_button" data-action="unfollow" data-id_follower="' . $sender_id . '"> Suivi</button>';
	} else {
		$output = '<button type="button" name="follow_button" class="btn btn-info action_button" data-action="follow" data-id_follower="' . $sender_id . '"><i class="glyphicon glyphicon-plus"></i> Suivre</button>';
	}
	return $output;
}

function total_likes($connect, $post_id)
{
	$query = "
	SELECT * FROM likes 
	WHERE id_tweet = '" . $post_id . "'
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	return $statement->rowCount();
}

function Get_user_id($connect, $username)
{
	$query = "
	SELECT id FROM users 
	WHERE nickname = '" . $username . "'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach ($result as $row) {
		return $row["id"];
	}
}

function count_total_post_like($connect, $post_id)
{
	$query = "
	SELECT * FROM likes 
	WHERE id_tweet = '" . $post_id . "'
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	return $statement->rowCount();
}
