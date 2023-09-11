<?php
$host = '';
$database_name = '';
$user = '';
$password = '';
if(isset($_FILES['zipfile'])){
    require_once(__DIR__.'/function.php');
    require_once(__DIR__.'/config.php');
    $dir_password = $_POST['dir_passwd'] ?? null;
    if(!empty($dir_password)){
        $dir_password = password_hash($dir_password, PASSWORD_DEFAULT);
    }
    $pathinfo = pathinfo($_FILES["zipfile"]["name"], PATHINFO_EXTENSION);
    $dir = random(25);
    mkdir(__DIR__.'/upload/'.$dir, 0700);
    if(is_uploaded_file($_FILES["zipfile"]["tmp_name"]) && $pathinfo === 'zip' && file_exists(__DIR__.'/upload/'.$dir)){
        move_uploaded_file($_FILES["zipfile"]["tmp_name"],__DIR__."/upload/".$dir.'/'.basename($_FILES["zipfile"]["name"]));
    }else{
        echo 'ZIPファイルではないため処理を停止します。';
        exit;
    }
    $zipfile = __DIR__.'/upload/'.$dir.'/'.basename($_FILES["zipfile"]["name"]);
    $zip = new ZipArchive();
    $zip->open($zipfile);
    $zip->extractTo(__DIR__.'/upload/'.$dir.'/');
    $zip->close();
    unset($zip);
    $files = glob(__DIR__.'/upload/'.$dir.'/*.*');
    unlink($zipfile);
    foreach($files as $file){
        if(is_file($file)){
            chmod ($file, 0700);
        }else{
            echo 'ファイルのパーミッションを変更することができませんでした。';
        }
    }
    register_sql($host, $database_name, $user, $password, $dir, $dir_password);
    $comment = "アップロード完了しました。\n以下のコードを共有することによって誰でもダウンロードすることができます。\n$dir";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>zipファイルアップロード</title>
</head>
<body>
    <?php
    if(isset($comment)){
        echo nl2br($comment);
    }
    ?>
    <p>zipファイルでアップロード</p>
    <p>zipファイルでアップロードしてZIPを内部で展開します。</p>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="zipfile" accept=".zip">
        <div>
            パスワード(任意): <input type="text" name="dir_passwd">
        </div>
        <input type="submit">
    </form>
</body>
</html>
