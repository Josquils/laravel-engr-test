<?php

namespace Tests\Feature;

use App\Mail\OrderBatchNotification;
use App\Models\Hmo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubmitOrderTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public $hmo_a;
    public $hmo_b;

    public $hmo_a_order;
    public $hmo_b_order;

    public $correct_order_a_batch_name;
    public $correct_order_b_batch_name;

    public function setUp(): void
    {
        parent::setUp();

        // Set up any required initial data
        $this->hmo_a = Hmo::factory()->create([
            'name' => 'HMO A',
            'code' => 'HMO-A',
            'is_batched_by_encounter' => false,
        ]); // HMO that wants orders sorted by creation date

        $this->hmo_b = Hmo::factory()->create([
            'name' => 'HMO B',
            'code' => 'HMO-B',
            'is_batched_by_encounter' => true,
        ]); // HMO that wants orders sorted by encounter date

        $this->hmo_a_order = [
            'hmoCode' => $this->hmo_a->code,
            'providerName' => 'Provider A',
            'encounterDate' => '2024-04-15',
            'items' => [
                ['name' => 'Item 1', 'unitPrice' => 100, 'quantity' => 2, 'subTotal' => 200],
                ['name' => 'Item 2', 'unitPrice' => 50.4, 'quantity' => 3, 'subTotal' => 151.2],
            ],
            'total' => 351.2
        ];
        $this->hmo_b_order = [
            'hmoCode' => $this->hmo_b->code,
            'providerName' => 'Provider B',
            'encounterDate' => '2024-04-15',
            'items' => [
                ['name' => 'Item 1', 'unitPrice' => 100, 'quantity' => 2, 'subTotal' => 200],
                ['name' => 'Item 2', 'unitPrice' => 50.4, 'quantity' => 3, 'subTotal' => 151.2],
            ],
            'total' => 351.2
        ];

        $this->correct_order_a_batch_name = "Provider A October 2024";
        $this->correct_order_b_batch_name = "Provider B April 2024";
    }

    public function test_order_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_batch_validation_requirements_passed(): void
    {
        $incorrect = [
            'hmoCode' => '',
            'providerName' => '',
            'encounterDate' => '',
            'items' => '',
            'total' => '',
        ];

        $response = $this->postJson('/orders/save', $incorrect);
        $response->assertStatus(422);
    }

    public function test_orders_are_created(): void
    {
        $hmo_a_order = $this->hmo_a_order;
        $response = $this->postJson('/orders/save', $this->hmo_a_order);
        $this->assertDatabaseHas('orders', [
            'provider_name' => $hmo_a_order['providerName'],
            'hmo_code' => $hmo_a_order['hmoCode'],
            'encounter_date' => $hmo_a_order['encounterDate'],
            'total' => $hmo_a_order['total']
        ]);
    }

    public function test_batches_with_right_name_are_created(): void
    {
        $hmo_a_order = $this->hmo_a_order;
        $response = $this->postJson('/orders/save', $hmo_a_order);

        $this->assertDatabaseHas('batches', [
            'name' => $this->correct_order_a_batch_name,
            'hmo_code' => $hmo_a_order['hmoCode'],
            'provider_name' => $hmo_a_order['providerName'],
            'batch_date' => now()->toDateString()
        ]);

        $hmo_b_order = $this->hmo_b_order;
        $response = $this->postJson('/orders/save', $hmo_b_order);

        $this->assertDatabaseHas('batches', [
            'name' => $this->correct_order_b_batch_name,
            'hmo_code' => $hmo_b_order['hmoCode'],
            'provider_name' => $hmo_b_order['providerName'],
            'batch_date' => $hmo_b_order['encounterDate']
        ]);
    }

    public function test_mail_is_sent_to_hmo(): void
    {
        Mail::fake();
        $hmo_a_order = $this->hmo_a_order;
        $response = $this->postJson('/orders/save', $hmo_a_order);
        Mail::assertSent(OrderBatchNotification::class);
    }
}
