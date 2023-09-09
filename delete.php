<?php
$host = '';
$database_name = '';
$user = '';
$password = '';
if(isset($_POST['key']) && file_exists('./upload/'.$_POST['key'])){
    require_once(__DIR__.'/function.php');
    require_once(__DIR__.'/config.php');
    $dir_password = $_POST['dir_passwd'] ?? null;
    $dir = __DIR__.'/upload/'.$_POST['key'];
    if(!password_sql($host, $database_name, $user, $password, $_POST['key'], $dir_password)){
        echo nl2br("パスワードが一致しません。\n再度お試しください");
        exit;
    }
    $files = glob($dir . '/*.*');
    if(!$files){
        rmdir($dir);
        delete_sql($host, $database_name, $user, $password, $_POST['key'], $dir_password);
        echo 'ディレクトリ内のファイルは見つかりませんでしたが、ディレクトリの削除に成功しました。';
        exit;
    }
    foreach($files as $file){
        $result = match(unlink($file)){
            true => 'ファイル '.basename($file).'を削除しました。',
            false => throw new Exception('ファイル '.basename($file).'の削除に失敗しました。'),
        };
        echo $result.'<br>';
    }
    $delete = delete_sql($host, $database_name, $user, $password, $_POST['key'], $dir_password);
    if(!rmdir($dir)){
        $error_message = "ファイルの削除に失敗したためディレクトリを削除することはできません。\n".$delete;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ファイル削除</title>
</head>
<body>
    <p>アップロードしたファイルを削除します。</p>
    <p>アップロード時にパスワードを設定した場合はパスワードは必要です。</p>
    <?php
    if(isset($error_message)){
        echo nl2br($error_message);
    }
    ?>
    <form action="" method="post">
        <div>
            共有キー: <input type="text" name="key">
        </div>
        <div>
            パスワード: <input type="password" name="dir_passwd">
        </div>
        <input type="submit">
    </form>
</body>
</html>
