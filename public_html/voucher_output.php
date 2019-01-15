<?php
require_once('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once('../vendor/autoload.php');

$loginCheck = new \lib\Common();
//　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
$loginCheck->getRequire_login();

//
// DBに登録
//

$voucherSignup = new lib\Signup();
// タイトルテーブルへの登録
$voucherSignup->VoucherTitle();
// 商品テーブルの登録
$voucherSignup->VoucherItem();
// 商品単価テーブルの登録
$voucherSignup->VoucherUnitPrice();


//
// 伝票の表示
//
$row = [];
$_SESSION['consumptionTax'] = '';
$_SESSION['total'] = '';
$titleNo = $_SESSION['titleNo'];
$db = new lib\Model();
$stat = $db->getDbh();

$sql = "SELECT ItemName, UnitPrice, Count, subtotal, consumptionTax from Item
JOIN UnitPrice ON Item.ID = UnitPrice.ID
WHERE Item.TitleNo = $titleNo";

$statement = $stat->query($sql);

foreach ($statement as $values) {
  $row[] = $values;
  // 合計消費税を計算
  $_SESSION['consumptionTax'] += $values['consumptionTax'];
  // 合計を計算
  $_SESSION['total'] += $values['subtotal'] + $values['consumptionTax'];
}

// 消費税の合計、合計金額の計算
$voucherSignup->totalInsert();

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('voucher_output.html', array(

  'total' => $_SESSION['total'],
  'consumptionTax' => $_SESSION['consumptionTax'],
  'row' => $row,
  'session_name' => $_SESSION['session_name'],
  'token' => sha1(session_id())
));
