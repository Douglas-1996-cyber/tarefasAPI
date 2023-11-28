<?php

namespace App\Http\Controllers;

use App\Exports\TarefasExport;
use App\Mail\MensagemCadastro;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Exception;
use PDF;

class TarefaController extends Controller
{

    public function __construct(Tarefa $tarefa)
    {
        $this->tarefa = $tarefa;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $id_usuario = auth()->user()->id;
       $this->verificarAtraso($id_usuario);

        if ($request->search == null) {
            $tarefas = $this->tarefa->where('user_id', $id_usuario);
      
        } else {
            $tarefas = $this->tarefa->where('name', 'like', '%' . $request->search . '%')->where('user_id', $id_usuario);
        }
        $tarefa = $this->tarefa->where('user_id', $id_usuario)->get();
       
        return response()->json($tarefas->paginate(10));
 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        date_default_timezone_set('America/Sao_Paulo');
        if ($request->data_limite < date("Y-m-d")) {
            return response()->json(['msg' => 'Data menor que a atual.'], 400);
        }
        $id_usuario = auth()->user()->id;
        $email = auth()->user()->email;
        $request->validate($this->tarefa->rules(), $this->tarefa->feedback());
        try{
        $tarefa = $this->tarefa->create([
            'name' => $request->name,
            'status' => 0,
            'description' => $request->description,
            'level' => $request->level,
            'user_id' => $id_usuario,
            'data_limite' => $request->data_limite,
        ]);
    }catch (Exception $e) {
        return response()->json(['Exception' => $e->getMessage()], 400);
    }
        Mail::to($email)->send(new MensagemCadastro($tarefa));
        return response()->json($tarefa);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id_usuario = auth()->user()->id;
        $tarefa = $this->tarefa->where('user_id', $id_usuario)->find($id);
        if ($tarefa === null) {
            return response()->json(['msg' => 'Nenhuma tarefa encontrada'], 404);
        }
        return response()->json($tarefa);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Integer
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    
    {
        
        date_default_timezone_set('America/Sao_Paulo');
        if ($request->data_limite < date("Y-m-d")) {
            return response()->json(['msg' => 'Data menor que a atual.'], 400);
        }
        $id_usuario = auth()->user()->id;
        $tarefa = $this->tarefa->where('user_id', $id_usuario)->find($id);
        if ($tarefa === null) {
            return response()->json(['msg' => 'Impossivel realizar a atualização, nenhuma tarefa encontrada.'], 404);
        }
        if ($tarefa->status == 1) {
            return response()->json(['msg' => 'Impossivel realizar a atualização, tarefa concluida.'], 400);
        } else {
            try{
            $tarefa->update($request->all());
            return response()->json(['msg' => 'Atualização realizada.'], 201);
             }catch (Exception $e) {
            return response()->json(['Exception' => $e->getMessage()], 400);
            }
           
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id_usuario = auth()->user()->id;
        $tarefa = $this->tarefa->where('user_id', $id_usuario)->find($id);
        if ($tarefa === null) {
            return response()->json(['msg' => 'Impossivel realizar a exclusão, nenhuma tarefa encontrada.'], 404);
        }

        if ($tarefa->status == 0) {
            return response()->json(['msg' => 'Impossivel realizar a exclusão, tarefa não concluida.'], 400);
        } else {
            $tarefa->delete();
            return response()->json(['msg' => 'Tarefa excluida com sucesso!']);
        }
    }
    public function exportacao()
    {
        return Excel::download(new TarefasExport, 'lista_de_tarefas.csv'); 
    }

    public function concluir($id)
    {

        $id_usuario = auth()->user()->id;
        $tarefa = $this->tarefa->where('user_id', $id_usuario)->find($id);
        if ($tarefa === null) {
            return response()->json(['msg' => 'Impossivel realizar a conclusão, nenhuma tarefa encontrada.'], 404);
        }
        // 0 = false e 1 = true
        if ($tarefa->status == 1) {
            return response()->json(['msg' => 'Impossivel realizar a solicitação, a tarefa já está concluida.'], 400);
        } else {
            try{
            $tarefa->status = 1;
            $tarefa->data_conclusao = date("Y-m-d");
            $tarefa->save();
            }
            catch (Exception $e) {
                return response()->json(['Exception' => $e->getMessage()], 400);
            }
            
            return response()->json(['msg' => 'Tarefa concluida com sucesso.'], 201);
        }

    }
    public function exportar(){  
        $tarefas = auth()->user()->tarefas()->get();  
        try{  
        $pdf = PDF::loadView('tarefa.pdf', ['tarefas'=>$tarefas]);
        return $pdf->download('lista_tarefas.pdf');
        }catch (Exception $e) {
            return response()->json(['Exception' => $e->getMessage()], 400);
        } 
    }
    public function verificarAtraso($id_usuario){
        date_default_timezone_set('America/Sao_Paulo');
        $tarefas = $this->tarefa->where('user_id', $id_usuario)->get();
        foreach($tarefas as $tarefa){
            if ($tarefa->data_limite < date("Y-m-d") &&  $tarefa->late == 0 && $tarefa->status == 0) {
                $tarefa->late = 1;
                $tarefa->save();
            }else if($tarefa->data_limite >= date("Y-m-d") &&  $tarefa->late == 1){
                $tarefa->late = 0;
                $tarefa->save();
            
            }

        }
    }
    public function enviarEmail(){
     dd($this->tarefa);
    }

}
