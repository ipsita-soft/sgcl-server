<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\MessageAttachmentResource;
use App\Http\Resources\SendMessage;
use App\Http\Resources\UserResource;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\User;

class MessageController extends Controller
{

    public function userShow($id){
        $user = User::where('id',$id)->first();
        return (new UserResource($user));
    }

    public function conversationShow($receiver_id)
    {
        $user_id = auth()->user()->id;

        $messages = Message::where(function ($query) use ($user_id, $receiver_id) {
            $query->where('sender_id', $user_id)
                ->orWhere('receiver_id', $user_id);
        })
            ->where(function ($query) use ($receiver_id) {
                $query->where('sender_id', $receiver_id)
                    ->orWhere('receiver_id', $receiver_id);
            })
            ->with(['sender', 'receiver', 'attachments'])
            ->orderBy('created_at', 'asc')
            ->get();

        return SendMessage::collection($messages);
    }

    public function getRecentConversation()
    {
        $subQuery = Message::selectRaw('MAX(id) as id')
            ->groupBy('sender_id', 'receiver_id')
            ->pluck('id');
        $recentConversations = Message::whereIn('id', $subQuery)
            ->orderBy('updated_at', 'desc')
            ->with(['sender', 'receiver', 'attachments'])
            ->take(10)
            ->get();
        return SendMessage::collection($recentConversations);
        return SendMessage::collection($conversations);
    }

    public function sendMessage(SendMessageRequest $request)
    {
        $validated = $request->validated();
        $message = Message::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $validated['receiver_id'],
            'sender_role' => auth()->user()->role->name,
            'receiver_role' => User::find($validated['receiver_id'])->role->name,
            'message' => $validated['message'],
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filePath = $file->store('attachmentsMessage', 'public');
                MessageAttachment::create([
                    'message_id' => $message->id,
                    'file_path' => $filePath,
                ]);
            }
        }

        return new SendMessage($message->load('attachments'));
    }




}
