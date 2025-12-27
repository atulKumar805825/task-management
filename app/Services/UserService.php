<?php
namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected $repo;

    public function __construct(UserRepository $repo) {
        $this->repo = $repo;
    }

    public function listUsers() {
        return $this->repo->all();
    }

    public function getUser($id) {
        return $this->repo->find($id);
    }

    public function createUser(array $data) {
        $data['password'] = md5($data['password']); // assignment rule
        return $this->repo->create($data);
    }

    public function updateUser($id, array $data) {
        $user = $this->repo->find($id);
        return $this->repo->update($user, $data);
    }

    public function deleteUser($id) {
        $user = $this->repo->find($id);
        return $this->repo->delete($user);
    }
}
