<?php

namespace App\Http\Controllers;

use App\Http\Requests\DateSearchRequest;
use App\Services\DateCalculatorService;
use App\Jobs\LogRequestJob;
use Illuminate\Http\JsonResponse;

class DateCalculatorController extends Controller
{
    protected $service;

    public function __construct(DateCalculatorService $service)
    {
        $this->service = $service;
    }

    public function __invoke(DateSearchRequest $request): JsonResponse
    {
        
        $results = $this->service->getMatches(
            $request->day,
            $request->date,
            $request->start_year,
            $request->end_year
        );

        LogRequestJob::dispatch([
            'day_of_week'  => $request->day,
            'day_of_month' => $request->date,
            'start_year'   => $request->start_year,
            'end_year'     => $request->end_year,
            'results'      => $results,
        ]);

        return response()->json([
            'status' => 'success',
            'data'   => $results,
        ]);
    }
}
