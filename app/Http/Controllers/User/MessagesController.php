<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreMessageRequest;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
                $other = $latest->sender_id === $userId ? $latest->receiver : $latest->sender;
                $unread = $msgs->where('receiver_id', $userId)->whereNull('read_at')->count();

                return (object) [
                    'other' => $other,
                    'latest' => $latest,
                    'unread' => $unread,
                ];
            })
            ->values();

        return view('user.messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $this->authorize('viewConversation', [Message::class, $user]);

        $authId = Auth::id();

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

    public function store(StoreMessageRequest $request)
    {
        $data = $request->validated();
        $sender = Auth::user();
        $receiver = User::query()->findOrFail($data['receiver_id']);
        $project = isset($data['project_id'])
            ? Project::query()->findOrFail($data['project_id'])
            : null;

        $authorization = Gate::inspect('sendMessage', [Message::class, $receiver, $project]);

        if ($authorization->denied()) {
            return back()->withErrors(['body' => $authorization->message()]);
        }

        Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $data['receiver_id'],
            'project_id' => $data['project_id'] ?? null,
            'body' => $data['body'],
        ]);

        return redirect()
            ->route('user.messages.show', $receiver)
            ->with('success', 'پیام شما ارسال شد.');
    }
}
