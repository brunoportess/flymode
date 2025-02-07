<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightOrderStoreRequest;
use App\Http\Requests\FlightOrderUpdateRequest;
use App\Services\FlightOrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlightOrderController extends BaseController
{
    private FlightOrderServiceInterface $flightOrderService;

    public function __construct(FlightOrderServiceInterface $flightOrderService)
    {
        $this->flightOrderService = $flightOrderService;
    }

    function index()
    {
        $response = $this->flightOrderService->getAll();
    }

    function find($id)
    {

    }

    /**
     * @param FlightOrderStoreRequest $request
     * @return JsonResponse
     */
    function store(FlightOrderStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $response = $this->flightOrderService->store($data);
        return $this->sendResponse($response, 'Ordem gerada com sucesso!');
    }

    function update($id, FlightOrderUpdateRequest $request)
    {
        $data = $request->validated();
        $response = $this->flightOrderService->update($data, $id);
        return $this->sendResponse($response, 'Ordem atualizada com sucesso!');
    }

    function destroy($id)
    {

    }
}
