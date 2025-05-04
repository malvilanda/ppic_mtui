<?php

namespace App\Controllers;

use App\Models\ItemModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\TransaksiModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Laporan extends BaseController
{
    protected $itemModel;
    protected $bahanBakuModel;
    protected $transaksiModel;
    protected $dompdf;
    
    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->transaksiModel = new TransaksiModel();

        // Inisialisasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $this->dompdf = new Dompdf($options);
    }
    
    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->bahanBakuModel = new \App\Models\BahanBakuModel();
    }
    
    public function index()
    {
        return view('laporan/index');
    }
    
    public function bahanBaku()
    {
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');
        $jenis = $this->request->getGet('jenis');

        $data = [
            'title' => 'Laporan Transaksi Bahan Baku',
            'transaksi' => $this->transaksiModel->getLaporanBahanBaku($start_date, $end_date, $jenis)
        ];

        return view('laporan/bahan_baku', $data);
    }
    
    public function tabung()
    {
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');
        $jenis = $this->request->getGet('jenis');

        $transaksi = $this->transaksiModel->getLaporanTabung($start_date, $end_date, $jenis);
        
        // Debugging
        log_message('debug', 'Data Transaksi: ' . json_encode($transaksi));
        if (empty($transaksi)) {
            log_message('debug', 'Transaksi kosong');
        } else {
            log_message('debug', 'Sample data: ' . json_encode($transaksi[0]));
        }

        $data = [
            'title' => 'Laporan Transaksi Tabung',
            'transaksi' => $transaksi
        ];
        
        return view('laporan/tabung', $data);
    }
    
    public function stokOpname()
    {
        $stockOpnameModel = new \App\Models\StockOpnameModel();
        $type = $this->request->getGet('type') ?? 'tabung';
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-01'); // Default awal bulan ini
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d'); // Default hari ini

        $data = [
            'title' => 'Laporan Stok Opname ' . ucfirst($type),
            'type' => $type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'opname_history' => $stockOpnameModel->getLaporanOpname($type, $start_date, $end_date)
        ];

        return view('laporan/stok_opname', $data);
    }
    
    public function exportBahanBaku()
    {
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');
        $jenis = $this->request->getGet('jenis');

        $data = $this->transaksiModel->getLaporanBahanBaku($start_date, $end_date, $jenis);

        // Set header untuk download Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Laporan_Bahan_Baku.xls"');

        echo view('laporan/export_bahan_baku', ['transaksi' => $data]);
    }

    public function exportTabung()
    {
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');
        $jenis = $this->request->getGet('jenis');

        $data = $this->transaksiModel->getLaporanTabung($start_date, $end_date, $jenis);

        // Set header untuk download Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Laporan_Tabung.xls"');

        echo view('laporan/export_tabung', ['transaksi' => $data]);
    }

    public function downloadPdfTabung()
    {
        try {
            $start_date = $this->request->getGet('start_date');
            $end_date = $this->request->getGet('end_date');
            $jenis = $this->request->getGet('jenis');

            // Validasi tanggal
            if (!empty($start_date) && !empty($end_date)) {
                if (strtotime($start_date) > strtotime($end_date)) {
                    return redirect()->back()->with('error', 'Tanggal akhir harus lebih besar dari tanggal mulai');
                }
            }

            $data = [
                'title' => 'Laporan Transaksi Tabung',
                'transaksi' => $this->transaksiModel->getLaporanTabung($start_date, $end_date, $jenis),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'jenis' => $jenis,
                'company' => [
                    'name' => 'PT. MTU Indonesia',
                    'address' => 'Jl. Raya Narogong Km. 18.5, Ds. Pasir Angin Kec. Cileungsi Bogor',
                    'phone' => '(021) 22950122',
                    'email' => 'marketing.support@mtu-indonesia.com'
                ]
            ];

            // Log data untuk debugging
            log_message('info', 'Generating PDF with data: ' . json_encode($data));

            // Generate HTML untuk PDF
            $html = view('laporan/pdf_tabung', $data);

            // Log HTML untuk debugging
            log_message('debug', 'Generated HTML: ' . $html);

            // Konfigurasi PDF
            $this->dompdf->loadHtml($html);
            $this->dompdf->setPaper('A4', 'landscape');
            $this->dompdf->render();

            // Generate nama file
            $filename = 'Laporan_Tabung_' . date('Y-m-d_His') . '.pdf';

            // Download file
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="'.$filename.'"')
                ->setBody($this->dompdf->output());

        } catch (\Exception $e) {
            log_message('error', 'Error generating PDF: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal menghasilkan PDF: ' . $e->getMessage());
        }
    }

    public function downloadPdfBahanBaku()
    {
        try {
            $start_date = $this->request->getGet('start_date');
            $end_date = $this->request->getGet('end_date');
            $jenis = $this->request->getGet('jenis');

            // Validasi tanggal
            if (!empty($start_date) && !empty($end_date)) {
                if (strtotime($start_date) > strtotime($end_date)) {
                    return redirect()->back()->with('error', 'Tanggal akhir harus lebih besar dari tanggal mulai');
                }
            }

            $data = [
                'title' => 'Laporan Transaksi Bahan Baku',
                'transaksi' => $this->transaksiModel->getLaporanBahanBaku($start_date, $end_date, $jenis),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'jenis' => $jenis,
                'company' => [
                    'name' => 'PT. MTU Indonesia',
                    'address' => 'Jl. Raya Narogong Km. 18.5, Ds. Pasir Angin Kec. Cileungsi Bogor',
                    'phone' => '(021) 22950122',
                    'email' => 'marketing.support@mtu-indonesia.com'
                ]
            ];

            // Log data untuk debugging
            log_message('info', 'Generating Bahan Baku PDF with data: ' . json_encode($data));

            // Generate HTML untuk PDF
            $html = view('laporan/pdf_bahan_baku', $data);

            // Log HTML untuk debugging
            log_message('debug', 'Generated HTML: ' . $html);

            // Konfigurasi PDF
            $this->dompdf->loadHtml($html);
            $this->dompdf->setPaper('A4', 'landscape');
            $this->dompdf->render();

            // Generate nama file
            $filename = 'Laporan_Bahan_Baku_' . date('Y-m-d_His') . '.pdf';

            // Download file
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment; filename="'.$filename.'"')
                ->setBody($this->dompdf->output());

        } catch (\Exception $e) {
            log_message('error', 'Error generating PDF: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal menghasilkan PDF: ' . $e->getMessage());
        }
    }
} 