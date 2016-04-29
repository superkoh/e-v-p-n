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
    '172.31.28.135' => '52.196.18.70',
    '107.170.225.101' => '107.170.225.101',
    '159.203.255.105' => '159.203.255.105'
];
$nasArr = $db->fetchAll('select * from nas');
$cntArr = $db->fetchAll('select count(*) as cnt, nasipaddress from radacct where acctstoptime is null group by nasipaddress');
$cntMap = [];
foreach ($cntArr as $cnt) {
    $cntMap[$nasMapping[$cnt['nasipaddress']]] = $cnt['cnt'] * 100;
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
            [
                'asset' => 'http://img.117go.com/timg/f640/160429/4HRHvjGRbgEOYnDr.jpg',
                'link' => 'http://tao.117go.com/activity/release/152?refer=evpn'
            ],
            [
                'asset' => 'http://img.117go.com/timg/f640/160426/4HOZBuRfvGkObOtK.jpg',
                'link' => 'http://tao.117go.com/activity/release/149/?refer=evpn'
            ]
        ],
        'needUpdate' => false,
        'updateInfo' => '请更新您的App以继续使用eVPN'
    ]
];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($config, JSON_UNESCAPED_UNICODE);
die;