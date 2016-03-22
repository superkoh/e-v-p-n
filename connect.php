<?php
include './K_MySQLi.php';
$res = [
    'ok' => -1
];
try {
    $db = new K_MySQLi([
        'host' => 'localhost',
        'username' => 'freeradius',
        'password' => 'radius123',
        'port' => '3306',
        'db' => 'radius',
        ''
    ]);

    $userKey = $_GET['vd'];
    $user = $db->fetchOne('select * from userinfo where username=?', $userKey);
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
    $res['ok'] = -1;
    $res['msg'] = $e->getMessage();
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($res, JSON_UNESCAPED_UNICODE);
die;