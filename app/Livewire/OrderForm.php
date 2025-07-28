<?php

namespace App\Livewire;

use App\Enums\OrderLotStatus;
use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\FlightLot;
use App\Models\FlightProduct;
use App\Models\Lot;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\OrderLot;
use App\Models\OrderProduct;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderForm extends Component
{
    use WithFileUploads;

    // Order instance for editing
    public $order;
    public $orderId;
    public $isEditing = false;

    // Basic order info
    public $order_date;
    public $order_number;
    public $responsible_id;
    public $has_prescription = false;
    public $prescription_file;
    public $observations;
    public $status = 'draft';

    // Client and service info
    public $client_id;
    public $service_id;
    public $aircraft_id;
    public $pilot_id;
    public $ground_support_id;
    public $totalHectares = 0;

    // Lists for dropdowns
    public $clients = [];
    public $services = [];
    public $aircrafts = [];
    public $pilots = [];
    public $groundSupports = [];

    // Products and lots
    // TODO: selectedProducts removed - product selection moved to FlightWizard
    // public $selectedProducts = [];
    public $selectedLots = [];
    public $flights = [];

    public ?int $tenant_id = 0;
    public Collection $tenants;

    // Modal states
    public $showClientModal = false;
    public $showServiceModal = false;
    public $showAircraftModal = false;
    public $showPilotModal = false;
    public $showGroundSupportModal = false;

    // Form data for modals
    public $clientForm = [
        'business_name' => '',
        'trade_name' => '',
        'fiscal_number' => '',
        'email' => '',
        'phone' => '',
        'locality' => '',
        'address' => '',
        'merchant_type' => 'client',
        'merchant_id' => null
    ];

    public $serviceForm = [
        'name' => '',
        'price_per_hectare' => '',
        'merchant_id' => null
    ];

    public $aircraftForm = [
        'brand' => '',
        'alias' => '',
        'models' => '',
        'manufacturing_year' => '',
        'acquisition_date' => '',
        'working_width' => '',
        'merchant_id' => null
    ];

    public $userForm = [
        'name' => '',
        'email' => '',
        'password' => '',
        'merchant_id' => null,
        'role' => 'Pilot'
    ];

    protected $rules = [
        'client_id' => 'required|exists:merchants,id',
        'service_id' => 'required|exists:services,id',
        'aircraft_id' => 'required|exists:aircrafts,id',
        'pilot_id' => 'required|exists:users,id',
        'ground_support_id' => 'required|exists:users,id',
        'observations' => 'nullable|string',
        'selectedLots' => 'required|array|min:1',
        'selectedLots.*.lot_id' => 'required|exists:lots,id',
        'selectedLots.*.hectares' => 'required|numeric|min:0.01',
        'selectedLots.*.status' => 'required|in:pending,in_progress,completed',
        // 'selectedProducts' => 'required|array|min:1',
        // 'selectedProducts.*.product_id' => 'required|exists:products,id',
    ];

    protected $listeners = [
        'lotsUpdated' => 'handleLotsUpdated',
        // 'productsUpdated' => 'handleProductsUpdated', // TODO: Removed - products handled in FlightWizard
        'flightsUpdated' => 'handleFlightsUpdated'
    ];

    public function mount($orderId = null)
    {
        // Set editing mode if order ID is provided
        if ($orderId) {
            $this->isEditing = true;
            $this->orderId = $orderId;
            $this->loadOrder($orderId);
        } else {
            // Set default values for new order
            $this->order_date = now()->format('d/m/y');
            $this->responsible_id = Auth::id();
            $this->order_number = $this->generateOrderNumber();
        }

        $this->tenant_id = auth()->user()->merchant_id;

        // Load dropdown options
        $this->loadClients();
        $this->loadAircrafts();
        $this->loadStaff();

        if (auth()->user()->hasRole('Admin')) {
            $this->tenants = Merchant::where('merchant_type', 'tenant')
                ->whereHas('aircraft')
                ->whereHas('services')
                ->orderBy('business_name')
                ->get();
        }

        if ($this->client_id) {
            $this->loadServices();
        }
    }

    protected function generateOrderNumber()
    {
        // Generate a unique order number format: ORD-YYYYMMDD-XXXX
        $date = now()->format('Ymd');
        $lastOrder = Order::where('order_number', 'like', "ORD-{$date}-%")
            ->orderByDesc('id')
            ->first();

        $sequence = '0001';
        if ($lastOrder) {
            // Extract the sequence number and increment
            $parts = explode('-', $lastOrder->order_number);
            $lastSequence = intval(end($parts));
            $sequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        }

        return "ORD-{$date}-{$sequence}";
    }

    public function loadOrder($orderId)
    {
        $this->order = Order::with(['orderLots.lot', 'orderProducts.product', 'flights.flightLots.lot', 'flights.flightProducts.product'])
            ->findOrFail($orderId);

        $this->order_date = $this->order->created_at->format('d/m/y');
        $this->order_number = $this->order->order_number;
        $this->responsible_id = $this->order->created_by ?? Auth::id();
        $this->client_id = $this->order->client_id;
        $this->service_id = $this->order->service_id;
        $this->aircraft_id = $this->order->aircraft_id;
        $this->pilot_id = $this->order->pilot_id;
        $this->ground_support_id = $this->order->ground_support_id;
        $this->observations = $this->order->observations;
        $this->status = $this->order->status;

        $this->selectedLots = $this->order->orderLots->map(function ($orderLot) {
            return [
                'lot_id' => $orderLot->lot_id,
                'hectares' => $orderLot->hectares,
                'status' => $orderLot->status instanceof OrderLotStatus ? $orderLot->status->value : $orderLot->status,
            ];
        })->toArray();

        // $this->selectedProducts = $this->order->orderProducts->map(function ($orderProduct) {
        //     return [
        //         'product_id' => $orderProduct->product_id,
        //         'client_provided_quantity' => $orderProduct->client_provided_quantity,
        //         'total_quantity_to_use' => $orderProduct->total_quantity_to_use,
        //         'calculated_dosage' => $orderProduct->calculated_dosage,
        //         'product_difference' => $orderProduct->product_difference,
        //         'difference_observation' => $orderProduct->difference_observation,
        //         'use_client_quantity' => $orderProduct->use_client_quantity,
        //         'use_manual_dosage' => $orderProduct->use_manual_dosage,
        //         'manual_dosage_per_hectare' => $orderProduct->manual_dosage_per_hectare,
        //         'manual_total_quantity' => $orderProduct->manual_total_quantity,
        //     ];
        // })->toArray();



        $this->flights = $this->order->flights->map(function ($flight) {
            return [
                'total_hectares' => $flight->total_hectares,
                'status' => $flight->status,
                'lots' => $flight->flightLots->map(function ($flightLot) {
                    return [
                        'lot_id' => $flightLot->lot_id,
                        'lot_hectares' => $flightLot->lot_hectares,
                        'hectares_to_apply' => $flightLot->hectares_to_apply,
                        'lot_total_hectares' => $flightLot->lot_total_hectares,
                    ];
                })->toArray(),
                'products' => $flight->flightProducts->map(function ($flightProduct) {
                    return [
                        'product_id' => $flightProduct->product_id,
                        'quantity' => $flightProduct->quantity,
                    ];
                })->toArray(),
            ];
        })->toArray();


        $this->totalHectares = $this->selectedLots ? array_sum(array_column($this->selectedLots, 'hectares')) : 0;
    }

    public function loadClients()
    {
        $query = Merchant::where('merchant_type', 'client')
            ->orderBy('business_name');
        $query = $query->when(Auth::user()->hasRole('Tenant'), function ($query) {
            $query->where('merchant_id', auth()->user()->merchant_id);
        });
        $this->clients = $query->get();
    }

    public function loadServices()
    {
        // Get services related to the selected client or available to all clients
        $this->services = Service::whereNull('disabled_at')
            ->where(function ($query) {
                $query->where('merchant_id', $this->tenant_id)
                    ->orWhereNull('merchant_id');
            })
            ->orderBy('name')
            ->get();
    }

    public function loadAircrafts()
    {
        // Get aircraft related to the tenant

        $this->aircrafts = Aircraft::when(Auth::user()->hasRole('Tenant'), function ($query) {
            return $query->where('merchant_id', $this->tenant_id);
        })
            ->orderBy('brand')
            ->get();
    }

    public function loadStaff()
    {
        // Load pilots (users with pilot role)
        $this->pilots = User::role('Pilot')
            ->when(Auth::user()->hasRole('Tenant'), function ($query) {
                return $query->where('merchant_id', $this->tenant_id);
            })
            ->orderBy('name')
            ->get();

        // Load ground support staff (users with ground support role)
        $this->groundSupports = User::role('Ground Support')
            ->when(Auth::user()->hasRole('Tenant'), function ($query) {
                return $query->where('merchant_id', $this->tenant_id);
            })
            ->orderBy('name')
            ->get();
    }

    // Update dependent fields when client changes
    public function updated($name, $value)
    {
        if ($name === 'client_id' && $value) {
            $this->loadServices();
            $this->service_id = null;
            $this->dispatch('clientSelected', $value);
        }

        if('tenant_id' === $name && $value) {
            $this->loadClients();
            $this->loadAircrafts();
            $this->loadStaff();
            $this->loadServices();
        }
    }

    /**
     * Handle opening the lot selection modal/page
     */
    public function openLotSelection()
    {
        // This could redirect to a lot creation page
        // or open a modal for lot selection
        return redirect()->route('lots.create', ['client_id' => $this->client_id]);
    }

    public function createNewClient()
    {
        $this->resetClientForm();
        $this->showClientModal = true;
    }

    public function createNewService()
    {
        $this->resetServiceForm();
        $this->showServiceModal = true;
    }

    public function createNewAircraft()
    {
        $this->resetAircraftForm();
        $this->showAircraftModal = true;
    }

    public function createNewPilot()
    {
        $this->resetUserForm();
        $this->showPilotModal = true;
    }

    public function createNewGroundSupport()
    {
        $this->resetUserForm();
        $this->showGroundSupportModal = true;
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            if ($this->isEditing) {
                // Update existing order
                $this->order->update([
                    'client_id' => $this->client_id,
                    'tenant_id' => $this->tenant_id,
                    'service_id' => $this->service_id,
                    'aircraft_id' => $this->aircraft_id,
                    'pilot_id' => $this->pilot_id,
                    'ground_support_id' => $this->ground_support_id,
                    'observations' => $this->observations,
                    'status' => $this->status,
                    'total_hectares' => $this->totalHectares,
                ]);

                // Handle prescription file if uploaded
                if ($this->prescription_file) {
                    $filePath = $this->prescription_file->store('prescriptions', 'public');
                    $this->order->prescription_file = $filePath;
                    $this->order->save();
                }

                // Delete existing related data to avoid duplicates
                $this->order->orderLots()->delete();
                $this->order->orderProducts()->delete();
                $this->order->flights()->delete();

                $message = 'Orden actualizada correctamente';
            } else {
                // Create new order
                $order = Order::create([
                    'order_number' => $this->order_number,
                    'client_id' => $this->client_id,
                    'tenant_id' => $this->tenant_id,
                    'service_id' => $this->service_id,
                    'aircraft_id' => $this->aircraft_id,
                    'pilot_id' => $this->pilot_id,
                    'ground_support_id' => $this->ground_support_id,
                    'observations' => $this->observations,
                    'status' => 'draft',
                    'created_by' => Auth::id(),
                    'total_hectares' => $this->totalHectares,
                    'total_amount' => 0,
                ]);

                // Handle prescription file if uploaded
                if ($this->prescription_file) {
                    $filePath = $this->prescription_file->store('prescriptions', 'public');
                    $order->prescription_file = $filePath;
                    $order->save();
                }

                $this->order = $order;
                $this->orderId = $order->id;
                $message = 'Orden creada correctamente';
            }

            // Save lots
            $this->saveLots();

            // Save products
            // $this->saveProducts();

            // Save flights
            $this->saveFlights();

            DB::commit();

            $this->dispatch('showAlert', [
                'title' => 'Éxito',
                'text' => $message,
                'type' => 'success'
            ]);

            // Redirect to next tab or order details
            return redirect()->route('orders.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showAlert', [
                'title' => 'Error',
                'text' => 'Ha ocurrido un error: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Save the order lots
     */
    protected function saveLots()
    {
        foreach ($this->selectedLots as $lot) {
            if (empty($lot['lot_id'])) continue;

            OrderLot::create([
                'order_id' => $this->order->id,
                'lot_id' => $lot['lot_id'],
                'hectares' => $lot['hectares'],
                'status' => is_string($lot['status']) ? $lot['status'] : $lot['status']->value
            ]);
        }
    }

    /**
     * TODO: saveProducts method removed - products are now saved through flights in FlightWizard
     */
    // protected function saveProducts()
    // {
    //     foreach ($this->selectedProducts as $product) {
    //         if (empty($product['product_id'])) continue;
    //
    //         OrderProduct::create([
    //             'order_id' => $this->order->id,
    //             'product_id' => $product['product_id'],
    //             'client_provided_quantity' => $product['client_provided_quantity'] ?? 0,
    //             'total_quantity_to_use' => $product['total_quantity_to_use'] ?? 0,
    //             'calculated_dosage' => $product['calculated_dosage'] ?? 0,
    //             'product_difference' => $product['product_difference'] ?? 0,
    //             'difference_observation' => $product['difference_observation'] ?? '',
    //             'use_client_quantity' => $product['use_client_quantity'] ?? false,
    //             'use_manual_dosage' => $product['use_manual_dosage'] ?? false,
    //             'manual_dosage_per_hectare' => $product['manual_dosage_per_hectare'] ?? 0,
    //             'manual_total_quantity' => $product['manual_total_quantity'] ?? 0,
    //         ]);
    //     }
    // }

    /**
     * Save the flights and their related data
     */
    protected function saveFlights()
    {
        foreach ($this->flights as $flightData) {
            if (empty($flightData['total_hectares']) || floatval($flightData['total_hectares']) <= 0) continue;

            // Create flight record
            $flight = Flight::create([
                'order_id' => $this->order->id,
                'total_hectares' => $flightData['total_hectares'],
                'status' => 'pending',
                'flight_number' => rand(1000, 9999),
                'total_hectares' => 0, // calculate?
            ]);

            // Save flight lots
            if (isset($flightData['lots']) && is_array($flightData['lots'])) {
                foreach ($flightData['lots'] as $lotData) {
                    if (empty($lotData['lot_id'])) continue;

                    FlightLot::create([
                        'flight_id' => $flight->id,
                        'lot_id' => $lotData['lot_id'],
                        'lot_hectares' => $lotData['lot_hectares'] ?? 0,
                        'hectares_to_apply' => $lotData['hectares_to_apply'] ?? 0,
                        'lot_total_hectares' => 0, // calculate?
                    ]);
                }
            }

            // Save flight products
            if (isset($flightData['products']) && is_array($flightData['products'])) {
                foreach ($flightData['products'] as $productData) {
                    if (empty($productData['product_id'])) continue;

                    FlightProduct::create([
                        'flight_id' => $flight->id,
                        'product_id' => $productData['product_id'],
                        'quantity' => $productData['quantity'] ?? 0,
                    ]);
                }
            }
        }
    }

    public function handleLotsUpdated($lots): void
    {
        $this->selectedLots = $lots;

        // Calculate total hectares
        $this->totalHectares = 0;
        foreach ($lots as $lot) {
            $this->totalHectares += floatval($lot['hectares'] ?? 0);
        }

        // Dispatch event to update products with new hectares
        $this->dispatch('hectaresUpdated', $this->totalHectares);
    }

    public function handleOpenLotCreation($clientId): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('lots.create', ['client_id' => $clientId]);
    }

    public function handleProductsUpdated($products): void
    {
        $this->selectedProducts = $products;
    }

    public function handleFlightsUpdated($flights): void
    {
        $this->flights = $flights;
    }

    // Modal reset methods
    public function resetClientForm()
    {
        $this->clientForm = [
            'business_name' => '',
            'trade_name' => '',
            'fiscal_number' => '',
            'email' => '',
            'phone' => '',
            'locality' => '',
            'address' => '',
            'merchant_type' => 'client',
            'merchant_id' => $this->tenant_id
        ];
    }

    public function resetServiceForm()
    {
        $this->serviceForm = [
            'name' => '',
            'price_per_hectare' => '',
            'merchant_id' => $this->tenant_id
        ];
    }

    public function resetAircraftForm()
    {
        $this->aircraftForm = [
            'brand' => '',
            'alias' => '',
            'models' => '',
            'manufacturing_year' => '',
            'acquisition_date' => '',
            'working_width' => '',
            'merchant_id' => $this->tenant_id
        ];
    }

    public function resetUserForm()
    {
        $this->userForm = [
            'name' => '',
            'email' => '',
            'password' => '',
            'merchant_id' => $this->tenant_id,
            'role' => 'Pilot'
        ];
    }

    // Modal save methods
    public function saveClient()
    {
        $this->validate([
            'clientForm.business_name' => 'required|string|max:255',
            'clientForm.trade_name' => 'nullable|string|max:255',
            'clientForm.fiscal_number' => 'required|string|unique:merchants,fiscal_number',
            'clientForm.email' => 'required|email|unique:merchants,email',
            'clientForm.phone' => 'nullable|string|max:20',
            'clientForm.locality' => 'nullable|string|max:255',
            'clientForm.address' => 'nullable|string|max:500',
        ]);

        $client = Merchant::create($this->clientForm);

        $this->loadClients();
        $this->client_id = $client->id;
        $this->showClientModal = false;

        $this->dispatch('showAlert', [
            'title' => 'Éxito',
            'text' => 'Cliente creado correctamente',
            'type' => 'success'
        ]);
    }

    public function saveService()
    {
        $this->validate([
            'serviceForm.name' => 'required|string|max:255',
            'serviceForm.price_per_hectare' => 'required|numeric|min:0',
        ]);

        $service = Service::create($this->serviceForm);

        $this->loadServices();
        $this->service_id = $service->id;
        $this->showServiceModal = false;

        $this->dispatch('showAlert', [
            'title' => 'Éxito',
            'text' => 'Servicio creado correctamente',
            'type' => 'success'
        ]);
    }

    public function saveAircraft()
    {
        $this->validate([
            'aircraftForm.brand' => 'required|string|max:255',
            'aircraftForm.alias' => 'required|string|max:255',
            'aircraftForm.models' => 'nullable|string|max:255',
            'aircraftForm.manufacturing_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'aircraftForm.acquisition_date' => 'nullable|date',
            'aircraftForm.working_width' => 'nullable|numeric|min:0',
        ]);

        $aircraft = Aircraft::create($this->aircraftForm);

        $this->loadAircrafts();
        $this->aircraft_id = $aircraft->id;
        $this->showAircraftModal = false;

        $this->dispatch('showAlert', [
            'title' => 'Éxito',
            'text' => 'Aeronave creada correctamente',
            'type' => 'success'
        ]);
    }

    public function savePilot()
    {
        $this->validate([
            'userForm.name' => 'required|string|max:255',
            'userForm.email' => 'required|email|unique:users,email',
            'userForm.password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $this->userForm['name'],
            'email' => $this->userForm['email'],
            'password' => bcrypt($this->userForm['password']),
            'merchant_id' => $this->userForm['merchant_id'],
        ]);

        $user->assignRole($this->userForm['role']);

        $this->loadStaff();

        if ($this->userForm['role'] === 'Pilot') {
            $this->pilot_id = $user->id;
        } else {
            $this->ground_support_id = $user->id;
        }

        $this->showPilotModal = false;
        $this->showGroundSupportModal = false;

        $this->dispatch('showAlert', [
            'title' => 'Éxito',
            'text' => ($this->userForm['role'] === 'Pilot' ? 'Piloto' : 'Apoyo de tierra') . ' creado correctamente',
            'type' => 'success'
        ]);
    }

    public function saveGroundSupport()
    {
        $this->userForm['role'] = 'Ground Support';
        $this->savePilot();
    }

    // Modal close methods
    public function closeClientModal()
    {
        $this->showClientModal = false;
        $this->resetClientForm();
    }

    public function closeServiceModal()
    {
        $this->showServiceModal = false;
        $this->resetServiceForm();
    }

    public function closeAircraftModal()
    {
        $this->showAircraftModal = false;
        $this->resetAircraftForm();
    }

    public function closePilotModal()
    {
        $this->showPilotModal = false;
        $this->resetUserForm();
    }

    public function closeGroundSupportModal()
    {
        $this->showGroundSupportModal = false;
        $this->resetUserForm();
    }

    public function render()
    {
        return view('livewire.order-form');
    }
}
