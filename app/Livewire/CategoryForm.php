<?php

namespace App\Livewire;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Livewire\Component;

class CategoryForm extends Component
{
    public $category;
    public $name = '';
    public $description = '';
    public $category_id = '';
    public $isEditing = false;
    public bool $isModal = false;

    protected function rules()
    {
        $categoryRequest = new CategoryRequest();
        $categoryRequest->setCategoryId($this->category_id);
        return $categoryRequest->rules();


    }

    public function mount($categoryId = null)
    {
        if ($categoryId) {
            $this->isEditing = true;
            $this->category = Category::find($categoryId);
            $this->name = $this->category->name;
            $this->description = $this->category->description;
            $this->category_id = $this->category->category_id;
        }
    }

    public function save()
    {
        try {
            $validatedData = $this->validate();

            if ($this->isEditing) {
                $this->category->update([
                    'name' => $validatedData['name'],
                    'description' => $validatedData['description'],
                    'category_id' => $validatedData['category_id'],
                ]);
                $message = __('crud.categories.actions.updated');
            } else {
                Category::create($validatedData);
                $message = __('crud.categories.actions.created');
            }

            $this->dispatch('category-saved');

            $this->dispatch('swal', [
                'title' => __('crud.categories.Success!'),
                'message' => $message,
                'icon' => 'success',
                'redirect' => route('categories.index'),
            ]);

            if ($this->isModal) {
                $this->dispatch('close-modal');
            }

            if (!$this->isEditing) {
                $this->reset(['name', 'description', 'category_id']);
            }
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => __('Error'),
                'message' => __('crud.categories.actions.error'),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.category-form');
    }
}
