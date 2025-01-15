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

    public $category_id;

    public $concentration;

    public $dosage_per_hectare;

    public $application_volume_per_hectare;

    public $stock;

    public $categories;

    public $merchant_id;

    public $merchants;

    public $isEditing = false;

    protected function rules()
    {
        $productRequest = new ProductRequest;
        $productRequest->setProductId($this->productId);

        return $productRequest->rules();
    }

    public function mount($productId = null)
    {
        $this->categories = Category::pluck('name', 'id');
        $this->merchants = Merchant::where('merchant_type', MerchantType::CLIENT)
            ->pluck('business_name', 'id');

        if ($productId) {
            $this->isEditing = true;
            $this->productId = $productId;
            $this->product = Product::find($productId);
            $this->name = $this->product->name;
            $this->category_id = $this->product->category_id;
            $this->merchant_id = $this->product->merchant_id;
            $this->concentration = $this->product->concentration;
            $this->dosage_per_hectare = $this->product->dosage_per_hectare;
            $this->application_volume_per_hectare = $this->product->application_volume_per_hectare;
            $this->stock = $this->product->stock;
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

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
