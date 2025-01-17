<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Employee;

class SendWelcomeEmail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $employee;
    /**
     * Create a new job instance.
     */
    public function __construct(Employee $employee)
    {
        //
        $this->employee = $employee;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Mail::to($this->employee->email)->send(new \App\Mail\WelcomeEmail($this->employee));
    }
}
