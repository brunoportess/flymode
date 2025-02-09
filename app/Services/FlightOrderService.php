<?php

namespace App\Services;

use App\Repository\FlightOrderRepositoryInterface;
use Carbon\Carbon;

class FlightOrderService implements FlightOrderServiceInterface
{
    private FlightOrderRepositoryInterface $flightOrderRepository;

    public function __construct(FlightOrderRepositoryInterface $flightOrderRepository)
    {
        $this->flightOrderRepository = $flightOrderRepository;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    function getAll(): mixed
    {
        try {
            return $this->flightOrderRepository->getAll();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    function getById($id): mixed
    {
        try {
            return $this->flightOrderRepository->getById($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    function store($request): mixed
    {
        try {
            $user = auth()->user();
            $item = $request;
            $item['user_id'] = $user->id;
            $item['solicitante'] = $user->name;
            $item['status'] = 'solicitado';
            $item['status_codigo'] = 's';
            return $this->flightOrderRepository->store($item);
        } catch (\Exception $e) {
            return new \Exception($e->getMessage());
        }
    }

    /**
     * @param $request
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    function update($request, $id): mixed
    {
        try {
            return $this->flightOrderRepository->update($request, $id);
        } catch (\Exception $e) {
            return new \Exception($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    function destroy($id): mixed
    {
        try {
            return $this->flightOrderRepository->destroy($id);
        } catch (\Exception $e) {
            return new \Exception($e->getMessage());
        }
    }

    /**
     * @param $item
     * @param $id
     * @param $status
     * @return \Exception|mixed
     */
    function statusUpdate($item, $id, $status): mixed
    {
        try {
            if($this->changeStatusValidate($item, $status)) {
                $item = [
                    'status' => $status
                ];
                return $this->update($item, $id);
            }
            throw new \Exception("Não foi possível alterar o status do pedido");
        } catch (\Exception $e) {
            return new \Exception($e->getMessage());
        }
    }

    /**
     * @param $item
     * @param $status
     * @return bool
     * @throws \Exception
     */
    private function changeStatusValidate($item, $status): bool
    {
        $statusItem = $item->status;
        $hoje = Carbon::now();
        $dataIda = $item->data_ida;

        // ATUALIZA PARA APROVADO SE A DATA DE IDA FOR DATA A PARTIR DO DIA SEGUINTE
        if($status == 'aprovado') {
            //SE O PEDIDO JA ESTIVER CANCELADO, NAO PODE RETORNAR O STATUS PARA APROVADO
            if($statusItem == 'cancelado') {
                throw new \Exception('Pedido já se encontra cancelado e não pode ser aprovado!');
            }
            // SE A DATA DE IDA FOR HOJE OU ANTERIOR, PROIBE ALTERAR O STATUS
            if($dataIda <= $hoje->format('Y-m-d')) {
                throw new \Exception('Pedido não pode ser aprovado com menos de 24h do pedido');
            }
            return true;
        } elseif($status == 'cancelado') {
            // SE TENTAR CANCELAR UM PEDIDO JÁ APROVADO E EM CIMA DA HORA (-24HS)
            if($statusItem == 'aprovado' && $dataIda <= $hoje->format('Y-m-d')) {
                throw new \Exception('Pedido não pode ser cancelado com menos de 24h do pedido');
            }
            return true;
        }

        return false;
    }

    /**
     * @param $status
     * @return mixed
     * @throws \Exception
     */
    function getByStatus($status): mixed
    {
        try {
            return $this->flightOrderRepository->getByStatus($status);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    function busca($data): mixed
    {
        try {
            return $this->flightOrderRepository->busca($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
