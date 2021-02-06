<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $you = auth()->user()->id;
        $users = DB::table('users')
            ->select([
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.middle_name',
                'users.email',
                'users.menuroles as roles',
                'users.status',
                'users.email_verified_at as registered',
            ])
            ->where('test_subject', false)
            ->whereNull('deleted_at')
            ->get();
        return response()->json(compact('users', 'you'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = DB::table('users')
            ->select([
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.middle_name',
                'users.email',
                'users.menuroles as roles',
                'users.status',
                'users.email_verified_at as registered',
            ])
            ->where('users.id', '=', $id)
            ->first();
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = DB::table('users')
            ->select([
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.middle_name',
                'users.email',
                'users.menuroles as roles',
                'users.status',
            ])
            ->where('users.id', '=', $id)
            ->first();
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:1|max:256',
            'email' => 'required|email|max:256',
        ]);
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();
        //$request->session()->flash('message', 'Successfully updated user');
        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
        }
        return response()->json(['status' => 'success']);
    }
}
