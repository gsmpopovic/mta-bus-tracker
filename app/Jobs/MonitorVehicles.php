<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mta\BusTime\BusTime;

class MonitorVehicles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $bustime; 

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BusTime $bustime)
    {
        $this->bustime = $bustime; 

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        echo "\n" . "Executing job..." . "\n";
        $this->bustime->monitorVehicles();

    }
}