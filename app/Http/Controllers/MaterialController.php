<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipArchive;
use Storage;

class MaterialController extends Controller
{
    public function downloadMaterials()
    {
        $qrUrl = 'https://psytestprof.ru';

        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($qrUrl);

        $zipFileName = 'materials_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $linkText = "Ссылка на электронную тетрадь:\n\nhttps://psytestprof.ru\n\nДля доступа к онлайн тестам перейдите по ссылке выше или отсканируйте QR-код из архива.";

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $pdfPath = public_path('pdf/Testbook.pdf');
            if (file_exists($pdfPath)) {
                $zip->addFile($pdfPath, 'Testbook.pdf');
            }

            $zip->addFromString('qr_tests.png', $qrCode);
            $zip->addFromString('ссылка_на_тесты.txt', $linkText);

            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Ошибка создания архива');
    }

    public function generateTestQr($testId)
    {

        $test = \App\Models\Test::where('code', $testId)->first();

        if ($test) {

            $testUrl = 'https://psytestprof.ru/tests/' . $testId;
        } else {

            $staticTests = [
                'temperament' => 'https://psytestprof.ru/pdf/1.pdf',
                'personality-type' => 'https://psytestprof.ru/pdf/2.pdf',
                'interests-map' => 'https://psytestprof.ru/pdf/3.pdf',
                'test-4' => 'https://psytestprof.ru/pdf/4.pdf',
                'test-5' => 'https://psytestprof.ru/pdf/5.pdf',
            ];

            $testUrl = $staticTests[$testId] ?? 'https://psytestprof.ru';
        }

        return response(QrCode::format('svg')->size(150)->generate($testUrl))
            ->header('Content-Type', 'image/svg+xml');
    }
}
