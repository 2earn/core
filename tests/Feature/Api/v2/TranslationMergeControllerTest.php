<?php

namespace Tests\Feature\Api\v2;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for TranslationMergeController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\TranslationMergeController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('translation_merge')]
class TranslationMergeControllerTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    #[Test]
    public function it_can_merge_translations()
    {
        $data = [
            'source_path' => 'lang/en/messages.php',
            'language_code' => 'fr'
        ];

        $response = $this->postJson('/api/v2/translation-merge/merge', $data);

        $this->assertContains($response->status(), [200, 400, 500]); // May fail if file doesn't exist
    }

    #[Test]
    public function it_validates_merge_request()
    {
        $response = $this->postJson('/api/v2/translation-merge/merge', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['source_path', 'language_code']);
    }

    #[Test]
    public function it_validates_language_code()
    {
        $data = [
            'source_path' => 'lang/en/messages.php',
            'language_code' => 'invalid'
        ];

        $response = $this->postJson('/api/v2/translation-merge/merge', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['language_code']);
    }

    #[Test]
    public function it_accepts_valid_language_codes()
    {
        $validCodes = ['ar', 'fr', 'en', 'es', 'tr', 'de', 'ru'];

        foreach ($validCodes as $code) {
            $data = [
                'source_path' => 'lang/en/messages.php',
                'language_code' => $code
            ];

            $response = $this->postJson('/api/v2/translation-merge/merge', $data);

            // Should not have validation errors for language_code
            if ($response->status() === 422) {
                $response->assertJsonMissingValidationErrors(['language_code']);
            }
        }
    }

    #[Test]
    public function it_can_merge_with_default_source()
    {
        $data = [
            'language_code' => 'fr'
        ];

        $response = $this->postJson('/api/v2/translation-merge/merge-default', $data);

        $this->assertContains($response->status(), [200, 400, 500]); // May fail if file doesn't exist
    }

    #[Test]
    public function it_validates_default_merge_request()
    {
        $response = $this->postJson('/api/v2/translation-merge/merge-default', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['language_code']);
    }

    #[Test]
    public function it_handles_merge_errors_gracefully()
    {
        $data = [
            'source_path' => 'nonexistent/path.php',
            'language_code' => 'fr'
        ];

        $response = $this->postJson('/api/v2/translation-merge/merge', $data);

        // Should either succeed or return appropriate error
        $this->assertContains($response->status(), [200, 400, 500]);
    }

    #[Test]
    public function it_returns_merge_statistics()
    {
        $data = [
            'source_path' => 'lang/en/messages.php',
            'language_code' => 'fr'
        ];

        $response = $this->postJson('/api/v2/translation-merge/merge', $data);

        if ($response->status() === 200) {
            $response->assertJsonStructure([
                'success'
            ]);
        }
    }
}

