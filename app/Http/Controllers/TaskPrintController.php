<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TaskPrintController extends Controller
{
    public function print(Task $task)
    {
        $pdf = Pdf::loadView('pdf.task', compact('task'));
        return $pdf->stream('task-'.$task->id.'.pdf');
    }
}
