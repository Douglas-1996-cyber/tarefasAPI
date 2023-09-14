<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;
    protected $fillable = ['name','status','description','level','user_id','data_limite'];

    public function rules(){
      return [
        'name' => 'required',
        'description'=>'required',
        'level'=>'required',
    ];
    }

    public function feedback(){
       return [
        'required' =>'O campo :attribute é obrigatório.',
        ];
    }

    public function user(){
       return $this->belongsTo('App\Models\User');
    }
}
