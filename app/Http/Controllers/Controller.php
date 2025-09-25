<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public $pageData = [];
    public $title = NULL;
    public $activeRoot = NULL;
    public $activeMenu = NULL;
    public $breadCrump = [];

    public function __construct()
    {
    }

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
