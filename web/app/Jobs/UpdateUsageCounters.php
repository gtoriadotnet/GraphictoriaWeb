<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Helpers\QAaMBHelper;
use App\Models\UsageCounter;

class UpdateUsageCounters implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $memoryInfo = QAaMBHelper::getSystemMemoryInfo();
		$cpuInfo = QAaMBHelper::getSystemCpuInfo();
		
		UsageCounter::where('name', 'CPU')->update([ 'value' => $cpuInfo ]);
		UsageCounter::where('name', 'Memory')->update([ 'value' => $memoryInfo ]);
    }
}
