<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Auth;

use App\Baobei;
use App\Shop;
use App\Order;
use App\User;
use App\Product;
use App\Forms\OrderForm;
use App\Forms\BbForm;
use App\Forms\BbBackForm;
use App\Helpers\Info;
use App\Helpers\Validator;

class OrderController extends Controller
{
    use FormBuilderTrait;

    /**
     * 列表
     *
     */
    public function index(Info $info)
    {
        $records = Order::
                where('shop_id', $info->id())
                ->latest()
                ->get();

        return view('orders.orders', compact('records'));
    }

    /**
     * 新订单
     *
     */
    public function create()
    {
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
    public function store(Request $request, Info $info)
    {
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
        
        $color = 'success';
        $icon = 'heart-o';
        $text = '订单登记成功! <br><br><a href="/orders/create" class="btn btn-sm btn-success">继续登记</a>';

        return view('note', compact('color', 'icon', 'text'));

    }

    /**
     * 报备
     *
     */
    public function bb(Request $request)
    {
        // print_r($request->all());
        switch ($request->success) {
            case 'yes':
                return $this->bbForm($request->order_id);
                break;

            case 'no':
                return $this->bbFail($request->order_id);
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * 报备
     *
     */
    private function bbForm($order_id)
    {
        $form = $this->form(BbForm::class, [
            'method' => 'POST',
            'url' => '/orders/bb/store/'.$order_id,
            'data' => ['order_id' => $order_id],
        ]);

        $title = '报备';

        $v = new Validator;
        if($v->bbBackForm($order_id)) $title = '报备: <small>录视频麻烦?<a href="/orders/bb/forms/back/'.$order_id.'" class="btn btn-sm btn-outline-primary">提供账号</a></small>';

        $icon = 'heart';

        return view('form', compact('form','title','icon'));
    }

    /**
     * 报备: 备用表单
     *
     */
    public function bbBack($order_id)
    {
        $form = $this->form(BbBackForm::class, [
            'method' => 'POST',
            'url' => '/orders/bb/store/'.$order_id,
            'data' => ['order_id' => $order_id],
        ]);

        $title = '报备';

        $v = new Validator;
        if($v->bbBackForm($order_id)) $title = '报备: <small>觉得录视频安全?<a href="/orders" class="btn btn-sm btn-outline-primary">重新报备</a></small>';

        $icon = 'heart';

        return view('form', compact('form','title','icon'));
    }

    /**
     * 报备
     *
     */
    public function bbStore($order_id, Request $request, Info $in)
    {
        $items = $request->except(['_token']);

        $info = [];

        foreach ($items as $key => $value) {
            if($request->hasFile($key)) {

                $path = Storage::putFileAs(
                    'storage/app/bb', $request->file($key), $order_id.'-'.$key.'-'.time().'.'.$request->file($key)->getClientOriginalExtension()
                );
                $info = array_add($info, $key, $path);
            }else{
                $info = array_add($info, $key, $value);
            }
        }

        $new = [
            'order_id' => $order_id,
            'info' => json_encode($info),
            'bb' => now(),
        ];

        Baobei::create($new);

        $color = 'success';
        $icon = 'heart-o';
        $text = '您已成功报备, '.$in->show('name').'感谢您的支持! <br><br><a href="/orders" class="btn btn-sm btn-success">返回</a>';

        return view('note', compact('color', 'icon', 'text'));

    }



    /**
     * 报备: 失败提示
     *
     */
    private function bbFail($id)
    {
        $order = Order::findOrFail($id);

        $color = 'warning';
        $icon = 'low-vision';
        $text = '您正将 '.$order->created_at.' 在 <strong>'.$order->product->name.'</strong> 审批的订单标记为未成功下款, 此产品将在您后续一个月登录中从产品列表中排除, 以呈现给您大通过率的产品,请确认! 若产品已下款, 请 <a href="/orders">返回</a> 报备  <br><br><a href="/orders/bb/fail/'.$id.'" class="btn btn-sm btn-warning">确认下款不成功</a>';

        return view('note', compact('color', 'icon', 'text'));
    }

    /**
     * 报备失败: 操作
     *
     */
    public function bbFailStore($id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'finish' => now(),
            // 'success' => false # 默认
        ]);

        $color = 'success';
        $icon = 'heart-o';
        $text = '您的操作已成功! <br><br><a href="/orders" class="btn btn-sm btn-success">返回</a>';

        return view('note', compact('color', 'icon', 'text'));

    }

    /**
     * 报备失败: 操作
     *
     */
    public function bbShow($id)
    {
        $record = Order::findOrFail($id);

        return view('orders.show', compact('record'));
    }

    /**
     *
     *
     */
}




















