<?php

namespace Flute\Modules\FAQ\src\Http\Controllers\View;

use Flute\Core\Admin\Http\Middlewares\HasPermissionMiddleware;
use Flute\Core\Admin\Services\PageGenerator\AdminFormPage;
use Flute\Core\Admin\Services\PageGenerator\AdminInput;
use Flute\Core\Admin\Services\PageGenerator\AdminTablePage;
use Flute\Core\Support\AbstractController;
use Flute\Core\Support\FluteRequest;
use Flute\Modules\FAQ\database\Entities\FaqItem;
use Flute\Modules\FAQ\src\Services\FaqService;

class FAQView extends AbstractController
{
    protected FaqService $faqService;

    public function __construct(FaqService $faqService)
    {
        HasPermissionMiddleware::permission(['admin', 'admin.faq']);
        $this->middleware(HasPermissionMiddleware::class);

        $this->faqService = $faqService;
    }

    public function list(FluteRequest $request)
    {
        $table = table();

        $table->setPhrases([
            'question' => __('faq.admin.question')
        ]);

        $table->fromEntity(rep(FaqItem::class)->findAll(), ['blocks'])->withActions('faq');

        $pageGenerator = new AdminTablePage();
        $pageContent = $pageGenerator
            ->setTitle('faq.admin.header')
            ->setHeader('faq.admin.header')
            ->setDescription('faq.admin.description')
            ->setContent($table->render())
            ->setWithAddBtn(true)
            ->setBtnAddPath('/admin/faq/add');

        return $pageContent->generatePage();
    }

    public function add(FluteRequest $request)
    {
        $question = new AdminInput('question', 'faq.admin.question', 'faq.admin.question_desc', 'text', true);
        $editor = new AdminInput('editor', 'faq.admin.content', '', 'editorjs');

        $formPage = new AdminFormPage(
            'faq.admin.add_title',
            'faq.admin.add_desc',
            '/admin/faq/list',
            'add',
            'faq'
        );

        $formPage->addInput($question);
        $formPage->addInput($editor);

        return $formPage->render();
    }

    public function edit(FluteRequest $request, $id)
    {
        try {
            $faq = $this->faqService->find((int) $id);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 404);
        }

        $id = new AdminInput('id', '', '', 'hidden', true, $faq->id, true);
        $question = new AdminInput('question', 'faq.admin.question', 'faq.admin.question_desc', 'text', true, $faq->question);
        $editor = new AdminInput('editor', 'faq.admin.content', '', 'editorjs', true, $faq->blocks->json);

        $formPage = new AdminFormPage(
            'faq.admin.edit_title',
            'faq.admin.edit_desc',
            '/admin/faq/list',
            'edit',
            'faq'
        );

        $formPage->addInput($id);
        $formPage->addInput($question);
        $formPage->addInput($editor);

        return $formPage->render();
    }
}