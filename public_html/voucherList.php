<?php

require_once('../config/config.php');
// Composerで作成されたautoload.phpを読み込む
require_once('../vendor/autoload.php');

//
// 該当する伝票の抽出
//
    $titleNo = filter_input(INPUT_POST, 'TitleNo');

    try {
        // 伝票の抽出
        $db = new lib\Model();
        // Db接続
        $stat = $db->getDbh();
        // クエリの作成(商品名、個数、価格、小計)
        $sql = "SELECT ItemName, UnitPrice, Count, subtotal, consumptionTax from Item
        JOIN UnitPrice ON Item.ID = UnitPrice.ID
        WHERE Item.TitleNo = $titleNo";
        // 合計消費税、合計金額の抽出
        $totlaSql = "SELECT totalTax, total FROM Title WHERE TitleNo = $titleNo";

        // クエリの実行
        $statement = $stat->query($sql);
        $totals = $stat->query($totlaSql);
        // 結果を変数に代入
        foreach ($statement as $value) {
            $row[] = $value;
        }
        foreach ($totals as $value) {
          $total[] = $value;
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }




// Twig_Loader_Filesystemを使う。そのファイルからのtemplatesディレクトリを指定。(相対パス)
$loader = new Twig_Loader_Filesystem('../templates');
// $loaderをTwigの環境設定として Twig instance を生成
$twig = new Twig_Environment($loader);

echo $twig->render('voucherList.html', array(

  'total' => $total,
  'row' => $row,
  'session_name' => $_SESSION['session_name']
));
