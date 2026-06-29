<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
  public function index()
  {
    $messages = ContactMessage::latest()->paginate(15);
    return view('admin.contact_messages.index', compact('messages'));
  }

  public function show($id)
  {
    $message = ContactMessage::findOrFail($id);

    if (!$message->is_read) {
      $message->update(['is_read' => true]);
    }

    return view('admin.contact_messages.show', compact('message'));
  }

  public function reply(Request $request, $id)
  {
    $message = ContactMessage::findOrFail($id);

    $request->validate([
      'reply_text' => 'required|string',
    ]);

    try {
      Mail::send('emails.reply-message', ['replyText' => $request->reply_text, 'originalMessage' => $message], function ($mail) use ($message) {
        $mail->to($message->email)
          ->subject('RE: ' . $message->subject);
      });

      $message->update([
        'is_replied' => true,
        'reply_text' => $request->reply_text,
      ]);

      return redirect()->back()->with('success', 'Email balasan berhasil dikirim langsung ke warga.');

    } catch (\Exception $e) {
  
      return redirect()->back()->with('error', 'Gagal mengirim email balasan! Error: ' . $e->getMessage());
    }
  }

  public function destroy($id)
  {
    $message = ContactMessage::findOrFail($id);
    $message->delete();
    return redirect()->route('admin.messages.index')->with('success', 'Pesan berhasil dihapus.');
  }
}