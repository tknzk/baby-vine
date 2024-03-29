<?php

class HomeController extends BaseController {

    protected $layout = 'layouts.index';

    const perpage = 3;

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

        $members    = $redis->srandmember($key, self::perpage);
        $records    = array();
        foreach ($members as $val) {
            $records[] = unserialize($val);
        }

        $this->layout->header   = View::make('layouts.header',  array());
        $this->layout->content  = View::make('layouts.content', array('records' => $records));
        $this->layout->footer   = View::make('layouts.footer',  array());
    }

    public function lists()
    {
        $page   = Input::get('page', 1);

        $config = Config::get('vine');
        $key    = $config['redis_sets'];

        $redis      = Redis::connection();

        //$members    = $redis->smembers($key);
        $members    = $redis->srandmember($key, self::perpage);

        $records    = array();

        $offset = ($page - 1) * self::perpage;
        $limit  = self::perpage;
        $i = 0;
        $j = 0;
        foreach ($members as $val) {
            if ($i >= $offset) {
                $records[] = unserialize($val);
                $j++;
                if ($j >= self::perpage) {
                    break;
                }
            }
            $i++;
        }

        $paginator = Paginator::make($records, count($members), self::perpage);

        $assets = array(
            'records'   => $records,
            'page'      => $page,
            'perpage'   => self::perpage,
            'paginator'   => $paginator,
        );

        $this->layout->header   = View::make('layouts.header',  array());
        $this->layout->content  = View::make('layouts.lists',   $assets);
        $this->layout->footer   = View::make('layouts.footer',  array());
    }
}
