<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviaEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $web;
    private $email;
    private $nome;
    private $leadLow;

    public function __construct($web, $email, $nome, $leadLow=0)
    {
        
        $this->email = $email;
        $this->nome = $nome;
        $this->leadLow = $leadLow;
        $this->web = $web;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        \Illuminate\Support\Facades\Mail::send(new \App\Mail\EnviarEmail($this->web, $this->email, $this->nome, $this->leadLow));
    }
}
