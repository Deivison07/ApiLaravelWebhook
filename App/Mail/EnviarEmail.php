<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $arg;
    private $email;
    private $nome;
    private $leadLow;
    public function __construct($dados,$email,$nome,$leadLow=0)
    {
        $this->arg = $dados;
        $this->email = $email;
        $this->nome = $nome;
        $this->leadLow = $leadLow;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from('sistemas@corretoraportal.com', 'Sistemas');
        $this->subject('API Distribuição de LEADS');

        if($this->leadLow){
            $this->subject('API Distribuição de LEADS (inválida) (Notificação de teste ou falta de informação)');
        }
        
        $this->to($this->email, $this->nome);
        //$this->cc('iran@corretoraportal.com', 'Iran');
        //$this->cc('isac@corretoraportal.com', 'Isac');
        $this->cc('suporteti@corretoraportal.com', 'Suporte TI');
        
        return $this->view('mail.envio',['parametro' => $this->arg['dados']]);
    }
}
