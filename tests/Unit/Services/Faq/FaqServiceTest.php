<?php

namespace Tests\Unit\Services\Faq;

use App\Models\Faq;
use App\Models\User;
use App\Services\Faq\FaqService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FaqServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected FaqService $faqService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faqService = new FaqService();
    }

    /**
     * Test getById returns FAQ
     */
    public function test_get_by_id_returns_faq()
    {
        // Arrange
        $faq = Faq::factory()->create();

        // Act
        $result = $this->faqService->getById($faq->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($faq->id, $result->id);
        $this->assertEquals($faq->question, $result->question);
    }

    /**
     * Test getById returns null when not found
     */
    public function test_get_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->faqService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getByIdOrFail returns FAQ
     */
    public function test_get_by_id_or_fail_returns_faq()
    {
        // Arrange
        $faq = Faq::factory()->create();

        // Act
        $result = $this->faqService->getByIdOrFail($faq->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($faq->id, $result->id);
    }

    /**
     * Test getByIdOrFail throws exception when not found
     */
    public function test_get_by_id_or_fail_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->faqService->getByIdOrFail(99999);
    }

    /**
     * Test getPaginated returns paginated results
     */
    public function test_get_paginated_returns_paginated_results()
    {
        // Arrange
        // Clear existing data to ensure test isolation
        Faq::query()->delete();

        Faq::factory()->count(20)->create();

        // Act
        $result = $this->faqService->getPaginated(null, 10);

        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(20, $result->total());
    }

    /**
     * Test getPaginated with search filter
     */
    public function test_get_paginated_filters_by_search()
    {
        // Arrange
        Faq::factory()->create(['question' => 'How to reset password?']);
        Faq::factory()->create(['question' => 'How to change email?']);
        Faq::factory()->create(['question' => 'What is the pricing?']);

        // Act
        $result = $this->faqService->getPaginated('password');

        // Assert
        $this->assertEquals(1, $result->total());
        $items = $result->items();
        $this->assertStringContainsString('password', $items[0]->question);
    }

    /**
     * Test getPaginated orders by created_at DESC
     */
    public function test_get_paginated_orders_by_created_at_desc()
    {
        // Arrange
        $faq1 = Faq::factory()->create(['created_at' => now()->subDays(2)]);
        $faq2 = Faq::factory()->create(['created_at' => now()]);

        // Act
        $result = $this->faqService->getPaginated();

        // Assert
        $items = $result->items();
        $this->assertEquals($faq2->id, $items[0]->id);
    }

    /**
     * Test getAll returns all FAQs
     */
    public function test_get_all_returns_all_faqs()
    {
        // Arrange
        // Clear existing data to ensure test isolation
        Faq::query()->delete();

        Faq::factory()->count(5)->create();

        // Act
        $result = $this->faqService->getAll();

        // Assert
        $this->assertCount(5, $result);
    }

    /**
     * Test getAll orders by created_at DESC
     */
    public function test_get_all_orders_by_created_at_desc()
    {
        // Arrange
        $faq1 = Faq::factory()->create(['created_at' => now()->subDays(2)]);
        $faq2 = Faq::factory()->create(['created_at' => now()]);

        // Act
        $result = $this->faqService->getAll();

        // Assert
        $this->assertEquals($faq2->id, $result->first()->id);
    }

    /**
     * Test create creates new FAQ
     */
    public function test_create_creates_new_faq()
    {
        // Arrange
        $data = [
            'question' => 'What is Laravel?',
            'answer' => 'Laravel is a PHP framework.',
        ];

        // Act
        $result = $this->faqService->create($data);

        // Assert
        $this->assertInstanceOf(Faq::class, $result);
        $this->assertEquals('What is Laravel?', $result->question);
        $this->assertEquals('Laravel is a PHP framework.', $result->answer);
        $this->assertDatabaseHas('faqs', ['question' => 'What is Laravel?']);
    }

    /**
     * Test update updates FAQ
     */
    public function test_update_updates_faq()
    {
        // Arrange
        $faq = Faq::factory()->create(['question' => 'Old Question']);
        $data = ['question' => 'New Question', 'answer' => 'New Answer'];

        // Act
        $result = $this->faqService->update($faq->id, $data);

        // Assert
        $this->assertTrue($result);
        $faq->refresh();
        $this->assertEquals('New Question', $faq->question);
        $this->assertEquals('New Answer', $faq->answer);
    }

    /**
     * Test update throws exception when not found
     */
    public function test_update_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->faqService->update(99999, ['question' => 'Test']);
    }

    /**
     * Test delete deletes FAQ
     */
    public function test_delete_deletes_faq()
    {
        // Arrange
        $faq = Faq::factory()->create();

        // Act
        $result = $this->faqService->delete($faq->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    /**
     * Test delete throws exception when not found
     */
    public function test_delete_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->faqService->delete(99999);
    }

    /**
     * Test create with additional fields
     */
    public function test_create_with_all_fields()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'question' => 'Test Question',
            'answer' => 'Test Answer',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];

        // Act
        $result = $this->faqService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($user->id, $result->created_by);
        $this->assertEquals($user->id, $result->updated_by);
    }
}
