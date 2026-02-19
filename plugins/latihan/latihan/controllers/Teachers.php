<?php namespace Latihan\Latihan\Controllers;

use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;

class Teachers extends Controller
{
    public $implement = ['Backend\Behaviors\ListController'];
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Latihan.Latihan', 'main-menu-latihan', 'side-menu-teachers');
    }
}
