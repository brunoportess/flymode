<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\FlightOrderController;
use App\Http\Requests\FlightOrderStoreRequest;
use App\Http\Requests\FlightOrderUpdateRequest;
use App\Models\FlightOrder;
use App\Models\User;
use App\Services\FlightOrderServiceInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;


class FlightOrderControllerTest extends TestCase
{

    use WithFaker;

    private $mockService;
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();


        $this->mockService = \Mockery::mock(FlightOrderServiceInterface::class)->shouldIgnoreMissing();
        $this->app->instance(FlightOrderServiceInterface::class, $this->mockService);
        $this->controller = new FlightOrderController($this->mockService);
        //$this->controller  = resolve(FlightOrderController::class);
    }

    public function test_index_returns_flight_orders()
    {
        $orders = FlightOrder::factory()->count(3)->make();

        $this->mockService->shouldReceive('getAll')
            ->once()
            ->andReturn($orders);

        $response = $this->controller->index();

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    //ESSE TESTE AUTORIZA ACESSAR UMA ORDEM SE
    // O ID DO USUARIO FOR O MESMO DO ID DE USUARIO DA ORDEM
    public function test_find_returns_order_when_user_is_authorized()
    {
        $order = FlightOrder::factory()->make();
        $order->user_id = 99;

        $this->mockService->shouldReceive('getById')
            ->with(1)
            ->once()
            ->andReturn($order);

        $user = User::factory()->make();
        $user->id = 99;
        $this->actingAs($user); // Simula usuário autenticado
        $response = $this->controller->find(1);
        $this->assertEquals(200, $response->getStatusCode());
    }

    // O TESTE VAI CRIAR UM USUARIO ID 99
    // GERAR E SIMULAR BUSCAR UMA ORDEM DO USUARIO ID 1
    // RETORNAR 401 REJEITANDO ACESSAR ORDEM DE OUTRO USUARIO
    // AO REJEITAR O ACESSO, O TESTE É POSITIVO
    public function test_find_denies_access_to_other_users_order()
    {
        $user = User::factory()->make();
        $user->id = 99;
        $this->actingAs($user, 'sanctum'); // Ativando a autenticação para o usuário

        // Mock do retorno do serviço getById
        $flightOrderMock = FlightOrder::factory()->make();
        $flightOrderMock->user_id = 1; // Simulando que a ordem pertence ao usuário com ID 1

        // Mockando o método getById para retornar a ordem falsa
        $this->mockService->shouldReceive('getById')
            ->with(1)  // ID da ordem a ser buscada
            ->once()
            ->andReturn($flightOrderMock);  // Retorna o mock da ordem

        // Acessando a rota
        $response = $this->getJson(route('orders.find', 1));

        // Verificando se o código de status retornado é 401 (não autorizado)
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_store_creates_order_successfully()
    {
        $dataItem = FlightOrder::factory()->make()->toArray();

        $data = [
            'destino' => $dataItem['destino'],
            'data_ida' => $dataItem['data_ida'],
            'data_volta' => $dataItem['data_volta']
        ];


        $request = new FlightOrderStoreRequest();
        $request->merge($data); // Adiciona os dados simulados

        $request->setContainer(app())->validateResolved();

        $obj = new FlightOrder($dataItem);
        // mockando ID e created_at para ser validado no Resource
        $obj->created_at = Carbon::now();
        $obj->id = 2;

        $this->mockService->shouldReceive('store')
            ->with($data)
            ->once()
            ->andReturn($obj);

        $response = $this->controller->store($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_update_denies_access_to_other_users_order()
    {
        $order = FlightOrder::factory()->make();
        $data = ['status' => 'aprovado'];

        $requestMock = Mockery::mock(FlightOrderUpdateRequest::class);
        $requestMock->shouldReceive('validated')->once()->andReturn($data);

        $this->mockService->shouldReceive('getById')
            ->with(1)
            ->once()
            ->andReturn($order);

        $user = User::factory()->create();
        $this->actingAs($user); // Simula usuário diferente
        $response = $this->controller->update(1, $requestMock);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_get_by_status_returns_orders()
    {
        $orders = FlightOrder::factory()->count(3)->make();

        $this->mockService->shouldReceive('getByStatus')
            ->with('solicitado')
            ->once()
            ->andReturn($orders);

        $response = $this->controller->getByStatus('solicitado');
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
