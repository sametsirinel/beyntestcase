<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderControllerTest extends TestCase
{
    use DatabaseTransactions;

    private string $host = 'api/orders';

    public Order $order;
    public User $user;
    public string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ThrottleRequests::class);
        $this->user = User::factory()->create();
        $this->order = Order::factory()->create([
            "user_id" => $this->user->id
        ]);

        $response = $this->postJson('api/login', [
            'email'    => $this->user->email,
            'password' => "password",
        ]);

        $this->token = $response->json("access_token");
    }

    public function testOrderControllerIndexMethodCanGetUserOrders()
    {
        $response = $this->withToken($this->token)
            ->getJson($this->host . '/')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(4)
            ->assertJsonStructure([
                "status",
                "message",
                "statusCode",
                "data" => [
                    "orders"
                ]
            ]);

        $this->assertSame($response->json("data.orders.0.id"), $this->order->id);
        $this->assertSame($response->json("data.orders.0.user_id"), $this->order->user_id);
        $this->assertSame($response->json("data.orders.0.order_id"), $this->order->order_id);

        Order::factory()->create();

        $response = $this->withToken($this->token)
            ->getJson($this->host . '/')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(4)
            ->assertJsonCount(1, "data.orders")
            ->assertJsonStructure([
                "status",
                "message",
                "statusCode",
                "data" => [
                    "orders"
                ]
            ]);
    }

    public function testOrderControllerShowMethodCanGetUserOrder()
    {
        $response = $this->withToken($this->token)
            ->getJson($this->host . '/' . $this->order->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(4)
            ->assertJsonStructure([
                "status",
                "message",
                "statusCode",
                "data" => [
                    "order"
                ]
            ]);

        $this->assertSame($response->json("data.order.id"), $this->order->id);
        $this->assertSame($response->json("data.order.user_id"), $this->order->user_id);
        $this->assertSame($response->json("data.order.order_id"), $this->order->order_id);

        $order = Order::factory()->create();

        $response = $this->withToken($this->token)
            ->putJson($this->host . '/' . $order->id)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testOrderControllerUpdateMethodCanGetUserOrder()
    {
        $order = Order::factory()->create([
            "shipping_at" => null,
            "user_id" => $this->user->id
        ]);

        $updateValues = Order::factory()->definition([
            "user_id" => $this->user->id
        ]);

        $response = $this->withToken($this->token)
            ->putJson($this->host . '/' . $order->id, $updateValues)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(4)
            ->assertJsonStructure([
                "status",
                "message",
                "statusCode",
                "data" => [
                    "order"
                ]
            ]);

        $this->assertSame($response->json("data.order.address"), $updateValues["address"]);
        $this->assertSame($response->json("data.order.product_id"), $updateValues["product_id"]);
        $this->assertSame($response->json("data.order.quantity"), $updateValues["quantity"]);
        $this->assertSame($response->json("data.order.order_code"), $updateValues["order_code"]);

        $order = Order::factory()->create();

        $response = $this->withToken($this->token)
            ->putJson($this->host . '/' . $order->id)
            ->assertStatus(Response::HTTP_NOT_FOUND);

        $order = Order::factory()->create([
            "shipping_at" => now(),
            "user_id" => $this->user->id
        ]);

        $updateValues = Order::factory()->definition([
            "user_id" => $this->user->id
        ]);

        $response = $this->withToken($this->token)
            ->putJson($this->host . '/' . $order->id, $updateValues)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $order = Order::factory()->create([]);

        $response = $this->withToken($this->token)
            ->putJson($this->host . '/' . $order->id, $updateValues)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testOrderControllerStoreMethodCanGetUserOrder()
    {
        $order = Order::factory()->create([
            "shipping_at" => null,
            "user_id" => $this->user->id
        ]);

        $updateValues = Order::factory()->definition([
            "user_id" => $this->user->id
        ]);

        $response = $this->withToken($this->token)
            ->putJson($this->host . '/' . $order->id, $updateValues)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(4)
            ->assertJsonStructure([
                "status",
                "message",
                "statusCode",
                "data" => [
                    "order"
                ]
            ]);

        $this->assertSame($response->json("data.order.address"), $updateValues["address"]);
        $this->assertSame($response->json("data.order.product_id"), $updateValues["product_id"]);
        $this->assertSame($response->json("data.order.quantity"), $updateValues["quantity"]);
        $this->assertSame($response->json("data.order.order_code"), $updateValues["order_code"]);

        $order = Order::factory()->create();

        $response = $this->withToken($this->token)
            ->putJson($this->host . '/' . $order->id)
            ->assertStatus(Response::HTTP_NOT_FOUND);

        $order = Order::factory()->create([]);

        $response = $this->withToken($this->token)
            ->putJson($this->host . '/' . $order->id, $updateValues)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
