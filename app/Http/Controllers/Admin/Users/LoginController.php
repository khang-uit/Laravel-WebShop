<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Roles;
use App\Models\RolesUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;
use Auth;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{

    public function list(){
        // $users = User::selectRaw('users.email as user_email, roles.id as roles_id')
        // ->join('admin_roles', 'admin_roles.admin_id', '=', 'users.id')
        // ->join('roles', 'admin_roles.roles_id', '=', 'roles.id')
        // ->orderBy('admin_roles.admin_id', 'DESC')
        // ->get();
        // return view('admin.users.list', [
        //     'users' => $users,
        //     'title' => 'Danh sách nhân viên'
        // ]); 

        // $users = User::orderBy('id','DESC')->get();

        // foreach($users as $key => $user){           
        //     $roleUsers = RolesUser::where('user_id', ((string)$user->id))->get();
        //     foreach($roleUsers as $key => $roleUser){
        //         $role = Roles::where('id', ((string)$roleUser->roles_id))->get()->first();
        //         $roles[] = $role;
        //         $dataUsers[] = array(
        //             'email' => $user->email,
        //             'roles' => $roles
        //         );
        //     }          
        // }
            
        $users = User::with('roles')->orderBy('id','DESC')->get();
        return view('admin.users.list', [
            'users' => $users,
            'title' => 'Danh sách nhân viên'
        ]);

        // return view('admin.users.list', [
        //     'dataUsers' => $dataUsers,
        //     'title' => 'Danh sách nhân viên'
        // ]);
    }

    public function delete($id){
        if(Auth::id()==$id){
            return redirect()->back();
        }


        $user = User::find($id);
        if($user){
            $user->role()->detach();
            $user->delete();
        }
        $user->delete();
    }

    public function index(){
        return view('admin.users.login', [
            'title' => 'Đăng nhập hệ thống'
        ]);
    }

    public function assign_roles(Request $request){

        $user = User::where('email', $request->email)->first();
        $user->roles()->detach();
        if($request->author_role){
           $user->roles()->attach(Roles::where('name','author')->first());     
        }
        if($request->user_role){
           $user->roles()->attach(Roles::where('name','user')->first());     
        }
        if($request->admin_role){
           $user->roles()->attach(Roles::where('name','admin')->first());     
        }
        return redirect()->back();
    }

    public function store(Request $request){
        $this->validate($request, [
            'email' => 'required|email:filter',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ], $request->input('remember'))) {
            $user = User::where('email', $request->input('email'))->first();
            FacadesSession::put('email', $user->email);
            FacadesSession::put('id', $user->id);
            return redirect()->route('admin');
        }
        

        FacadesSession::flash('error', 'Email hoặc Password không đúng');
        return redirect()->back();
    }

    public function logout(Request $request){
        FacadesSession::put('email', null);
        FacadesSession::put('id', null);

        return redirect()->route('login');
    }

    public function registerAuth(){
        return view('admin.users.register', [
            'title' => 'Trang đăng ký'
        ]);
    }

    public function register(Request $request){
        $this->validation($request);
        $data = $request->all();

        $user = new User();
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);

        $user->save();
        return redirect('admin/users/login');
    }

    public function validation($request){
        return $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|max:255'
        ]);
    }
}

