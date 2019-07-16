<?php

namespace App\Observers;

use App\task;

class TaskObserver
{
    /**
     * Handle the task "created" event.
     *
     * @param  \App\task  $task
     * @return void
     */
    public function created(Task $task)
    {
        $task->recordActivity('created_task');
    }

    /**
     * Handle the task "updated" event.
     *
     * @param  \App\task  $task
     * @return void
     */
    public function updated(task $task)
    {
        //
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $task->recordActivity('deleted_task');
    }

    /**
     * Handle the task "restored" event.
     *
     * @param  \App\task  $task
     * @return void
     */
    public function restored(task $task)
    {
        //
    }

    /**
     * Handle the task "force deleted" event.
     *
     * @param  \App\task  $task
     * @return void
     */
    public function forceDeleted(task $task)
    {
        //
    }
}
