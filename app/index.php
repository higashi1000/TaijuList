<?php

// phpinfo();

// require_once('functions.php');
// require_once('config.php');

// $pdo = pdo_connect();
try {
   $pdo = new PDO('DSN', 'DB_USER', 'DB_PASS',
   [
   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
   PDO::ATTR_EMULATE_PREPARES => false,
   ]
);
return $pdo;
} catch (PDOException $e) {
echo $e->getMessage();
exit;
}

$lists = takelists($pdo);

$stmt = $pdo->query("SELECT * FROM todos ORDER BY id DESC");
$lists = $stmt->fetchAll();
return $lists;

?>

<!DOCTYPE html>
<html lang="ja">
<head> 
   <meta charset="utf-8">
   <title>体重管理アプリ</title>
   <link rel="stylesheet" href="css/styles.css">
</head>
<body>
   <header>
      <h1>目標体重</h1>
   </header>
   <main>
      <h2>今日のToDoリスト</h2>
         <table>
            <thead>
               <tr>
                  <th scope="col">タイトル</th>
                  <th scope="col">目標</th>
               </tr>
            </thead>
            <tbody>
               <?php if($lists): ?>
                  <?php foreach ($lists as $todo): ?>
                     <tr>
                        <!-- <td><input type="checkbox" /></td> -->
                        <td><? echo $todo->title; ?></td>
                        <td><? echo $todo->content; ?></td>
                        <td><a href="" class="editbtn">編集</a></td>
                        <td><a href="" class="deletebtn">削除</a></td>
                     </tr>
                  <?php endforeach; ?>
               <?php else : ?>
                  <td>Todoなし</td>
               <? endif; ?>
            </tbody>
         </table>
      <h2>継続するToDoリスト</h2>
      <h2>明日への一言</h2>
   </main>
</body>
</html> 
