<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GetMovies extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:GetMovies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Movie from Vine.co by tags';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {

        /**
         * options
         */
        $verbose    = $this->option('vmode');
        $tag        = $this->option('tag');
        $page       = $this->option('page');

        $config = Config::get('vine');

        $username   = $config['username'];
        $password   = $config['password'];
        $key        = $config['redis_sets'];

        if ($verbose) {
            $this->comment(sprintf('%-10s : %s', 'tag',     $tag));
            $this->comment(sprintf('%-10s : %s', 'page',    $page));
        }

        if ($tag == null) {
            $this->error('tag option is requuired.');
            exit;
        }

        $session = $this->login($username, $password);

        if ($session->success == true) {
            // kick api
            $endpoint   = 'timelines/tags/'. urlencode($tag) . '?' . http_build_query(array('page' => $page));
            $results    = $this->kick($session, $endpoint);
            if ($verbose) {
                $this->comment(sprintf('call api endpoint %s', $endpoint));
            }

            if ($results->success == true) {

                $perpage = 20;
                $maxPage = ceil($results->data->count / $perpage);

                if ($verbose) {
                    $this->comment(sprintf('response count          : %s', $results->data->count));
                    $this->comment(sprintf('response nextPage       : %s', $results->data->nextPage));
                    $this->comment(sprintf('response previousPage   : %s', $results->data->previousPage));
                    $this->comment(sprintf('response nextPage       : %s', $results->data->nextPage));
                    $this->comment(sprintf('response anchor         : %s', $results->data->anchor));
                    $this->comment(sprintf('response anchorStr      : %s', $results->data->anchorStr));
                    $this->comment(sprintf('total page              : %s', $maxPage));
                }

                $records = array();
                foreach ($results->data->records as $val) {
                    $records[] = $val;
                }

                for ($i = 2; $i <= $maxPage; $i++) {
                    $endpoint   = 'timelines/tags/'. urlencode($tag) . '?' . http_build_query(array('page' => $i));
                    $results    = $this->kick($session, $endpoint);
                    if ($verbose) {
                        $this->comment(sprintf('call api endpoint %s', $endpoint));
                    }
                    foreach ($results->data->records as $val) {
                        $records[] = $val;
                    }
                }

                $redis = Redis::connection();

                foreach ($records as $val) {
                    if ($redis->sismember($key, serialize($val))) {
                        $this->comment(sprintf('already sets  : %s', $val->shareUrl));
                    } else {
                        $this->comment(sprintf('add sets  : %s', $val->shareUrl));
                        $redis->sadd($key, serialize($val));
                    }
                }

            } else {
                $this->error(sprintf('api kick failed. please check call api endpoint', $endpoint));
            }

            $this->logout($session);

        } else {
            $this->error(sprintf('vine login failed. please check your username/passwod [%s/%s]', $username, $password));
        }
    }

    private function checkNextPage($result)
    {
        if ($result->data->nextPage) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            //array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('vmode',      null, InputOption::VALUE_OPTIONAL, 'verbose mode',  false),
            array('tag',        null, InputOption::VALUE_REQUIRED, 'hash tag'),
            array('page',       null, InputOption::VALUE_REQUIRED, 'page', 1),
        );
    }

    private function kick($session, $api)
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
        $result     = json_decode($response);
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

    private  function recursive_info($val, $key = null)
    {
        if (is_array($val)) {
            foreach ($val as $k => $v) {
                if ($key) {
                    $this->info(sprintf('%s : %s : %s', $key, $k, $v));
                } else {
                    $this->info(sprintf('%s : %s', $k, $v));
                }
                if (is_array($v)) {
                    if ($key) {
                        $this->recursive_info($v, sprintf('%s : %s', $key, $k));
                    } else {
                        $this->recursive_info($v, $k);
                    }
                }
            }
        }
    }

    private  function recursive_comment($val, $key = null)
    {
        if (is_array($val)) {
            foreach ($val as $k => $v) {
                if ($key) {
                    $this->comment(sprintf('%s : %s : %s', $key, $k, $v));
                } else {
                    $this->comment(sprintf('%s : %s', $k, $v));
                }
                if (is_array($v)) {
                    if ($key) {
                        $this->recursive_comment($v, sprintf('%s : %s', $key, $k));
                    } else {
                        $this->recursive_comment($v, $k);
                    }
                }
            }
        }
    }

}
