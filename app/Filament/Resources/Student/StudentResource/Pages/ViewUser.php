<?php

namespace App\Filament\Resources\Student\StudentResource\Pages;

use App\Filament\Resources\Student\StudentResource;
use Filament\Resources\Pages\ViewRecord;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;

class ViewUser extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    public function getView(): string
    {
        return 'filament.resources.users.view';
    }

    public function getViewData(): array
    {
        $siswa = $this->record;
        $writer = new PngWriter();

        $qrCode = new QrCode(
            data: $siswa->nisn ?? 'NIS-DEFAULT',
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        $logo = new Logo(
            path: public_path('favicon.jpg'),
            resizeToWidth: 50,
            punchoutBackground: true
        );

        $label = new Label(
            text: 'QR Siswa',
            textColor: new Color(255, 0, 0)
        );

        $result = $writer->write($qrCode, null, $label);
        $dataUri = $result->getDataUri();

        return [
            'record' => $siswa,
            'qrCodeDataUri' => $dataUri,
        ];
    }
}
