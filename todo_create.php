<?php

// 送信確認
// var_dump($_POST);
// exit();

// 項目入力のチェック
// 値が存在しないor空で送信されてきた場合はNGにする
if (
  !isset($_POST['username']) || $_POST['username'] == '' ||
  !isset($_POST['password']) || $_POST['password'] == ''
) {
  // 項目が入力されていない場合はここでエラーを出力し，以降の処理を中止する
  echo json_encode(["error_msg" => "no input"]);
  exit();
}

// 受け取ったデータを変数に入れる
$username = $_POST['username'];
$password = $_POST['password'];

//includeで違いファイル（functions.php）呼び出し
include('functions.php');
//$pdoに接続情報を入れたい
$pdo = connect_to_db();

// DB接続の設定
// DB名は`gsacf_x00_00`にする
// $dbn = 'mysql:dbname=gscf_d07_20;charset=utf8;port=3306;host=localhost';
// $user = 'root';
// $pwd = '';

// try {
//   // ここでDB接続処理を実行する
//   $pdo = new PDO($dbn, $user, $pwd);
// } catch (PDOException $e) {
//   // DB接続に失敗した場合はここでエラーを出力し，以降の処理を中止する
//   echo json_encode(["db error" => "{$e->getMessage()}"]);
//   exit();
// }

// データ登録SQL作成(今回はusers_table)
// `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する
$sql = 'INSERT INTO users_table(id, username, password, is_admin, is_deleted, created_at, updated_at) VALUES(NULL, :username, :password, NULL, NULL, sysdate(),sysdate())';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$status = $stmt->execute();


// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  header("Location:todo_input.php");
  exit();
}
