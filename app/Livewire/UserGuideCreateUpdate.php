<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserGuide;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class UserGuideCreateUpdate extends Component
{
    use WithFileUploads;

    public $userGuideId;
    public $title = '';
    public $description = '';
    public $file;
    public $file_path = '';

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => $this->userGuideId ? 'nullable|file|mimes:pdf,doc,docx' : 'required|file|mimes:pdf,doc,docx',
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
        }
    }

    public function save()
    {
        $this->validate();
        $filePath = $this->file_path;
        if ($this->file) {
            if ($this->file->isValid()) {
                $filename = uniqid('guide_') . '.' . $this->file->getClientOriginalExtension();
                try {
                    $saved = $this->file->storeAs('uploads/guides', $filename, ['disk' => 'public']);
                    if ($saved) {
                        $filePath = 'uploads/guides/' . $filename;
                    } else {
                        session()->flash('error', __('File could not be saved.'));
                    }
                } catch (\Exception $e) {
                    session()->flash('error', __('File upload error: ') . $e->getMessage());
                }
            } else {
                session()->flash('error', __('Uploaded file is not valid.'));
            }
        }
        if ($this->userGuideId) {
            $guide = UserGuide::findOrFail($this->userGuideId);
            $guide->update([
                'title' => $this->title,
                'description' => $this->description,
                'file_path' => $filePath,
            ]);
            session()->flash('success', __('User guide updated successfully.'));
        } else {
            UserGuide::create([
                'title' => $this->title,
                'description' => $this->description,
                'file_path' => $filePath,
                'user_id' => Auth::id(),
            ]);
            session()->flash('success', __('User guide created successfully.'));
            $this->reset(['title', 'description', 'file']);
        }
        return redirect()->route('user_guides_index', app()->getLocale());
    }

    public function render()
    {
        return view('livewire.user-guide-create-update')->extends('layouts.master')->section('content');
    }
}
