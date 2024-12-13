<?php

namespace App\Http\Livewire;

use App\Models\User;
use Core\Models\Platform;
use Core\Models\UserPlatforms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class EditAdmin extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $mobile;
    public $name;
    public $userRole;
    public $allRoles = [];
    public $platformes = [];
    protected $rules = [
        'platformes.*.selected' => 'required',
    ];
    public $currentId;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function changeRole($idUser)
    {
        try {
            DB::table('user_plateforme')->where('user_id', $idUser)->delete();
            foreach ($this->platformes->where('selected', 1) as $platformUser) {
                UserPlatforms::create(['user_id' => $idUser, 'plateforme_id' => $platformUser->id]);
            }
            if ($this->userRole == "") {
                dd("pas de role ");
                return;
            }
            $user = User::find($idUser);
            $user->syncRoles($this->userRole);
            return redirect()->route('role_assign', app()->getLocale())->with('success', Lang::get('User role updated successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('role_assign', app()->getLocale())->with('error', Lang::get('User role update failed'));
        }
    }

    public function render()
    {

        $userRoles = DB::table('users')
            ->leftjoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftjoin('countries', 'users.idCountry', '=', 'countries.id')
            ->selectRaw('users.id,users.name,users.mobile,users.idCountry,ifnull(model_has_roles.model_id,0) as idrole, ifnull(roles.name,\'sansRole\') role ,countries.name countrie ,countries.apha2 apha2')
            ->where('users.name', 'like', '%' . $this->search . '%')
            ->orWhere('users.mobile', 'like', '%' . $this->search . '%')
            ->orWhere('countries.name', 'like', '%' . $this->search . '%')
            ->paginate(10);
        return view('livewire.edit-admin', ['userRoles' => $userRoles])->extends('layouts.master')->section('content');
    }

    public function edit($idUser)
    {
        $this->allRoles = Role::all();
        $user = User::find($idUser);
        if (!$user->hasAnyRole(Role::all())) {
            $user->syncRoles('user');
        }
        $this->userRole = $user->getRoleNames()[0];
        $this->name = $user->name;
        $this->mobile = $user->mobile;
        $this->currentId = $user->id;
        $this->platformes = Platform::all();
        foreach ($this->platformes as $p) {
            $p->selected = 0;
            if ($p->selected($idUser)) {
                $p->selected = 1;
            }
        }
    }
}
