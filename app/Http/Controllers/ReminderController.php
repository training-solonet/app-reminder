<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk pencarian
        $query = Reminder::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('tentang_reminder', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('status_pelaksanaan', 'like', "%{$search}%")
                  ->orWhere('tanggal_reminder', 'like', "%{$search}%");
            });
        }

        // Pengambilan data Reminder dengan paginasi
        $reminders = $query->paginate(15);

        // Pengingat yang belum dilaksanakan
        $pengingat = Reminder::where('status_pelaksanaan', 'belum')->get();

        // Logika untuk update status reminder
        foreach ($pengingat as $reminder) {
            $currentDateTime = Carbon::now();
            $reminderDateTime = Carbon::parse($reminder->tanggal_reminder . ' ' . $reminder->waktu_reminder);

            if ($currentDateTime->greaterThanOrEqualTo($reminderDateTime) && $reminder->status == 'tidak-aktif') {
                $reminder->update(['status' => 'aktif']);
            }
        }

        return view('reminders.index', compact('reminders', 'pengingat'));
    }

    public function store(Request $request)
    {
        // Validasi inputan
        $request->validate([
            'tentang_reminder' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_reminder' => 'required|date',
            'waktu_reminder' => 'required|date_format:H:i',
            'status' => 'required|in:aktif,tidak-aktif',
            'status_pelaksanaan' => 'required|in:sudah,belum',
        ]);

        // Menyimpan data Reminder baru
        Reminder::create($request->all());

        return redirect()->route('reminders.index')->with('success', 'Reminder berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        // Validasi inputan
        $request->validate([
            'tentang_reminder' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_reminder' => 'required|date',
            'waktu_reminder' => 'required|date_format:H:i',
            'status' => 'required|in:aktif,tidak-aktif',
            'status_pelaksanaan' => 'required|in:sudah,belum',
        ]);

        // Cari data reminder berdasarkan ID
        $reminder = Reminder::find($id);

        if (!$reminder) {
            return redirect()->route('reminders.index')->with('error', 'Reminder tidak ditemukan');
        }

        // Update status jika pelaksanaan sudah dilakukan
        if ($request->status_pelaksanaan == 'sudah') {
            $reminder->update([
                'status_pelaksanaan' => 'sudah',
                'status' => 'tidak-aktif',
            ]);
        } else {
            $reminder->update($request->all());
        }

        return redirect()->route('reminders.index')->with('success', 'Reminder berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Cari dan hapus reminder
        $reminder = Reminder::find($id);
        
        if (!$reminder) {
            return redirect()->route('reminders.index')->with('error', 'Reminder tidak ditemukan');
        }

        $reminder->delete();

        return redirect()->route('reminders.index')->with('success', 'Reminder berhasil dihapus');
    }
}
