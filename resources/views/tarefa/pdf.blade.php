<html>
    <head>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
        .page-break {
            page-break-after: always;
        }
         .titulo{
            border:1px;
            background-color:#c2c2c2;
            text-align:center;
            width:100%;
            text-transform:uppercase;
            font-weight:bold;
            margin-bottom:25px;
         }
         .tabela{
            width:100%
         }
         table th{
            text-align:left;
         }

      </style>
       
    </head>
    
        <body> 
            <div class="titulo">Lista de tarefas</div>
            <table class="tabela">
                <thead>
                    <tr>
                    <th>Nome</th>
                    <th>Status</th>
                    <th>Data limite</th>
                    <th>Data Conclusão</th>
                    <th>Prioridade</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($tarefas as $key=>$tarefa)
                    <tr>
                    <td>{{$tarefa->name}}</td>
                    <td>{{$tarefa->status == 1 ?'Concluida':'Pendente'}}</td>
                    <td>{{date('d/m/Y', strtotime($tarefa->data_limite))}}</td>
                    <td>{{$tarefa->data_conclusao ? date('d/m/Y', strtotime($tarefa->data_conclusao)) : '' }}</td>
                    <td>{{$tarefa->level == 1 ?'Alta':'Baixa'}}</td>
                    <tr>
                @endforeach
                </tbody>
            </table>
      
       
        </body>
</html> 