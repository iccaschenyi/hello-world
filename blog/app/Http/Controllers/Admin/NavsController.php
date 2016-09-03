<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\navs;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class NavsController extends CommonController
{
    //get.admin/navs   全部导航列表
    public function index()
    {
        $data =navs::orderBy('navs_order','asc')->get();
        return view('admin.navs.index',compact('data'));
    }

    //post.admin/navs
    public function changeOrder()
    {
        $input = Input::all();
        $navs =Navs::find($input['navs_id']);
        $navs->navs_order = $input['navs_order'];
        $re = $navs->update();
        if($re){
            $data=[
                'status'=>0,
                'msg'=>'自定义导航顺序更新成功！',
            ];
        }else{
            $data=[
                'status'=>1,
                'msg'=>'自定义导航排序更新失败，请稍后重试！',
            ];
        }
        return $data;
    }

    //post.admin/navs   添加自定义导航
    public function store()
    {
        $input=Input::except('_token');
        if($input){
            $rules=[
                'navs_name'=>'required',
                'navs_url'=>'required',
            ];
            $message=[
                'navs_name.required'=>'自定义导航名称不能为空！',
                'navs_url.required'=>'自定义导航Url不能为空！',
            ];
            $validator=  Validator::make($input,$rules,$message);
            if($validator->passes()){
                $re =  navs::create($input);
                if($re){
                    return redirect('admin/navs');
                }else{
                    return back()->with('errors','添加导航失败，请稍后重试！');
                }
            }else{
                return back()->withErrors($validator);
            }
        }else{
            return view('admin.add');
        }

    }

    //get.admin/navs/create   添加自定义导航
    public function create()
    {
        return view('admin/navs/add');
    }


    //DELETE       | admin/navs/{nav}    删除单个分类
    public function destroy($navs_id)
    {
        $re = Navs::where('navs_id',$navs_id)->delete();
        if($re){
            $data = [
                'status'=>0,
                'msg'=> '自定义导航删除成功！',
            ];
        }else{
            $data = [
                'status'=>1,
                'msg'=> '自定义导航删除失败！',
            ];
        }
        return $data;
    }


    //GET    | admin/navs/{nav}/edit    编辑自定义导航
    public function edit($navs_id)
    {
        $field = Navs::find($navs_id);
        return view('admin.navs.edit',compact('field'));
    }


    //PUT|PATCH   | admin/navs/{nav}   更新分类
    public function update($navs_id)
    {
        $input=Input::except('_token','_method');
        $re = Navs::where('navs_id',$navs_id)->update($input);
       if($re){
            return redirect('admin/navs');
        }else{
            return back()->with('errors','自定义导航信息更新失败！');
        }
    }

    // get admin/navs/{navs}  显示单个自定义导航信息
    public function show()
    {


}
}
