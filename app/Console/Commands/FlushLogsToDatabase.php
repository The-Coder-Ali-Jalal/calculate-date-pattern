<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

#[Signature('logs:flush')]
#[Description(' Move logs from Redis to MySQL in groups')]
class FlushLogsToDatabase extends Command
{

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (Redis::exists('log_buffer')){
            Redis::rename('log_buffer', 'log_buffer_processing');
            $logs = Redis::lrange('log_buffer_processing', 0, -1);
            Redis::del('log_buffer_processing');
        }
     

        if (empty($logs)) {
            $this->info('No logs to flush.');
            return;
        }

     
        $dataToInsert = array_map(fn($log) => json_decode($log, true), $logs);

        // bulk insert 
        foreach (array_chunk($dataToInsert, 500) as $chunk) {
            DB::table('request_logs')->insert($chunk);
        }

        $this->info(count($dataToInsert) . ' logs inserted successfully.');
    }
    
}
