<?php

namespace App\Listeners;

use App\Models\Eticket\Eticket;
use App\Events\EticketCreated;
use App\Mail\EticketPdfMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class SendEticketPdf
{
    /**
     * Handle the event.
     *
     * @param  EticketCreated  $event
     * @return void
     */
    public function handle(EticketCreated $event)
    {
        $eticketId = $event->eticket['id'];

        $data = Eticket::where('id', $eticketId)->with(['motive', 'originPort', 'embarkationPort', 'disembarkationPort', 'airline', 
            'travelers' => function($query) use ($eticketId) {
                return $query->with(['relationship', 'occupation', 'civilStatus', 'birthPlace', 'nationality', 'residentialCountry', 'city', 
                        'sector' => ['municipality' => ['province']],
                        'travelerInformation' => function($query) use ($eticketId) {
                            return $query->with(['hotel'])->where('eticket_id', $eticketId);
                        },
                        'customsInformation' => function($query) use ($eticketId) {
                            return $query->where('eticket_id', $eticketId);
                        }]);
            }])->first();

        $eticket = [];
        $eticket['eticket'] = $data;
        $pdf = PDF::loadView('pdf.eticket_pdf', $eticket);

        // Send Email
        Mail::to('no-reply@xperiencedominicanrepublic.com')->send(new EticketPdfMail($pdf));
    }
}
