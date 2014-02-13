<?php

class ApiController extends BaseController {

    const perpage = 2;

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

        $result = array();
        foreach ($records as $i => $val) {
            $result[$i]['shareUrl'] = $val->shareUrl;
        }

        sleep(1);

        return Response::json($result);

    }

    public function lists()
    {
        $page   = Input::get('page', 1);

        $config = Config::get('vine');
        $key    = $config['redis_sets'];

        $redis      = Redis::connection();

        $members    = $redis->smembers($key);

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

        $result = array();
        foreach ($records as $i => $val) {
            $result[$i]['shareUrl'] = $val->shareUrl;
        }

        return Response::json($result);
    }
}
