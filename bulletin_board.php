<?php
$dsn = "mysql:dbname=****;host=****";
$user = "****";
$password = "****";
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$edit_number = $_POST["edit_num"];
$edit_name = $_POST["name"];
$edit_comment = $_POST["comment"];
$edit_password = $_POST["password"];

$delete_number = $_POST["delete_number"];

$sql = $pdo->prepare('SELECT * FROM tbtest WHERE id=:id'); //編集番号を選んだとき
$sql -> bindParam(':id', $_POST["edit"], PDO::PARAM_STR);
$sql->execute();
$edit = $sql->fetch();
if ($edit["password"] == $_POST["edit_password"]) {
  $temp_number = $edit["id"];
  $temp_name = $edit["name"];
  $temp_comment = $edit["comment"];
  $temp_password = $edit["password"];
}

if (!empty($_POST["name"] && $_POST["comment"])) { //一番上のフォームに書いたとき
  if (empty($_POST["edit_num"])) { //新規
    $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, password) VALUES (:name, :comment, :password)");
    $sql -> bindParam(':name', $edit_name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $edit_comment, PDO::PARAM_STR);
    $sql -> bindParam(':password', $edit_password, PDO::PARAM_STR);
    $sql -> execute();
  } else { //編集
    $sql = $pdo -> prepare("UPDATE tbtest SET name=:name, comment=:comment, password=:password WHERE id=:id");
    $sql -> bindParam(':name', $edit_name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $edit_comment, PDO::PARAM_STR);
    $sql -> bindParam(':id', $edit_number, PDO::PARAM_STR);
    $sql -> bindParam(':password', $edit_password, PDO::PARAM_STR);
    $sql -> execute();
  }
}
if(!empty($_POST["delete_number"])){ //削除
  $sql2 = $pdo -> prepare("SELECT * FROM tbtest WHERE id=:id");
  $sql2 -> bindParam(':id', $delete_number, PDO::PARAM_STR);
  $sql2->execute();
  $delete = $sql->fetch();
  if ($delete["password"] == $_POST["delete_password"]) {
    $sql3 = $pdo -> prepare("DELETE FROM tbtest WHERE id=:id");
    $sql3 -> bindParam(':id', $delete_number, PDO::PARAM_STR);
    $sql3 -> execute();
  }
}

$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
$stmt->execute();                             // ←SQLを実行する。
$results = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>mission5-1</title>
</head>
<body>
  <form action="" method="post">
    <input type="hidden" name="edit_num" value="<?php echo $temp_number ?>">
    <input type="text" name="name" value="<?php echo $temp_name ?>" placeholder="名前">
    <input type="text" name="comment" value="<?php echo $temp_comment ?>" placeholder="コメント">
    <input type="text" name="password" value="<?php echo $temp_password ?>" placeholder="パスワード">
    <input type="submit" name="submit" value="送信">
  </form>
  <form action="" method="post">
    <div>
      削除する投稿番号を指定：
    </div>
    <input type="number" name="delete_number" value="">
    <input type="text" name="delete_password" value="" placeholder="パスワード">
    <input type="submit" name="submit2" value="削除">
  </form>
  <form action="" method="post">
    <div>
      編集する投稿番号を指定：
    </div>
    <input type="number" name="edit" value="">
    <input type="text" name="edit_password" value="" placeholder="パスワード">
    <input type="submit" name="submit3" value="編集">
  </form>
  <table>
    <tr>
      <th>ID</th>
      <th>名前</th>
      <th>コメント</th>
      <th>パスワード</th>
    </tr>
    <?php
    foreach ($results as $row){
    ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $row['name']; ?></td>
      <td><?php echo $row['comment']; ?></td>
      <td><?php echo $row['password']/*"表示されません"*/; ?></td>
    </tr>
    <?php
    }
    ?>
  </table>
</body>
</html>
