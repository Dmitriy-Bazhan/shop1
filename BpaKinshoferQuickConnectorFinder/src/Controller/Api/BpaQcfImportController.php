<?php declare(strict_types=1);

namespace Bpa\KinshoferQuickConnectorFinder\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Component\Routing\Annotation\Route;

use Bpa\KinshoferQuickConnectorFinder\Service\ImportService;

/**
 * @RouteScope(scopes={"api"})
 */
class BpaQcfImportController extends AbstractController
{

    /**
     * @var ImportService
     */
    private $importService;

    public function __construct(
        ImportService $importService
    ) {
        $this->importService = $importService;
    }

    /**
     * @Route("/api/bpa-qcf-import/process", name="api.action.bpa_qcf_import.process", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function process(Request $request): JsonResponse
    {
        $params = $request->request->all();

        if( !isset($params['config']['method']) ) {
            return new JsonResponse([
                'state' => 'failed',
                'error' => 'Configuration invalid'
            ]);
        }

        /** @var UploadedFile|null $bpaCsvFile */
        $bpaCsvFile = $request->files->get('bpaCsvFile');

        if ($bpaCsvFile !== null) {
            $progress = $this->importService->processImport($bpaCsvFile, $params['config']['method']);

            return new JsonResponse($progress);
        }

        return new JsonResponse([
            'state' => 'failed',
            'error' => 'File has not been uploaded'
        ]);
    }

}

