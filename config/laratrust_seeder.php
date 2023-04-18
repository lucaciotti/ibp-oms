<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin' => [
            'users' => 'c,r,u,d',
            'tasks' => 'c,r,u,d',
            'config' => 'c,r,u,d',
            'xlsimport' => 'c,r,u,d',
        ],
        'manager' => [
            'users' => 'r',
            'tasks' => 'c,r,u',
            'config' => 'c,r,u',
            'xlsimport' => 'c,r,u',
        ],
        'user' => [
            'tasks' => 'r,u',
            'xlsimport' => 'r,d',
        ]
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
