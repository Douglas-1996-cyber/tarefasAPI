@component('mail::message')
# Uma nova tarefa foi cadastrada com sucesso!
Titulo: {{$name}}

Descrição: {{$description}}



Att,<br>
{{ config('app.name') }}
@endcomponent
