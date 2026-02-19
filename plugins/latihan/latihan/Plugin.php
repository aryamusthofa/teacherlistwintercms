<?php namespace Latihan\Latihan;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'Latihan',
            'description' => 'Plugin latihan untuk dashboard guru dinamis',
            'author'      => 'Latihan',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerComponents()
    {
        return [
            \Latihan\Latihan\Components\TeacherList::class => 'teacherList',
        ];
    }

    public function registerNavigation()
    {
        return [
            'main-menu-latihan' => [
                'label' => 'Data Sekolah',
                'url' => \Backend::url('latihan/latihan/teachers'),
                'icon' => 'icon-graduation-cap',
                'order' => 500,
                'sideMenu' => [
                    'side-menu-teachers' => [
                        'label' => 'Teachers',
                        'icon' => 'icon-users',
                        'url' => \Backend::url('latihan/latihan/teachers'),
                    ]
                ]
            ]
        ];
    }
}