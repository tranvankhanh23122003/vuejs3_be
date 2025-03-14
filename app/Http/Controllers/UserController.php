<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Psy\Readline\Hoa\Console;
use Symfony\Component\HttpFoundation\Response;

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
        $users = User::
        // where("users.id" , "!" ,"1")
        join('departments', 'users.department_id', '=', 'departments.id')
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
        $validated = $request->validate([
            "status" => "required",
            "username" => "required|unique:users,username",
            "name" => "required|max:255",
            "email" => "required|email|unique:users,email",
            "department_id" => "required",
            "password" => "required|confirmed"

        ], [
            "status.required" => " Vui lòn Nhập tình trạng ",
            "username.required" => " Vui lòn Nhập Tên tài khoản ",
            "username.unique" => " Tên tài khoản đã tồn tại ",
            "name.required" => " Vui lòn Nhập Họ và tên ",
            "email.required" => " Vui lòn Nhập email ",
            "email.unique" => " Email đã tồn tại ",
            "department_id.required" => " Vui lòn Nhập phòng ban ",
            "password.required" => "  Vui lòn Nhập mật khẩu ",
            "password_confirmation.required" => " Vui lòng nhập xác nhận mật khẩu"

        ]);


        //Eloquent ORM
        $user = $request->except(["password", "password_confirmation"]); // CÁCH 1
        $user["password"] = Hash::make($request["password"]);
        User::create($user);
        return response()->json($user);



        //Eloquent ORM
        // User::create([                                           // CÁCH 2
        //     "status" =>$request["status"],
        //     "username" =>$request["username"],
        //     "name" =>$request["name"],
        //     "email" =>$request["email"],
        //     "department_id" =>$request["department_id"],
        //     "password" =>Hash::make($request["password"]),
        // ]);

    }
    function edit($id)
    {

        $user = User::find($id);
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
            "users" => $user,
            "users_status" => $users_status,
            "departments" => $departments,
        ]);
    }
    function update(Request $request, $id)
    {
        // $validated = $request->validate([
        //     "status" => "required",
        //     "username" => "required|unique:users,username,".$id,
        //     "name" => "required|max:255",
        //     "email" => "required|email",
        //     "department_id" => "required",

        // ], [
        //     "status.required" => " Vui lòn Nhập tình trạng ",
        //     "username.required" => " Vui lòn Nhập Tên tài khoản ",
        //     "username.unique" => " Tên tài khoản đã tồn tại ",
        //     "name.required" => " Vui lòn Nhập Họ và tên ",
        //     "email.required" => " Vui lòn Nhập email ",
        //     "department_id.required" => " Vui lòn Nhập phòng ban ",

        // ]);
        // return "ok";
        if ($request["change_password"] == false) {
            $update = User::find($id)->update([
                "status" => $request["status"],
                "username" => $request["username"],
                "name" => $request["name"],
                "email" => $request["email"],
                "department_id" => $request["department_id"],
            ]);

            if ($update) {
                return response()->json([
                    'message' => 'Cập nhật thành công'
                ], Response::HTTP_OK);
            }
        } else {
            $validated = $request->validate([
                'password' => 'required|confirmed|min:6',  // Password cần phải được xác nhận và ít nhất 6 ký tự
            ], [
                "password.required" => "Nhập mật khẩu ",
                "password.min" => "Mật khẩu ít nhất 6 ký tự ",
                "password.confirmed" => "Mật khẩu xác nhận không khớp", // Thêm thông báo lỗi khi không khớp
            ]);

            $update = User::find($id)->update([
                "password" => Hash::make($request["password"]),
                "change_password_at" => NOW(),
            ]);

            if ($update) {
                return response()->json([
                    'message' => 'Đổi pass thành công'
                ], Response::HTTP_OK);
            }
        }
    }
    function destroy($id){
       User::find($id)->delete();
    }
}
