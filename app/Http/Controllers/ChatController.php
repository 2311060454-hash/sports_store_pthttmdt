<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Lấy hoặc khởi tạo session ID của khách cho khung chat
     */
    protected function getChatSessionId(Request $request): string
    {
        $sessionId = $request->session()->get('chat_session_id');

        if (!$sessionId) {
            $sessionId = (string) Str::uuid();
            $request->session()->put('chat_session_id', $sessionId);
        }

        return $sessionId;
    }

    /**
     * Lấy danh sách tin nhắn của khách hàng
     */
    public function getMessages(Request $request)
    {
        $sessionId = $this->getChatSessionId($request);
        $userId = Auth::id();

        // Lấy các tin nhắn thuộc session_id hiện tại (hoặc user_id nếu đã login)
        $query = ChatMessage::query();
        if ($userId) {
            $query->where(function ($q) use ($sessionId, $userId) {
                $q->where('session_id', $sessionId)
                  ->orWhere('user_id', $userId);
            });
        } else {
            $query->where('session_id', $sessionId);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        // Đánh dấu các tin nhắn của admin là đã đọc
        ChatMessage::where('session_id', $sessionId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
            'messages' => $messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'message' => e($msg->message),
                    'time' => $msg->created_at->format('H:i'),
                    'is_read' => $msg->is_read,
                ];
            }),
        ]);
    }

    /**
     * Khách hàng gửi tin nhắn mới
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $sessionId = $this->getChatSessionId($request);
        $userId = Auth::id();

        $message = ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'sender_type' => 'user',
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_type' => $message->sender_type,
                'message' => e($message->message),
                'time' => $message->created_at->format('H:i'),
                'is_read' => $message->is_read,
            ],
        ]);
    }
}
