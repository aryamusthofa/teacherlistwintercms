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
}