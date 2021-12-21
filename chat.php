<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>チャット</title>
</head>
 
<body>
     
<h1>チャット</h1>
 
<form method="post" action="chat.php">
    名前　<input type="text" name="name">
    メッセージ　<input type="text" name="message">
 
    <button name="send" type="submit">送信</button>
 
    チャット履歴
</form>

<section>
    <?php // DBからデータ(投稿内容)を取得 $stmt = select(); foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $message) {
        // 投稿内容を表示
        echo $chat['time'],"：",$chat['name'],"：",$chat['message'];
        echo nl2br("\n");

        // 投稿内容を登録
        if(isset($_POST["send"])) 
        {
            insert();
            // 投稿した内容を表示
            $stmt = select_new();
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $chat) 
            {
                echo $chat['time'],"：",$chat['name'],"：",$chat['message'];
                echo nl2br("\n");
            }
        }

        // DB接続
        function connectDB() 
        {
            //$dsn = 'pgsql:dbname=phpchat host=127.0.0.1 port=5432';
            $dbname='d7rp6rg9un3b7n';
            $dbhost='ec2-52-20-143-167.compute-1.amazonaws.com';
            $user = 'lsdcwkuczvhjfz';
            $password = 'a86fad44999cb4ef0b42a50426250ddeb7c27dc65ac45d20cbbfc3686f8a72f1';
            $port="5432";
            $dbh = new PDO("pgsql:host=$dbhost; port=$port; dbname=$dbname; user=$user; password=$password");
            return $dbh;
        }

        // DBから投稿内容を取得
        function select() 
        {
            $dbh = connectDB();
            $sql = "SELECT * FROM chat ORDER BY time";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            return $stmt;
        }

        // DBから投稿内容を取得(最新の1件)
        function select_new() 
        {
            $dbh = connectDB();
            $sql = "SELECT * FROM chat ORDER BY time desc limit 1";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            return $stmt;
        }

        // DBから投稿内容を登録
        function insert() 
        {
            $dbh = connectDB();
            $sql = "INSERT INTO chat (name, message, time) VALUES (:name, :message, now())";
            $stmt = $dbh->prepare($sql);
            $params = array(':name'=>$_POST['name'], ':message'=>$_POST['message']);
            $stmt->execute($params);
        }
        
    ?>
</section>
 
</body>