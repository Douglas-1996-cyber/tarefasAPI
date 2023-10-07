<?php

namespace App\Exports;

use App\Models\Tarefa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TarefasExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function collection()
    {
      return auth()->user()->tarefas()->get();
    }
    public function headings():array{
      return ['Tarefa','Descrição','Data Limite','Data Conclusão'];
    }
    public function map($linha):array{
          return [
             $linha->name,
             $linha->description,
             $linha->data_limite,
             $linha->data_conclusao
          ];
    }
}
