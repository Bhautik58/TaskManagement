<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(true, 'Users fetched succesfully!', User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        if ($validateUser->fails()) {
            return $this->sendError(false, $validateUser->errors());
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            return $this->sendResponse(true, 'User created successfully!', $user, 201);
        } catch (Exception $e) {
            return $this->sendError(false, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->sendResponse(false, 'User not found', null, 401);
        }

        return $this->sendResponse(true, 'User retrieved successfully!', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->sendResponse(false, 'User not found', null, 401);
        }

        try {
            $user->update([
                'name' => $request->name
            ]);
            return $this->sendResponse(true, 'User updated successfully!', $user);
        } catch (Exception $e) {
            return $this->sendError(false, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->sendResponse(false, 'User not found', null, 401);
        }

        try {
            $user->delete();
            return $this->sendResponse(true, 'User deleted successfully!', null);
        } catch (Exception $e) {
            return $this->sendError(false, $e->getMessage());
        }
    }
}
