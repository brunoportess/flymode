<?php

namespace App\Repository;

use App\Models\FlightOrder;
use Illuminate\Support\Facades\DB;

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
            return $this->flightOrder->where('id', '=', $id)->update($request);
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

    function getByStatus($status)
    {
        try {
            return $this->flightOrder->where('status', '=', $status)->get();
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    function busca($data)
    {

        try {
            $search = FlightOrder::query();
            if (!empty($data['destino'])) {
                $search->where('destino', 'like', '%' . $data['destino'] . '%');
            }

            if (!empty($data['data_inicial'])) {
                $search->where(function ($query) use ($data) {
                    $query->where('data_ida', '>=', $data['data_inicial'])
                        ->orWhere('data_volta', '>=', $data['data_inicial']);
                });
            }

            if (!empty($data['data_final'])) {
                $search->where(function ($query) use ($data) {
                    $query->where('data_ida', '<=', $data['data_final'])
                        ->orWhere('data_volta', '<=', $data['data_final']);
                });
            }
            return $search->get();
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }
}
