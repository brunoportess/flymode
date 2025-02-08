<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\FlightOrderResource;
use App\Services\FlightOrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchFlightOrderController extends BaseController
{
    private FlightOrderServiceInterface $flightOrderService;

    public function __construct(FlightOrderServiceInterface $flightOrderService)
    {
        $this->flightOrderService = $flightOrderService;
    }

    /**
     * @param SearchRequest $request
     * @return JsonResponse
     */
    public function __invoke(SearchRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $response = $this->flightOrderService->busca($data);
        return $this->sendResponse(FlightOrderResource::collection($response));
    }
}
