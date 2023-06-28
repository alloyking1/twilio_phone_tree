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
            'action' => route('phone-tree.customer-care'),
            'method' => 'GET'
        ]);
        $gather->say('press 1 for customer care, press 2 for marketing');
        $response->say('We didn\'t receive any input. Goodbye!');
        return response($response)->header('Content-Type', 'text/xml');
    }

    public function customerCare(Request $request)
    {
        $response = new VoiceResponse();
        $phoneInput = $request->input('Digits');
        switch ($phoneInput) {
            case 1:
                return $this->marketing();
            case 2:
                return $this->speakToAgent();
            case 0:
                return back;
        }

        return response($response)->header('Content-Type', 'text/xml');
    }

    public function marketing()
    {
        $response = new VoiceResponse();
        $gather = $response->gather([
            'numDigits' => 1,
            'action' => route('phone-tree.customer-care'),
            'method' => 'GET'
        ]);
        $gather->say('Marketing, press 1 to listen to ...., press 2 to speak with an agent');
        return response($response)->header('Content-Type', 'text/xml');
    }

    public function speakToAgent()
    {
        $response = new VoiceResponse();
        $response->say("this is the customer care department");
        return response($response)->header('Content-Type', 'text/xml');
    }

    public function render()
    {
        return view('livewire.phone-tree-livewire-controller');
    }
}
