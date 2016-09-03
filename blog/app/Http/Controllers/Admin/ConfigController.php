<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\config;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ConfigController extends CommonController
{
    //get.admin/config   全部导航列表
    public function index()
    {
        $data =Config::orderBy('config_order','asc')->get();
        foreach($data as $k => $v){
            switch($v->field_type){
                case 'input':
                   $data[$k]->_html = '<input type="text" name="config_content[]" class="lg" value="'.$v->config_content.'">';
                    break;
                case 'textarea':
                    $data[$k]->_html = '<textarea type="text"  name="config_content[]"   class="lg"">'.$v->config_content.'</textarea>';
                    break;
                case 'radio':
                    //1|开始，0|关闭
                    $arr = explode(',',$v->field_value);
                    $str = '';
                  foreach($arr as $m=>$n) {
                      //1|开启
                      $r = explode('|', $n);
                      $c = $v->config_content== $r[0]? 'checked':'';
                      $str.= '<label><input type = "radio" name = "config_content[]"  value = "' . $r[0] . '"'.$c.'>' . $r[1].'　'.'</label>';
                  }
                    $data[$k]->_html =  $str;

                    break;
            }
        }
        return view('admin.config.index',compact('data'));
    }
    
    //post /config/changecontent
    public function changeContent()
    {
        $input =Input::all();
        foreach($input['config_id'] as $k=>$v){
            Config::where('config_id',$v)->update(['config_content'=>$input['config_content'][$k]]);
        }
        $this->putFile();
        return back()->with('errors','配置项更新成功，请稍后重试！');
    }

    public function putFile()
    {
        $config = Config::pluck('config_content','config_name')->all();
       /*echo var_export($config,true);*/
        $path=base_path().'\config\web.php';
        $str = '<?php return'.' '.var_export($config,true).';';
        file_put_contents($path,$str);
        /*echo \Illuminate\Support\Facades\Config::get('web.web_title');*/
        /*echo $path;*/
    }

    //post.admin/config
    public function changeOrder()
    {
       $input = Input::all();
        $config =Config::find($input['config_id']);
        $config->config_order = $input['config_order'];
        $re = $config->update();
        if($re){
            $data=[
                'status'=>0,
                'msg'=>'配置项顺序更新成功！',
            ];
        }else{
            $data=[
                'status'=>1,
                'msg'=>'配置项排序更新失败，请稍后重试！',
            ];
        }
        return $data;
    }

    //post.admin/config   添加配置项
    public function store()
    {
        $input=Input::except('_token');
        if($input){
            $rules=[
                'config_name'=>'required',
                'config_title'=>'required',
            ];
            $message=[
                'config_name.required'=>'配置项名称不能为空！',
                'config_title.required'=>'配置项标题不能为空！',
            ];
            $validator=  Validator::make($input,$rules,$message);
            if($validator->passes()){
                $re =  Config::create($input);
                if($re){
                    return redirect('admin/config');
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

    //get.admin/config/create   添加配置项
    public function create()
    {
        return view('admin/config/add');
    }


    //DELETE       | admin/config/{nav}    删除单个分类
    public function destroy($config_id)
    {
        $re = Config::where('config_id',$config_id)->delete();
        if($re){
            $this->putFile();
            $data = [
                'status'=>0,
                'msg'=> '配置项删除成功！',
            ];
        }else{
            $data = [
                'status'=>1,
                'msg'=> '配置项删除失败！',
            ];
        }
        return $data;
    }


    //GET    | admin/config/{nav}/edit    编辑配置项
    public function edit($config_id)
    {
        $field = Config::find($config_id);
        return view('admin.config.edit',compact('field'));
    }


    //PUT|PATCH   | admin/config/{nav}   更新分类
    public function update($config_id)
    {
        $input=Input::except('_token','_method');
        $re = Config::where('config_id',$config_id)->update($input);
       if($re){
           $this->putFile();
            return redirect('admin/config');
        }else{
            return back()->with('errors','配置项信息更新失败！');
        }
    }

    // get admin/config/{config}  显示单个配置项信息
    public function show()
    {


}
}
