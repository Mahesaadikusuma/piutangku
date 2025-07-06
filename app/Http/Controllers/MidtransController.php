<?php

namespace App\Http\Controllers;

use App\Service\MidtransService;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    protected MidtransService $midtransService;
    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function notificationHandler(Request $request)
    {
        return  $this->midtransService->handleWebHookNotification($request);
    }


    public function finishRedirect(Request $request)
    {
        return view('pages.success');
    }

    public function unfinishRedirect(Request $request)
    {
        return view('pages.unfinish');
    }

    public function errorRedirect(Request $request)
    {
        return view('pages.error');
    }
}
