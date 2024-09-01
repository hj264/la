<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use JetBrains\PhpStorm\NoReturn;

class WebhookController extends Controller
{
    public function webhook(Request $request): JsonResponse
    {
        $data = $request->all();
        $data = json_encode($data, true);
        Redis::rpush('chat_message', $data);

        return $this->success($data);
    }
}

