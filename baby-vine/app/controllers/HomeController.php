<?php

class HomeController extends BaseController {

    protected $layout = 'layouts.index';

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |   Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function index()
    {
        $config = Config::get('vine');
        $key    = $config['redis_sets'];

        $redis      = Redis::connection();

        $members    = $redis->srandmember($key, 6);
        $records    = array();
        foreach ($members as $val) {
            $records[] = unserialize($val);
        }

        $this->layout->header   = View::make('layouts.header',  array());
        $this->layout->content  = View::make('layouts.content', array('records' => $records));
        $this->layout->footer   = View::make('layouts.footer',  array());
    }
}
