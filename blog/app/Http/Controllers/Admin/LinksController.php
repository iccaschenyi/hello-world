<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Links;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LinksController extends CommonController
{
    //get.admin/links   全部友情链接列表
    public function index()
    {
        $data =Links::orderBy('link_order','asc')->get();
        return view('admin.links.index',compact('data'));
    }

    //post.admin/links
    public function changeOrder()
    {
        $input = Input::all();
        $links =Links::find($input['link_id']);
        $links->link_order=$input['link_order'];
        $re = $links->update();
        if($re){
            $data=[
                'status'=>0,
                'msg'=>'友情链接顺序更新成功！',
            ];
        }else{
            $data=[
                'status'=>1,
                'msg'=>'友情链接排序更新失败，请稍后重试！',
            ];
        }
        return $data;
    }

    //post.admin/links   添加友情链接
    public function store()
    {
        $input=Input::except('_token');
        if($input){
            $rules=[
                'link_name'=>'required',
                'link_url'=>'required',
            ];
            $message=[
                'link_name.required'=>'友情链接名称不能为空！',
                'link_url.required'=>'友情链接Url不能为空！',
            ];
            $validator=  Validator::make($input,$rules,$message);
            if($validator->passes()){
                $re =  Links::create($input);
                if($re){
                    return redirect('admin/links');
                }else{
                    return back()->with('errors','添加链接失败，请稍后重试！');
                }
            }else{
                return back()->withErrors($validator);
            }
        }else{
            return view('admin.add');
        }

    }

    //get.admin/links/create   添加友情链接
    public function create()
    {
        return view('admin/links/add');
    }


    //DELETE       | admin/links/{link}    删除单个分类
    public function destroy($link_id)
    {
        $re = Links::where('link_id',$link_id)->delete();
        if($re){
            $data = [
                'status'=>0,
                'msg'=> '友情链接删除成功！',
            ];
        }else{
            $data = [
                'status'=>1,
                'msg'=> '友情链接删除失败！',
            ];
        }
        return $data;
    }


    //GET    | admin/links/{link}/edit    编辑友情链接
    public function edit($link_id)
    {
        $field = Links::find($link_id);
        return view('admin.links.edit',compact('field'));
    }


    //PUT|PATCH   | admin/links/{link}   更新分类
    public function update($link_id)
    {
        $input=Input::except('_token','_method');
        $re = Links::where('link_id',$link_id)->update($input);
       if($re){
            return redirect('admin/links');
        }else{
            return back()->with('errors','友情链接信息更新失败！');
        }
    }

    // get admin/links/{link}  显示单个友情链接信息
    public function show()
    {


}
}
