<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PurgeMovies extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'command:PurgeMovies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge Movie from Vine.co by tags';

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

        $config     = Config::get('vine');

        $key        = $config['redis_sets'];

        $redis = Redis::connection();

        $members = $redis->smembers($key);

        foreach ($members as $member) {
            $val = unserialize($member);
            if ($verbose) {
                $this->comment(sprintf('remove sets  : %s', $val->shareUrl));
            }
            $redis->srem($key, $member);
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
        );
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
