<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        return view('messages.index', ['messages' => Message::orderBy('dibaca')->latest()->get()]);
    }

    public function balas(Request $request, Message $message)
    {
        $data = $request->validate(['balasan' => ['required', 'string', 'max:1000']]);
        $message->update($data + ['balasan_at' => now(), 'dibaca' => true]);

        return back()->with('ok', 'Balasan disimpan.');
    }

    public function baca(Message $message)
    {
        $message->update(['dibaca' => true]);

        return back();
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return back()->with('ok', 'Pesan dihapus.');
    }
}
