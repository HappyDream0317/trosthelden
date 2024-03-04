<?php

namespace App\Jobs;

use App\Matching\Matching;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessAllMatchings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private bool $debug;
    private bool $write;
    private $uid;

    /**
     * Create a new job instance.
     *
     * @param null $uid
     * @param bool $debug
     * @param bool $write
     */
    public function __construct($uid = null, $debug = false, $write = false)
    {
        $this->uid = $uid;
        $this->write = $write;
        $this->debug = $debug;
    }

    public function calcMatching()
    {
        $matching = new Matching($this->write, $this->debug);

        ob_start();
        if ($this->uid) {
            $matching->runForUser($this->uid);
        } else {
            set_time_limit(1800); // half an hour
            $matching->runForAll();
        }

        return ob_get_clean();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Storage::put('generating_matches.lock','');
        Storage::put('latest_matches.html', $this->calcMatching());
        Storage::delete('generating_matches.lock');
    }
}
