<div class="faq-widget faq-style-{{ $settings['accordion_style'] ?? 'default' }}"
    data-allow-multiple="{{ $settings['allow_multiple_open'] ? 'true' : 'false' }}">
    @if (empty($faqs))
        <div class="faq-empty">
            <p>{{ __('faq.no_faqs') }}</p>
        </div>
    @else
        <div class="faq-accordion">
            @foreach ($faqs as $index => $faq)
                <div class="faq-item" data-index="{{ $index }}">
                    <button class="faq-question" type="button" aria-expanded="false"
                        aria-controls="faq-answer-{{ $index }}" id="faq-question-{{ $index }}">
                        <span class="faq-question-text">{{ $faq['question'] }}</span>
                        <span class="faq-toggle-icon">
                            <x-icon path="ph.regular.plus" class="faq-icon-plus" />
                            <x-icon path="ph.regular.minus" class="faq-icon-minus" />
                        </span>
                    </button>

                    <div class="faq-answer" id="faq-answer-{{ $index }}"
                        aria-labelledby="faq-question-{{ $index }}" role="region">
                        <div class="faq-answer-content">
                            {!! markdown()->parse($faq['answer'], false) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
