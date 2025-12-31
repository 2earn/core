<?php

namespace App\Services;

use App\Models\TranslaleModel;
use Illuminate\Support\Facades\Log;

class TranslaleModelService
{
    /**
     * Get translation by name
     *
     * @param string $name
     * @return TranslaleModel|null
     */
    public function getByName(string $name): ?TranslaleModel
    {
        try {
            return TranslaleModel::where('name', $name)->first();
        } catch (\Exception $e) {
            Log::error('Error fetching translation by name: ' . $e->getMessage(), ['name' => $name]);
            return null;
        }
    }

    /**
     * Get translate name for a model
     *
     * @param mixed $model
     * @param string $attribute
     * @return string
     */
    public function getTranslateName($model, string $attribute): string
    {
        return TranslaleModel::getTranslateName($model, $attribute);
    }

    /**
     * Update or create translation
     *
     * @param string $name
     * @param array $translations
     * @return TranslaleModel|null
     */
    public function updateOrCreate(string $name, array $translations): ?TranslaleModel
    {
        try {
            return TranslaleModel::updateOrCreate(
                ['name' => $name],
                [
                    'value' => $translations['ar'] ?? '',
                    'valueFr' => $translations['fr'] ?? '',
                    'valueEn' => $translations['en'] ?? '',
                    'valueEs' => $translations['es'] ?? '',
                    'valueTr' => $translations['tr'] ?? '',
                    'valueRu' => $translations['ru'] ?? '',
                    'valueDe' => $translations['de'] ?? '',
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error updating or creating translation: ' . $e->getMessage(), [
                'name' => $name,
                'translations' => $translations
            ]);
            return null;
        }
    }

    /**
     * Get translations array from TranslaleModel
     *
     * @param TranslaleModel $trans
     * @return array
     */
    public function getTranslationsArray(TranslaleModel $trans): array
    {
        return [
            'ar' => $trans->value,
            'fr' => $trans->valueFr,
            'en' => $trans->valueEn,
            'es' => $trans->valueEs,
            'tr' => $trans->valueTr,
            'ru' => $trans->valueRu,
            'de' => $trans->valueDe,
        ];
    }

    /**
     * Prepare translations with fallback values
     *
     * @param array $translations
     * @param string $fallbackName
     * @return array
     */
    public function prepareTranslationsWithFallback(array $translations, string $fallbackName): array
    {
        return [
            'ar' => !empty($translations['ar']) ? $translations['ar'] : $fallbackName . ' - ar',
            'fr' => !empty($translations['fr']) ? $translations['fr'] : $fallbackName . ' - fr',
            'en' => !empty($translations['en']) ? $translations['en'] : $fallbackName . ' - en',
            'es' => !empty($translations['es']) ? $translations['es'] : $fallbackName . ' - es',
            'tr' => !empty($translations['tr']) ? $translations['tr'] : $fallbackName . ' - tr',
            'ru' => !empty($translations['ru']) ? $translations['ru'] : $fallbackName . ' - ru',
            'de' => !empty($translations['de']) ? $translations['de'] : $fallbackName . ' - de',
        ];
    }

    /**
     * Update translation with title suffix format
     *
     * @param TranslaleModel $translationModel
     * @param string $title
     * @return bool
     */
    public function updateTranslation(TranslaleModel $translationModel, string $title): bool
    {
        try {
            return $translationModel->update([
                'value' => $title . ' AR',
                'valueFr' => $title . ' FR',
                'valueEn' => $title . ' EN',
                'valueTr' => $title . ' TR',
                'valueEs' => $title . ' ES',
                'valueRu' => $title . ' Ru',
                'valueDe' => $title . ' De'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating translation: ' . $e->getMessage(), [
                'translation_id' => $translationModel->id,
                'title' => $title
            ]);
            return false;
        }
    }
}

