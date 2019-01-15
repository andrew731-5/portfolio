<?php
namespace lib;
require_once('../config/db.php');

class Model {

  protected $dbh;

  // プロパティーのゲッター
  public function getDbh() {

    return $this->dbh;
  }

  // コンストラクタ
  public function __construct() {

    try {

      $this->dbh = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);

    } catch(PDOException $e) {

      echo $e->getMessage();
      exit;

    }

  }


}
