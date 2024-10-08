<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index()
    {
        $pengingat = Reminder::where('status', 'aktif')->where('status_pelaksanaan', 'belum')->get();

        foreach ($pengingat as $reminder) {
            $currentDate = Carbon::now()->format('Y-m-d');
            $currentTime = Carbon::now()->format('H:i');

            if ($reminder->tanggal_reminder <= $currentDate && $reminder->waktu_reminder <= $currentTime) {
                $reminder->update(['status_pelaksanaan' => 'sudah']);
            }
        }
        $reminders = Reminder::all();
        return view('reminders.index', compact('reminders','pengingat'));
    }

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

    public function edit($id)
    {
        $reminder = Reminder::find($id);
        return view('reminders.edit', compact('reminder'));
    }

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

    public function destroy($id)
    {
        $reminder = Reminder::find($id);
        $reminder->delete();

        return redirect()->route('reminders.index')->with('success', 'Reminder berhasil dihapus');
    }
}
