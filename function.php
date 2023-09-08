<?php
function random(int $length){
        $randombytes = random_bytes($length);
        $randomhex = bin2hex($randombytes);
        return $randomhex;
}
function password_sql(string $host, string $database_name, string $user, string $password, string $dir_name, string $dir_password){
    $pdo = new PDO('mysql:dbname='.$database_name.';host='.$host.';',$user,$password);
    $sql = "SELECT * FROM upload WHERE dir_name = :user_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':dir_name', $dir_name);
    $stmt->execute();
    $result = $stmt->fetch();
    if(password_verify($dir_password, $result['passwd'])){
        unset($pdo);
        unset($result);
        return true;
    }else{
        unset($pdo);
        unset($result);
        return false;
    }
}
function delete_sql(string $host, string $database_name, string $user, string $password, string $dir_name, string $dir_password){
    $pdo = new PDO('mysql:dbname='.$database_name.';host='.$host.';',$user,$password);
    $sql = "SELECT * FROM upload WHERE dir_name = :user_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':dir_name', $dir_name);
    $stmt->execute();
    $result = $stmt->fetch();
    if(password_verify($dir_password, $result['passwd'])){
        $sql = "DELETE FROM upload WHERE dir_name = :dir_name;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':dir_name', $dir_name);
        $stmt->execute();
        unset($pdo);
        unset($result);
        return true;
    }else{
        unset($pdo);
        unset($result);
        return 'パスワードの認証に失敗したためデーターベースの削除に失敗しました';
    }
}
