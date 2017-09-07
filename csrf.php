<?php

if( filter_input(INPUT_SERVER,'REQUEST_METHOD') === 'POST' ) {
    
    # если запрос пришел с POST
    if(filter_input(INPUT_POST, 'session') !== $_SESSION['session'] ) {        
        $ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        $log = fopen(dirname(__FILE__).'/logs/'.$ip.'.log', 'a');
        $arr = [
            'title' => 'CSFR attack IP: '.$ip.' '.date('d.m.Y H:i:s',time()),
            'SERVER' => json_encode($_SERVER),
            'HEADERS' => json_encode(getallheaders())
        ];
        fwrite($log, json_encode($arr).PHP_EOL);
        exit( 'CSRF Attack! Your Ip is recorded: '. $ip );
    }
}

$salt = md5(time().filter_input(INPUT_SERVER, 'REMOTE_ADDR').rand(0,1000));
$_SESSION['session'] = password_hash($salt, PASSWORD_DEFAULT);