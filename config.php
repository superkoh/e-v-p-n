<?php
include __DIR__ . '/K_MySQLi.php';
$db = new K_MySQLi([
    'host' => 'localhost',
    'username' => 'freeradius',
    'password' => 'radius123',
    'port' => '3306',
    'db' => 'radius'
]);
$nasMapping = [
    '172.31.16.104' => '52.69.16.186',
    '104.131.143.69' => '104.131.143.69',
    '172.31.28.135' => '52.196.18.70'
];
$nasArr = $db->fetchAll('select * from nas');
$cntArr = $db->fetchAll('select count(*) as cnt, nasipaddress from radacct where acctstoptime is null group by nasipaddress');
$cntMap = [];
foreach ($cntArr as $cnt) {
    $cntMap[$nasMapping[$cnt['nasipaddress']]] = $cnt['cnt'] * 10;
}
$servers = [];
foreach ($nasArr as $nas) {
    $servers[] = [
        'name' => $nas['shortname'],
        'ip' => $nas['nasname'],
        'cnt' => $cntMap[$nas['nasname']] ?? 0
    ];
}
$config = [
    'ok'=> 0,
    'obj'=> [
        'servers'=> $servers,
        'ads' => [
//            [
//                'asset' => 'https://img.117go.com/ws/f640/160323/rd4GrUZksRTGO97uts.jpg',
//                'link' => 'https://tao.117go.com'
//            ]
        ],
        'needUpdate' => false,
        'updateInfo' => '请更新您的App以继续使用eVPN'
    ]
];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($config, JSON_UNESCAPED_UNICODE);
die;