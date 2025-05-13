<p>Hola {{ $usuario->nombre }},</p>
<p>Te han invitado a unirte al sistema ERP. Para activar tu cuenta, haz clic en el siguiente enlace:</p>
<p><a href="{{ url('/activar?token=' . $usuario->token_activacion) }}">Activar cuenta</a></p>
