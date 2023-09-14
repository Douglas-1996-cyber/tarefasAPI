<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Tarefa;



class MensagemCadastro extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $description;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Tarefa $tarefa)
    {
        $this->name = $tarefa->name;
        $this->description = $tarefa->description;
        $this->data_limite = $tarefa->data_limite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.mensagem-cadastro')->subject('Nova tarefa criada');
    }
}
