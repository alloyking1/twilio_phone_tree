<?php

namespace App\Http\Livewire;

use Twilio\TwiML\VoiceResponse;
use Twilio\Rest\Client;
use Illuminate\Http\Request;

use Livewire\Component;

class PhoneTreeLivewireController extends Component
{

    public function call()
    {
        $response = new VoiceResponse();
        $gather = $response->gather([
            'numDigits' => 1,
            'action' => route('phone-tree.process-call'),
            'method' => 'GET'
        ]);
        $gather->say('Thanks for calling. This is a phone tree application for educational purpose. press 1 for marketing, press 2 for customer care');
        $response->say('We didn\'t receive any input. Goodbye!');
        return response($response)->header('Content-Type', 'text/xml');
    }

    public function processCall(Request $request)
    {
        $response = new VoiceResponse();
        $phoneInput = $request->input('Digits');
        switch ($phoneInput) {
            case 1:
                return $this->marketing();
            case 2:
                return $this->customerCare();
                // default:
                //     return back;
        }

        return response($response)->header('Content-Type', 'text/xml');
    }

    public function customerCare()
    {
        $response = new VoiceResponse();
        $gather = $response->gather([
            'numDigits' => 1,
            'action' => route('phone-tree.speak-to-agent'),
            'method' => 'GET'
        ]);
        $gather->say('This is the customer care department, press 1 to listen to a helpful voice note or press 2 to speak with an agent');
        return response($response)->header('Content-Type', 'text/xml');
    }

    public function marketing()
    {
        $response = new VoiceResponse();
        $response->say("This is the marketing department. Instructions on how to solve your marketing issues will go here");
        return response($response)->header('Content-Type', 'text/xml');
    }

    public function speakToAgent(Request $request)
    {
        $response = new VoiceResponse();
        $phoneInput = $request->input('Digits');
        switch ($phoneInput) {
            case 1:
                return $response->say('Customer is king. This is the most helpful instruction you can get here. You can speak to an agent if you need more help');
            case 2:
                $response->say('Thanks for reaching out. You have been added to a queue. An agent will get to you shortly');
                $response->enqueue('support', ['url' => 'about_to_connect.xml']);
                return response($response)->header('Content-Type', 'text/xml');
            case 0:
                return back;
        }

        return response($response)->header('Content-Type', 'text/xml');
    }

    public function makeCall()
    {
        csrf_token();
        $client = new Client(getenv("TWILIO_ACCOUNT_SID"), getenv("TWILIO_AUTH_TOKEN"));
        $call = $client->calls->create(
            +2347058096684,
            +16606284326,
            ['url' => 'https://4860-160-152-49-62.ngrok-free.app/agent-accept-call']
        );
    }

    public function agentAcceptCall()
    {
        try {
            csrf_token();
            $response = new VoiceResponse();
            $dial = $response->dial();
            $dial->queue('support');
            return $response;
        } catch (TwimlException $e) {
            return $e->getCode();
        }
    }

    public function render()
    {
        return view('livewire.phone-tree-livewire-controller');
    }
}
