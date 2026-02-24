<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $q = Report::query()->latest();
        if ($status) {
            $q->where('status', $status);
        }
        $reports = $q->paginate(20);
        return view('admin.reports.index', compact('reports', 'status'));
    }

    public function show(Report $report)
    {
        $report->load('reportable');
        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => ['required', 'in:open,reviewing,resolved,rejected'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $report->forceFill([
            'status' => $request->status,
            'admin_note' => $request->input('admin_note'),
        ])->save();

        return back()->with('success', 'Status laporan diperbarui.');
    }
}
