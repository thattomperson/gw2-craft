<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTask;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;

class ScheduleMonitorTaskLogItemController extends Controller
{
    public function index(MonitoredScheduledTask $task)
    {
      return $task->logItems;
    }

    public function show(MonitoredScheduledTask $task, MonitoredScheduledTaskLogItem $logItems)
    {
      return $logItems;
    }
}
