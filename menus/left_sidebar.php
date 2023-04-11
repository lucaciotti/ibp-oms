<?php

$leftSidebar = [
    // [
    //     'type' => 'sidebar-menu-search',
    //     'text' => 'search',
    // ],
    [
        'text'        => 'Home',
        'url'         => 'home',
        'icon'        => 'fas fa-fw fa-home',
    ],
    [
        'header' => 'main_header',
        'classes'  => 'text-bold text-center',
    ],
    [
        'text'        => ' Lista Lavori',
        'url'         => 'planned_tasks',
        'icon'        => 'fas fa-tasks',
        // 'label'       => 4,
        // 'label_color' => 'success',
    ],
    [
        'text'        => ' File Excels',
        'url'         => 'plan_xls',
        'icon'        => 'fas fa-file-excel',
        // 'label'       => 4,
        // 'label_color' => 'success',
    ],
    [
        'header' => 'Configurazioni',
        'classes'  => 'text-bold text-center',
    ],
    [
        'text'        => ' Tipi Pianificazioni',
        'url'         => 'config/plantypes',
        'icon'        => 'fas fa-paste',
    ],
    [
        'text'        => ' Attributi',
        'url'         => 'config/attributes',
        'icon'        => 'fas fa-sitemap',
    ],
    // [ ==> Spostato in AppServiceProvider
    //     'text' => 'listClients',
    //     // 'url'  => '#',
    //     'route'  => 'client::list',
    //     'icon' => 'fa fa-users',
    // // ],
    // [
    //     'key'     => 'documents',
    //     'text'    => 'documents',
    //     'icon'    => 'fas fa-copy',
    //     'submenu' => [
    //         [
    //             'text' => 'quotes',
    //             // 'url'  => '#',
    //             'icon' => 'fa fa-clipboard-list',
    //             'route' => ['doc::list', ['P']],
    //         ],
    //         [
    //             'text' => 'orders',
    //             // 'url'  => '#',
    //             'icon' => 'fa fa-file-invoice',
    //             'route' => ['doc::list', ['O']],
    //         ],
    //         [
    //             'text' => 'ddt',
    //             // 'url'  => '#',
    //             'icon' => 'fa fa-truck-loading',
    //             'route' => ['doc::list', ['B']],                
    //         ],
    //         [
    //             'text' => 'invoice',
    //             // 'url'  => '#',
    //             'icon' => 'fa fa-file-invoice-dollar',
    //             'route' => ['doc::list', ['F']],
    //         ],
    //     ],
    // ],
    // [
    //     'key'  => 'products',
    //     'text' => 'products',
    //     // 'url'  => '#',
    //     'route' => 'product::list',
    //     'icon' => 'fa fa-boxes',
    // ],

];
