<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth:sanctum');
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->listUsers();

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return new UserResource($user);
    }

    public function show($id)
    {
        $user = $this->userService->getUser($id);

        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $user = $this->userService->updateUser($id, $request->all());

        return new UserResource($user);
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    
}
