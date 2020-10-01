<?php 
mb_internal_encoding("utf8");
session_start();

//mypage.phpからの導線以外は、リダイレクト
if(!isset($_POST['from_mypage'])) {
	header("Location:http://localhost/login_mypage/login_error.php");
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<title>マイページ登録</title>
	<link rel="stylesheet" type="text/css" href="mypage_hensyu.css">
</head>

<body>
	<header>
		<img src="4eachblog_logo.jpg">
		<div class="logout"><a href="log_out.php">ログアウト</a></div>
	</header>

	<main>
		<div class="box">
			<form action="mypage_update.php" method="post">
				<h2>会員情報</h2>
				<div class="hello">
					<?php echo "こんにちは!　".$_SESSION['name']."さん" ?>
				</div>
				<div class="profile_pic">
					<img src="<?php echo $_SESSION['picture']; ?>">
				</div>
				<div class="basic_info">
						<p>氏名：<input type="text" class="formbox" size="30" name="name" value="<?php echo $_SESSION['name'] ?>"></p>
						<p>メール：<input type="text" class="formbox" size="30" name="mail" pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" value="<?php echo $_SESSION['mail'] ?>"></p>
						<p>パスワード：<input type="password" class="formbox" size="30" name="password" id="password" pattern="^[a-z0-9]{6,}$" value="<?php echo $_SESSION['password'] ?>">
						<input type="hidden" value="<?php echo rand(1,10); ?>" name="from_mypage_hensyu"></p>
				</div>
				<div class="comments">
					<textarea class="formbox" name="comments" cols="75" rows="3"><?php echo $_SESSION['comments']; ?></textarea>
				</div>
				<div class="hensyubutton">
					<input type="submit" value="この内容に編集する" class="submit_button">
				</div>
			</form>
		</div>
	</main>

	<footer>
		© 2018 InterNous.inc ALL rights reserved
	</footer>
</body>

</html>
