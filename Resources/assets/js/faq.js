document.addEventListener('DOMContentLoaded', function () {
    initFAQAccordions();

    if (document.querySelector('.faq-settings-form')) {
        initFAQSettings();
    }
});

document.addEventListener('htmx:afterSwap', () => {
    initFAQAccordions();
    initFAQSettings();
});

document.addEventListener('widgetSettingsLoaded', () => {
    initFAQSettings();
});

document.addEventListener('widgetInitialized', (e) => {
    if (e.detail.widgetName === 'faq') {
        initFAQAccordions();
    }
});

document.addEventListener('widgetRefreshed', (e) => {
    if (e.detail.widgetName === 'faq') {
        initFAQAccordions();
    }
});

/**
 * Initializes FAQ accordion functionality
 */
function initFAQAccordions() {
    const faqWidgets = document.querySelectorAll('.faq-widget');

    faqWidgets.forEach(widget => {
        if (widget.hasAttribute('data-faq-initialized')) return;
        widget.setAttribute('data-faq-initialized', 'true');

        const allowMultiple = widget.dataset.allowMultiple === 'true';
        const faqItems = widget.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            const answer = item.querySelector('.faq-answer');

            if (!question || !answer) return;

            if (question.hasAttribute('data-initialized')) return;
            question.setAttribute('data-initialized', 'true');

            question.addEventListener('click', () => {
                const isExpanded = question.getAttribute('aria-expanded') === 'true';

                if (!allowMultiple && !isExpanded) {
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            const otherQuestion = otherItem.querySelector('.faq-question');
                            const otherAnswer = otherItem.querySelector('.faq-answer');

                            if (otherQuestion && otherAnswer) {
                                closeFAQItem(otherItem);
                            }
                        }
                    });
                }

                if (isExpanded) {
                    closeFAQItem(item);
                } else {
                    openFAQItem(item);
                }
            });

            question.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });

            answer.style.maxHeight = '0';
            answer.style.overflow = 'hidden';
            answer.style.transition = 'max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            question.setAttribute('aria-expanded', 'false');
        });
    });
}

/**
 * Initializes FAQ settings management
 */
function initFAQSettings() {
    const faqsList = document.getElementById('faqs-list');
    const btnAddFAQ = document.getElementById('btn-add-faq');
    const template = document.getElementById('faq-item-template');

    if (!faqsList || !btnAddFAQ || !template) return;

    if (btnAddFAQ.hasAttribute('data-faq-initialized')) return;
    btnAddFAQ.setAttribute('data-faq-initialized', 'true');

    /**
     * Updates FAQ indices
     */
    function updateFAQIndices() {
        const items = faqsList.querySelectorAll('.faq-item');
        const emptyState = document.getElementById('faqs-empty');
        const faqsCount = document.querySelector('.faqs-count');

        items.forEach((item, index) => {
            item.dataset.index = index;
            const title = item.querySelector('.faq-title');
            if (title) {
                title.textContent = title.textContent.replace(/#\d+/, `#${index + 1}`);
            }

            const inputs = item.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/faqs\[\d+\]/, `faqs[${index}]`));
                }
            });
        });

        if (items.length === 0) {
            if (!emptyState) {
                const emptyDiv = document.createElement('div');
                emptyDiv.id = 'faqs-empty';
                emptyDiv.className = 'faqs-empty';
                emptyDiv.innerHTML = `
                    <p class="empty-text">${window.faqLang?.no_faqs_yet || 'No FAQs created yet'}</p>
                    <p class="empty-subtext">${window.faqLang?.add_first_faq || 'Click "Add FAQ" to create your first question'}</p>
                `;
                faqsList.appendChild(emptyDiv);
            }
            if (faqsCount) faqsCount.style.display = 'none';
        } else {
            if (emptyState) emptyState.remove();
            if (faqsCount) {
                faqsCount.style.display = 'flex';
                const countText = faqsCount.querySelector('.count-text');
                if (countText) {
                    countText.textContent = `${items.length} ${window.faqLang?.faqs_total || 'FAQs total'}`;
                }
            }
        }
    }

    btnAddFAQ.addEventListener('click', function () {
        const itemCount = faqsList.querySelectorAll('.faq-item').length;
        const nextIndex = itemCount;
        const uniqId = Date.now();

        const templateContent = template.innerHTML
            .replace(/{index}/g, nextIndex)
            .replace(/{number}/g, nextIndex + 1)
            .replace(/{uniqid}/g, uniqId);

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = templateContent;
        const faqItem = tempDiv.firstElementChild;

        const emptyState = document.getElementById('faqs-empty');
        if (emptyState) emptyState.remove();

        faqsList.appendChild(faqItem);
        updateFAQIndices();

        setTimeout(() => {
            const newEditor = faqItem.querySelector('textarea[name*="answer"]');
            if (newEditor && window.initEditor) {
                window.initEditor(newEditor);
            }
        }, 100);

        const questionInput = faqItem.querySelector('input[name*="question"]');
        if (questionInput) {
            questionInput.focus();
        }
    });

    faqsList.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.btn-remove-faq');
        if (removeBtn) {
            const faqItem = removeBtn.closest('.faq-item');
            if (faqItem) {
                removeFAQItemWithAnimation(faqItem);
            }
        }
    });

    updateFAQIndices();
}

/**
 * Opens FAQ item
 */
function openFAQItem(faqItem) {
    const question = faqItem.querySelector('.faq-question');
    const answer = faqItem.querySelector('.faq-answer');
    const content = answer.querySelector('.faq-answer-content');

    faqItem.classList.add('faq-open');
    question.setAttribute('aria-expanded', 'true');

    const contentHeight = content.scrollHeight;
    answer.style.maxHeight = contentHeight + 'px';

    setTimeout(() => {
        if (faqItem.classList.contains('faq-open')) {
            answer.style.maxHeight = 'none';
        }
    }, 300);
}

/**
 * Closes FAQ item
 */
function closeFAQItem(faqItem) {
    const question = faqItem.querySelector('.faq-question');
    const answer = faqItem.querySelector('.faq-answer');
    const content = answer.querySelector('.faq-answer-content');

    answer.style.maxHeight = content.scrollHeight + 'px';

    answer.offsetHeight;

    faqItem.classList.remove('faq-open');
    question.setAttribute('aria-expanded', 'false');
    answer.style.maxHeight = '0';
}

/**
 * Removes FAQ item
 */
function removeFAQItemWithAnimation(faqItem) {
    faqItem.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
    faqItem.style.opacity = '0';
    faqItem.style.transform = 'translateX(-10px)';
    faqItem.style.maxHeight = faqItem.offsetHeight + 'px';

    setTimeout(() => {
        faqItem.style.maxHeight = '0';
        faqItem.style.marginBottom = '0';
        faqItem.style.paddingTop = '0';
        faqItem.style.paddingBottom = '0';
    }, 150);

    setTimeout(() => {
        faqItem.remove();
        updateFAQIndices();
    }, 300);
}

/**
 * Updates FAQ numbers and indices
 */
function updateFAQIndices() {
    const faqsList = document.getElementById('faqs-list');
    if (!faqsList) return;

    const items = faqsList.querySelectorAll('.faq-item');
    const emptyState = document.getElementById('faqs-empty');
    const faqsCount = document.querySelector('.faqs-count');

    items.forEach((item, index) => {
        item.dataset.index = index;
        const title = item.querySelector('.faq-title');
        if (title) {
            title.textContent = title.textContent.replace(/#\d+/, `#${index + 1}`);
        }

        const inputs = item.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            }
        });
    });

    if (items.length === 0) {
        if (!emptyState) {
            const emptyDiv = document.createElement('div');
            emptyDiv.id = 'faqs-empty';
            emptyDiv.className = 'faqs-empty';
            emptyDiv.innerHTML = `
                <p class="empty-text">${window.faqLang?.no_faqs_yet || 'No FAQs created yet'}</p>
                <p class="empty-subtext">${window.faqLang?.add_first_faq || 'Click "Add FAQ" to create your first question'}</p>
            `;
            faqsList.appendChild(emptyDiv);
        }
        if (faqsCount) faqsCount.style.display = 'none';
    } else {
        if (emptyState) emptyState.remove();
        if (faqsCount) {
            faqsCount.style.display = 'flex';
            const countText = faqsCount.querySelector('.count-text');
            if (countText) {
                countText.textContent = `${items.length} ${window.faqLang?.faqs_total || 'FAQs total'}`;
            }
        }
    }
}

function smoothScrollTo(element, offset = 0) {
    const elementPosition = element.getBoundingClientRect().top;
    const offsetPosition = elementPosition + window.pageYOffset - offset;

    window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
    });
} 