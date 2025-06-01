<?php

namespace Flute\Modules\FAQ\Widgets;

use Flute\Core\Modules\Page\Widgets\AbstractWidget;

class FAQWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'faq.widget';
    }

    public function getIcon(): string
    {
        return 'ph.regular.question';
    }

    public function getCategory(): string
    {
        return 'content';
    }

    public function render(array $settings): string
    {
        $faqs = $settings['faqs'] ?? [];

        $faqs = array_filter($faqs, function ($faq) {
            return !empty($faq['question']) && !empty($faq['answer']);
        });

        return view('faq::widgets.faq', [
            'faqs' => array_values($faqs),
            'settings' => $settings
        ])->render();
    }

    public function getDefaultWidth(): int
    {
        return 12;
    }

    public function hasSettings(): bool
    {
        return true;
    }

    /**
     * Get default settings
     */
    public function getSettings(): array
    {
        return [
            'faqs' => [],
            'accordion_style' => 'default',
            'allow_multiple_open' => false
        ];
    }

    /**
     * Returns the settings form
     */
    public function renderSettingsForm(array $settings): string
    {
        return view('faq::widgets.settings', [
            'settings' => $settings
        ])->render();
    }

    /**
     * Validates the widget's settings before saving.
     */
    public function validateSettings(array $input)
    {
        return validator()->validate($input, [
            'accordion_style' => 'sometimes|string|in:default,minimal',
            'allow_multiple_open' => 'sometimes|boolean',
        ]);
    }

    /**
     * Saves the widget's settings.
     */
    public function saveSettings(array $input): array
    {
        $settings = $this->getSettings();

        $settings['accordion_style'] = $input['accordion_style'] ?? 'default';
        $settings['allow_multiple_open'] = isset($input['allow_multiple_open']);

        $faqs = [];

        if (isset($input['faqs']) && is_array($input['faqs'])) {
            foreach ($input['faqs'] as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $faqs[] = [
                        'question' => trim($faq['question']),
                        'answer' => trim($faq['answer'])
                    ];
                }
            }
        }

        $settings['faqs'] = array_values($faqs);

        return $settings;
    }
}
