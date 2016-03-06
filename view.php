<?php

session_start();
require('dbconnect.php');

if(empty($_REQUEST['id'])){
	header('Location: index.php');
}

//投稿を取得する

$sql = sprintf('SELECT m.username, m.icon, p.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=%d ORDER BY p.created DESC',
	mysql_real_escape_string($_REQUEST['id'])
	);
$posts = mysql_query($sql) or die(mysql_error());

?>

<!doctype>

<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/style.css">
<title>タイムライン｜My Checker</title>
</head>

<body>

<div id="content">

	<?php
	if ($post = mysql_fetch_assoc($posts)):
	?>

	<div id="head">
		<img src="member_icon/<?php echo htmlspecialchars($post['icon'], ENT_QUOTES, 'utf-8'); ?>" width="45" height="45" alt="<?php echo htmlspecialchars($post['username'], ENT_QUOTES, 'utf-8'); ?>" >
			 <?php echo htmlspecialchars($post['username'], ENT_QUOTES, 'utf-8'); ?>
	</div><!-- head -->

	<p>

		<?php echo htmlspecialchars($post['message'], ENT_QUOTES, 'utf-8'); ?>(<?php echo htmlspecialchars($post['created'], ENT_QUOTES, 'utf-8'); ?>)<br>

		<img src="./member_picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES, 'utf-8'); ?>" width="400px" height="380px" /><br>

	</p>

	
	<p>&laquo;<a href="index.php">タイムラインにもどる</a></p>
	

	<?php
	else:
	?>
		<p>その投稿は削除されたか、URLが間違えています。</p>

	<?php
	endif;
	?>

</div><!-- content -->
</body>
</html>