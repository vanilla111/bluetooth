<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\CourseCheck;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class checkAttendance extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $condition = [
            'stuNum' => $this->data['stuNum'],
            'jxbID'  => $this->data['jxbID'],
            'week'   => $this->data['week'],
            'hash_day' => $this->data['hash_day'],
            'hash_lesson' => $this->data['hash_lesson']
        ];
        if (CourseCheck::where($condition)->exists()) {
            CourseCheck::where($condition)->update($this->data);
        } else 
            CourseCheck::create($this->data);
    }
}
