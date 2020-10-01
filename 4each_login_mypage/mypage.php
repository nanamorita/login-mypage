<?php 
mb_internal_encoding("utf8");
session_start();

//session配列がなかった場合、postデータ(mailとパスワード)を使用しデータベースから照合
if (empty($_SESSION['id'])) {

	try {
	$pdo = new PDO("mysql:dbname=lesson01;host=localhost;","root","");
	} catch(PDOException $e) {
		die("<p>申し訳ございません。現在サーバーが混み合っており一時的にアクセスができません。,br>しばらくしてから再度ログインをしてください。</p>
		<a href='http://localhost/login_mypage/login.php'>ログイン画面へ</a>");
	}

	//preparedステートメントでSQLをセット
	$stmt = $pdo->prepare("SELECT * FROM login_mypage WHERE mail = ? && password = ?");

	//bindValueメソッドでパラメータをセット
	$stmt->bindValue(1,$_POST['mail']);
	$stmt->bindValue(2,$_POST['password']);

	//executeでクエリを実行
	$stmt->execute();

	//データベースを切断
	$pdo = NULL;

	//fetch・while文で照合したデータをsessionに格納
	while ($row = $stmt->fetch()) {
		$_SESSION['id'] = $row['id'];
		$_SESSION['name'] = $row['name'];
		$_SESSION['mail'] = $row['mail'];
		$_SESSION['password'] = $row['password'];
		$_SESSION['picture'] = $row['picture'];
		$_SESSION['comments'] = $row['comments'];
	}

	//データ取得ができずにsessionがなければ、リダイレクト（エラー画面へ）
	if (empty($_SESSION['id'])) {
		header("Location:http://localhost/login_mypage/login_error.php");
	}
	
	//「ログイン状態を保持する」にチェックが入っていた場合、sessionに保存する
	if (!empty($_POST['login_keep'])) {
		$_SESSION['login_keep'] = $_POST['login_keep'];
	}
}

//true: ログインに成功、かつ「ログイン状態を保持する」にチェックが入っていた場合、Cookieにデータを保存する
//false: 「ログイン状態を保持する」にチェックが入っていない場合、Cookieからデータを削除する
if (!empty($_SESSION['id']) && !empty($_SESSION['login_keep'])) {
	setcookie('mail',$_SESSION['mail'],time()+60*60*24*7);
	setcookie('password',$_SESSION['password'],time()+60*60*24*7);
	setcookie('login_keep',$_SESSION['login_keep'],time()+60*60*24*7);
} else if (empty($_SESSION['login_keep'])) {
	setcookie('mail','',time()-1);
	setcookie('password','',time()-1);
	setcookie('login_keep','',time()-1);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<title>マイページ登録</title>
	<link rel="stylesheet" type="text/css" href="mypage.css">
</head>

<body>
	<header>
		<img src="4eachblog_logo.jpg">
		<div class="logout"><a href="log_out.php">ログアウト</a></div>
	</header>

	<main>
		<div class="box">
				<h2>会員情報</h2>
				<div class="hello">
					<?php echo "こんにちは!　".$_SESSION['name']."さん" ?>
				</div>
				<div class="profile_pic">
					<img src="<?php echo $_SESSION['picture']; ?>">
				</div>
				<div class="basic_info">
					<p>氏名：<?php echo $_SESSION['name']; ?></p>
					<p>メール：<?php echo $_SESSION['mail']; ?></p>
					<p>パスワード：<?php echo $_SESSION['password']; ?></p>
				</div>
				<div class="comments">
					<?php echo $_SESSION['comments']; ?>
				</div>
				<!-- mypage_hensyu.phpへのアクセスは、mypage.php以外からは拒否 -->
				<form action="mypage_hensyu.php" method="post" class="form_center">
					<input type="hidden" value="<?php echo rand(1,10); ?>" name="from_mypage">
					<div class="hensyubutton">
						<input type="submit" value="編集する" class="submit_button">
					</div>
				</form>	　
			</div>
	</main>

	<footer>
		© 2018 InterNous.inc ALL rights reserved
	</footer>
</body>

</html>
