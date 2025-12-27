<?php
namespace App\Services;

use App\Repositories\TaskRepository;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Notifications\TaskStatusUpdatedNotification;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    protected $repo;

    public function __construct(TaskRepositoryInterface $repo) {
        $this->repo = $repo;
    }

    public function list($filters) {
        return $this->repo->all($filters);
    }

    public function get($id) {
        return $this->repo->find($id);
    }

    public function create(array $data) {
        $data['user_id'] = Auth()->id();
        return $this->repo->create($data);
    }

    public function update($id, array $data) {
        $task = $this->repo->find($id);
        return $this->repo->update($task, $data);
    }

    public function delete($id) {
        $task = $this->repo->find($id);
        return $this->repo->delete($task);
    }
}
