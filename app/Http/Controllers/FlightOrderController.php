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

        $response = $this->flightOrderService->getById($id);
        if($response && $response->user_id !== auth()->user()->id)
        {
            return $this->sendError([], ['Você não pode acessar uma ordem de outro usuário!'], 401);
        }
        return $this->sendResponse($response);
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

        $item = $this->flightOrderService->getById($id);
        if($item && $item->user_id !== auth()->user()->id)
        {
            return $this->sendError([], ['Usuário autenticado não pode alterar ordem de outros usuários!'], 401);
        }
        $response = $this->flightOrderService->update($data, $id);
        return $this->sendResponse($response, 'Ordem atualizada com sucesso!');
    }

    function destroy($id)
    {

    }

    function getByStatus($status)
    {

        $response = $this->flightOrderService->getByStatus($status);

        return $this->sendResponse($response);
    }
}
