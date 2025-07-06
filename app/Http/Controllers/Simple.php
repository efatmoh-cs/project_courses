<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Simple extends Controller
{
    public function index (){

$fakeData =[

        'id'=>1,
        'name' =>'efat',
'email' =>'efatkhadr1982@gmail.com',

];

return response()->json ($fakeData);

    }
}
