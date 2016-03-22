<?php
$config = [
    'ok': 0,
    'obj': [
        'servers': [
            [
                'name': '东京',
                'ip': '52.69.16.186'
            ],
            [
                'name': '加利福尼亚',
                'ip': '104.131.143.69'
            ]
        ],
        'secret': 'superkoh'
    ]
];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($config, JSON_UNESCAPED_UNICODE);
die;