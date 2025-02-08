<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Services\FlightOrderServiceInterface;
use Illuminate\Http\Request;

class SearchFlightOrderController extends BaseController
{
    private FlightOrderServiceInterface $flightOrderService;

    public function __construct(FlightOrderServiceInterface $flightOrderService)
    {
        $this->flightOrderService = $flightOrderService;
    }

    public function __invoke(SearchRequest $request)
    {
        $data = $request->validated();
        $response = $this->flightOrderService->busca($data);
        return $this->sendResponse($response);
    }
}
