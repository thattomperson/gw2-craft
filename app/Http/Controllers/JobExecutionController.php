<?php

namespace App\Http\Controllers;

use App\Models\JobExecution;
use Illuminate\Http\Request;

class JobExecutionController extends Controller
{
    public function index()
    {
        $jobExecutions = JobExecution::paginate();
        return view('jobExecutions.list', ['jobExecutions' => $jobExecutions]);
    }

    public function show(JobExecution $jobExecution)
    {
      return view('jobExecutions.show', ['jobExecution' => $jobExecution]);
    }

}
