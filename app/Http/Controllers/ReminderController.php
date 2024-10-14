<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index(Request $request)
{
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

    $reminders = $query->paginate(15);

    $todayReminders = Reminder::where('status', 'aktif')->where('tanggal_reminder', '<=', Carbon::today()->format('Y-m-d'))->get();

    return view('reminders.index', compact('reminders','todayReminders'));
}




    public function store(Request $request)
    {
        $request->validate([
            'tentang_reminder' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_reminder' => 'required|date',
            'status' => 'required|in:aktif,tidak-aktif',
            'status_pelaksanaan' => 'required|in:sudah,belum',
        ]);

        Reminder::create($request->all());

        return redirect()->route('reminders.index')->with('success', 'Reminder berhasil ditambahkan');
    }




    public function update(Request $request, $id)
    {
        $request->validate([
            'tentang_reminder' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_reminder' => 'required|date',
            'status' => 'required|in:aktif,tidak-aktif',
            'status_pelaksanaan' => 'required|in:sudah,belum',
        ]);

        $reminders = Reminder::find($id);
        $reminders->update($request->all());

        return redirect()->route('reminders.index')->with('success', 'Reminder berhasil diperbarui');
    }


    public function destroy($id)
    {
        $reminder = Reminder::find($id);
        $reminder->delete();

        return redirect()->route('reminders.index')->with('success', 'Reminder berhasil dihapus');
    }
}