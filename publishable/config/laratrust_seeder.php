<?php

return [
    'role_structure' => [
        'super' => [
            'admin.dashboard' => 'i',
            'admin.setting' => 'i',
            'admin.setting.site' => 'i,u',
            'admin.setting.menus' => 'i,c,u,d',
            'admin.setting.menu-groups' => 'i,c,u,d',
            'admin.setting.roles' => 'i,c,u,d',
            'admin.data' => 'i',
            'admin.data.users' => 'i,c,u,d',
            'admin.data.categories' => 'i,c,u,d',
            'admin.data.tags' => 'i,c,u,d',
            'admin.content' => 'i',
            'admin.content.banners' => 'i,c,u,d',
            'admin.content.posts' => 'i,c,u,d',
            'admin.content.pages' => 'i,c,u,d',
            'admin.content.gallery' => 'i',
            'admin.content.gallery.photos' => 'i,c,u,d',
            'admin.content.gallery.videos' => 'i,c,u,d',
            'admin.report' => 'i',
            'admin.report.inbox' => 'i,r,d',
            'admin.report.comments' => 'i,c,r,u,d',
            'admin.report.incoming-terms' => 'i',
            'admin.report.subscribers' => 'i',
            'admin.log' => 'i',
            'admin.log.activities' => 'i,r',
            'admin.log.errors' => 'i,r',
            'admin.log.visitors' => 'i,r',
        ],
        'admin' => [
            'admin.dashboard' => 'i',
            'admin.setting' => 'i',
            'admin.setting.site' => 'i,u',
            'admin.setting.menus' => 'i,c,u,d',
            'admin.setting.menu-groups' => 'i,c,u,d',
            'admin.setting.roles' => 'i,c,u,d',
            'admin.data' => 'i',
            'admin.data.users' => 'i,c,u,d',
            'admin.data.categories' => 'i,c,u,d',
            'admin.data.tags' => 'i,c,u,d',
            'admin.content' => 'i',
            'admin.content.banners' => 'i,c,u,d',
            'admin.content.posts' => 'i,c,u,d',
            'admin.content.pages' => 'i,c,u,d',
            'admin.content.gallery' => 'i',
            'admin.content.gallery.photos' => 'i,c,u,d',
            'admin.content.gallery.videos' => 'i,c,u,d',
            'admin.report' => 'i',
            'admin.report.inbox' => 'i,r,d',
            'admin.report.comments' => 'i,c,r,u,d',
            'admin.report.incoming-terms' => 'i',
            'admin.report.subscribers' => 'i',
        ],
        'author' => [
            'admin.dashboard' => 'i',
            'admin.content' => 'i',
            'admin.content.posts' => 'i,c,u,d',
            'admin.content.pages' => 'i,c,u,d',
        ],
        'editor' => [
            'admin.dashboard' => 'i',
            'admin.content' => 'i',
            'admin.content.posts' => 'i,u',
            'admin.content.pages' => 'i,u',
        ],
        'user' => [
            // 'profile' => 'r,u',
            // 'dashboard' => 'i',
        ],
    ],
    'permission_structure' => [
        /* 'cru_user' => [
    'profile' => 'c,r,u'
    ], */
    ],
    'permissions_map' => [
        'i' => 'index',
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
