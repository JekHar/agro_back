<?php

namespace app\Livewire;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Livewire\Component;

class CategoryForm extends Component
{
    public $category;
    public $name = '';
    public $description = '';
    public $category_id = '';
    public $isEditing = false;
    public bool $isModal = false;

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
            } else {
                Category::create($validatedData);
            }

            session()->flash('success', __('crud.categories.actions.saved'));
            $this->dispatch('categorySaved');
            $this->reset(['name', 'description', 'category_id']);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => __('Error'),
                'message' => $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.category-form');
    }
}

