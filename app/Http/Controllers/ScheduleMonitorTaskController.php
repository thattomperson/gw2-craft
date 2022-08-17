<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTask;

class ScheduleMonitorTaskController extends Controller
{
    public function index()
    {
      return MonitoredScheduledTask::all();
    }

    public function show(MonitoredScheduledTask $monitoredScheduledTask)
    {
      return $monitoredScheduledTask;
    }
}