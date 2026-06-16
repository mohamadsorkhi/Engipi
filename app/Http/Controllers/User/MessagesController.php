<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Load the latest message per conversation partner
        $conversations = Message::query()
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver', 'project'])
            ->latest()
            ->get()
            ->groupBy(function (Message $msg) use ($userId) {
                $other = $msg->sender_id === $userId ? $msg->receiver_id : $msg->sender_id;
                return $other;
            })
            ->map(function ($msgs) use ($userId) {
                $latest = $msgs->first();
                $other  = $latest->sender_id === $userId ? $latest->receiver : $latest->sender;
                $unread = $msgs->where('receiver_id', $userId)->whereNull('read_at')->count();
                return (object) [
                    'other'   => $other,
                    'latest'  => $latest,
                    'unread'  => $unread,
                ];
            })
            ->values();

        return view('user.messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $authId = Auth::id();

        if ($authId === $user->id) {
            abort(403, 'نمی‌توانید با خودتان پیام تبادل کنید.');
        }

        $messages = Message::query()
            ->where(function ($q) use ($authId, $user) {
                $q->where('sender_id', $authId)->where('receiver_id', $user->id);
            })
            ->orWhere(function ($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })
            ->with(['project'])
            ->oldest()
            ->get();

        // Mark unread messages from the other user as read
        Message::query()
            ->where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('user.messages.show', compact('messages', 'user'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => ['required', 'uuid', 'exists:users,id'],
            'body'        => ['required', 'string', 'min:1', 'max:5000'],
            'project_id'  => ['nullable', 'uuid', 'exists:projects,id'],
        ], [
            'receiver_id.required' => 'گیرنده الزامی است.',
            'receiver_id.exists'   => 'کاربر گیرنده یافت نشد.',
            'body.required'        => 'متن پیام الزامی است.',
            'body.max'             => 'پیام نمی‌تواند بیش از ۵۰۰۰ کاراکتر باشد.',
        ]);

        $authId = Auth::id();

        if ($authId === $data['receiver_id']) {
            return back()->withErrors(['body' => 'نمی‌توانید به خودتان پیام بفرستید.']);
        }

        Message::create([
            'sender_id'   => $authId,
            'receiver_id' => $data['receiver_id'],
            'project_id'  => $data['project_id'] ?? null,
            'body'        => $data['body'],
        ]);

        $receiver = User::findOrFail($data['receiver_id']);

        return redirect()
            ->route('user.messages.show', $receiver)
            ->with('success', 'پیام شما ارسال شد.');
    }
}
