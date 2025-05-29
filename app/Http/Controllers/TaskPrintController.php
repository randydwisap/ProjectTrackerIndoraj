<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAlihMedia;
use App\Models\TaskFumigasi;
use Illuminate\Http\Request;
\Carbon\Carbon::setLocale('id');
use Barryvdh\DomPDF\Facade\Pdf;

class TaskPrintController extends Controller
{
    public function print(Task $task)
    {
        $pdf = Pdf::loadView('pdf.task', compact('task'));
        return $pdf->stream('task-'.$task->id.'.pdf');
    }

    public function printAlihMedia(TaskAlihMedia $taskAlihMedia)
    {
        $pdf = Pdf::loadView('pdf.task_alih_media', compact('taskAlihMedia'));
        return $pdf->stream('task-alih-media-'.$taskAlihMedia->id.'.pdf');
    }

        public function printFumigasi(TaskFumigasi $taskFumigasi)
    {
        $pdf = Pdf::loadView('pdf.task_fumigasi', compact('taskFumigasi'));
        return $pdf->stream('task-fumigasi-'.$taskFumigasi->id.'.pdf');
    }
}
