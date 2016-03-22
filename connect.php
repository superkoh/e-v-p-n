<?php
function catchFatalError() {
    $error = error_get_last();
    if (empty($error)) return;
    $ignore = E_WARNING | E_NOTICE | E_USER_WARNING | E_USER_NOTICE | E_STRICT | E_DEPRECATED | E_USER_DEPRECATED;
    if (($error['type'] & $ignore) == 0) {
        // handle the error - but DO NOT THROW ANY EXCEPTION HERE.
        echo print_r($error, true);
        die;
    }
}

register_shutdown_function('catchFatalError');

function kalec_exception_handler($e) {
    echo print_r($e, true);
    die;
}

set_exception_handler('kalec_exception_handler');


include __DIR__ . '/K_MySQLi.php';
$res = [
    'ok' => -1
];
try {
    $db = new K_MySQLi([
        'host' => 'localhost',
        'username' => 'freeradius',
        'password' => 'radius123',
        'port' => '3306',
        'db' => 'radius'
    ]);

    $userKey = $_GET['vd'];
    $user = $db->fetchOne('select * from userinfo where username=?', $userKey);
    var_dump($user);die;
    if (isset($user)) {
        $check = $db->fetchOne('select * from radcheck where username=?', $userKey);
        $obj = [
            'username' => $userKey,
            'password' => $check['value'],
            'secret' => 'superkoh'
        ];
        $res['ok'] = 0;
        $res['obj'] = $obj;
    } else {

    }
} catch (Exception $e) {
    print_r($e);die;
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($res, JSON_UNESCAPED_UNICODE);
die;