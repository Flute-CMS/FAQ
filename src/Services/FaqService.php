<?php

namespace Flute\Modules\FAQ\src\Services;

use Flute\Core\Page\PageEditorParser;
use Flute\Modules\FAQ\database\Entities\FaqItem;
use Flute\Modules\FAQ\database\Entities\FaqItemBlock;
use Nette\Utils\Json;

class FaqService
{
    public function find($id)
    {
        $faqItem = rep(FaqItem::class)
            ->select()
            ->load('blocks')
            ->where(['id' => $id])
            ->fetchOne();

        if (!$faqItem) {
            throw new \Exception(__('faq.not_found'));
        }

        return $faqItem;
    }

    public function parseBlocks(FaqItem $faqItem)
    {
        /** @var PageEditorParser $parser */
        $parser = app(PageEditorParser::class);

        $blocks = Json::decode($faqItem->blocks->json ?? '[]', Json::FORCE_ARRAY);

        return $parser->parse($blocks);
    }

    public function store(string $question, $json)
    {
        $faqItem = new FaqItem();

        $faqItem->question = $question;

        $block = new FaqItemBlock();
        $block->json = $json;
        $block->faqItem = $faqItem;

        $faqItem->blocks = $block;

        transaction($faqItem)->run();
    }

    public function update(int $id, string $question, $json)
    {
        $faqItem = $this->find($id);

        $faqItem->question = $question;

        $block = new FaqItemBlock();
        $block->json = $json;
        $block->faqItem = $faqItem;

        $faqItem->blocks = $block;

        transaction($faqItem)->run();
    }

    public function delete(int $id): void
    {
        $faqItem = $this->find($id);

        transaction($faqItem, 'delete')->run();
    }
}
