<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <!--<meta http-equiv="refresh" content="10" >//10秒ごとにページ更新-->

  <br>
  <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font: 16px Helvetica, Arial; background: #fffacd;}
      h2 {padding: 10px}
      .form { background: #ffd700; padding: 3px; position: fixed; bottom: 0; width: 100%; }
      .form input { border: 0; padding: 10px; width: 89%; margin-right: .5%; }
      .form button { width: 9%; background: #f4a460; border: none; padding: 10px; }
      ul { list-style-type: none; margin: 0; padding: 0; }
    </style>

</head>
 
<body>

<h2>ボイス入力チャット</h2>
<hr>
<br>
<form method="post" action="v-chat.php">
    名前　<input type="text" name="name" value="<?php echo $_POST['name']; ?>">
    メッセージ　<input type="text" name="message">
 
    <button name="send" type="submit">送信</button>
</form>
<br>

<section>
    <?php // DBからデータ(投稿内容)を取得 $stmt = select(); foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $message) {
        

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

        // 投稿内容を表示
        function showmsg()
        {

            
            $stmt = select_new();
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $chat) 
            {
                echo $chat['time'],"：",$chat['name'],"：",$chat['message'];
                echo nl2br("\n");
            }
        }
        setInterval(showmsg(), 1000);

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
            $sql = "SELECT * FROM chat ORDER BY time desc limit 20"; //asc->昇順 desc->降順　limit 20->20行表示
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            return $stmt;
        }

        // DBから投稿内容を登録
        function insert() 
        {
            $dbh = connectDB();

            $tz_object = new DateTimeZone('Asia/Tokyo');
            $datetime = new DateTime();
            $datetime->setTimezone($tz_object);
            
            $sql = "INSERT INTO chat (name, message, time) VALUES (:name, :message, :time)";
            $stmt = $dbh->prepare($sql);
            $params = array(':name'=>$_POST['name'], ':message'=>$_POST['message'], ':time'=>($datetime->format('Y-m-d h:i:s')));
            $stmt->execute($params);
        }
        
    ?>
</section>
 
</body>