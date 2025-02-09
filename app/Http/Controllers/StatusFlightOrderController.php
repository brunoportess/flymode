<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightOrderStatusRequest;
use App\Services\FlightOrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusFlightOrderController extends BaseController
{
    private FlightOrderServiceInterface $flightOrderService;

    /**
     * @param FlightOrderServiceInterface $flightOrderService
     */
    public function __construct(FlightOrderServiceInterface $flightOrderService)
    {
        $this->flightOrderService = $flightOrderService;
    }

    /**
     * @param $id
     * @param FlightOrderStatusRequest $request
     * @return JsonResponse
     */
    public function __invoke($id, FlightOrderStatusRequest $request): JsonResponse
    {
        $data = $request->all();
        $user = auth()->user();
        $item = $this->flightOrderService->getById($id);

        // VERIFICA SE O USUARIO AUTENTICADO TENTA MUDAR O PROPRIO PEDIDO
        if($item && $item->user_id == $user->id)
        {
            return $this->sendError([], ['Usuário solicitante não pode alterar status do próprio pedido!'], 401);
        }
        $response = $this->flightOrderService->statusUpdate($item, $id, $data['status']);
        return $this->sendResponse($response, 'Status alterado com sucesso!');
    }
}
