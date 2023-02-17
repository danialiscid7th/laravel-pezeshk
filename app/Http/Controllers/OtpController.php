<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{

    public function setOtp(Request $request)
    {
        $otp = floor(999 + rand(0, 9000));

        $message = Http::get("https://api.kavenegar.com/v1/4E32754741505A4F4B4964737A6C6D725764683251357262646C64625854794A73452B476F696443636A6F3D/verify/lookup.json", [
            'receptor' => $request->phone,
            'token' => $otp,
            'template' => 'test2',
        ]);

        $result = Otp::create([
            'phone' => $request->phone,
            'otp' => $otp,
        ]);
        return $message;
    }
    public function getOtp(Request $request)
    {
        $result = Otp::where('phone', $request->phone)->orderBy('id', 'desc')->first();
        return $result;
    }
    public function checkOtp(Request $request)
    {
        $result = Otp::where('phone', $request->phone)->where('otp', $request->otp)->first();
        if ($result) {
            return 'true';
        } else {
            return 'false';
        }
    }
}
