<div class="faq-settings-container">
    <form class="faq-settings-form">
        <div class="settings-section">
            <div class="settings-grid">
                <x-forms.field>
                    <x-forms.label for="accordion_style">{{ __('faq.settings.accordion_style') }}</x-forms.label>
                    <x-fields.select name="accordion_style" id="accordion_style">
                        <option value="default"
                            {{ ($settings['accordion_style'] ?? 'default') === 'default' ? 'selected' : '' }}>
                            {{ __('faq.settings.style_default') }}
                        </option>
                        <option value="minimal"
                            {{ ($settings['accordion_style'] ?? 'default') === 'minimal' ? 'selected' : '' }}>
                            {{ __('faq.settings.style_minimal') }}
                        </option>
                    </x-fields.select>
                </x-forms.field>

                <x-forms.field>
                    <x-fields.checkbox name="allow_multiple_open" id="allow_multiple_open"
                        checked="{{ $settings['allow_multiple_open'] ?? false }}"
                        label="{{ __('faq.settings.allow_multiple_open') }}" />
                </x-forms.field>
            </div>
        </div>

        <div class="settings-section">
            <div class="settings-header">
                <x-forms.label class="section-title">{{ __('faq.settings.manage_faqs') }}</x-forms.label>
                <x-button type="button" id="btn-add-faq" class="btn-add">
                    <x-icon path="ph.regular.plus" />
                    <span>{{ __('faq.settings.add_faq') }}</span>
                </x-button>
            </div>

            <div class="faqs-container">
                @if (!empty($settings['faqs']) && count($settings['faqs']) > 0)
                    <div class="faqs-count">
                        <span class="count-text">{{ count($settings['faqs']) }} {{ __('faq.settings.faqs_total') }}</span>
                    </div>
                @endif
                
                <div class="faqs-list" id="faqs-list">
                    @if (!empty($settings['faqs']) && count($settings['faqs']) > 0)
                        @foreach ($settings['faqs'] as $index => $faq)
                            <div class="faq-item" data-index="{{ $index }}" data-initialized="true">
                                <div class="faq-header">
                                    <h5 class="faq-title">{{ __('faq.faq') }} #{{ $index + 1 }}</h5>
                                    <button type="button" class="btn-remove-faq" title="{{ __('faq.settings.remove_faq') }}">
                                        <x-icon path="ph.regular.trash" />
                                    </button>
                                </div>

                                <div class="faq-inputs">
                                    <x-forms.field>
                                        <x-forms.label>{{ __('faq.settings.question') }}</x-forms.label>
                                        <x-fields.input type="text" name="faqs[{{ $index }}][question]"
                                            value="{{ $faq['question'] ?? '' }}" 
                                            placeholder="{{ __('faq.settings.question_placeholder') }}"
                                            required />
                                    </x-forms.field>

                                    <x-forms.field>
                                        <x-forms.label>{{ __('faq.settings.answer') }}</x-forms.label>
                                        <x-editor name="faqs[{{ $index }}][answer]"
                                            id="faq-answer-{{ $index }}-{{ uniqid() }}" 
                                            height="150"
                                            value="{{ $faq['answer'] ?? '' }}" 
                                            placeholder="{{ __('faq.settings.answer_placeholder') }}" />
                                    </x-forms.field>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="faqs-empty" id="faqs-empty">
                            <p class="empty-text">{{ __('faq.settings.no_faqs_yet') }}</p>
                            <p class="empty-subtext">{{ __('faq.settings.add_first_faq') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<template id="faq-item-template">
    <div class="faq-item" data-index="{index}" data-initialized="false">
        <div class="faq-header">
            <h5 class="faq-title">{{ __('faq.faq') }} #{number}</h5>
            <button type="button" class="btn-remove-faq" title="{{ __('faq.settings.remove_faq') }}">
                <x-icon path="ph.regular.trash" />
            </button>
        </div>

        <div class="faq-inputs">
            <x-forms.field>
                <x-forms.label>{{ __('faq.settings.question') }}</x-forms.label>
                <x-fields.input type="text" name="faqs[{index}][question]" 
                    placeholder="{{ __('faq.settings.question_placeholder') }}"
                    required />
            </x-forms.field>

            <x-forms.field>
                <x-forms.label>{{ __('faq.settings.answer') }}</x-forms.label>
                <x-editor name="faqs[{index}][answer]" 
                    id="faq-answer-{index}-{uniqid}" 
                    height="150" 
                    value="" 
                    placeholder="{{ __('faq.settings.answer_placeholder') }}" />
            </x-forms.field>
        </div>
    </div>
</template>
