@component('mail::message')
# Uma nova tarefa foi cadastrada com sucesso!
Titulo: {{$name}}

Descrição: {{$description}}

Data limite: {{$data_limite}}

Att,<br>
{{ config('app.name') }}
@endcomponent
