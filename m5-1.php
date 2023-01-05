<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5-1</title>
</head>
<body>
    <?php
        //DB接続設定    
        echo "<hr>";
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        //DB内にテーブルを作成
        $sql = "CREATE TABLE IF NOT EXISTS tech"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date TEXT,"
        . "pass char(32)"
        .");";
        $stmt = $pdo->query($sql);
   
        //データベース構成詳細
        $sql2 = 'SHOW CREATE TABLE tech';
        $result = $pdo -> query($sql2);
        foreach ($result as $row) {
            echo $row[1];
            echo '<br>';
        }
        echo "<hr>";
        
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $delete_num = $_POST["delete_num"];
        $edit_num = $_POST["edit_num"];
        $hide = $_POST["hide"];
        $pass = $_POST["password"];
        $delpass = $_POST["delpass"];
        $editpass = $_POST["editpass"];
        $date = date("Y年m月d日 H時i分s秒");
        ?>
        <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($name2)){echo $name2;}?>">
        <br>
        <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($comment2)){echo $comment2;}?>">
        <input type="hidden" name="hide" value=<?php if(!empty($edit_num)){echo $edit_num;}?>>
        <br>
        <input type="text" name="password" placeholder = "パスワード">
        <input type="submit" name="submit" value = "投稿">
        </form>
        <form action="" method="post">
        <input type="number" name="delete_num" placeholder = "削除対象番号">
        <br>
        <input type="text" name="delpass" placeholder = "パスワード">
        <input type="submit" name="delete" value = "削除">
        </form>
        <form action="" method="post">
        <input type="number" name="edit_num" placeholder = "編集対象番号">
        <br>
        <input type="text" name="editpass" placeholder = "パスワード">
        <input type="submit" name="edit" value = "編集">
        </form>
        <?php
        
        //編集フォーム受信    
        if($edit_num && $editpass){
            $sql_edit = 'SELECT * FROM tech';
            $stmt_edit = $pdo -> query ($sql_edit);
            $result_edit = $stmt_edit -> fetchALL ();
            foreach ($result_edit as $line) {
                if ($line['id'] == $edit_num && $line['pass'] == $editpass) {
                    $name2 = $line['name'];
                    $comment2 = $line['comment'];
                }
            }
        }
        //削除機能
        elseif($delete_num && !empty($delpass)){
            $sql_del = 'SELECT * FROM tech';
            $stmt_del = $pdo -> query ($sql_del);
            $result_del = $stmt_del -> fetchALL ();
            foreach ($result_del as $line2) {
                if ($line2['id'] == $delete_num && $line2['pass'] == $delpass) {
                    $id = $delete_num;
                    $sql_del = 'delete from tech where id=:id';
                    $stmt_del = $pdo -> prepare($sql_del);
                    $stmt_del -> bindParam (':id', $id, PDO::PARAM_INT);
                    $stmt_del -> execute ();
            
                    $sql_del = 'SELECT id, name, comment, date FROM tech';
                    $stmt_del = $pdo -> query ($sql_del);
                    $result_del = $stmt_del -> fetchALL ();
                    foreach ($result_del as $row) {
                        echo $row['id']. "<>";
                        echo $row['name']. "<>";
                        echo $row['comment']. "<>";
                        echo $row['date']. "<br>";
                        echo "<hr>";
                    }
                    echo "<br>";
                }
            } 
        } 
        //編集投稿
        if(!empty($name && $comment && $hide)){
            $sql_up = 'SELECT * FROM tech';
            $stmt_up = $pdo -> query ($sql_up);
            $result_up = $stmt_up -> fetchALL ();
            foreach ($result_up as $line3) {
                if ($line3['id'] == $hide) {
                    $id_up = $hide;
                    $name_up = $name;
                    $comment_up = $comment;
                    $sql_up = 'UPDATE tech SET name=:name,comment=:comment WHERE id=:id';
                    $stmt_up = $pdo -> prepare ($sql_up);
                    $stmt_up -> bindParam (':name', $name_up, PDO::PARAM_STR);
                    $stmt_up -> bindParam (':comment', $comment_up, PDO::PARAM_STR);
                    $stmt_up -> bindParam (':id', $id_up, PDO::PARAM_INT);
                    $stmt_up -> execute ();
                    
                    $sql_up = 'SELECT id, name, comment, date FROM tech';
                    $stmt_up = $pdo -> query ($sql_up);
                    $result_up = $stmt_up -> fetchALL ();
                    foreach ($result_up as $row) {
                        echo $row['id']. "<>";
                        echo $row['name']. "<>";
                        echo $row['comment']. "<>";
                        echo $row['date']. "<br>";
                        echo "<hr>";
                    }
                    echo "<br>";
                }
            }
        //新規投稿    
        }elseif(!empty($name && $comment && $pass) && empty($hide)){
            $sql = $pdo -> prepare ("INSERT INTO tech (name, comment, date, pass) 
            VALUES (:name, :comment, :date, :pass)");
            $sql -> bindParam (':name', $name, PDO::PARAM_STR);
            $sql -> bindParam (':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam (':date', $date, PDO::PARAM_STR);
            $sql -> bindParam (':pass', $pass, PDO::PARAM_STR);
            $sql -> execute ();
            
            $sql = 'SELECT id, name, comment, date FROM tech';
            $stmt = $pdo -> query ($sql);
            $result = $stmt -> fetchALL ();
            foreach ($result as $row) {
                echo $row['id']. "<>";
                echo $row['name']. "<>";
                echo $row['comment']. "<>";
                echo $row['date']. "<br>";
                echo "<hr>";
            }
            echo "<br>";
        }

    ?>
    
</body>
</html>