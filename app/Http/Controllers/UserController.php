<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Auth;
use Hash;
use Session;

use App\User;
use App\Helpers\Validator;
use App\Helpers\Info;
use App\Helpers\Role;
use App\Forms\RegisterForm;
use App\Forms\LoginForm;
use App\Forms\PasswordForm;

class UserController extends Controller
{
    use FormBuilderTrait;

    /**
     * 登录
     *
     */
    public function login()
    {
         $form = $this->form(LoginForm::class, [
            'method' => 'POST',
            'url' => '/login/check'
        ]);

        $title = '登录: <a href="/register" class="btn btn-sm btn-outline-primary"><small>没有账号?</small></a>';
        $icon = 'key';

        return view('form', compact('form','title','icon'));
    }

    /**
     * 验证
     *
     */
    public function check(Request $request, Info $info, Role $role)
    {

        $exists = User::where('mobile', $request->mobile)
                        ->first();

        if(!$exists) return redirect()->back()->withErrors(['mobile'=>'号码不存在!'])->withInput();
        if(!Hash::check($request->password, $exists->password)) return redirect()->back()->withErrors(['password'=>'密码错误!'])->withInput();

        $to = Session::has('to') ? session('to') : '/';
        if(Session::has('to')) Session::forget('to');
        
        if(isset($request->remember_me) && $request->remember_me == 1 ) Auth::login($exists, true);
        Auth::login($exists);

        return redirect($to);

    }

    /**
     * 退出登录
     *
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    /**
     * 注册
     *
     */
    public function register()
    {
         $form = $this->form(RegisterForm::class, [
            'method' => 'POST',
            'url' => '/register/store'
        ]);

        $title = '注册: <a href="/login" class="btn btn-sm btn-outline-primary"><small>已有账号,去登录!</small></a>';
        $icon = 'magic';

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
        if($request->password != $request->password_confirmed) return redirect()->back()->withErrors(['password_confirmed'=>'两次密码不一致!'])->withInput();

        $exists = User::where('mobile', $request->mobile)
                        ->first();

        if($exists) return redirect()->back()->withErrors(['mobile'=>'号码已存在, 若确实属于您本人,请联系客服!'])->withInput();

        $new = [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'password' => bcrypt($request->password),
            'shop_id' => $info->id(),
        ];

        $user = User::create($new);

        Auth::login($user);
        
        $color = 'success';
        $icon = 'thumbs-o-up';
        $text = '恭喜! 您已经成功注册!<br><br><a href="/" class="btn btn-sm btn-success">去看看产品</a>';

        return view('note', compact('color', 'icon', 'text'));

    }

    /**
     * 重设密码
     *
     */
    public function passwordReset()
    {
        $form = $this->form(PasswordForm::class, [
            'method' => 'POST',
            'url' => '/password_reset/do'
        ]);

        $title = '修改密码';
        $icon = 'wrench';

        return view('form', compact('form','title','icon'));
    }

    /**
     * 重设密码
     *
     */
    public function passwordResetDo(Request $request)
    {
        if(!Auth::check()) return redirect('/');

        if($request->password != $request->password_confirmed) return redirect()->back()->withErrors(['password_confirmed'=>'两次密码不一致!'])->withInput();
        Auth::user()->update(['password' => bcrypt($request->password)]);

        $color = 'success';
        $icon = 'check-square-o';
        $text = '密码修改成功!';

        return view('note', compact('color', 'icon', 'text'));

    }

    // lock 锁定
    public function lock($id)
    {
        // $r = new Role;
        // if(!$r->higher($id)) abort('403');

        User::findOrFail($id)->update(['auth->locked' => true]);
        return redirect()->back();
    }

    // lock 解锁
    public function unlock($id)
    {
        // $r = new Role;
        // if(!$r->higher($id)) abort('403');

        User::findOrFail($id)->update(['auth->locked' => false]);
        return redirect()->back();
    }

    /**
     *
     *
     */
}





















