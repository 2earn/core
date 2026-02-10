# CommunicationControllerTest - Survey Duplication Fix

## Date
February 10, 2026

## Summary
Fixed the failing test `it_can_duplicate_survey` in CommunicationControllerTest by ensuring the survey has all required relationships (question, question choices, and targets) before attempting duplication.

---

## Test Fix

### it_can_duplicate_survey

**Issue:** Survey duplication was failing because the test was creating a minimal survey without the required relationships that the `duplicateSurvey` method expects.

**Error:** The test was likely failing because the duplication service attempts to access:
- `$original->question()->first()` - Survey question
- `$original->targets->first()` - Survey target
- `$originalQuestion->serveyQuestionChoice()->get()` - Question choices

When these relationships don't exist, the duplication fails.

**Root Cause:**
The `duplicateSurvey` method in `Communication.php` requires:
1. At least one **Target** attached to the survey
2. At least one **SurveyQuestion** for the survey
3. At least one **SurveyQuestionChoice** for the question

The original test only created a basic survey without these relationships.

---

## Solution

**File:** `tests/Feature/Api/v2/CommunicationControllerTest.php`

**Line:** 39-59

**Change:**
```php
// Before - Incomplete survey
#[Test]
public function it_can_duplicate_survey()
{
    $survey = Survey::factory()->create([
        'name' => 'Original Survey'
    ]);

    $response = $this->postJson("/api/v2/communication/surveys/{$survey->id}/duplicate");

    $response->assertStatus(201)
        ->assertJsonFragment(['status' => true]);
}

// After - Complete survey with all relationships
#[Test]
public function it_can_duplicate_survey()
{
    // Create a complete survey with all required relationships
    $survey = Survey::factory()->create([
        'name' => 'Original Survey',
        'description' => 'Original Description'
    ]);
    
    // Create a target and attach it to the survey
    $target = \App\Models\Target::factory()->create();
    $survey->targets()->attach($target->id);
    
    // Create a question for the survey
    $question = \App\Models\SurveyQuestion::factory()->create([
        'survey_id' => $survey->id,
        'content' => 'Test Question'
    ]);
    
    // Create question choices
    \App\Models\SurveyQuestionChoice::factory()->count(3)->create([
        'question_id' => $question->id
    ]);

    $response = $this->postJson("/api/v2/communication/surveys/{$survey->id}/duplicate");

    $response->assertStatus(201)
        ->assertJsonFragment(['status' => true]);
}
```

---

## Survey Duplication Requirements

The `Communication::duplicateSurvey()` method performs the following operations:

1. **Replicates the Survey** with modifications:
   - Appends " (Copy)" to name and description
   - Sets enabled to false
   - Sets status to NEW
   - Updates timestamps

2. **Attaches Targets**:
   - Gets the first target from the original survey
   - Attaches it to the duplicated survey

3. **Duplicates the Question**:
   - Gets the first question from the original survey
   - Replicates it with the new survey_id
   - Creates translation for the question content

4. **Duplicates Question Choices**:
   - Gets all choices from the original question
   - Replicates each with the new question_id
   - Creates translations for each choice title

5. **Transaction Safety**:
   - Wraps everything in a database transaction
   - Rolls back on error
   - Throws exception for proper error handling

---

## Related Models & Relationships

### Survey Model
```php
// Relationships
- targets() - BelongsToMany Target
- question() - HasOne SurveyQuestion (note: singular, not questions)
```

### SurveyQuestion Model
```php
// Relationships
- survey() - BelongsTo Survey
- serveyQuestionChoice() - HasMany SurveyQuestionChoice (note: typo in method name)
```

### SurveyQuestionChoice Model
```php
// Relationships
- question() - BelongsTo SurveyQuestion
```

### Target Model
```php
// Relationships
- surveys() - BelongsToMany Survey
```

---

## Test Results

### Before Fix:
```
Tests:    1 failed, 5 passed
```

### After Fix:
```
✅ Tests: 6 passed (12 assertions)
Duration: 1.03s
```

---

## All Tests in CommunicationControllerTest

1. ✅ **it_can_duplicate_survey** ← Fixed
2. ✅ it_returns_404_when_duplicating_nonexistent_survey
3. ✅ it_can_duplicate_news
4. ✅ it_returns_404_when_duplicating_nonexistent_news
5. ✅ it_can_duplicate_event
6. ✅ it_returns_404_when_duplicating_nonexistent_event

---

## Files Modified

1. `tests/Feature/Api/v2/CommunicationControllerTest.php`
   - Enhanced `it_can_duplicate_survey` test to create complete survey with all required relationships

---

## Key Learnings

### 1. Test Complete Object Graphs
When testing methods that operate on complex object relationships:
- Create all required relationships
- Don't assume factories create related data automatically
- Check the actual service/method code to understand dependencies

### 2. Survey Duplication Pattern
The duplication follows this pattern:
1. Clone the main entity
2. Modify specific fields (name, status, enabled)
3. Clone related entities (one level deep)
4. Re-establish relationships with cloned data
5. Handle translations for multilingual content

### 3. Factory Dependencies
When models have required relationships:
```php
// ❌ Won't work if duplication expects relationships
$survey = Survey::factory()->create();

// ✅ Create with all required relationships
$survey = Survey::factory()->create();
$target = Target::factory()->create();
$survey->targets()->attach($target->id);
$question = SurveyQuestion::factory()->create(['survey_id' => $survey->id]);
SurveyQuestionChoice::factory()->count(3)->create(['question_id' => $question->id]);
```

### 4. Translation Support
The system uses `createTranslaleModel()` helper function to:
- Create multilingual translations for entities
- Support name, description, content, title fields
- Maintain translation consistency across duplicates

---

## Related Documentation

- Previous Fix (Session 1): `TEST_FIXES_SIX_CONTROLLERS.md`
- Commission Breakdown Fix: `TEST_FIX_COMMISSION_BREAKDOWN.md`
- Final Summary: `TEST_FIXES_FINAL_SUMMARY.md`

---

## Status

✅ **Complete** - The `it_can_duplicate_survey` test is now passing

All 6 tests in CommunicationControllerTest are working correctly.

---

## Notes

The `Communication::duplicateSurvey()` method was previously fixed in Session 1 to:
1. Remove syntax error (extra 'd' character before `<?php`)
2. Add exception rethrowing in the catch block

This fix completes the survey duplication functionality by ensuring tests provide proper test data with all required relationships.

