<?php

namespace App\Livewire\Company\Permissions;

use App\Repository\Interface\PermissionInterface;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

#[Layout('components.layouts.app')]
#[Title('Permissions')]
class Permissions extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'newest';

    protected PermissionInterface $permissionRepo;

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

    public function boot(PermissionInterface $permissionRepo)
    {
        $this->permissionRepo = $permissionRepo;
    }


    #[On('reloadPermissions')]
    public function reloadPermissions()
    {
        $query = Permission::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->sortBy === 'latest', fn($q) => $q->oldest())
            ->when($this->sortBy === 'newest', fn($q) => $q->latest());

        return $query->paginate($this->perPage);
    }

    public function edit($id)
    {
        $this->dispatch('permissionEdit', $id);
    }

    public function delete($id)
    {
        $this->dispatch('permissionDelete', $id);
    }

    public function render()
    {
        // $query = Permission::query()
        //     ->when($this->search, function ($query) {
        //         $query->where('name', 'like', '%' . $this->search . '%');
        //     })
        //     ->when($this->sortBy === 'latest', fn($q) => $q->oldest())
        //     ->when($this->sortBy === 'newest', fn($q) => $q->latest());

        // $permissions = $query->paginate($this->perPage);

        $permissions = $this->permissionRepo->paginateFiltered($this->search, $this->sortBy, $this->perPage);
        return view('livewire.company.permissions.permissions', compact('permissions'));
    }
}
