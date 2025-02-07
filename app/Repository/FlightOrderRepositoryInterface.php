<?php

namespace App\Repository;

interface FlightOrderRepositoryInterface
{
    function getAll();
    function getById($id);
    function store($request);
    function update($request, $id);
    function destroy($id);
}
