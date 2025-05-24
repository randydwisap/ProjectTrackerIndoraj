<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAlihMedia;
use Illuminate\Http\Request;
\Carbon\Carbon::setLocale('id');
use Barryvdh\DomPDF\Facade\Pdf;

class TaskPrintController extends Controller
{
    public function print(Task $task)
    {
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('dpi', 150); // atau 300
        $dompdf = new \Dompdf\Dompdf($options);
        $pdf = Pdf::loadView('pdf.task', compact('task'));
        return $pdf->stream('task-'.$task->id.'.pdf');
    }

    public function printAlihMedia(TaskAlihMedia $taskAlihMedia)
    {
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('dpi', 150); // atau 300
        $dompdf = new \Dompdf\Dompdf($options);
        $pdf = Pdf::loadView('pdf.task_alih_media', compact('taskAlihMedia'));
        return $pdf->stream('task-alih-media-'.$taskAlihMedia->id.'.pdf');
    }
}
