<?php

namespace App\Livewire\Company\Roles;

use App\Repository\Interface\RoleInterface;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.app')]
#[Title('Roles')]
class Roles extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'newest';

    protected RoleInterface $roleRepo;
    protected function queryString()
    {
        return [
            'sortBy' => ['except' => 'newest', 'as' => 'sort'],
            'search' => ['as' => 'q',],
        ];
    }

    public function resetFilter()
    {
        $this->reset(['search', 'sortBy', 'perPage']);
        $this->resetPage();
    }

    public function boot(RoleInterface $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    #[On('reloadRoles')]
    public function reloadRoles()
    {
        return $this->roleRepo->paginateFiltered($this->search, $this->sortBy, $this->perPage);
    }

    public function edit($id)
    {
        $this->dispatch('roleEdit', $id);
    }

    public function delete($id)
    {
        $this->dispatch('roleDelete', $id);
    }

    public function render()
    {
        // $query = Role::query()
        //     ->when($this->search, function ($query) {
        //         $query->where('name', 'like', '%' . $this->search . '%');
        //     })
        //     ->when($this->sortBy === 'latest', fn($q) => $q->oldest())
        //     ->when($this->sortBy === 'newest', fn($q) => $q->latest());

        // $roles = $query->paginate($this->perPage);
        $roles = $this->roleRepo->paginateFiltered($this->search, $this->sortBy, $this->perPage);
        return view('livewire.company.roles.roles', compact('roles'));
    }
}
