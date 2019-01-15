<?php

require_once ('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once ('../vendor/autoload.php');

$loginCheck = new \lib\Common();
//　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
$loginCheck->getRequire_login();

//
// 検索条件をセッション変数に入れる
//
$_SESSION['searchId'] = filter_input(INPUT_POST, 'id');
$_SESSION['searchName'] = filter_input(INPUT_POST, 'name');
$_SESSION['radio'] = filter_input(INPUT_POST, 'radio');

// オブジェクトの作成
$user_complete = new lib\Signup();
// DB接続
$searchResult = $user_complete->search();

// 検索結果がない場合
if (empty($searchResult)) {
  header('Location:' . SITE_URL . '/searchEmpty.php');
  exit;
}

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('search_results.html', array(

'session_name' => $_SESSION['session_name'],
'searchResult' => $searchResult,
'token' => sha1(session_id())
));
