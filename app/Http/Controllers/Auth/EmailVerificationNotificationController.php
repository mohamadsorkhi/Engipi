<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            session()->flash("success","ایمیل شما فعال میباشد.");
            return response()->json(["success" => true,"redirect"=>route("app.profile.index")]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(["success" => true,"message"=>"لینک فعالسازی به ایمیل شما ارسال شد. لطفا در صورت عدم مشاهده پوشه ی spam را نیز چک کنید."]);
    }
}
