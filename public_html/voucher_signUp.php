<?php
require_once('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once('../vendor/autoload.php');

$loginCheck = new \lib\Common();
//　ログアウトの状態で直接アクセスされた場合、ログイン画面に遷移
$loginCheck->getRequire_login();

//　セッション変数の保持
isset($_SESSION['productNameArr'][0]) ?  $_SESSION['productNameArr'][0] : $_SESSION['productNameArr'][0] = '';
isset($_SESSION['numberArr'][0]) ?  $_SESSION['numberArr'][0] : $_SESSION['numberArr'][0] = '';
isset($_SESSION['priceArr'][0]) ?  $_SESSION['priceArr'][0] : $_SESSION['priceArr'][0] = '';
isset($_SESSION['productNameArr'][1]) ?  $_SESSION['productNameArr'][1] : $_SESSION['productNameArr'][1] = '';
isset($_SESSION['numberArr'][1]) ?  $_SESSION['numberArr'][1] : $_SESSION['numberArr'][1] = '';
isset($_SESSION['priceArr'][1]) ?  $_SESSION['priceArr'][1] : $_SESSION['priceArr'][1] = '';
isset($_SESSION['productNameArr'][2]) ?  $_SESSION['productNameArr'][2] : $_SESSION['productNameArr'][2] = '';
isset($_SESSION['numberArr'][2]) ?  $_SESSION['numberArr'][2] : $_SESSION['numberArr'][2] = '';
isset($_SESSION['priceArr'][2]) ?  $_SESSION['priceArr'][2] : $_SESSION['priceArr'][2] = '';
isset($_SESSION['productNameArr'][3]) ?  $_SESSION['productNameArr'][3] : $_SESSION['productNameArr'][3] = '';
isset($_SESSION['numberArr'][3]) ?  $_SESSION['numberArr'][3] : $_SESSION['numberArr'][3] = '';
isset($_SESSION['priceArr'][3]) ?  $_SESSION['priceArr'][3] : $_SESSION['priceArr'][3] = '';
isset($_SESSION['productNameArr'][4]) ?  $_SESSION['productNameArr'][4] : $_SESSION['productNameArr'][4] = '';
isset($_SESSION['numberArr'][4]) ?  $_SESSION['numberArr'][4] : $_SESSION['numberArr'][4] = '';
isset($_SESSION['priceArr'][4]) ?  $_SESSION['priceArr'][4] : $_SESSION['priceArr'][4] = '';
isset($_SESSION['productName_error']) ?  $_SESSION['productName_error']: $_SESSION['productName_error'] = '';
isset($_SESSION['number_error']) ?  $_SESSION['number_error']: $_SESSION['number_error'] = '';
isset($_SESSION['price_error']) ?  $_SESSION['price_error']: $_SESSION['price_error'] = '';
isset($_SESSION['secondLineErr']) ?  $_SESSION['secondLineErr']: $_SESSION['secondLineErr'] = '';
isset($_SESSION['thirdLineErr']) ?  $_SESSION['thirdLineErr']: $_SESSION['thirdLineErr'] = '';
isset($_SESSION['fourthLineErr']) ?  $_SESSION['fourthLineErr']: $_SESSION['fourthLineErr'] = '';
isset($_SESSION['fifthLineErr']) ?  $_SESSION['fifthLineErr']: $_SESSION['fifthLineErr'] = '';
isset($_SESSION['secondCountErr']) ?  $_SESSION['secondCountErr']: $_SESSION['secondCountErr'] = '';
isset($_SESSION['secondPriceErr']) ?  $_SESSION['secondPriceErr']: $_SESSION['secondPriceErr'] = '';
isset($_SESSION['thirdCountErr']) ?  $_SESSION['thirdCountErr']: $_SESSION['thirdCountErr'] = '';
isset($_SESSION['thirdPriceErr']) ?  $_SESSION['thirdPriceErr']: $_SESSION['thirdPriceErr'] = '';
isset($_SESSION['fourthCountErr']) ?  $_SESSION['fourthCountErr']: $_SESSION['fourthCountErr'] = '';
isset($_SESSION['fourthPriceErr']) ?  $_SESSION['fourthPriceErr']: $_SESSION['fourthPriceErr'] = '';
isset($_SESSION['fifthCountErr']) ?  $_SESSION['fifthCountErr']: $_SESSION['fifthCountErr'] = '';
isset($_SESSION['fifthPriceErr']) ?  $_SESSION['fifthPriceErr']: $_SESSION['fifthPriceErr'] = '';
isset($_SESSION['title']) ?  $_SESSION['title']: $_SESSION['title'] = '';
isset($_SESSION['title_error']) ? $_SESSION['title_error'] : $_SESSION['title_error'] = '';

// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('voucher_signUp.html', array(

  'session_name' => $_SESSION['session_name'],
  'title_error' => $_SESSION['title_error'],
  'title' => $_SESSION['title'],
  'productName_error' => $_SESSION['productName_error'],
  'number_error' => $_SESSION['number_error'],
  'price_error' => $_SESSION['price_error'],
  'secondLineErr' => $_SESSION['secondLineErr'],
  'thirdLineErr' => $_SESSION['thirdLineErr'],
  'fourthLineErr' => $_SESSION['fourthLineErr'],
  'fifthLineErr' => $_SESSION['fifthLineErr'],
  'secondCountErr' => $_SESSION['secondCountErr'],
  'secondPriceErr' => $_SESSION['secondPriceErr'],
  'thirdCountErr' => $_SESSION['thirdCountErr'],
  'thirdPriceErr' => $_SESSION['thirdPriceErr'],
  'fourthCountErr' => $_SESSION['fourthCountErr'],
  'fourthPriceErr' => $_SESSION['fourthPriceErr'],
  'fifthCountErr' => $_SESSION['fifthCountErr'],
  'fifthPriceErr' => $_SESSION['fifthPriceErr'],
  'productNameArr0' => $_SESSION['productNameArr'][0],
  'numberArr0' => $_SESSION['numberArr'][0],
  'priceArr0' => $_SESSION['priceArr'][0],
  'productNameArr1' => $_SESSION['productNameArr'][1],
  'numberArr1' => $_SESSION['numberArr'][1],
  'priceArr1' => $_SESSION['priceArr'][1],
  'productNameArr2' => $_SESSION['productNameArr'][2],
  'numberArr2' => $_SESSION['numberArr'][2],
  'priceArr2' => $_SESSION['priceArr'][2],
  'productNameArr3' => $_SESSION['productNameArr'][3],
  'numberArr3' => $_SESSION['numberArr'][3],
  'priceArr3' => $_SESSION['priceArr'][3],
  'productNameArr4' => $_SESSION['productNameArr'][4],
  'numberArr4' => $_SESSION['numberArr'][4],
  'priceArr4' => $_SESSION['priceArr'][4],
  'token' => sha1(session_id())
));
