<?php
namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function all();
    public function find(int $id): User;
    public function create(array $data): User;
    public function update(User $user, array $data): User;
    public function delete(User $user): bool;
}
