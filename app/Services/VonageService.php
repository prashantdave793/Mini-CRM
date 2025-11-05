<?php
namespace App\Services;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Voice\OutboundCall;
use Vonage\Voice\Endpoint\Phone;
use Vonage\Voice\NCCO\NCCO;
use Vonage\Voice\NCCO\Action\Talk;
use Vonage\Voice\Webhook;
use Vonage\SMS\Message\SMS;

class VonageService
{
    protected $client;

    public function __construct()
    {
        $basic = new Basic(
            config('services.vonage.key'),
            config('services.vonage.secret')
        );
        $this->client = new Client($basic);
    }

      public function sendSMS($from, $to, $message)
    {
        $response = $this->client->sms()->send(
            new SMS($to, $from, $message)
        );

        $messageResponse = $response->current();
        if ($messageResponse->getStatus() != 0) {
            throw new \Exception('SMS failed with status: ' . $messageResponse->getStatus());
        }

        return $messageResponse;
    }
    public function makeCallTTS($from, $to, $text)
    {
        // Build endpoints
        $fromPhone = new Phone($from);
        $toPhone   = new Phone($to);

        // Build OutboundCall object
        $outbound = new OutboundCall($fromPhone, $toPhone);
    
        // Define answer webhook (optional) and event webhook (optional)
        // If not needed you might skip adding them.
        // For TTS you might not need custom webhooks.
        // $outbound->setAnswerWebhook(new Webhook('https://yourdomain.com/voice/answer'))
        //          ->setEventWebhook(new Webhook('https://yourdomain.com/voice/event'));

        // Create NCCO with Talk action
        $ncco = new NCCO();
        $ncco->addAction(new Talk($text));

        $outbound->setNCCO($ncco);

        // Make the call
        $response = $this->client->voice()->createOutboundCall($outbound);
        \Log::info("Vonage call response: " . json_encode($response));

        return $response;
    }
}
