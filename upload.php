<?php
$host = null;
$database_name = null;
$user = null;
$password = null;
if(isset($_FILES["files"])){
    require_once(__DIR__.'/function.php');
    require_once(__DIR__.'/config.php');
    $dir_password = $_POST['dir_passwd'] ?? null;
    if(!is_null($dir_password)){
        $dir_password = password_hash($dir_password, PASSWORD_DEFAULT);
    }
    $dir = random(25);
    mkdir(__DIR__.'/upload/'.$dir, 0700);
    if(!file_exists(__DIR__.'/upload/'.$dir)){
        exit;
    }
    for($i = 0; $i < count($_FILES["files"]["name"]); $i++ ){
        if(is_uploaded_file($_FILES["files"]["tmp_name"][$i])){
            move_uploaded_file($_FILES["files"]["tmp_name"][$i],__DIR__."/upload/".$dir.'/'.basename($_FILES["files"]["name"][$i]));
        }
    }
    $pdo = new PDO('mysql:dbname='.$database_name.';host='.$host.';',$user,$passwd);
    $sql = "INSERT INTO upload (id, dir_name, dir_passwd) VALUES (NULL, :dir_name, :dir_passwd);";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':dir_name', $dir);
    $stmt->bindValue(':dir_passwd', $dir_password);
    $stmt->execute();
    unset($pdo);
    $comment = "アップロード完了しました。\n以下のコードを共有することによって誰でもダウンロードすることができます。\n$dir";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アップロード</title>
</head>
<body>
    <?php
    if(isset($comment)){
        echo nl2br($comment);
    }
    ?>
    <p>ファイルをアップロードしてください</p>
    <p>パスワードはアップロードしたファイルを削除の際に使います。</p>
    <p>パスワードは設定しなくても大丈夫ですが、誰でも削除可能になります。</p>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="files[]" multiple>
        <div>
            パスワード(任意): <input type="text" name="dir_passwd">
        </div>
        <input type="submit">
    </form>
</body>
</html>
