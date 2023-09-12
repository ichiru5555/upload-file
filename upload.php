<?php
$host = '';
$database_name = '';
$user = '';
$password = '';
if(isset($_FILES["files"])){
    require_once(__DIR__.'/function.php');
    require_once(__DIR__.'/config.php');
    $dir_password = str_replace([' ', '　'], '', $_POST['dir_passwd']);
    if(!empty($dir_password)){
        $dir_password = password_hash($dir_password, PASSWORD_DEFAULT);
    }
    $dir = random(25);
    mkdir(__DIR__.'/upload/'.$dir, 0700);
    if(!file_exists(__DIR__.'/upload/'.$dir)){
        exit;
    }
    $filecount = count($_FILES["files"]["name"]);
    for($i = 0; $i < $filecount; ++$i){
        if(is_uploaded_file($_FILES["files"]["tmp_name"][$i])){
            move_uploaded_file($_FILES["files"]["tmp_name"][$i],__DIR__."/upload/".$dir.'/'.basename($_FILES["files"]["name"][$i]));
        }
    }
    unset($filecount);
    register_sql($host, $database_name, $user, $password, $dir, $dir_password);
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
