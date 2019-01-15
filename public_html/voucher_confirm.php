<?php
require_once('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once('../vendor/autoload.php');

$loginCheck = new \lib\Common();
//　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
$loginCheck->getRequire_login();

// 伝票入力フォームから送られた値
$_SESSION['title'] = $_POST['title'];
$_SESSION['productNameArr'] = $_POST['productName'];
$_SESSION['numberArr'] = $_POST['number'];
$_SESSION['priceArr'] = $_POST['price'];

// 伝票確認フォームに情報を表示
$voucher = [
  '0' => [
    'productName' => $_SESSION['productNameArr'][0],
    'number' => $_SESSION['numberArr'][0],
    'price' => $_SESSION['priceArr'][0]
  ],
  '1' => [
    'productName' => $_SESSION['productNameArr'][1],
    'number' => $_SESSION['numberArr'][1],
    'price' => $_SESSION['priceArr'][1]
  ],
  '2' => [
    'productName' => $_SESSION['productNameArr'][2],
    'number' => $_SESSION['numberArr'][2],
    'price' => $_SESSION['priceArr'][2]
  ],
  '3' => [
    'productName' => $_SESSION['productNameArr'][3],
    'number' => $_SESSION['numberArr'][3],
    'price' => $_SESSION['priceArr'][3]
  ],
  '4' => [
    'productName' => $_SESSION['productNameArr'][4],
    'number' => $_SESSION['numberArr'][4],
    'price' => $_SESSION['priceArr'][4]
  ]

];

// 伝票入力フォームに入力されなかったら、配列の削除
if ($voucher['1']['productName'] == '') {
    unset($voucher['1']);
}

if ($voucher['2']['productName'] == '') {
    unset($voucher['2']);
}

if ($voucher['3']['productName'] == '') {
    unset($voucher['3']);
}

if ($voucher['4']['productName'] == '') {
    unset($voucher['4']);
}

//
// バリデーション
//

$validation = new \lib\Validation();
// タイトル空白チェック
$_SESSION['title_error'] = $validation->emptyCheck($_SESSION['title'], 'タイトル');
// 商品名 空白チェック
$_SESSION['productName_error'] = $validation->productNameCheck();
//個数 空白チェック
$_SESSION['number_error'] = $validation->numberCheck();
// 価格 空白チェック
$_SESSION['price_error'] = $validation->priceCheck();

// 2行目以降、全ての項目が埋められているかチェック
$_SESSION['secondLineErr'] =  $validation->emptyCherck('1');
$_SESSION['thirdLineErr'] =  $validation->emptyCherck('2');
$_SESSION['fourthLineErr'] =  $validation->emptyCherck('3');
$_SESSION['fifthLineErr'] =  $validation->emptyCherck('4');

// 2行目以降の半角チェック(個数、価格)
$_SESSION['secondCountErr'] = $validation->halfWidthCheck('2行目の個数', $_SESSION['numberArr'][1]);
$_SESSION['secondPriceErr'] = $validation->halfWidthCheck('2行目の価格', $_SESSION['priceArr'][1]);
$_SESSION['thirdCountErr'] = $validation->halfWidthCheck('3行目の個数', $_SESSION['numberArr'][2]);
$_SESSION['thirdPriceErr'] = $validation->halfWidthCheck('3行目の価格', $_SESSION['priceArr'][2]);
$_SESSION['fourthCountErr'] = $validation->halfWidthCheck('4行目の個数', $_SESSION['numberArr'][3]);
$_SESSION['fourthPriceErr'] = $validation->halfWidthCheck('4行目の価格', $_SESSION['priceArr'][3]);
$_SESSION['fifthCountErr'] = $validation->halfWidthCheck('5行目の個数', $_SESSION['numberArr'][4]);
$_SESSION['fifthPriceErr'] = $validation->halfWidthCheck('5行目の価格', $_SESSION['priceArr'][4]);

//　エラーがあれば「登録フォーム」にリダイレクト
if (isset($_SESSION['title_error']) || isset($_SESSION['productName_error']) || isset($_SESSION['number_error']) || isset($_SESSION['price_error'])
|| isset($_SESSION['secondLineErr']) || isset($_SESSION['thirdLineErr']) || isset($_SESSION['fourthLineErr'])
|| isset($_SESSION['fifthLineErr']) || isset($_SESSION['secondCountErr']) || $_SESSION['secondPriceErr']
|| isset($_SESSION['thirdCountErr']) || isset($_SESSION['thirdPriceErr']) || isset($_SESSION['fourthCountErr'])
|| isset($_SESSION['fourthPriceErr']) || isset($_SESSION['fifthCountErr']) || isset($_SESSION['fifthPriceErr'])) {
  header('Location:' . SITE_URL . '/voucher_signUp.php');
}

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('voucher_confirm.html', array(

  'session_name' => $_SESSION['session_name'],
  'title_error' => $_SESSION['title_error'],
  'productName_error' => $_SESSION['productName_error'],
  'number_error' => $_SESSION['number_error'],
  'price_error' => $_SESSION['price_error'],
  'secondLineErr' => $_SESSION['secondLineErr'],
  'thirdLineErr' => $_SESSION['thirdLineErr'],
  'fourthLineErr' => $_SESSION['fourthLineErr'],
  'fifthLineErr' => $_SESSION['fifthLineErr'],
  'title' => $_SESSION['title'],
  'token' => sha1(session_id()),
  'voucher' => $voucher
));
