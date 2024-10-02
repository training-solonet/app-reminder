<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;

class ReminderController extends Controller
{
    // Function untuk menampilkan semua data reminder (READ)
    public function index()
    {
        $reminders = Reminder::all();
        return view('reminders.index', compact('reminders'));
    }

    // Function untuk menyimpan data reminder baru (CREATE)
    public function store(Request $request)
    {
        $request->validate([
            'tentang_reminder' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_reminder' => 'required|date',
            'waktu_reminder' => 'required|date_format:H:i',
            'status' => 'required|in:aktif,tidak-aktif',
            'status_pelaksanaan' => 'required|in:sudah,belum',
        ]);

        Reminder::create($request->all());

        return redirect()->route('reminders.index')->with('success', 'Reminder berhasil ditambahkan');
    }

    // Function untuk menampilkan form edit reminder
    public function edit($id)
    {
        $reminder = Reminder::find($id);
        return view('reminders.edit', compact('reminder'));
    }

    // Function untuk mengupdate data reminder (UPDATE)
    public function update(Request $request, $id)
    {
        $request->validate([
            'tentang_reminder' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_reminder' => 'required|date',
            'waktu_reminder' => 'required|date_format:H:i',
            'status' => 'required|in:aktif,tidak-aktif',
            'status_pelaksanaan' => 'required|in:sudah,belum',
        ]);

        $reminder = Reminder::find($id);
        $reminder->update($request->all());

        return redirect()->route('reminders.index')->with('success', 'Reminder berhasil diperbarui');
    }

    // Function untuk menghapus data reminder (DELETE)
    public function destroy($id)
    {
        $reminder = Reminder::find($id);
        $reminder->delete();

        return redirect()->route('reminders.index')->with('success', 'Reminder berhasil dihapus');
    }
}
