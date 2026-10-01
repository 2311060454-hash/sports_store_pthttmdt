<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('subject', 'like', "%{$q}%");
            });
        }

        $contacts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        if ($contact->status === 'unread') {
            $contact->update(['status' => 'read']);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'status' => 'required|in:unread,read,replied',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $contact->update($validated);

        return back()->with('success', 'Cập nhật trạng thái liên hệ thành công!');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Đã xóa tin nhắn liên hệ.');
    }
}
