<?php

namespace App\Services;

use App\Services\EventService;
use App\Services\News\NewsService;
use App\Services\Survey\SurveyService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CommunicationBoardService
{
    public function __construct(
        protected SurveyService $surveyService,
        protected NewsService $newsService,
        protected EventService $eventService
    ) {}

    /**
     * Get all communication board items (surveys, news, events)
     * merged and sorted by created_at date
     *
     * @return array
     */
    public function getCommunicationBoardItems(): array
    {
        try {
            $surveys = $this->surveyService->getNonArchivedSurveys();
            $news = $this->newsService->getEnabledNews();
            $events = $this->eventService->getEnabledEvents();

            $communicationBoard = $surveys->merge($news)->merge($events)->sortByDesc('created_at')->values();

            return $this->formatCommunicationBoardItems($communicationBoard);
        } catch (\Exception $e) {
            Log::error('Error fetching communication board items: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Format communication board items with type information
     *
     * @param Collection $items
     * @return array
     */
    protected function formatCommunicationBoardItems(Collection $items): array
    {
        $formattedItems = [];

        foreach ($items as $key => $value) {
            if (get_class($value) === 'App\\Models\\Survey') {
                if ($value->canShow()) {
                    $formattedItems[$key] = [
                        'type' => get_class($value),
                        'value' => $value
                    ];
                }
            } else {
                $formattedItems[$key] = [
                    'type' => get_class($value),
                    'value' => $value
                ];
            }
        }

        return $formattedItems;
    }
}

