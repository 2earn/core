# Quick Test Reference Guide
## Run Individual Test Suites
### SurveyResponseServiceTest
```bash
php artisan test tests/Unit/Services/SurveyResponseServiceTest.php
```
### SurveyQuestionServiceTest
```bash
php artisan test tests/Unit/Services/SurveyQuestionServiceTest.php
```
### SurveyQuestionChoiceServiceTest
```bash
php artisan test tests/Unit/Services/SurveyQuestionChoiceServiceTest.php
```
## Run All Survey Tests Together
```bash
php artisan test tests/Unit/Services/SurveyResponseServiceTest.php tests/Unit/Services/SurveyQuestionServiceTest.php tests/Unit/Services/SurveyQuestionChoiceServiceTest.php
```
## Run with Verbose Output
```bash
php artisan test tests/Unit/Services/SurveyResponseServiceTest.php --testdox
```
## Run Specific Test Method
```bash
php artisan test --filter test_get_by_id_returns_survey_response
```
## Quick Status Check
All tests use DatabaseTransactions for isolation and can be run independently.
