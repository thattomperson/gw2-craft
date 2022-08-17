<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTask;

class ScheduleMonitorTaskController extends Controller
{
    public function index()
    {
      return view('schedule-monitor.list', ['monitoredTasks' => MonitoredScheduledTask::paginate()]);
    }

    public function show(MonitoredScheduledTask $monitoredScheduledTask)
    {
      return view('schedule-monitor.show', [
        'monitoredTask' => $monitoredScheduledTask,
        'monitoredTaskLogItems' => $monitoredScheduledTask->logItems()->paginate(),
      ]);
    }
}