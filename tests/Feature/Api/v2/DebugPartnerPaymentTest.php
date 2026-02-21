<?php

namespace Tests\Feature\Api\v2;

use App\Models\PartnerPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DebugPartnerPaymentTest extends TestCase
{
      use DatabaseTransactions;

      protected function setUp(): void
      {
            parent::setUp();
            Notification::fake();
            Role::firstOrCreate(['name' => User::SUPER_ADMIN_ROLE_NAME]);
            $user = User::factory()->create();
            $user->assignRole(User::SUPER_ADMIN_ROLE_NAME);
            Sanctum::actingAs($user);
      }

      public function test_debug_filter()
      {
            $this->withoutExceptionHandling();
            PartnerPayment::factory()->count(1)->pending()->create();
            $response = $this->getJson('/api/v2/partner-payments?status_filter=pending');
            $response->assertStatus(200);
      }

      public function test_debug_search()
      {
            $this->withoutExceptionHandling();
            PartnerPayment::factory()->create(['method' => 'debug_search_method']);
            $response = $this->getJson('/api/v2/partner-payments?search=debug_search_method');
            $response->assertStatus(200);
      }

      public function test_debug_date_range()
      {
            $this->withoutExceptionHandling();
            PartnerPayment::factory()->create(['payment_date' => '2024-06-15']);
            $response = $this->getJson('/api/v2/partner-payments?from_date=2024-01-01&to_date=2024-12-31');
            $response->assertStatus(200);
      }

      public function test_debug_reject()
      {
            $this->withoutExceptionHandling();
            $payment = PartnerPayment::factory()->pending()->create();
            $response = $this->postJson("/api/v2/partner-payments/{$payment->id}/reject", [
                  'reason' => 'Invalid data',
                  'rejector_id' => auth()->id()
            ]);
            $response->assertStatus(200);
      }
}
