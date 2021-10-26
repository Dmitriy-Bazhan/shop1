<?php declare(strict_types=1);

namespace WbpDeliveryReport\Controller\Api;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"api"})
 */
class ExportOrders extends AbstractController
{
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @Route("/api/_action/wbp-report/order/export", name="api.action.wbp-report.order.export", methods={"POST"})
     *
     * @param Request $request
     * @param Context $context
     * @throws \Exception
     */
    public function export(Request $request, Context $context)
    {
        if ($request->getContent()) {
            $warehouse = json_decode($request->getContent(), true)['warehouse'];
            $dateFrom = json_decode($request->getContent(), true)['dateFrom'];
            $dateTo = json_decode($request->getContent(), true)['dateTo'];

            $criteria = new Criteria();

            if ($dateFrom) {
                $criteria->addFilter(
                    new RangeFilter('orderDate', [
                        RangeFilter::GTE => $dateFrom,
                    ])
                );
            }
            if ($dateTo) {
                $criteria->addFilter(
                    new RangeFilter('orderDate', [
                        RangeFilter::LTE => $dateTo,
                    ])
                );
            }

            if ($warehouse && strlen($warehouse) > 2) {
                $criteria->addFilter(new ContainsFilter('customFields.custom_warehouse', $warehouse));
            }

            $orders = $this->container->get('order.repository')->search($criteria, $context);

            try {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A1', 'Order number');
                $sheet->setCellValue('B1', 'Warehouse');
                $sheet->setCellValue('C1', 'Order Date');
                $sheet->setCellValue('D1', 'Driver First');
                $sheet->setCellValue('E1', 'Driver Second');
                $sheet->setCellValue('F1', 'Driver first start');
                $sheet->setCellValue('G1', 'Driver first end');
                $sheet->setCellValue('H1', 'Driver first time');
                $sheet->setCellValue('I1', 'Driver second start');
                $sheet->setCellValue('I1', 'Driver second end');
                $sheet->setCellValue('J1', 'Driver second time');

                $i = 1;

                foreach ($orders as $order) {

                    $date = $order->getOrderDate();
                    $orderNum = $order->getOrderNumber();
                    if ($order->getCustomFields() && array_key_exists('custom_warehouse', $order->getCustomFields())) {

                        $i++;

                        $warehouse = array_key_exists('custom_warehouse', $order->getCustomFields()) ? $order->getCustomFields()['custom_warehouse'] : null;
                        $driverOne = array_key_exists('custom_driver1', $order->getCustomFields()) ? $order->getCustomFields()['custom_driver1'] : null;
                        $driverSecond = array_key_exists('custom_driver2', $order->getCustomFields()) ? $order->getCustomFields()['custom_driver2'] : null;
                        $driverOneStart = array_key_exists('custom_driver1_start', $order->getCustomFields()) ? $this->dateConvert($order->getCustomFields()['custom_driver1_start']) : null;
                        $driverSecondStart = array_key_exists('custom_driver2_start', $order->getCustomFields()) ? $this->dateConvert($order->getCustomFields()['custom_driver2_start']) : null;
                        $driverOneEnd = array_key_exists('custom_driver1_end', $order->getCustomFields()) ? $this->dateConvert($order->getCustomFields()['custom_driver1_end']) : null;
                        $driverSecondEnd = array_key_exists('custom_driver2_end', $order->getCustomFields()) ? $this->dateConvert($order->getCustomFields()['custom_driver2_end']) : null;
                        $driverOneTime = array_key_exists('custom_driver1_time', $order->getCustomFields()) ? $order->getCustomFields()['custom_driver1_time'] : null;
                        $driverSecondTime = array_key_exists('custom_driver2_time', $order->getCustomFields()) ? $order->getCustomFields()['custom_driver2_time'] : null;

                        $sheet->setCellValue('A' . $i, $orderNum);
                        $sheet->setCellValue('B' . $i, $warehouse);
                        $sheet->setCellValue('C' . $i, $date);
                        $sheet->setCellValue('D' . $i, $driverOne);
                        $sheet->setCellValue('E' . $i, $driverSecond);
                        $sheet->setCellValue('F' . $i, $driverOneStart);
                        $sheet->setCellValue('G' . $i, $driverOneEnd);
                        $sheet->setCellValue('H' . $i, $driverOneTime);
                        $sheet->setCellValue('I' . $i, $driverSecondStart);
                        $sheet->setCellValue('J' . $i, $driverSecondEnd);
                        $sheet->setCellValue('K' . $i, $driverSecondTime);
                    }
                }
                $publicDirectory = $this->get('kernel')->getProjectDir() . '/files/wbporderreport';
                $path = $publicDirectory . '/order-report.xls';
                $writer = new xls($spreadsheet);
                $writer->save($path);

                return new JsonResponse(['result' => true]);
            } catch (\Exception $e) {
                return new JsonResponse(['result' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    /**
     * @Route("/api/_action/wbp-report/download", name="api.action.wbp-report.download", defaults={"auth_required"=false}, methods={"GET"})
     */
    public function getFileReports()
    {
        $publicDirectory = $this->get('kernel')->getProjectDir() . '/files/wbporderreport';
        $path = $publicDirectory . '/order-report.xls';

        if(file_exists($path) == false) {
            echo "<script>window.close();</script>";
            exit;
        }

        $response = new BinaryFileResponse($path);
        $response->headers->set('Content-Type', 'text/xls');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        $response->send();

        exit;
    }

    /**
     * @throws \Exception
     */
    private function dateConvert($date)
    {
        $date = new \DateTime($date);
        return $date->format('Y-m-d H:i:s');
    }
}
