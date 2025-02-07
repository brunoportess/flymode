<?php

namespace App\Repository;

use App\Models\FlightOrder;

class FlightOrderRepository implements FlightOrderRepositoryInterface
{
    private FlightOrder $flightOrder;

    public function __construct(FlightOrder $flightOrder)
    {
        $this->flightOrder = $flightOrder;
    }

    function getAll()
    {
        try {
            return $this->flightOrder->get();
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    function getById($id)
    {
        try {
            return $this->flightOrder->find($id);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    function store($request)
    {
        try {
            $request['status_codigo'] = $this->getStatusCode($request['status']);
            return $this->flightOrder->create($request);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    function update($request, $id)
    {
        try {
            $request['status_codigo'] = $this->getStatusCode($request['status']);
            return $this->flightOrder->where($id)->update($request);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return bool|null
     */
    function destroy($id): ?bool
    {
        try {
            return $this->flightOrder->delete($id);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    /**
     * @param string $status
     * @return string
     */
    private function getStatusCode(string $status): string
    {
        // retorna a primeira letra maiuscula removendo potencial espaço no inicio da string
        return strtoupper(substr(trim($status), 0, 1));
    }
}
