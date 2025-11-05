<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ActivityLog;
use App\Services\VonageService;
use Illuminate\Support\Facades\Auth;

class VonageController extends Controller {
    protected $vonage;
    public function __construct(VonageService $vonage) {
        $this->vonage = $vonage;
    }

    public function sendSms(Request $request, Customer $customer) {
        $request->validate(['message'=>'required|string|max:1000']);
        $from = config('services.vonage.from');
        // $to = $customer->phone;
        $to = '+'.$customer->phone;
        if(!$to) return back()->with('error','Customer has no phone.');

        try {
            $res = $this->vonage->sendSMS($from, $to, $request->message);
            ActivityLog::create([
                'user_id'=>Auth::id(),
                'type'=>'sms_sent',
                'description'=>"Sent SMS to {$customer->name}: ".$request->message,
                'customer_id'=>$customer->id
            ]);
            // print_r($res);die;
            return back()->with('success','SMS sent.');
        } catch (\Exception $e) {
            return back()->with('error','Vonage error: '.$e->getMessage());
        }
    }

    public function call(Request $request, Customer $customer) {
        $from = config('services.vonage.from');
        $to = $customer->phone;
        $to = preg_match('/^\+/', $customer->phone) ? $customer->phone : '+91'.$customer->phone;

        if(!$to) return back()->with('error','Customer has no phone.');

        $text = "Hello {$customer->name}, this is a call from Mini CRM.";
        try {
            $call = $this->vonage->makeCallTTS($from, $to, $text);
            ActivityLog::create([
                'user_id'=>Auth::id(),
                'type'=>'call_made',
                'description'=>"Initiated call to {$customer->name}",
                'customer_id'=>$customer->id
            ]);
            return back()->with('success','Call initiated (check Vonage dashboard).');
        } catch (\Exception $e) {
            return back()->with('error','Vonage error: '.$e->getMessage());
        }
    }
}
