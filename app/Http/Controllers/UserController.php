<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
        join('departments', 'users.department_id', '=', 'departments.id')
        ->join('users_status', 'users.status', '=', 'users_status.id')
        ->select('users.*',
        'departments.name as departments',
         'users_status.name as status')
        ->get();
        return response()->json($users);

    }
}
