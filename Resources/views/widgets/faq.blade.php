@php $wid = $settings['widget_id'] ?? uniqid('faq_'); @endphp

<div class="faq-widget faq-style-{{ $settings['accordion_style'] ?? 'default' }}"
    data-allow-multiple="{{ isset($settings['allow_multiple_open']) && $settings['allow_multiple_open'] ? 'true' : 'false' }}">
    @if (empty($faqs))
        <div class="faq-empty">
            <p>{{ __('faq.no_faqs') }}</p>
        </div>
    @else
        <div class="faq-accordion" role="list">
            @foreach ($faqs as $index => $faq)
                <section class="faq-item" role="listitem" data-index="{{ $index }}">
                    <h3 class="faq-heading">
                        <button class="faq-question" type="button" aria-expanded="false"
                            aria-controls="faq-answer-{{ $wid }}-{{ $index }}" id="faq-question-{{ $wid }}-{{ $index }}">
                            <span class="faq-question-text">{{ __($faq['question']) }}</span>
                            <span class="faq-toggle-icon" aria-hidden="true">
                                <x-icon path="ph.regular.plus" class="faq-icon-plus" />
                                <x-icon path="ph.regular.minus" class="faq-icon-minus" />
                            </span>
                        </button>
                    </h3>

                    <div class="faq-answer" id="faq-answer-{{ $wid }}-{{ $index }}"
                        aria-labelledby="faq-question-{{ $wid }}-{{ $index }}" role="region" hx-boost="true"
                        hx-target="#main" hx-swap="outerHTML transition:true" hidden tabindex="-1">
                        <div class="faq-answer-content">
                            {!! markdown()->parse($faq['answer'], false) !!}
                        </div>
                    </div>
                </section>
            @endforeach
        </div>
    @endif
</div>
