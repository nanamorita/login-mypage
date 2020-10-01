<?php 
mb_internal_encoding("utf8");
session_start();

//DB接続・try catch文
try {
	$pdo = new PDO("mysql:dbname=lesson01;host=localhost;","root","");
	} catch(PDOException $e) {
		die("<p>申し訳ございません。現在サーバーが混み合っており一時的にアクセスができません。,br>しばらくしてから再度ログインをしてください。</p>
		<a href='http://localhost/login_mypage/login.php'>ログイン画面へ</a>");
	}

	//preparedステートメントでSQLをセット
	$stmt = $pdo->prepare("UPDATE login_mypage SET name = ?, mail = ?, password = ?, comments = ? WHERE id = ?");

	//bindValueメソッドでパラメータをセット
	$stmt->bindValue(1,$_POST['name']);
	$stmt->bindValue(2,$_POST['mail']);
	$stmt->bindValue(3,$_POST['password']);
	$stmt->bindValue(4,$_POST['comments']);
	$stmt->bindValue(5,$_SESSION['id']);

	//executeでクエリを実行
	$stmt->execute();

	//preparedステートメントでSQLをセット(更新された情報をDBから取得)
	$stmt = $pdo->prepare("SELECT * FROM login_mypage WHERE mail = ? && password = ?");

    //bindValueメソッドでパラメータをセット
	$stmt->bindValue(1,$_POST['mail']);
	$stmt->bindValue(2,$_POST['password']);

	//executeでクエリを実行
	$stmt->execute();

	//データベースを切断
	$pdo = NULL;

	//fetch・while文でデータを取得し、sessionに代入
	while ($row = $stmt->fetch()) {
		$_SESSION['id'] = $row['id'];
		$_SESSION['name'] = $row['name'];
		$_SESSION['mail'] = $row['mail'];
		$_SESSION['password'] = $row['password'];
		$_SESSION['picture'] = $row['picture'];
		$_SESSION['comments'] = $row['comments'];
	}

header("Location:http://localhost/login_mypage/mypage.php");

?>