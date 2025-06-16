<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DeepSeekService;

class ChatController extends Controller
{
    protected $deepSeek;

    public function __construct(DeepSeekService $deepSeek)
    {
        $this->deepSeek = $deepSeek;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = $request->input('message');

        $result = $this->deepSeek->chat($message);

        if (isset($result['error'])) {
            return response()->json([
                'error' => $result['error'],
                'details' => $result['details'] ?? null,
            ], 500);
        }

        return response()->json([
            'response' => $result['answer'],
            'usage' => $result['usage'] ?? [],
        ]);
    }
}



