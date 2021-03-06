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
    $user = $db->fetchOne('select * from userinfo where username=?', 's', $userKey);
    if (isset($user)) {
//        $used = $db->fetchOne('SELECT SUM(acctinputoctets+acctoutputoctets) AS usesum FROM radacct WHERE username=? AND date_format(acctstarttime, \'%Y-%m-%d\') >= date_format(now(),\'%Y-%m-01\') AND date_format(acctstoptime, \'%Y-%m-%d\') <= last_day(now())','s',$userKey);
//        if (isset($used) && $used['usesum'] > 5 * 1024 * 1024 * 1024) {
//            echo json_encode([
//                'ok' => -1,
//                'msg' => '您当月已使用流量超过5G,欢迎下月继续使用.如有任何问题,欢迎使用意见反馈与我们联系'
//            ], JSON_UNESCAPED_UNICODE);
//            die;
//        }
        $check = $db->fetchOne('select * from radcheck where username=?', 's', $userKey);
        $obj = [
            'username' => $userKey,
            'password' => $check['value'],
            'secret' => 'superkoh'
        ];
        $res['ok'] = 0;
        $res['obj'] = $obj;
    } else {
        $password = md5($userKey);
        $db->batchExecute([
            ['insert into userinfo (username,changeuserinfo,creationdate,creationby) values (?,?,?,?)', 'siss', $userKey,0,date('Y-m-d H:i:s'),'administrator'],
            ['insert into userbillinfo (username,changeuserbillinfo,creationdate,creationby) values (?,?,?,?)', 'siss', $userKey,0,date('Y-m-d H:i:s'),'administrator'],
            ['insert into radcheck (`username`,`attribute`,`op`,`value`) values (?,?,?,?)', 'ssss', $userKey, 'User-Password', ':=', $password],
            ['insert into radusergroup (`username`,`groupname`,`priority`) values (?,?,?)', 'ssi', $userKey, 'user', 0]
        ]);
        $res['ok'] = 0;
        $res['obj'] = [
            'username' => $userKey,
            'password' => $password,
            'secret' => 'superkoh'
        ];;
    }
} catch (Exception $e) {
    print_r($e);die;
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($res, JSON_UNESCAPED_UNICODE);
die;