<?php

namespace App\Observers;
use App\Models\Job;
use App\Models\User;
use App\Notifications\JobFailedNotification;

class JobObserver
{
    public function updated(Job $job)
{
    if ($job->isDirty('status') && $job->status === Job::STATUS_FAILED) {
        // Notify all admin users
        User::where('role', 'admin')->each(function($admin) use ($job) {
            $admin->notify(new JobFailedNotification($job));
        });
    }
}
}
