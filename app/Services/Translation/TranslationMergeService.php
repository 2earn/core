<?php

namespace App\Services\Translation;

use Exception;

class TranslationMergeService
{
    /**
     * Merge translations from source file into target language file.
     *
     * @param string $sourcePath Path to source JSON file
     * @param string $languageCode Language code (ar, fr, en, etc.)
     * @return array Result array with status, message, and data
     */
    public function mergeTranslations(string $sourcePath, string $languageCode): array
    {
        try {
            if (!file_exists($sourcePath)) {
                return $this->errorResponse("Source file not found: {$sourcePath}");
            }

            $targetPath = resource_path("lang/{$languageCode}.json");

            $backupPath = $this->createBackup($targetPath);

            $current = $this->readTranslations($targetPath);
            if ($current === null) {
                return $this->errorResponse("Could not parse current {$languageCode}.json file");
            }

            $currentCount = count($current);

            $new = $this->readTranslations($sourcePath);
            if ($new === null) {
                return $this->errorResponse("Could not parse source translations file");
            }

            $newCount = count($new);

            $merged = array_merge($current, $new);

            ksort($merged);

            if (!$this->writeTranslations($targetPath, $merged)) {
                return $this->errorResponse("Could not write to {$languageCode}.json file");
            }

            return [
                'success' => true,
                'currentCount' => $currentCount,
                'newCount' => $newCount,
                'mergedCount' => count($merged),
                'backupPath' => $backupPath,
                'targetPath' => $targetPath,
                'sample' => array_slice($merged, 0, 5, true)
            ];

        } catch (Exception $e) {
            return $this->errorResponse("Error during merge: " . $e->getMessage());
        }
    }

    /**
     * Read translations from a JSON file.
     *
     * @param string $path
     * @return array|null
     */
    private function readTranslations(string $path): ?array
    {
        if (!file_exists($path)) {
            return [];
        }

        $content = file_get_contents($path);
        if ($content === false) {
            return null;
        }

        $data = json_decode($content, true);
        return $data === null ? null : $data;
    }

    /**
     * Write translations to a JSON file.
     *
     * @param string $path
     * @param array $translations
     * @return bool
     */
    private function writeTranslations(string $path, array $translations): bool
    {
        $json = json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        if ($json === false) {
            return false;
        }

        return file_put_contents($path, $json) !== false;
    }

    /**
     * Create a backup of the target file.
     *
     * @param string $targetPath
     * @return string|null Backup path or null if no backup was needed
     */
    private function createBackup(string $targetPath): ?string
    {
        if (!file_exists($targetPath)) {
            return null;
        }

        $backupPath = $targetPath . '.backup_' . date('YmdHis');
        copy($targetPath, $backupPath);

        return $backupPath;
    }

    /**
     * Create an error response.
     *
     * @param string $message
     * @return array
     */
    private function errorResponse(string $message): array
    {
        return [
            'success' => false,
            'message' => $message
        ];
    }

    /**
     * Get the language name from language code.
     *
     * @param string $code
     * @return string
     */
    public function getLanguageName(string $code): string
    {
        $languages = [
            'ar' => 'Arabic',
            'fr' => 'French',
            'en' => 'English',
            'es' => 'Spanish',
            'tr' => 'Turkish',
            'de' => 'German',
            'ru' => 'Russian'
        ];

        return $languages[$code] ?? ucfirst($code);
    }

    /**
     * Get the default source path for a language.
     *
     * @param string $languageCode
     * @return string
     */
    public function getDefaultSourcePath(string $languageCode): string
    {
        return base_path("new trans/{$languageCode}.json");
    }
}

