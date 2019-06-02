<?php

namespace App\Http\Controllers;

use App\BarangTitipan;
use App\Titipan;
use Illuminate\Http\Request;

class TitipanController extends Controller
{
    public function createPenitip(Request $request){
        $data=new Titipan;
        $data->nama_owner=$request->nama;
        $data->save();
        return response(json_encode($data),201);
    }
    public function simpanData($id){
        $data=Titipan::find($id);
        $data->status=1;
        $data->save();
        return BarangTitipan::where('titipan_id','=',$id)->get();
    }
    public function getAllPenitip(){
        return Titipan::where('status_pengambilan','=',0)->get();
    }
    public function getName($id){
        return Titipan::find($id);
    }
    public function getAllProduk($id){
        return BarangTitipan::where('titipan_id','=',$id)->get();
    }
}
