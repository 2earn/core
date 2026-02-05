<?php

namespace Tests\Unit\Services;

use App\Services\MessageService;
use Tests\TestCase;

class MessageServiceTest extends TestCase
{

    protected MessageService $messageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->messageService = new MessageService();
    }

    /**
     * Test getMessageFinal method returns formatted message with prefix
     */
    public function test_get_message_final_works()
    {
        // Arrange
        $message = 'Test message content';
        $typeOperation = \App\Enums\TypeEventNotificationEnum::none;

        // Act
        $result = $this->messageService->getMessageFinal($message, $typeOperation);

        // Assert
        $this->assertIsString($result);
        $this->assertStringContainsString($message, $result);
    }

    /**
     * Test getMessageFinal with Inscri operation type includes correct prefix
     */
    public function test_get_message_final_with_inscri_type_includes_prefix()
    {
        // Arrange
        $message = '123456';
        $typeOperation = \App\Enums\TypeEventNotificationEnum::Inscri;

        // Act
        $result = $this->messageService->getMessageFinal($message, $typeOperation);

        // Assert
        $this->assertIsString($result);
        $this->assertStringContainsString($message, $result);
        $this->assertNotEquals($message, $result); // Should have prefix
    }

    /**
     * Test getMessageFinal with none type has no prefix
     */
    public function test_get_message_final_with_none_type_has_no_prefix()
    {
        // Arrange
        $message = 'Test message';
        $typeOperation = \App\Enums\TypeEventNotificationEnum::none;

        // Act
        $result = $this->messageService->getMessageFinal($message, $typeOperation);

        // Assert
        $this->assertIsString($result);
        $this->assertEquals(' ' . $message, $result); // Only space + message
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
