<?php

namespace App\Services;

interface FlightOrderServiceInterface
{
    function getAll();
    function getById($id);
    function store($request);
    function update($request, $id);
    function destroy($id);
}
