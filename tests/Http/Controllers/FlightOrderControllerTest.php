<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\FlightOrderController;
use App\Http\Requests\FlightOrderStoreRequest;
use App\Http\Requests\FlightOrderUpdateRequest;
use App\Models\FlightOrder;
use App\Models\User;
use App\Repository\FlightOrderRepositoryInterface;
use App\Services\FlightOrderService;
use App\Services\FlightOrderServiceInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Mockery;
//use PHPUnit\Framework\TestCase;
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

    public function test_find_returns_order_when_user_is_authorized()
    {
        $order = (object) ['id' => 1, 'user_id' => 1];


        $this->mockService->shouldReceive('getById')
            ->with(1)
            ->once()
            ->andReturn($order);

        $this->actingAs((object) ['id' => 1]); // Simula usuário autenticado
        $response = $this->controller->find(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_find_denies_access_to_other_users_order()
    {
        $order = (object) ['id' => 1, 'user_id' => 2];


        $this->mockService->shouldReceive('getById')
            ->with(1)
            ->once()
            ->andReturn($order);

        $this->actingAs((object) ['id' => 1]); // Simula usuário diferente
        $response = $this->controller->find(1);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_store_creates_order_successfully()
    {
        $data = ['user_id' => 1, 'status' => 'solicitado'];

        $requestMock = Mockery::mock(FlightOrderStoreRequest::class);
        $requestMock->shouldReceive('validated')->once()->andReturn($data);

        $this->mockService->shouldReceive('store')
            ->with($data)
            ->once()
            ->andReturn((object) $data);

        $response = $this->controller->store($requestMock);
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
