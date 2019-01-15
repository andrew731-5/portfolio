<?php

require_once ('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once ('../vendor/autoload.php');

$loginCheck = new \lib\Common();
//　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
$loginCheck->getRequire_login();

// バリデーションの実行
$login = new lib\Login();
$login->login();

//
// 社員一覧の取得
//
$db = new lib\Model();
$stat = $db->getDbh();

$sql = "SELECT No, Name, MailAddress from Employee WHERE DeleteFlg is NULL";
$statement = $stat->query($sql);

foreach ($statement as $value) {
  $row[] = $value;
}

//
//バリデーションエラー(セッション)の解除
//
unset($_SESSION['noError']);
unset($_SESSION['nameError']);
unset($_SESSION['emailError']);
unset($_SESSION['passwordError']);
unset($_SESSION['duplication']);
unset($_SESSION['email_duplication']);
unset($_SESSION['no_duplication']);
unset($_SESSION['No']);
unset($_SESSION['name']);
unset($_SESSION['email']);
unset($_SESSION['password']);

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('worker_list.html', array(

'row' => $row,
'session_name' => $_SESSION['session_name'],
'token' => sha1(session_id())
));
