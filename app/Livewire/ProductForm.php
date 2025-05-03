<?php

namespace App\Livewire;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Product;
use App\Types\MerchantType;
use Livewire\Component;

class ProductForm extends Component
{
    public $product;

    public $productId;

    public $name;

    public $brand;

    public $category_id;

    public $dosage_per_hectare;

    public $liters_per_container;

    public $stock;

    public $categories;

    public $merchant_id;

    public $merchants;

    public $isEditing = false;

    public $isTenant = false;

    protected function rules()
    {
        $productRequest = new ProductRequest;
        $productRequest->setProductId($this->productId);

        return [
            'rules' => $productRequest->rules(),
            'messages' => $productRequest->messages()
        ];
    }

    public function mount($productId = null)
    {
        $this->categories = Category::pluck('name', 'id');
        $this->isTenant = auth()->user()->hasRole('Tenant');
        
        if (auth()->user()->hasRole('Admin')) {
            $this->merchants = Merchant::where('merchant_type', 'client')
                ->pluck('business_name', 'id');
        } elseif ($this->isTenant) {
            $this->merchant_id = auth()->user()->merchant_id;

            $this->merchants = Merchant::where('merchant_type', 'client')
                ->where('merchant_id', auth()->user()->merchant_id)
                ->pluck('business_name', 'id');
        }

        if ($productId) {
            $this->isEditing = true;
            $this->productId = $productId;
            $this->product = Product::find($productId);
            $this->name = $this->product->name;
            $this->brand = $this->product->brand;
            $this->category_id = $this->product->category_id;
            
            if (!$this->isTenant) {
                $this->merchant_id = $this->product->merchant_id;
            }
            
            $this->dosage_per_hectare = $this->product->dosage_per_hectare;
            $this->liters_per_container = $this->product->liters_per_container;
            $this->stock = $this->product->stock;
        }
    }

    public function save()
    {
        $validatedData = $this->validate(
            $this->rules()['rules'],
            $this->rules()['messages']
        );

        if ($this->isTenant) {
            $validatedData['merchant_id'] = auth()->user()->merchant_id;
        }

        try {
            if ($this->isEditing) {
                $this->product->update($validatedData);
                $message = __('crud.products.updated');
            } else {
                Product::create($validatedData);
                $message = __('crud.products.success');
            }

            $this->dispatch('swal', [
                'title' => 'Éxito!',
                'message' => $message,
                'icon' => 'success',
                'redirect' => route('products.index'),
            ]);

        } catch (\Throwable $th) {
            $this->dispatch('swal', [
                'title' => ('Error'),
                'message' => ('Ocurrió un error al procesar la solicitud'),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.product-form');
    }
}