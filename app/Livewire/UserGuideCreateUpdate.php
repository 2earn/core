<?php

namespace App\Livewire;

use App\Models\TranslaleModel;
use App\Models\UserGuide;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserGuideCreateUpdate extends Component
{
    use WithFileUploads;

    public $userGuideId;
    public $title = '';
    public $description = '';
    public $file;
    public $file_path = '';
    public $routes = [];

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => $this->userGuideId ? 'nullable|file|mimes:pdf,doc,docx' : 'required|file|mimes:pdf,doc,docx',
            'routes' => 'required|array|min:1',
        ];
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->userGuideId = $id;
            $guide = UserGuide::findOrFail($id);
            $this->title = $guide->title;
            $this->description = $guide->description;
            $this->file_path = $guide->file_path;
            $this->routes = $guide->routes ?? [];
        }
    }

    public function save()
    {
        $this->validate();
        $filePath = $this->file_path;
        if ($this->file) {
            $filename = uniqid('guide_') . '.' . $this->file->getClientOriginalExtension();
            try {
                $saved = $this->file->storeAs('uploads/guides', $filename, ['disk' => 'public']);
                if ($saved) {
                    $filePath = 'uploads/guides/' . $filename;
                } else {
                    session()->flash('error', __('File could not be saved.'));
                    return;
                }
            } catch (\Exception $e) {
                session()->flash('error', __('File upload error: ') . $e->getMessage());
                return;
            }
        }
        if ($this->userGuideId) {
            $guide = UserGuide::findOrFail($this->userGuideId);
            $guide->update([
                'title' => $this->title,
                'description' => $this->description,
                'file_path' => $filePath,
                'routes' => $this->routes,
            ]);
        } else {
            $userGuide = UserGuide::create([
                'title' => $this->title,
                'description' => $this->description,
                'file_path' => $filePath,
                'user_id' => Auth::id(),
                'routes' => $this->routes,
            ]);
            $this->reset(['title', 'description', 'file', 'routes']);
            $translations = ['title', 'description'];
            foreach ($translations as $translation) {
                TranslaleModel::create([
                    'name' => TranslaleModel::getTranslateName($userGuide, $translation),
                    'value' => $this->{$translation} . ' AR',
                    'valueFr' => $this->{$translation} . ' FR',
                    'valueEn' => $this->{$translation} . ' EN',
                    'valueTr' => $this->{$translation} . ' TR',
                    'valueEs' => $this->{$translation} . ' ES',
                    'valueRu' => $this->{$translation} . ' Ru',
                    'valueDe' => $this->{$translation} . ' De',
                ]);
            }
        }
        return redirect()->route('user_guides_index', app()->getLocale());
    }

    protected function getAllRoutes()
    {
        return collect(\Route::getRoutes())
            ->filter(function ($route) {
                $name = $route->getName();
                if (!$name || $route->getActionMethod() === 'closure') {
                    return false;
                }
                return !(
                    str_contains($name, 'debugbar') ||
                    str_contains($name, 'passport') ||
                    str_contains($name, 'log-viewer') ||
                    str_starts_with($name, 'api_')
                );
            })
            ->map(function ($route) {
                return [
                    'name' => $route->getName(),
                    'uri' => $route->uri(),
                ];
            })->values();
    }

    public function render()
    {
        $allRoutes = $this->getAllRoutes();
        return view('livewire.user-guide-create-update', [
            'allRoutes' => $allRoutes,
        ])->extends('layouts.master')->section('content');
    }
}
