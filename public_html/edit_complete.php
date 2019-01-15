<?php

require_once ('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once ('../vendor/autoload.php');

$loginCheck = new \lib\Common();
//　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
$loginCheck->getRequire_login();
// ログイン状態で直接アクセスされた場合社員一覧画面に遷移
$loginCheck->getRequire_unlogin();

// 二重投稿防止
if (!$_POST['duplication_token'] == $_SESSION['duplication_token']) {
  header('Location:' . SITE_URL . '/editDuplicateError.php');
  exit;
}

// セッション変数を解放し、ブラウザの戻るボタンで戻った場合に備える。
unset($_SESSION['duplication_token']);

// オブジェクトの作成
$user_complete = new lib\Signup();
// DB接続
$user_complete->update();

unset($_SESSION['No']);
unset($_SESSION['name']);
unset($_SESSION['email']);
unset($_SESSION['password']);

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('edit_complete.html', array(

'session_name' => $_SESSION['session_name']
));
