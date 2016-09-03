<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use App\Http\Model\Article;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ArticleController extends CommonController
{
    //get.admin/article   全部文章列表
    public function index()
    {
        $data = Article::orderBy('art_id','desc')->paginate(5);
       return view('admin.article.index',compact('data'));
    }

    //get.admin/article/create   添加文章
    public function create()
    {
        $data= (new Category)->tree();
        return view('admin.article.add',compact('data'));
    }

    //post.admin/article  添加文章
    public function store()
    {
        $input = Input::except('_token');
        $input['art_time']=time();
        if($input){
            $rules=[
                'art_title'=>'required',
                'art_content'=>'required',
            ];
            $message=[
                'art_name.required'=>'文章标题不能为空！',
                'art_content.required'=>'文章内容不能为空！',
            ];
            $validator=  Validator::make($input,$rules,$message);
            if($validator->passes()){
                $re = Article::create($input);
                if($re){
                    return redirect('admin/article');
                }else{
                    return back()->with('errors','文章添加失败！');
                }
            }else{
                return back()->withErrors($validator);
            }
        }
    }

    //GET    | admin/article/{article}/edit    编辑文章
    public function edit($art_id)
    {
        $data= (new Category)->tree();
        $field = Article::find($art_id);
        return view('admin.article.edit',compact('data','field'));
    }

    //PUT|PATCH   | admin/article/{article}   更新文章
    public function update($art_id)
    {
        $input=Input::except('_token','_method');
        $re = Article::where('art_id',$art_id)->update($input);
        if($re){
            return redirect('admin/article');
        }else{
            return back()->with('errors','文章编辑失败！');
        }
    }

    //DELETE       | admin/category/{category}    删除单个分类
    public function destroy($art_id)
    {
        $re = Article::where('art_id',$art_id)->delete();
        if($re){
            $data = [
                'status'=>0,
                'msg'=> '文章删除成功！',
            ];
        }else{
            $data = [
                'status'=>1,
                'msg'=> '文章删除失败！',
            ];
        }
        return $data;
    }
// get admin/article/{article}  显示单个友情链接信息
    public function show()
    {


    }
}

