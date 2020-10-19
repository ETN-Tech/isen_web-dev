<?php

require_once('../php/session-locale.php');

require_once('../php/models/accounts.php');


// test if user connected
if (isset($_SESSION['user_id'])) {
    $user_connected = true;
    
    $account = get_account($_SESSION['user_id'])->fetch();
    
    $full_name = $account['surname'] .' '. $account['name'];
} else {
    $user_connected = false;
}


require_once('../php/views/home.view.php');