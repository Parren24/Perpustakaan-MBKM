<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public $pageData = [];
    public $title = NULL;
    public $activeRoot = NULL;
    public $activeMenu = NULL;
    public $breadCrump = [];

    public function __construct() {}

    public function dataView(array $var)
    {
        foreach ($var as $key => $value) {
            $this->pageData[$key] = $value;
        }
    }

    public function view($param1)
    {
        $this->pageData['title']      = $this->title;
        $this->pageData['activeRoot'] = $this->activeRoot;
        $this->pageData['activeMenu'] = $this->activeMenu;
        $this->pageData['breadCrump'] = $this->breadCrump;

        $pageData = (object) $this->pageData;
        return view('contents.' . $param1, compact('pageData'));
    }
}
