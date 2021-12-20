<section>
    <?php // DBからデータ(投稿内容)を取得 $stmt = select(); foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $message) {
        // 投稿内容を表示
        echo $chat['time'],"：　",$chat['name'],"：",$chat['message'];
        echo nl2br("\n");

    // 投稿内容を登録
    if(isset($_POST["send"])) {
        insert();
        // 投稿した内容を表示
        $stmt = select_new();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $chat) {
            echo $chat['time'],"：　",$chat['name'],"：",$chat['message'];
            echo nl2br("\n");
        }
    }

    // DB接続
    function connectDB() {
        $dsn = 'pgsql:dbname=phpchat host="127.0.0.1" port=5432';
        $user = "postgres";
        $password = "Moca1432";
        $dbh = new PDO($dsn, $user, $password);
        return $dbh;
    }

    // DBから投稿内容を取得
    function select() {
        $dbh = connectDB();
        $sql = "SELECT * FROM chat ORDER BY time";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    // DBから投稿内容を取得(最新の1件)
    function select_new() {
        $dbh = connectDB();
        $sql = "SELECT * FROM chat ORDER BY time desc limit 1";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    // DBから投稿内容を登録
    function insert() {
        $dbh = connectDB();
        $sql = "INSERT INTO chat (name, message, time) VALUES (:name, :message, now())";
        $stmt = $dbh->prepare($sql);
        $params = array(':name'=>$_POST['name'], ':message'=>$_POST['message']);
        $stmt->execute($params);
    }
?>
</section>
 
 
</body>