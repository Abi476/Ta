<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class LandingController extends Controller
{
    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda telah berhasil dikirim! Kami akan segera merespons.'
            ]);
        }

        return redirect()->back()->with('success', 'Pesan Anda telah berhasil dikirim! Kami akan segera merespons.');
    }
}
