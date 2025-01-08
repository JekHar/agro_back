<?php

namespace App\Livewire;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CategoryForm extends Component
{
    public $category;

    public $categoryId;

    public $name = '';

    public $description = '';

    public $category_id = '';

    public $categories;

    public $isEditing = false;

    public bool $isModal = false;

    protected function rules()
    {
        $categoryRequest = new CategoryRequest;
        $categoryRequest->setCategoryId($this->category_id);

        return $categoryRequest->rules();

    }

    public function mount($categoryId = null)
    {

        if ($categoryId) {
            $this->isEditing = true;
            $this->categoryId = $categoryId;
            $this->category = Category::find($categoryId);
            $this->name = $this->category->name;
            $this->description = $this->category->description;
            $this->category_id = $this->category->category_id;

            $this->categories = Category::where('id', '!=', $categoryId)
                ->whereNotIn('category_id', $this->getDescendantIds($categoryId))
                ->pluck('name', 'id');

        } else {
            $this->categories = Category::pluck('name', 'id');
        }
    }

    protected function getDescendantIds($categoryId)
    {
        $ids = [];
        $children = Category::where('category_id', $categoryId)->pluck('id')->toArray();

        foreach ($children as $childId) {
            $ids[] = $childId;
            $ids = array_merge($ids, $this->getDescendantIds($childId));
        }

        return $ids;
    }

    public function save()
    {
        $validatedData = $this->validate();
        try {
            if ($this->isEditing) {
                $this->category->update([
                    'name' => $validatedData['name'],
                    'description' => $validatedData['description'],
                    'category_id' => $validatedData['category_id'] ?: null,
                ]);
            } else {
                Category::create([
                    'name' => $validatedData['name'],
                    'description' => $validatedData['description'],
                    'category_id' => $validatedData['category_id'] ?: null,
                ]);
            }

            $this->dispatch('swal', [
                'title' => __('Éxito!'),
                'message' => __($this->isEditing ? 'crud.categories.actions.updated' : 'crud.categories.actions.created'),
                'icon' => 'success',
                'redirect' => route('categories.index'),
            ]);

            if ($this->isModal) {
                $this->dispatch('close-modal');
            }

            if (! $this->isEditing) {
                $this->reset(['name', 'description', 'category_id']);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
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
