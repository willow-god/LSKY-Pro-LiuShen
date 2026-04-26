<?php

return [
    'groups' => [
        [
            'key' => 'images',
            'label' => '图片组',
            'abilities' => [
                'images.read' => '获取图片列表',
                'images.upload' => '上传图片',
                'images.delete' => '删除图片',
            ],
        ],
        [
            'key' => 'albums',
            'label' => '相册组',
            'abilities' => [
                'albums.read' => '获取相册列表',
                'albums.delete' => '删除相册',
            ],
        ],
        [
            'key' => 'management',
            'label' => '管理组',
            'abilities' => [
                'profile.read' => '获取用户资料',
                'strategies.read' => '获取可用策略',
            ],
        ],
    ],
    'forbidden_message' => '当前密钥无权执行此操作',
];
