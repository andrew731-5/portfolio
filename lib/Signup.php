<?php

namespace lib;

class Signup extends Model {

    //
    //ユーザー作成
    //
    public function create() {

        //プリペアドステートメント
        $stmt = $this->dbh->prepare("INSERT INTO Employee (No, name, MailAddress, Password)
        VALUES (:No, :name, :MailAddress, :password)");

        if ($stmt) {

            //プレースホルダへ実際の値を設定する
            if (isset($_SESSION['No']) || isset($_SESSION['name']) || isset($_SESSION['MailAddress'])
            || isset($_SESSION['Password'])) {
                $stmt->bindValue(':No', $_SESSION["No"], \PDO::PARAM_STR);
                $stmt->bindValue(':name', $_SESSION["name"], \PDO::PARAM_STR);
                $stmt->bindValue(':MailAddress', $_SESSION["email"], \PDO::PARAM_STR);
                $stmt->bindValue(':password', password_hash($_SESSION['password'], PASSWORD_DEFAULT), \PDO::PARAM_STR);
            }
        }

        // 実行する
        $stmt->execute();
    }

    //
    // ユーザー情報のアップデート
    //
    public function update() {
        try {

            //プリペアドステートメント
            $stmt = $this->dbh->prepare("UPDATE Employee SET No = :No, name = :name, MailAddress = :MailAddress, password = :password
            WHERE No = :updateNo");

            if ($stmt) {

                //プレースホルダへ実際の値を設定する
                if (isset($_SESSION['No']) || isset($_SESSION['name']) || isset($_SESSION['MailAddress'])
                || isset($_SESSION['Password'])) {
                    $stmt->bindValue(':No', $_SESSION["No"], \PDO::PARAM_STR);
                    $stmt->bindValue(':name', $_SESSION["name"], \PDO::PARAM_STR);
                    $stmt->bindValue(':MailAddress', $_SESSION["email"], \PDO::PARAM_STR);
                    $stmt->bindValue(':password', password_hash($_SESSION['password'], PASSWORD_DEFAULT), \PDO::PARAM_STR);
                    $stmt->bindValue(':updateNo', $_SESSION['updateNo'], \PDO::PARAM_STR);
                }
            }

            //実行する
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    //
    // 検索(伝票一覧)
    //
    public function voucherSearch() {

      try {

        if (!empty($_SESSION['itemName'])) {

          $itemName = $_SESSION['itemName'];
          $searchItemName = "%$itemName%";

          // TitleNoの検索
          $stmt = $this->dbh->prepare("SELECT TitleNo FROM Item WHERE ItemName LIKE :itemName");
          $stmt->bindValue(':itemName', $searchItemName);

          if ($stmt->execute()) {
            // TitleNoの取得
              while ($row = $stmt->fetch()) {
                  $_SESSION['searchTitleNo'] = $row['TitleNo'];
              }

              // TitieNoを元にタイトル名などを検索
              $stmt = $this->dbh->prepare("SELECT TitleNo, Title, RegisteredPerson FROM Title
                WHERE TitleNo = :titleNo");

              $stmt->bindValue(':titleNo', $_SESSION['searchTitleNo']);
              if ($stmt->execute()) {
                  while ($row = $stmt->fetch()) {
                      $rows[] = $row;
                  }
                  return $rows;
                  header('Location:' . SITE_URL . '/searchVoucherOneList.php');
                   unset($_SESSION['searchTitleNo']);
                   unset($_SESSION['itemName']);
                   unset($_SESSION['TitleNo']);
          }

        }
      } else if(empty($_SESSION['itemName'])) {

        // もし商品名が空白だったら全件検索
        $stmt = $this->dbh->prepare("SELECT TitleNo, Title, RegisteredPerson FROM Title");

        if ($stmt->execute()) {

          while ($row = $stmt->fetch()) {
                $rows[] = $row;
              }
              return $rows;

      unset($_SESSION['searchTitleNo']);
      unset($_SESSION['itemName']);
      unset($_SESSION['TitleNo']);
    }
  }

      } catch(PDOException $e) {
        echo $e->getMessage();
      }
    }


    //
    // 検索(社員一覧)
    //
    public function search() {
        try {

            //
            // IDと名前が空白のとき全件出力
            //
            if (empty($_SESSION['searchId']) && empty($_SESSION['searchName'])) {
                $stmt = $this->dbh->prepare("SELECT No, Name, MailAddress FROM Employee WHERE DeleteFlg is NULL");

                if ($stmt->execute()) {

                    while ($row = $stmt->fetch()) {
                        $rows[] = $row;
                    }
                }
                unset($_SESSION['searchId']);
                unset($_SESSION['searchName']);
                return $rows;
            }



            //
            // IDと名前が両方埋まっているかつ、ラジオボタンが「and」のとき
            //
            if (!empty($_SESSION['searchId']) && !empty($_SESSION['searchName'] && $_SESSION['radio'] == 'and')) {
                $stmt = $this->dbh->prepare("SELECT No, Name, MailAddress FROM Employee
          WHERE No LIKE :No AND Name LIKE :Name");

                $searchId = $_SESSION['searchId'];
                $searchName = $_SESSION['searchName'];
                $likeSearchId = "%$searchId%";
                $likesearchName = "%$searchName%";

                if ($stmt) {
                    //プレースホルダへ実際の値を設定する
                    $stmt->bindValue(':No', $likeSearchId);
                    $stmt->bindValue(':Name', $likesearchName);
                }

                if ($stmt->execute()) {

                    while ($row = $stmt->fetch()) {
                        $rows[] = $row;
                    }
                    return $rows;
                }
                unset($_SESSION['searchId']);
                unset($_SESSION['searchName']);
            }


            //
            //　IDと名前が両方埋まっているかつ、ラジオボタンが「or」のとき
            //
            if (!empty($_SESSION['searchId']) && !empty($_SESSION['searchName'] && $_SESSION['radio'] == 'or')) {
                $stmt = $this->dbh->prepare("SELECT No, Name, MailAddress FROM Employee
          WHERE No LIKE :No OR Name LIKE :Name");

                $searchId = $_SESSION['searchId'];
                $searchName = $_SESSION['searchName'];
                $likeSearchId = "%$searchId%";
                $likesearchName = "%$searchName%";

                if ($stmt) {
                    //プレースホルダへ実際の値を設定する
                    $stmt->bindValue(':No', $likeSearchId);
                    $stmt->bindValue(':Name', $likesearchName);
                }

                if ($stmt->execute()) {

                    while ($row = $stmt->fetch()) {
                        $rows[] = $row;
                    }
                    return $rows;
                }
                unset($_SESSION['searchId']);
                unset($_SESSION['searchName']);
            }


            //
            // IDのみ埋まっている場合
            //
            if (!empty($_SESSION['searchId'])) {
                $stmt = $this->dbh->prepare("SELECT No, Name, MailAddress FROM Employee
          WHERE No LIKE :No");

                $searchId = $_SESSION['searchId'];
                $likeSearchId = "%$searchId%";

                if ($stmt) {
                    //プレースホルダへ実際の値を設定する
                    $stmt->bindValue(':No', $likeSearchId);
                }

                if ($stmt->execute()) {

                    while ($row = $stmt->fetch()) {
                        $rows[] = $row;
                    }
                    return $rows;
                }
                unset($_SESSION['searchId']);
            }

            //
            // 名前のみ埋まって場合
            //
            if (!empty($_SESSION['searchName'])) {
                $stmt = $this->dbh->prepare("SELECT No, Name, MailAddress FROM Employee
          WHERE Name LIKE :Name");

                $searchName = $_SESSION['searchName'];
                $likesearchName = "%$searchName%";

                if ($stmt) {
                    //プレースホルダへ実際の値を設定する
                    $stmt->bindValue(':Name', $likesearchName);
                }

                if ($stmt->execute()) {

                    while ($row = $stmt->fetch()) {
                        $rows[] = $row;
                    }
                    return $rows;
                }
                unset($_SESSION['searchName']);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    //
    // 伝票登録(タイトルの登録)
    //
    public function VoucherTitle() {

    //プリペアドステートメント
        $stmt = $this->dbh->prepare("INSERT INTO Title (Title, RegisteredPerson)
    VALUES (:Title, :RegisteredPerson)");

        //プレースホルダへ実際の値を設定する
        $stmt->bindValue(':Title', $_SESSION["title"], \PDO::PARAM_STR);
        $stmt->bindValue(':RegisteredPerson', $_SESSION["session_name"], \PDO::PARAM_STR);

        // 実行する
        $stmt->execute();
        // INSERTされたデータのIDを取得
        $_SESSION['titleNo'] = $this->dbh->lastInsertId();
    }

    //
    // 消費税の合計、合計金額の計算
    //
    public function totalInsert() {

      //プリペアドステートメント
          $stmt = $this->dbh->prepare("UPDATE Title SET totalTax = :totalTax, total = :total
          WHERE TitleNo = :titleNo");

          //プレースホルダへ実際の値を設定する
          $stmt->bindValue(':totalTax', $_SESSION['consumptionTax'], \PDO::PARAM_INT);
          $stmt->bindValue(':total', $_SESSION['total'], \PDO::PARAM_INT);
          $stmt->bindValue(':titleNo', $_SESSION['titleNo'], \PDO::PARAM_INT);

          // 実行する
          $stmt->execute();
    }


    //
    // 伝票登録(商品名の登録)
    //
    public function VoucherItem() {

      //プリペアドステートメント
        $stmtItem0 = $this->dbh->prepare("INSERT INTO Item (ItemName, TitleNo) VALUES (:ItemName0, :TitleNo)");
        $stmtItem1 = $this->dbh->prepare("INSERT INTO Item (ItemName, TitleNo) VALUES (:ItemName1, :TitleNo)");
        $stmtItem2 = $this->dbh->prepare("INSERT INTO Item (ItemName, TitleNo) VALUES (:ItemName2, :TitleNo)");
        $stmtItem3 = $this->dbh->prepare("INSERT INTO Item (ItemName, TitleNo) VALUES (:ItemName3, :TitleNo)");
        $stmtItem4 = $this->dbh->prepare("INSERT INTO Item (ItemName, TitleNo) VALUES (:ItemName4, :TitleNo)");

        //プレースホルダへ実際の値を設定する
        if (isset($_SESSION['productNameArr'][0])) {
            $stmtItem0->bindValue(':ItemName0', $_SESSION['productNameArr'][0], \PDO::PARAM_STR);
            $stmtItem0->bindValue(':TitleNo', $_SESSION['titleNo'], \PDO::PARAM_STR);
            $stmtItem0->execute();
        }

        if (isset($_SESSION['productNameArr'][1]) && !empty($_SESSION['productNameArr'][1])) {
            $stmtItem1->bindValue(':ItemName1', $_SESSION['productNameArr'][1], \PDO::PARAM_STR);
            $stmtItem1->bindValue(':TitleNo', $_SESSION['titleNo'], \PDO::PARAM_STR);
            $stmtItem1->execute();
        }

        if (isset($_SESSION['productNameArr'][2]) && !empty($_SESSION['productNameArr'][2])) {
            $stmtItem2->bindValue(':ItemName2', $_SESSION['productNameArr'][2], \PDO::PARAM_STR);
            $stmtItem2->bindValue(':TitleNo', $_SESSION['titleNo'], \PDO::PARAM_STR);
            $stmtItem2->execute();
        }

        if (isset($_SESSION['productNameArr'][3]) && !empty($_SESSION['productNameArr'][3])) {
            $stmtItem3->bindValue(':ItemName3', $_SESSION['productNameArr'][3], \PDO::PARAM_STR);
            $stmtItem3->bindValue(':TitleNo', $_SESSION['titleNo'], \PDO::PARAM_STR);
            $stmtItem3->execute();
        }

        if (isset($_SESSION['productNameArr'][4]) && !empty($_SESSION['productNameArr'][4])) {
            $stmtItem4->bindValue(':ItemName4', $_SESSION['productNameArr'][4], \PDO::PARAM_STR);
            $stmtItem4->bindValue(':TitleNo', $_SESSION['titleNo'], \PDO::PARAM_STR);
            $stmtItem4->execute();
        }
    }


    //
    // 伝票登録(商品単価の登録)
    //
    public function VoucherUnitPrice() {

      // 消費税
        $consumptionTax = 0.08;

        //プリペアドステートメント
        $stmtUnitPrice0 = $this->dbh->prepare("INSERT INTO UnitPrice (UnitPrice, Count, TitleNo, subtotal, consumptionTax) VALUES (:UnitPrice0, :Count, :TitleNo, :subtotal, :consumptionTax)");
        $stmtUnitPrice1 = $this->dbh->prepare("INSERT INTO UnitPrice (UnitPrice, Count, TitleNo, subtotal, consumptionTax) VALUES (:UnitPrice1, :Count, :TitleNo, :subtotal, :consumptionTax)");
        $stmtUnitPrice2 = $this->dbh->prepare("INSERT INTO UnitPrice (UnitPrice, Count, TitleNo, subtotal, consumptionTax) VALUES (:UnitPrice2, :Count, :TitleNo, :subtotal, :consumptionTax)");
        $stmtUnitPrice3 = $this->dbh->prepare("INSERT INTO UnitPrice (UnitPrice, Count, TitleNo, subtotal, consumptionTax) VALUES (:UnitPrice3, :Count, :TitleNo, :subtotal, :consumptionTax)");
        $stmtUnitPrice4 = $this->dbh->prepare("INSERT INTO UnitPrice (UnitPrice, Count, TitleNo, subtotal, consumptionTax) VALUES (:UnitPrice4, :Count, :TitleNo, :subtotal, :consumptionTax)");

        //プレースホルダへ実際の値を設定する
        if (isset($_SESSION['priceArr'][0])) {
            $stmtUnitPrice0->bindValue(':UnitPrice0', $_SESSION['priceArr'][0], \PDO::PARAM_STR);
            $stmtUnitPrice0->bindValue(':Count', $_SESSION['numberArr'][0], \PDO::PARAM_STR);
            $stmtUnitPrice0->bindValue(':TitleNo', $_SESSION['titleNo'], \PDO::PARAM_STR);
            $stmtUnitPrice0->bindValue(':subtotal', $_SESSION['priceArr'][0] * $_SESSION['numberArr'][0], \PDO::PARAM_INT);
            $stmtUnitPrice0->bindValue(':consumptionTax', $_SESSION['priceArr'][0] * $_SESSION['numberArr'][0] * $consumptionTax, \PDO::PARAM_INT);
            $stmtUnitPrice0->execute();
        }

        if (isset($_SESSION['priceArr'][1]) && !empty($_SESSION['priceArr'][1])) {
            $stmtUnitPrice1->bindValue(':UnitPrice1', $_SESSION['priceArr'][1], \PDO::PARAM_STR);
            $stmtUnitPrice1->bindValue(':Count', $_SESSION['numberArr'][1], \PDO::PARAM_STR);
            $stmtUnitPrice1->bindValue(':TitleNo', $_SESSION['titleNo'], \PDO::PARAM_STR);
            $stmtUnitPrice1->bindValue(':subtotal', $_SESSION['priceArr'][1] * $_SESSION['numberArr'][1], \PDO::PARAM_INT);
            $stmtUnitPrice1->bindValue(':consumptionTax', $_SESSION['priceArr'][1] * $_SESSION['numberArr'][1] * $consumptionTax, \PDO::PARAM_INT);
            $stmtUnitPrice1->execute();
        }

        if (isset($_SESSION['priceArr'][2]) && !empty($_SESSION['priceArr'][2])) {
            $stmtUnitPrice2->bindValue(':UnitPrice2', $_SESSION['priceArr'][2], \PDO::PARAM_STR);
            $stmtUnitPrice2->bindValue(':Count', $_SESSION['numberArr'][2], \PDO::PARAM_STR);
            $stmtUnitPrice2->bindValue(':TitleNo', $_SESSION['titleNo'], \PDO::PARAM_STR);
            $stmtUnitPrice2->bindValue(':subtotal', $_SESSION['priceArr'][2] * $_SESSION['numberArr'][2], \PDO::PARAM_INT);
            $stmtUnitPrice2->bindValue(':consumptionTax', $_SESSION['priceArr'][2] * $_SESSION['numberArr'][2] * $consumptionTax, \PDO::PARAM_INT);
            $stmtUnitPrice2->execute();
        }

        if (isset($_SESSION['priceArr'][3]) && !empty($_SESSION['priceArr'][3])) {
            $stmtUnitPrice3->bindValue(':UnitPrice3', $_SESSION['priceArr'][3], \PDO::PARAM_STR);
            $stmtUnitPrice3->bindValue(':Count', $_SESSION['numberArr'][3], \PDO::PARAM_STR);
            $stmtUnitPrice3->bindValue(':TitleNo', $_SESSION['titleNo'], \PDO::PARAM_STR);
            $stmtUnitPrice3->bindValue(':subtotal', $_SESSION['priceArr'][3] * $_SESSION['numberArr'][3], \PDO::PARAM_INT);
            $stmtUnitPrice3->bindValue(':consumptionTax', $_SESSION['priceArr'][3] * $_SESSION['numberArr'][3] * $consumptionTax, \PDO::PARAM_INT);
            $stmtUnitPrice3->execute();
        }

        if (isset($_SESSION['priceArr'][4]) && !empty($_SESSION['priceArr'][4])) {
            $stmtUnitPrice4->bindValue(':UnitPrice4', $_SESSION['priceArr'][4], \PDO::PARAM_STR);
            $stmtUnitPrice4->bindValue(':Count', $_SESSION['numberArr'][4], \PDO::PARAM_STR);
            $stmtUnitPrice4->bindValue(':TitleNo', $_SESSION['titleNo'], \PDO::PARAM_STR);
            $stmtUnitPrice4->bindValue(':subtotal', $_SESSION['priceArr'][4] * $_SESSION['numberArr'][4], \PDO::PARAM_INT);
            $stmtUnitPrice4->bindValue(':consumptionTax', $_SESSION['priceArr'][4] * $_SESSION['numberArr'][4] * $consumptionTax, \PDO::PARAM_INT);
            $stmtUnitPrice4->execute();
        }
    }


    //
    // @param int $count 個数
    // @param int $price 値段
    // @return int 小計
    //
    public function Subtotal($count, $price) {
        return $count * $price;
    }
}
