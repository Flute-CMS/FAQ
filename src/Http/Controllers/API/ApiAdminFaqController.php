<?php

namespace Flute\Modules\FAQ\src\Http\Controllers\API;

use Flute\Core\Admin\Http\Middlewares\HasPermissionMiddleware;
use Flute\Core\Support\AbstractController;
use Flute\Core\Support\FluteRequest;
use Flute\Modules\FAQ\src\Services\FaqService;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminFaqController extends AbstractController
{
    protected $faqService;

    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;

        HasPermissionMiddleware::permission(['admin', 'admin.faq']);
    }

    public function store(FluteRequest $request): Response
    {
        try {
            $this->faqService->store(
                $request->question,
                $request->input('blocks', '[]')
            );

            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function delete(FluteRequest $request, $id): Response
    {
        try {
            $this->faqService->delete((int) $id);

            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function update(FluteRequest $request): Response
    {
        $id = $request->input('id', 0);

        try {
            $this->faqService->update(
                (int) $id,
                $request->question,
                $request->input('blocks', '[]')
            );

            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
