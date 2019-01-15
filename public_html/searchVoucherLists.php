<?php

require_once('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once('../vendor/autoload.php');

//
// 検索条件をセッション変数に入れる
//
$_SESSION['itemName'] = filter_input(INPUT_POST, 'itemName');

// オブジェクトの作成
$user_complete = new lib\Signup();
// DB接続
$searchResult = $user_complete->voucherSearch();

// 検索結果がない場合
if (empty($searchResult)) {
  header('Location:' . SITE_URL . '/searchVoucherEmpty.php');
  exit;
}

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('searchVoucherLists.html', array(

  'searchResult' => $searchResult,
  'session_name' => $_SESSION['session_name']
));
