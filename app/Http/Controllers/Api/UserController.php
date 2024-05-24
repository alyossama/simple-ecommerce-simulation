<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserStoreRequest;
use App\Http\Requests\Api\User\UserUpdateRequest;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return $this->returnData($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = $user->createToken('User Personal Token');

        return $this->returnSuccessMessageWithToken('User successfully created!', $token);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->returnData($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->fill($request->all());
        $user->save();

        return $this->returnSuccessMessage('User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->returnSuccessMessage('User deleted successfully!');
    }

    /**
     * Login to an existing user.
     */
    public function login(Request $request)
    {
        //attempt with email and password if not send error message
        if (!Auth::attempt($request->only(['email', 'password']))) {
            $this->returnErrorMessage('Wrong email or password!');
        }

        // else find the user for the given email
        $user = User::where('email', $request->email)->first();

        // create token for this user
        return $this->returnSuccessMessageWithToken('User logged in successfully!', $user->createToken('personal access token')->plainTextToken);
    }

    /**
     * Logout.
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        // create token for this user
        return $this->returnSuccessMessage('User logged out successfully!');
    }
}
