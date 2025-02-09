<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightOrderStoreRequest;
use App\Http\Requests\FlightOrderUpdateRequest;
use App\Http\Resources\FlightOrderResource;
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

    /**
     * @return JsonResponse
     */
    function index(): JsonResponse
    {
        $response = $this->flightOrderService->getAll();
        return $this->sendResponse(FlightOrderResource::collection($response));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    function find($id): JsonResponse
    {

        $response = $this->flightOrderService->getById($id);
        if($response && $response->user_id !== auth()->user()->id)
        {
            return $this->sendError([], ['Você não pode acessar uma ordem de outro usuário!'], 401);
        }
        return $this->sendResponse(new FlightOrderResource($response));
    }

    /**
     * @param FlightOrderStoreRequest $request
     * @return JsonResponse
     */
    function store(FlightOrderStoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $response = $this->flightOrderService->store($data);

        //return $this->sendResponse($response, 'Ordem gerada com sucesso!');
        return $this->sendResponse(new FlightOrderResource($response), 'Ordem gerada com sucesso!');
    }

    /**
     * @param $id
     * @param FlightOrderUpdateRequest $request
     * @return JsonResponse
     */
    function update($id, FlightOrderUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $item = $this->flightOrderService->getById($id);
        if($item && $item->user_id !== auth()->user()->id)
        {
            return $this->sendError([], ['Usuário autenticado não pode alterar ordem de outros usuários!'], 401);
        }
        if($item->status != 'solicitado')
        {
            return $this->sendError([], ['Sua ordem já se encontra com status '.$item->status.' e não pode sofrer alterações!'], 401);
        }
        $response = $this->flightOrderService->update($data, $id);
        return $this->sendResponse($response, 'Ordem atualizada com sucesso!');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    function destroy($id): JsonResponse
    {
        $response = $this->flightOrderService->destroy($id);
        return $this->sendResponse(new FlightOrderResource($response));
    }

    /**
     * @param $status
     * @return JsonResponse
     */
    function getByStatus($status): JsonResponse
    {

        $response = $this->flightOrderService->getByStatus($status);

        return $this->sendResponse(FlightOrderResource::collection($response));
    }
}
