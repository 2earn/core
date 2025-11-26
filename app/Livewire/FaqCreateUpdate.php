<?php

namespace App\Livewire;

use App\Services\Faq\FaqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class FaqCreateUpdate extends Component
{
    public $idFaq;
    public $question, $answer;

    public $update = false;

    protected FaqService $faqService;

    protected $rules = [
        'question' => 'required',
        'answer' => 'required',
    ];

    public function boot(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }

    public function mount(Request $request)
    {
        $this->idFaq = $request->input('idFaq');
        if (!is_null($this->idFaq)) {
            $this->edit($this->idFaq);
        }
    }


    public function edit($idFaq)
    {
        $faq = $this->faqService->getByIdOrFail($idFaq);
        $this->idFaq = $idFaq;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('faq_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Faq operation cancelled'));
    }

    public function updateFaq()
    {
        $this->validate();

        try {
            $this->faqService->update($this->idFaq, [
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
            $createdFAQ = $this->faqService->create($faq);
            createTranslaleModel($createdFAQ, 'question', $this->question);
            createTranslaleModel($createdFAQ, 'answer', $this->answer);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('faq_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Faq'));
        }
        return redirect()->route('faq_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Faq Created Successfully'));
    }

    public function render()
    {
        $params = ['faqs' => $this->faqService->getById($this->idFaq)];
        return view('livewire.faq-create-update', $params)->extends('layouts.master')->section('content');
    }
}
