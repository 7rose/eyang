<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Auth;

use App\Baobei;
use App\Shop;
use App\Order;
use App\User;
use App\Product;
use App\Forms\OrderForm;
use App\Forms\BbFailForm;
use App\Forms\BbForm;
use App\Forms\BbBackForm;
use App\Helpers\Info;
use App\Helpers\Validator;
use App\Helpers\Role;
use App\Helpers\Filter;

class OrderController extends Controller
{
    use FormBuilderTrait;

    private $role;
    /**
     * 列表
     *
     */
    public function index(Info $info, Role $role)
    {
        $this->role = $role;

        $records = Order::
                where('shop_id', $info->id())
                ->where(function ($query){
                    if(!$this->role->admin() && !$this->role->boss()) {
                        $query->where('user_id', Auth::id());
                    }
                })
                ->latest()
                ->get();

        return view('orders.orders', compact('records'));
    }

    /**
     * 新订单
     *
     */
    public function create(Role $role)
    {
        if(!$role->admin() && !$role->shopBoss()) abort('403');

         $form = $this->form(OrderForm::class, [
            'method' => 'POST',
            'url' => '/orders/store'
        ]);

        $title = '新订单';
        $icon = 'heart-o';

        return view('form', compact('form','title','icon'));
    }

    /**
     * 保存
     *
     */
    public function store(Request $request, Info $info, Role $role)
    {
        if(!$role->admin() && !$role->shopBoss()) abort('403');

        $v = new Validator;
        if(!$v->checkMobile($request->mobile)) return redirect()->back()->withErrors(['mobile'=>'手机号不正确!'])->withInput();

        $user = User::where('mobile', $request->mobile)->first();
        if(!$user) return redirect()->back()->withErrors(['mobile'=>'无对应客户, 若需人工核验请打开用户管理'])->withInput();

        $product = Product::where('name', $request->name)->first();
        if(!$product) return redirect()->back()->withErrors(['name'=>'此产品名不存在!'])->withInput();


        $new['user_id'] = $user->id;
        $new['product_id'] = $product->id;
        $new['shop_id'] = $info->id();
        $new['amount'] = $request->amount;
        $new['created_by'] = Auth::id();

        Order::create($new);

        // 限制次数 -1
        $role->limitCut($user->id);

        $color = 'success';
        $icon = 'heart-o';
        $text = '订单登记成功! <br><br><a href="/orders/create" class="btn btn-sm btn-success">继续登记</a>';

        return view('note', compact('color', 'icon', 'text'));

    }

    /**
     * 报备成功
     *
     */
    public function bbSuccess($id, Filter $filter)
    {
        $form = $this->form(BbForm::class, [
            'method' => 'POST',
            'url' => '/bb/success/store/'.$id,
            'data' => ['id' => $id],
        ]);

        $title = '报备';

        $order = Order::findOrFail($id);

        if($filter->bbBackup($order)) $title = '报备: <small>录视频麻烦?<a href="/bb/success/back_form/'.$id.'" class="btn btn-sm btn-outline-primary">提供账号</a></small>';

        $icon = 'heart';

        return view('form', compact('form','title','icon'));
    }

    /**
     * 报备: 备用表单
     *
     */
    public function bbBack($id, Filter $filter)
    {
        $form = $this->form(BbBackForm::class, [
            'method' => 'POST',
            'url' => '/bb/success/store/'.$id,
        ]);

        $title = '报备: 成功下款';
        $order = Order::findOrFail($id);

        if($filter->bbBackup($order)) $title = '报备: <small>录视频安全?<a href="/bb/success/'.$id.'" class="btn btn-sm btn-outline-primary">正常报备</a></small>';

        $icon = 'heart';

        return view('form', compact('form','title','icon'));
    }

    /**
     * 保存
     *
     */
    public function bbSuccessStore($id, Request $request, Info $in)
    {
        $items = $request->except(['_token']);

        $info = [];

        foreach ($items as $key => $value) {
            if($request->hasFile($key)) {

                $path = Storage::putFileAs(
                    'storage/app/bb', $request->file($key), $id.'-'.$key.'-'.time().'.'.$request->file($key)->getClientOriginalExtension()
                );
                $info = array_add($info, $key, $path);
            }else{
                $info = array_add($info, $key, $value);
            }
        }

        $new = [
            'order_id' => $id,
            'info' => json_encode($info),
            'success' => true,
        ];

        Baobei::create($new);

        $color = 'success';
        $icon = 'heart-o';
        $text = '您已成功报备, '.$in->show('name').'感谢您的支持! <br><br><a href="/orders" class="btn btn-sm btn-success">返回</a>';

        return view('note', compact('color', 'icon', 'text'));
    }

    /**
     * 报备失败
     *
     */
    public function bbFail($id)
    {
        $form = $this->form(BbFailForm::class, [
            'method' => 'POST',
            'url' => '/bb/fail/store/'.$id,
        ]);

        $title = '报备: 未下款';
        $icon = 'heart-o';

        return view('form', compact('form','title','icon'));
    }

    /**
     * 保存
     *
     */
    public function bbFailStore($id, Request $request)
    {
        $path = Storage::putFileAs('storage/app/bb', $request->file('fail'), $id.'-'.'fail'.'-'.time().'.'.$request->file('fail')->getClientOriginalExtension());

        $fail = Baobei::create([
            'order_id' => $id,
            'info->fail' => $path,
        ]);

        $color = 'success';
        $icon = 'heart-o';
        $text = '您已报备未下款产品, 感谢支持! <br><br><a href="/orders" class="btn btn-sm btn-success">返回</a>';

        return view('note', compact('color', 'icon', 'text'));
    }

    /**
     * 下载
     *
     */
    public function bbShow($id, Role $role)
    {
        if(!$role->admin() && !$role->shopBoss()) abort('403');

        $record = Order::findOrFail($id);
        return view('orders.show', compact('record'));
    }

    /**
     * 审核成功
     *
     */
    public function checkOk($id, Role $role)
    {
        if(!$role->admin() && !$role->shopBoss()) abort('403');

        $record = Order::findOrFail($id);
        $info = json_decode($record->bb->info);

        $record->bb->update([
            'check' => now(),
            'resault' => true,
        ]);

        // 限制次数 +1
        $role->limitAdd($record->customer->id);

        return redirect('/orders');
    }


    /**
     * 审核失败
     *
     */
    public function checkFail($id, Role $role)
    {
        if(!$role->admin() && !$role->shopBoss()) abort('403');

        $record = Order::findOrFail($id);
        $info = json_decode($record->bb->info);

        // if(isset($info->))
        foreach ($info as $key => $value) {
            if($info->$key) Storage::delete([$value]);
        }
        $record->bb->delete();
        
        return redirect('/orders');
    }


    /**
     * 视频下载
     *
     */
    public function videoDownload($id, Role $role)
    {
        if(!$role->admin() && !$role->shopBoss()) abort('403');

        $target = Baobei::find($id);

        // $target->info
        $info = json_decode($target->info, true);

        if(array_key_exists('video', $info)) {
            return response()->download('storage/'.$info['video']);
        }
    }


    /**
     *
     *
     */
}




















