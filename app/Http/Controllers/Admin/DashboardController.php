<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKaryawan = User::where('role', 'karyawan')->count();
        $totalTeamLeader = User::where('role', 'team leader')->count();
        $pendingCount = Evidence::where('status_laporan', 'pending')->count();
        $approvedThisMonthCount = Evidence::where('status_laporan', 'approved')
                                          ->whereMonth('updated_at', Carbon::now()->month)
                                          ->whereYear('updated_at', Carbon::now()->year)
                                          ->count();

        $chartLabels = [];
        $approvedData = [];
        $rejectedData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $approvedData[] = Evidence::where('status_laporan', 'approved')->whereDate('updated_at', $date)->count();
            $rejectedData[] = Evidence::where('status_laporan', 'rejected')->whereDate('updated_at', $date)->count();
        }

        $chartData = [
            'labels'   => json_encode($chartLabels),
            'approved' => json_encode($approvedData),
            'rejected' => json_encode($rejectedData),
        ];

        $pendingEvidences = Evidence::where('status_laporan', 'pending')->latest()->take(5)->get();

        return view('admin.dashboard', [
            'totalKaryawan'          => $totalKaryawan,
            'totalTeamLeader'        => $totalTeamLeader,
            'pendingCount'           => $pendingCount,
            'approvedThisMonthCount' => $approvedThisMonthCount,
            'chartData'              => $chartData,
            'pendingEvidences'       => $pendingEvidences,
        ]);
    }
}