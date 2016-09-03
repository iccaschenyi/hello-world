<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    //文件上传
    public function  upload()
    {
        $file = Input::file('Filedata');
        if($file -> isValid()) {
            $entension = $file->getClientOriginalExtension();//文件名后缀
            $newName = date('YmdHis') . mt_rand(100, 999) . '.' . $entension;
            $path = $file->move(base_path() . '/uploads', $newName);
            $filepath= 'uploads/'.$newName;
            return $filepath;
        }
    }
}