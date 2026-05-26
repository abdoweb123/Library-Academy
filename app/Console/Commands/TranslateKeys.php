<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateKeys extends Command
{
    protected $signature = 'translate:keys {lang=ar}';
    protected $description = 'Translate missing keys using Google Translate and save to file.php';

    public function handle()
    {
        $locale = $this->argument('lang');
        $langPath = resource_path("lang/{$locale}");
        $filePath = "{$langPath}/file.php";

        if (!File::exists($langPath)) {
            File::makeDirectory($langPath, 0755, true);
        }

        if (!File::exists($filePath)) {
            File::put($filePath, "<?php\n\nreturn [];\n");
        }

        $translations = include $filePath;

        $keysToTranslate = [
            'view_all',
            'add_new',
            'delete_item',
            'save_changes',
            'submit',
            'cancel',
        ];

        try {
            $tr = new GoogleTranslate($locale);
            $tr->setSource('en');

            foreach ($keysToTranslate as $key) {
                if (!isset($translations[$key])) {
                    $text = str_replace('_', ' ', $key);
                    $translation = $locale === 'en' ? ucwords($text) : $tr->translate($text);
                    $translations[$key] = $translation;
                    $this->info("Translated {$key} => {$translation}");
                }
            }

            $exported = var_export($translations, true);
            File::put($filePath, "<?php\n\nreturn {$exported};\n");
            $this->info("✅ Translations saved to {$filePath}");

        } catch (\Exception $e) {
            $this->error("❌ Error during translation: " . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}
