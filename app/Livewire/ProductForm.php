<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;

class ProductForm extends Component
{
    public $product;
    public $productId;
    public $name;
    public $sku;
    public $category_id;
    public $concentration;
    public $dosage_per_hectare;
    public $application_volume_per_hectare;
    public $stock;
    public $categories;
    public $isEditing = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $this->productId,
            'category_id' => 'required|exists:categories,id',
            'concentration' => 'required|numeric|min:0',
            'dosage_per_hectare' => 'required|numeric|min:0',
            'application_volume_per_hectare' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0'
        ];
    }
    
    public function mount($productId = null)
    {
        $this->categories = Category::pluck('name', 'id');

        if ($productId) {
            $this->isEditing = true;
            $this->productId = $productId;
            $this->product = Product::find($productId);
            $this->name = $this->product->name;
            $this->sku = $this->product->sku;
            $this->category_id = $this->product->category_id;
            $this->concentration = $this->product->concentration;
            $this->dosage_per_hectare = $this->product->dosage_per_hectare;
            $this->application_volume_per_hectare = $this->product->application_volume_per_hectare;
            $this->stock = $this->product->stock;
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEditing) {
            $this->product->update($validatedData);
        } else {
            Product::create($validatedData);
        }

        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.product-form');
    }
}