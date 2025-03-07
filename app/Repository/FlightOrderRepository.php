<?php

namespace App\Repository;

use App\Models\FlightOrder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class FlightOrderRepository implements FlightOrderRepositoryInterface
{
    private FlightOrder $flightOrder;

    public function __construct(FlightOrder $flightOrder)
    {
        $this->flightOrder = $flightOrder;
    }

    /**
     * @return mixed
     */
    function getAll(): mixed
    {
        try {
            return $this->flightOrder->get();
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    function getById($id): mixed
    {
        try {
            return $this->flightOrder->find($id);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    /**
     * @param $request
     * @return mixed
     */
    function store($request): mixed
    {
        try {
            $request['status_codigo'] = $this->getStatusCode($request['status']);
            return $this->flightOrder->create($request);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    function update($request, $id): mixed
    {
        try {
            if(array_key_exists("status", $request)) {
                $request['status_codigo'] = $this->getStatusCode($request['status']);
            }

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

    /**
     * @param $status
     * @return mixed
     */
    function getByStatus($status): mixed
    {
        try {
            return $this->flightOrder
                ->where('user_id', '=', auth()->user()->id)
                ->where('status', '=', $status)
                ->get();
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    /**
     * @param $data
     * @return Collection
     */
    function busca($data): \Illuminate\Database\Eloquent\Collection
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

            //consulta somente as proprias ordens
            $search = $search->where('user_id', '=', auth()->user()->id);
            return $search->get();
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }
}
