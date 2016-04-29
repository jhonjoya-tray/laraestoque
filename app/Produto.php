<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
	//Somente é preciso declarar este atributo quando a model não respeita a convenção
	// https://laravel.com/docs/5.2/eloquent#eloquent-model-conventions
    protected $table = 'produtos';

    /**
     * Este atributo é declarado informado quais os campos podem ser populados em massa
     * 
     * @var string
     */
    protected $fillable = ['nome'];

}
