<?php
require_once('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once('../vendor/autoload.php');

$loginCheck = new \lib\Common();
//　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
$loginCheck->getRequire_login();

//
// 伝票一覧の取得
//
$db = new lib\Model();
$stat = $db->getDbh();

$sql = "SELECT TitleNo, Title, RegisteredPerson from Title";

$statement = $stat->query($sql);

foreach ($statement as $values) {
  $row[] = $values;
}

// セッション解除
unset($_SESSION['productName']);
unset($_SESSION['number']);
unset($_SESSION['price']);
unset($_SESSION['productName_error']);
unset($_SESSION['number_error']);
unset($_SESSION['price_error']);
unset($_SESSION['secondLineErr']);
unset($_SESSION['thirdLineErr']);
unset($_SESSION['fourthLineErr']);
unset($_SESSION['fifthLineErr']);
unset($_SESSION['secondCountErr']);
unset($_SESSION['secondPriceErr']);
unset($_SESSION['thirdCountErr']);
unset($_SESSION['thirdPriceErr']);
unset($_SESSION['fourthCountErr']);
unset($_SESSION['fourthPriceErr']);
unset($_SESSION['fifthCountErr']);
unset($_SESSION['fifthPriceErr']);
unset($_SESSION['productNameArr'][0]);
unset($_SESSION['numberArr'][0]);
unset($_SESSION['priceArr'][0]);
unset($_SESSION['productNameArr'][1]);
unset($_SESSION['numberArr'][1]);
unset($_SESSION['priceArr'][1]);
unset($_SESSION['productNameArr'][2]);
unset($_SESSION['numberArr'][2]);
unset($_SESSION['priceArr'][2]);
unset($_SESSION['productNameArr'][3]);
unset($_SESSION['numberArr'][3]);
unset($_SESSION['priceArr'][3]);
unset($_SESSION['productNameArr'][4]);
unset($_SESSION['numberArr'][4]);
unset($_SESSION['priceArr'][4]);
unset($_SESSION['title']);
unset($_SESSION['consumptionTax']);
unset($_SESSION['total']);

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('voucher_list.html', array(

  'row' => $row,
  'session_name' => $_SESSION['session_name'],
  'token' => sha1(session_id())
));
