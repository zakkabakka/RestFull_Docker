<?php
if(!file_exists(__DIR__.'/'. $SERVER['REQUEST_URI'])){
    $_GET['_url'] = $_SERVER['REQUEST_URI'];
}
return false;