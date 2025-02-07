<?php

namespace App\Services;

use App\Repository\FlightOrderRepositoryInterface;

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
}
