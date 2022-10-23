<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarEventResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Yadda\Enso\Facades\EnsoCrud;

class EventController extends Controller
{
    /**
     * Show the Event list
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = EnsoCrud::query('event');

        $start_date = \Carbon\Carbon::parse(Arr::first(explode('(', $request->get('start'), 2)))->subMonth();
        $end_date = \Carbon\Carbon::parse(Arr::first(explode('(', $request->get('end'), 2)))->addMonth();

        $query->where('end_at', '>', $start_date)
            ->where('start_at', '<', $end_date);

        return CalendarEventResource::collection($query->get())->toResponse($request);
    }
}
