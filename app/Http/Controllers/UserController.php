<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    public function show($id)
    {
        return response()->json([
            'data' => User::find($id)
        ]);
    }
    public function index()
    {
        $users = User::join('departments', 'users.department_id', '=', 'departments.id')
            ->join('users_status', 'users.status', '=', 'users_status.id')
            ->select(
                'users.*',
                'departments.name as departments',
                'users_status.name as status'
            )
            ->get();
        return response()->json($users);
    }
    public function create()
    {
        $users_status = DB::table('users_status')
            ->select(
                "id as value",
                "name as label",
            )
            ->get();
        $departments = DB::table('departments')
            ->select(
                "id as value",
                "name as label",
            )
            ->get();
        return response()->json([
            "users_status" => $users_status,
            "departments" => $departments,
        ]);
    }
    public function store(Request $request)
    {
        //         $validated = $request->validate([
        // "status" => "required",
        // "username" => "required|unique:users,username",
        // "name" => "required|max:255",
        // "email" => "required|email",
        // "department_id" => "required",
        // "password" => "required|confirmed"

        //         ],[
        //         "status.required" => " Vui lòn Nhập tình trạng ",
        //         "usename.required" => " Vui lòn Nhập Tên tài khoản ",
        //         "usename.unique" => " Tên tài khoản đã tồn tại ",
        //         "name.required" => " Vui lòn Nhập Họ và tên ",
        //         "email.required" => " Vui lòn Nhập email ",
        //         "department_id.required" => " Vui lòn Nhập phòng ban ",
        //         "password.required" => "  Vui lòn Nhập mật khẩu ",
        //         "password.required" =>" Vui lòng nhập xác nhận mật khẩu"

        //     ]);
        // return $request;

        //Eloquent ORM
        User::create([
            "status" =>$request["status"],
            "username" =>$request["username"],
            "name" =>$request["name"],
            "email" =>$request["email"],
            "department_id" =>$request["department_id"],
            "password" =>Hash::make($request["password"]),
        ]);

    }
}
