<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class OrderPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdfPath;

    public function __construct($order, $pdfPath)
    {
        $this->order = $order;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        $filePath = 'orders/' . 'orden-' . $this->order->id . '.pdf';
        $fileContent = Storage::disk('public')->get($filePath);
        
        return $this->subject('Tu orden PDF')
                    ->view('orderEmail')
                    ->attachData($fileContent, 'orden-' . $this->order->id . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
        
    }
}

