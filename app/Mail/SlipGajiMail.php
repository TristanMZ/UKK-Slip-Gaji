<?php

namespace App\Mail;

use App\Models\SlipGaji;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SlipGajiMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SlipGaji $slipGaji
    ) {
    }

    public function build()
    {
        $slipGaji = $this->slipGaji->load('karyawan');

        return $this->subject('Slip Gaji ' . $slipGaji->karyawan->nama . ' - ' . $slipGaji->periode_awal->format('d M Y'))
            ->view('emails.slip-gaji', [
                'slipGaji' => $slipGaji,
            ]);
    }
}
