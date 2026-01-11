<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RateLimitController extends Controller
{
    public function basic()
    {
        return response()->json([
            'message' => 'Basic request successful!',
            'limit' => '10 requests per minute',
            'timestamp' => now()->format('h:i:s A')
        ]);
    }

    public function strict()
    {
        return response()->json([
            'message' => 'Request successful! Strict rate limit: 3 requests per minute',
            'limit' => '3 requests per minute',
            'timestamp' => now()->format('h:i:s A')
        ]);
    }

    public function custom()
    {
        return response()->json([
            'message' => 'Custom request successful!',
            'limit' => '5 requests per minute',
            'timestamp' => now()->format('h:i:s A')
        ]);
    }

    public function noLimit()
    {
        return response()->json([
            'message' => 'Request successful! No rate limit applied to this endpoint',
            'limit' => 'Unlimited',
            'timestamp' => now()->format('h:i:s A')
        ]);
    }
}
