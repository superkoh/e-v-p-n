<?php
include __DIR__ . '/K_MySQLi.php';
$db = new K_MySQLi([
    'host' => 'localhost',
    'username' => 'freeradius',
    'password' => 'radius123',
    'port' => '3306',
    'db' => 'radius'
]);
$nasArr = $db->fetchAll('select * from nas');
$servers = [];
foreach ($nasArr as $nas) {
    $servers[] = [
        'name' => $nas['shortname'],
        'ip' => $nas['nasname']
    ];
}
$config = [
    'ok'=> 0,
    'obj'=> [
        'servers'=> $servers,
        'secret'=> 'superkoh'
    ]
];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($config, JSON_UNESCAPED_UNICODE);
die;