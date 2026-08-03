<?php

namespace App\Livewire\Admin;

use App\Models\Faq;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class FaqManager extends Component
{
    use WithPagination;

    public $faqId;
    public $question;
    public $answer;
    public $is_active = true;

    public $isEditMode = false;
    public $showModal = false;
    public $deletingId = null;

    protected $rules = [
        'question' => 'required|string|max:255',
        'answer' => 'required|string',
        'is_active' => 'boolean',
    ];

    public function create()
    {
        $this->resetFields();
        $this->isEditMode = false;
        $this->dispatch('open-modal', 'faq-modal');
    }

    public function edit($id)
    {
        $this->resetFields();
        $faq = Faq::findOrFail($id);
        $this->faqId = $faq->id;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->is_active = (bool) $faq->is_active;

        $this->isEditMode = true;
        $this->dispatch('open-modal', 'faq-modal');
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditMode) {
            $faq = Faq::findOrFail($this->faqId);
            $faq->update([
                'question' => $this->question,
                'answer' => $this->answer,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'FAQ updated successfully.');
        } else {
            Faq::create([
                'question' => $this->question,
                'answer' => $this->answer,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'FAQ created successfully.');
        }

        $this->dispatch('close-modal', 'faq-modal');
        $this->resetFields();
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
        $this->dispatch('open-modal', 'delete-confirmation-modal');
    }

    public function delete()
    {
        if ($this->deletingId) {
            Faq::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'FAQ deleted successfully.');
        }
        $this->dispatch('close-modal', 'delete-confirmation-modal');
        $this->deletingId = null;
    }

    public function resetFields()
    {
        $this->faqId = null;
        $this->question = '';
        $this->answer = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.admin.faq-manager', [
            'faqs' => Faq::latest()->paginate(10)
        ]);
    }
}
