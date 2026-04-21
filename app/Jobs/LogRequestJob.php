<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;


class LogRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Redis::rpush('log_buffer', json_encode([
        'day_of_week'   => $this->data['day_of_week'],
        'day_of_month'  => $this->data['day_of_month'],
        'year_range'    => $this->data['start_year'] . '-' . $this->data['end_year'],
        'response_data' => json_encode($this->data['results']),
        'matches_count' => count($this->data['results']),
        'created_at'    => now()->toDateTimeString(),
        'updated_at'    => now()->toDateTimeString(),
        ]));
    }
}
