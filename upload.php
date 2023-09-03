<?php
require_once(__DIR__.'/function.php');
if(isset($_FILES["files"])){
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
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="files[]" multiple>
        <input type="submit">
    </form>
</body>
</html>
