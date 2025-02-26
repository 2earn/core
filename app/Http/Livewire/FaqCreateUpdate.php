<?php

namespace App\Http\Livewire;

use App\Models\Faq;
use App\Models\TranslaleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class FaqCreateUpdate extends Component
{
    public $idFaq;
    public $question, $answer;

    public $update = false;


    protected $rules = [
        'question' => 'required',
        'answer' => 'required',
    ];

    public function mount(Request $request)
    {
        $this->idFaq = $request->input('idFaq');
        if (!is_null($this->idFaq)) {
            $this->edit($this->idFaq);
        }
    }


    public function edit($idFaq)
    {
        $faq = Faq::findOrFail($idFaq);
        $this->idFaq = $idFaq;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('faq_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Faq operation cancelled'));
    }

    public function update()
    {
        $this->validate();

        try {
            Faq::where('id', $this->idFaq)
                ->update(
                    [
                        'question' => $this->question,
                        'answer' => $this->answer
                    ]);
        } catch (\Exception $exception) {
            $this->cancel();
            Log::error($exception->getMessage());
            return redirect()->route('faq_index', ['locale' => app()->getLocale(), 'idFaq' => $this->idFaq])->with('danger', Lang::get('Something goes wrong while updating Faq'));
        }
        return redirect()->route('faq_index', ['locale' => app()->getLocale(), 'idFaq' => $this->idFaq])->with('success', Lang::get('Faq Updated Successfully'));

    }

    public function store()
    {
        $this->validate();
        $faq = [
            'question' => $this->question,
            'answer' => $this->answer
        ];
        try {
           $createdFAQ= Faq::create($faq);

            $translations = ['question', 'answer'];
            foreach ($translations as $translation) {
                TranslaleModel::create(
                    [
                        'name' => TranslaleModel::getTranslateName($createdFAQ, $translation),
                        'value' => $this->{$translation} . ' AR',
                        'valueFr' => $this->{$translation} . ' FR',
                        'valueEn' => $this->{$translation} . ' EN',
                        'valueTr' => $this->{$translation} . ' TR',
                        'valueEs' => $this->{$translation} . ' ES'
                    ]);
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('faq_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Faq'));
        }
        return redirect()->route('faq_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Faq Created Successfully'));
    }

    public function render()
    {
        $params = ['faqs' => Faq::find($this->idFaq)];
        return view('livewire.faq-create-update', $params)->extends('layouts.master')->section('content');
    }
}
