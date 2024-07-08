<?php

namespace App\Http\Livewire;

use App\Models\User;
use Core\Models\plateforme;
use Core\Models\UserPlatforms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function changeRole($idUser)
    {
        $userPlatforms = DB::table('user_plateforme')
            ->where('user_id', $idUser)
            ->delete();
        foreach ($this->platformes->where('selected', 1) as $platformUser) {
            UserPlatforms::create([
                'user_id' => $idUser,
                'plateforme_id' => $platformUser->id
            ]);
        }
        if ($this->userRole == "") {
            dd("pas de role ");
            return;
        }
        $user = User::find($idUser);
        $user->syncRoles($this->userRole);
        return redirect()->route('edit_admin', app()->getLocale())->with('SuccesUpdateRole', Lang::get('user role updated'));
    }

    public function render()
    {

        $translate = DB::table('users')
            ->leftjoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftjoin('countries', 'users.idCountry', '=', 'countries.id')
            ->selectRaw('users.id,users.name,users.mobile,users.idCountry,ifnull(model_has_roles.model_id,0) as idrole,
            ifnull(roles.name,\'sansRole\') role ,countries.name countrie')
            ->where('users.name', 'like', '%' . $this->search . '%')
            ->orWhere('users.mobile', 'like', '%' . $this->search . '%')
            ->orWhere('countries.name', 'like', '%' . $this->search . '%')
            ->paginate(5);
        return view('livewire.edit-admin', [
            'translates' => $translate
        ])->extends('layouts.master')->section('content');
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
        $this->platformes = plateforme::all();
        foreach ($this->platformes as $p) {
            $p->selected = 0;
            if ($p->selected($idUser) == 1) {
                $p->selected = 1;
            }
        }
    }
}
