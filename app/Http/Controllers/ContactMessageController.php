<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
            'website' => ['nullable', 'max:0'],
        ]);

        ContactMessage::query()->create([
            'user_id' => $request->user()?->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        return back()->with('success', 'Pesan berhasil dikirim ke inbox hotel. Tim kami akan menghubungi Anda melalui email atau telepon.');
    }

    public function index(Request $request): View
    {
        $this->authorizeStaff($request);

        $query = ContactMessage::query()->latest();

        if ($request->filled('status') && in_array($request->string('status')->value(), ['new', 'in_progress', 'resolved'], true)) {
            $query->where('status', $request->string('status')->value());
        }

        if ($request->filled('search')) {
            $search = '%' . strtolower($request->string('search')->value()) . '%';
            $query->where(function ($builder) use ($search) {
                $builder->whereRaw('LOWER(name) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(email) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(subject) LIKE ?', [$search]);
            });
        }

        $messages = $query->paginate(12)->withQueryString();
        $counts = [
            'all' => ContactMessage::query()->count(),
            'new' => ContactMessage::query()->where('status', 'new')->count(),
            'in_progress' => ContactMessage::query()->where('status', 'in_progress')->count(),
            'resolved' => ContactMessage::query()->where('status', 'resolved')->count(),
        ];

        return view('admin.contact-messages', compact('messages', 'counts'));
    }

    public function updateStatus(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'status' => ['required', 'in:new,in_progress,resolved'],
        ]);

        $contactMessage->update($validated);

        return back()->with('success', 'Status pesan berhasil diperbarui.');
    }

    public function destroy(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $contactMessage->delete();

        return back()->with('success', 'Pesan contact berhasil dihapus.');
    }

    private function authorizeStaff(Request $request): void
    {
        abort_unless(in_array($request->user()?->role, ['admin', 'manager'], true), 403);
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'admin', 403);
    }
}
