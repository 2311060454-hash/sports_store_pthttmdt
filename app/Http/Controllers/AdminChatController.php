<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminChatController extends Controller
{
    /**
     * Danh sách hội thoại và màn hình Live Chat của Admin
     */
    public function index(Request $request)
    {
        // Lấy danh sách các phiên chat nhóm theo session_id kèm tin nhắn mới nhất
        $sessions = ChatMessage::select('session_id', DB::raw('MAX(id) as last_msg_id'), DB::raw('MAX(created_at) as last_activity'))
            ->groupBy('session_id')
            ->orderBy('last_activity', 'desc')
            ->paginate(15);

        // Nạp thông tin chi tiết của từng hội thoại
        $conversations = collect();
        foreach ($sessions as $item) {
            $lastMsg = ChatMessage::with('user')->find($item->last_msg_id);
            if ($lastMsg) {
                $unreadCount = ChatMessage::where('session_id', $item->session_id)
                    ->where('sender_type', 'user')
                    ->where('is_read', false)
                    ->count();

                $conversations->push((object)[
                    'session_id' => $item->session_id,
                    'user' => $lastMsg->user,
                    'last_message' => $lastMsg->message,
                    'last_sender' => $lastMsg->sender_type,
                    'last_time' => $lastMsg->created_at,
                    'unread_count' => $unreadCount,
                ]);
            }
        }

        $activeSessionId = $request->get('session_id', $conversations->first()?->session_id);

        return view('admin.chats.index', compact('conversations', 'sessions', 'activeSessionId'));
    }

    /**
     * Lấy danh sách tin nhắn theo session_id (API/AJAX cho admin)
     */
    public function getConversation($sessionId)
    {
        $messages = ChatMessage::where('session_id', $sessionId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Đánh dấu các tin nhắn của user gửi cho admin là đã đọc
        ChatMessage::where('session_id', $sessionId)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
            'messages' => $messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'sender_name' => $msg->sender_type === 'admin' ? 'Quản trị viên' : ($msg->user ? $msg->user->name : 'Khách hàng'),
                    'message' => e($msg->message),
                    'time' => $msg->created_at->format('H:i d/m'),
                    'is_read' => $msg->is_read,
                ];
            }),
        ]);
    }

    /**
     * Admin gửi tin nhắn trả lời
     */
    public function reply(Request $request, $sessionId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Tìm user_id nếu session này có gắn với user
        $existing = ChatMessage::where('session_id', $sessionId)->whereNotNull('user_id')->first();
        $userId = $existing ? $existing->user_id : null;

        $message = ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'sender_type' => 'admin',
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_type' => $message->sender_type,
                'sender_name' => 'Quản trị viên',
                'message' => e($message->message),
                'time' => $message->created_at->format('H:i d/m'),
                'is_read' => $message->is_read,
            ],
        ]);
    }

    /**
     * Lấy tổng số tin nhắn chưa đọc của admin
     */
    public function unreadCount()
    {
        $unread = ChatMessage::where('sender_type', 'user')
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $unread]);
    }
}
