<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Usuario;

class InvitacionUsuario extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $token;

    public function __construct(Usuario $usuario, string $token)
    {
        $this->usuario = $usuario;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Invitación al ERP')
                    ->view('emails.invitacion');
    }
}
