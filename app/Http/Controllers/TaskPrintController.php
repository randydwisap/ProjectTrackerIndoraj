<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAlihMedia;
use Illuminate\Http\Request;
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
}
