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
        $gather->say('press 1 for marketing, press 2 for customer care');
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
            case 0:
                return back;
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
        $gather->say('Marketing, press 1 to listen to ...., press 2 to speak with an agent');
        return response($response)->header('Content-Type', 'text/xml');
    }

    public function marketing()
    {
        $response = new VoiceResponse();
        $response->say("This is the marketing department. More can be said here");
        return response($response)->header('Content-Type', 'text/xml');
    }

    public function speakToAgent(Request $request)
    {
        $response = new VoiceResponse();
        $phoneInput = $request->input('Digits');
        switch ($phoneInput) {
            case 1:
                return $this->instruction();
            case 2:
                //add caller to queue for agent
                $response->say('Thanks for reaching out. You have been added to a queue. An agent will get to you shortly');
                $response->enqueue('support', ['url' => 'about_to_connect.xml']); //fix
                return response($response)->header('Content-Type', 'text/xml');
            case 0:
                return back;
        }

        return response($response)->header('Content-Type', 'text/xml');
    }

    public function makeCall()
    {
        // make the call to your agent
        $client = new Client(getenv("TWILIO_ACCOUNT_SID"), getenv("TWILIO_AUTH_TOKEN"));

        //agent phone rings
        $call = $client->calls->create(
            +2347058096684,
            +16606284326,
            ['url' => 'https://4860-160-152-49-62.ngrok-free.app/agent-accept-call']
        );
    }

    public function agentAcceptCall()
    {
        try {
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
