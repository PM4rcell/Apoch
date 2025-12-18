<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function update(UpdateUserRoleRequest $request, User $user){
        
        $user->profile()->update([
            'role' => $request->role
        ]);

        return new UserResource($user->fresh('profile'));
    }
}
