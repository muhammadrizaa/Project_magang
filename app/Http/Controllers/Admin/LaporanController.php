<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\SimpleType\Jc;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan admin
     */
    public function index(Request $request)
    {
        $karyawanList = User::where('role', 'karyawan')->orderBy('name')->get();

        $query = Evidence::with('user')->where('status', 'approved');

        $selectedMonthYear = $request->input('month_year');
        $selectedUserId = $request->input('user_id');
        $selectedMonth = null;
        $selectedYear = null;

        if ($selectedMonthYear) {
            list($selectedMonth, $selectedYear) = explode('-', $selectedMonthYear);
            $query->whereMonth('updated_at', $selectedMonth)
                ->whereYear('updated_at', $selectedYear);
        }

        if ($selectedUserId) {
            $query->where('user_id', $selectedUserId);
        }

        $approvedEvidences = $query->latest('updated_at')->get();

        $availableFilters = Evidence::select(
            DB::raw('YEAR(updated_at) as year'),
            DB::raw('MONTH(updated_at) as month')
        )
            ->where('status', 'approved')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.laporan.index', compact(
            'approvedEvidences',
            'availableFilters',
            'selectedMonth',
            'selectedYear',
            'karyawanList',
            'selectedUserId'
        ));
    }

    /**
     * Generate laporan
     */
    public function generate(Request $request)
    {
        $request->validate([
            'evidence_ids' => 'required|array|min:1',
            'evidence_ids.*' => 'exists:evidences,id',
            'format' => 'required|in:word,pdf'
        ], [
            'evidence_ids.required' => 'Mohon pilih minimal satu evidence untuk digenerate.'
        ]);

        $evidenceIds = $request->input('evidence_ids');
        $evidences = Evidence::with('user')->whereIn('id', $evidenceIds)->get();
        $format = $request->input('format');

        try {
            if ($format === 'word') {
                return $this->generateWord($evidences);
            }

            if ($format === 'pdf') {
                return $this->generatePdf($evidences);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat laporan: ' . $e->getMessage());
        }
    }

    /**
     * Generate Word (docx)
     */
    private function generateWord($evidences)
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        foreach ($evidences as $index => $evidence) {
            $section = $phpWord->addSection();

            // Header Logo
            $header = $section->addHeader();
            $table = $header->addTable(['width' => 100 * 50, 'unit' => 'pct']);
            $table->addRow();
            if (file_exists(public_path('images/logo-kiri.png'))) {
                $table->addCell(4500)->addImage(public_path('images/logo-kiri.png'), ['width' => 120]);
            }
            if (file_exists(public_path('images/logo-kanan.png'))) {
                $table->addCell(4500)->addImage(public_path('images/logo-kanan.png'), ['width' => 100, 'alignment' => Jc::END]);
            }

            $section->addTextBreak(1);
            $section->addText('EVIDENCE PEKERJAAN', ['bold' => true, 'size' => 14, 'underline' => 'single'], ['alignment' => Jc::CENTER, 'spaceAfter' => 300]);

            // Info Project
            $info = [
                'PROYEK'   => "PENGADAAN PEKERJAAN OUTSIDE PLANT FIBER TO THE HOME (OSP - FTTH)\nTAHUN 2025 TELKOM REGIONAL IV KALIMANTAN",
                'KONTRAK'  => '',
                'AREA'     => 'BANJARMASIN',
                'LOKASI'   => $evidence->lokasi ?? '-',
                'PELAKSANA'=> 'PT. TELKOM AKSES'
            ];

            $infoTable = $section->addTable();
            foreach ($info as $label => $value) {
                $infoTable->addRow();
                $infoTable->addCell(2000)->addText($label, ['bold' => true]);
                $infoTable->addCell(500)->addText(':');
                $infoTable->addCell(7000)->addText($value);
            }

            $section->addTextBreak(1);

            // Evidence Images
            if (is_array($evidence->file_path)) {
                $filesData = $this->normalizeFilesData($evidence->file_path);
                $imageChunks = array_chunk($filesData, 3);

                foreach ($imageChunks as $chunk) {
                    $imageTable = $section->addTable([
                        'borderSize' => 6,
                        'borderColor' => '000000',
                        'cellMargin' => 80,
                        'alignment' => Jc::CENTER
                    ]);

                    $imageTable->addRow();
                    foreach ($chunk as $fileData) {
                        $cell = $imageTable->addCell(3000);
                        // === PERUBAHAN DI SINI ===
                        // Menggunakan storage_path() untuk mendapatkan path file dari folder storage
                        $safePath = ltrim($fileData['path'], '/');
                        $fullPath = storage_path('app/public/' . $safePath);

                        if (file_exists($fullPath)) {
                            $cell->addImage($fullPath, [
                                'width' => 150,
                                'alignment' => Jc::CENTER
                            ]);
                        }
                    }

                    for ($i = count($chunk); $i < 3; $i++) {
                        $imageTable->addCell(3000);
                    }

                    $imageTable->addRow();
                    foreach ($chunk as $fileData) {
                        $imageTable->addCell(3000)->addText($fileData['caption'], ['size' => 9], ['alignment' => Jc::CENTER]);
                    }
                    for ($i = count($chunk); $i < 3; $i++) {
                        $imageTable->addCell(3000);
                    }
                }
            }

            if ($index < count($evidences) - 1) {
                $section->addPageBreak();
            }
        }

        $fileName = 'Laporan-Evidence-' . now()->format('d-m-Y') . '.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(storage_path($fileName));

        return response()->download(storage_path($fileName))->deleteFileAfterSend(true);
    }

    /**
     * Generate PDF
     */
    private function generatePdf($evidences)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);

        $logoAksesBase64 = file_exists(public_path('images/logo-kiri.png'))
            ? 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo-kiri.png')))
            : null;

        $logoIndonesiaBase64 = file_exists(public_path('images/logo-kanan.png'))
            ? 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo-kanan.png')))
            : null;

        // Normalisasi file evidence menjadi Base64
        foreach ($evidences as $evidence) {
            $normalizedFiles = [];
            if (is_array($evidence->file_path)) {
                foreach ($evidence->file_path as $file) {
                    $path = is_array($file) ? $file['path'] : $file;
                    $caption = is_array($file) && isset($file['caption']) ? $file['caption'] : '';

                    // === PERUBAHAN DI SINI ===
                    // Menggunakan storage_path() untuk mendapatkan path file dari folder storage
                    $safePath = ltrim($path, '/');
                    $fullPath = storage_path('app/public/' . $safePath);

                    if (file_exists($fullPath)) {
                        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                        $normalizedFiles[] = [
                            'base64' => 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($fullPath)),
                            'caption' => $caption
                        ];
                    }
                }
            }
            $evidence->file_path = $normalizedFiles;
        }

        $data = [
            'evidences' => $evidences,
            'logoAksesBase64' => $logoAksesBase64,
            'logoIndonesiaBase64' => $logoIndonesiaBase64
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf_template', $data)
            ->setPaper('A4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        $fileName = 'Laporan-Evidence-' . now()->format('d-m-Y') . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Normalisasi file evidence
     */
    private function normalizeFilesData($files)
    {
        if (!is_array($files)) {
            return [];
        }

        $normalized = [];
        foreach ($files as $file) {
            if (is_array($file) && isset($file['path'])) {
                $normalized[] = ['path' => $file['path'], 'caption' => $file['caption'] ?? ''];
            } elseif (is_string($file)) {
                $normalized[] = ['path' => $file, 'caption' => ''];
            }
        }
        return $normalized;
    }
}