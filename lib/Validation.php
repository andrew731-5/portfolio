<?php
namespace lib;

class Validation Extends Model {

  protected $_errors;

  // 社員ID バリデーション
  public function noCheck() {
    if (empty($_SESSION['No'])) {
      return $this->_errors['No'] = '社員IDを入力してください。';
    } else if (!preg_match('/^[0-9a-zA-Z]+$/', $_SESSION['No'])) {
      return $this->errors['No'] = '社員IDは半角英数で入力してください。';
    }
  }

  // 氏名 バリデーション
  public function nameCheck() {
    if (empty($_SESSION['name'])) {
      return $this->_errors['name'] = '氏名を入力してください。';
    }
  }

  // メールアドレス バリデーション
  public function emailCheck() {
    if (empty($_SESSION['email'])) {
      return $this->_errors['email'] = 'メールアドレスを入力してください。';
    } else if (!filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL)) {
      return $this->_errors['email'] = 'メールアドレスを正しい形式で入力してください。';
    } else if(!preg_match('/^[a-z0-9\-\_]+@/', $_SESSION['email'])) {
      return $this->_errors['email'] = '@より前は「半角英数字」「-」「_」が有効です。';
    } else if(!preg_match('/^@solt-inc.com$/', substr($_SESSION['email'], strpos($_SESSION['email'], '@')))) {
       return $this->_errors['email'] = 'ドメインは「solt-inc.com」で入力してください。';
    }
  }

  // パスワード バリデーション
  public function passwordCheck() {
    if (empty($_SESSION['password'])) {
      return $this->_errors['password'] = 'パスワードを入力してください。';
    } else if (!preg_match('/^[a-z1-9]{8}/', $_SESSION['password'])) {
      return $this->_errors['password'] = 'パスワードは半角英数、もしくは8文字以上で入力してください。';
    }
  }

  //
  // 伝票入力画面
  //

  // 商品名
  public function productNameCheck() {
    if (empty($_SESSION['productNameArr'][0])) {
      return $this->errors['productName'] = '商品名を入力してください。';
    }
  }

  // 個数
  public function numberCheck() {
    if (empty($_SESSION['numberArr'][0])) {
      return $this->errors['number'] = '個数を入力してください。';
    } else if (!preg_match('/^[0-9]+$/', $_SESSION['numberArr'][0])) {
      return $this->errors['number'] = '個数は半角数字で入力してください。';
    }
  }

  // 価格
  public function priceCheck() {
    if (empty($_SESSION['priceArr'][0])) {
      return $this->errors['price'] = '価格を入力してください。';
    } else if (!preg_match('/^[0-9]+$/', $_SESSION['priceArr'][0]) && !empty($_SESSION['priceArr'][0])) {
      return $this->errors['price'] = '価格は半角数字で入力してください。';
    }
  }

  // 半角数字チェック
  public function halfWidthCheck($name, $value) {
    if (!preg_match('/^[0-9]+$/', $value) && !empty($value)) {
      return $this->errors['number'] = $name . 'は半角数字で入力してください。';
    }
  }

  // 全ての項目チェック
  public function emptyCherck($number) {
    if (!empty($_SESSION['productNameArr'][$number]) || !empty($_SESSION['numberArr'][$number])
          || !empty($_SESSION['priceArr'][$number]))  {

        return $this->errors['number'] = ++$number . '行目は全ての項目を入力してください。';
    }
  }

  // 社員ID 重複チェック
  public function no_duplication() {
    $stat = $this->getDbh();
    $sql = "SELECT No FROM Employee;";
    $stmt = $stat->query($sql);

    foreach ($stmt as $value) {
      $row['No'] = $value['No'];
      if ($row['No'] === $_SESSION['No']) {
        return $this->_errors['no_duplication'] = '社員IDが重複しています。';
       }
    }
  }

  // メアド 重複チェック
  public function email_duplication() {

    $stat = $this->getDbh();
    $sql = "SELECT MailAddress from Employee";
    $stmt = $stat->query($sql);

    foreach ($stmt as $value) {
      $row['MailAddress'] = $value['MailAddress'];
      if ($row['MailAddress'] === $_SESSION['email']) {
        return $this->_errors['duplication'] = 'メールアドレスが重複しています。';
       }
    }

}

// 空白チェック
public function emptyCheck($value, $name) {

  if (empty($value)) {
    return $name . 'が入力されていません。';
  }
}




}
