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

        $user = 'tkm.knzk@gmail.com';
        $pass = 'vine1123';

        $session = $this->login($user, $pass);

        $tag = "baby";
        $tags = $this->call($session, 'timelines/tags/'. urlencode($tag));

        $logout = $this->logout($session);

        $this->layout->header   = View::make('layouts.header',  array());
        $this->layout->content  = View::make('layouts.content', array('tags' => $tags));
        $this->layout->footer   = View::make('layouts.footer',  array());
    }

    public function about()
    {
        $this->layout->header   = View::make('layouts.header',  array());
        $this->layout->content  = View::make('layouts.about', array());
        $this->layout->footer   = View::make('layouts.footer',  array());
    }


    private function call($session, $api)
    {
        $header = array(
            'vine-session-id:'.$session->data->key,
        );

        $api_url       = 'https://api.vineapp.com/' . $api;

        $curl = curl_init();
        curl_setopt($curl,  CURLOPT_URL,            $api_url);
        curl_setopt($curl,  CURLOPT_HTTPHEADER,     $header);
        curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);

        $response   = curl_exec($curl);
        $result     = json_decode($response, true);
        return $result;
    }

    private function login($user, $pass)
    {

        $auth_url       = 'https://api.vineapp.com/users/authenticate';
        $login_values   = array(
            'username' => $user,
            'password' => $pass,
        );

        $curl = curl_init();
        curl_setopt($curl,  CURLOPT_URL,            $auth_url);
        curl_setopt($curl,  CURLOPT_POST,           true);
        curl_setopt($curl,  CURLOPT_HEADER,         false);
        curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,  CURLOPT_POSTFIELDS,     $login_values);

        $response = curl_exec($curl);

        $session = json_decode($response);

        return $session;
    }

    private function logout($session)
    {
        $header = array(
            'vine-session-id:'.$session->data->key,
        );

        $auth_url       = 'https://api.vineapp.com/users/authenticate';

        $curl = curl_init();
        curl_setopt($curl,  CURLOPT_URL,            $auth_url);
        curl_setopt($curl,  CURLOPT_CUSTOMREQUEST,  'DELETE');
        curl_setopt($curl,  CURLOPT_HTTPHEADER,     $header);
        curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        $session = json_decode($response);

        return $session;
    }
}
