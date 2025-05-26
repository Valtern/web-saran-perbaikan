<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ReportManagement extends Component
{
    public $searchTerm = '';
    public $startDate;
    public $endDate;
    public $reports;

    public function render()
    {
        $query = DB::table('report')
            ->join('users', 'report.user_ID', '=', 'users.id')
            ->select('report.*', 'users.name as reporter_name');

        if ($this->searchTerm) {
            $query->where('users.name', 'like', '%' . $this->searchTerm . '%');
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('report.created_at', [$this->startDate, $this->endDate]);
        }

        $this->reports = $query->orderBy('report.created_at', 'desc')->get();

        return view('livewire.admin.Menu.report.report-management', [
            'reports' => $this->reports
        ]);
    }

public function updateStatus($reportId, $newStatus)
{
    DB::table('report')->where('report_ID', $reportId)->update(['status' => $newStatus]);
}

public function deleteReport($reportId)
{
    DB::table('report')->where('report_ID', $reportId)->delete();
}

}
