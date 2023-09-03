<?php
function random($length){
        $randombytes = random_bytes($length);
        $randomhex = bin2hex($randombytes);
        if(isset($randomhex)){
            return $randomhex;
        }else{
            return false;
        }
}
