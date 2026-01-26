<?php

namespace Tests\Unit\Services;

use App\Services\MessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageServiceTest extends TestCase
{
    use RefreshDatabase;

    protected MessageService $messageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->messageService = new MessageService();
    }

    /**
     * Test getMessageFinal method
     * TODO: Implement actual test logic
     */
    public function test_get_message_final_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->getMessageFinal();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for getMessageFinal not yet implemented');
    }

    /**
     * Test getMessageFinalByLang returns formatted message in specific language
     */
    public function test_get_message_final_by_lang_returns_message_in_language()
    {
        // Arrange
        $message = 'Test message content';
        $typeOperation = \App\Enums\TypeEventNotificationEnum::none;
        $newLang = 'en';
        $originalLang = app()->getLocale();

        // Act
        $result = $this->messageService->getMessageFinalByLang($message, $typeOperation, $newLang);

        // Assert
        $this->assertIsString($result);
        $this->assertStringContainsString($message, $result);
        // Verify locale was restored
        $this->assertEquals($originalLang, app()->getLocale());
    }
}
