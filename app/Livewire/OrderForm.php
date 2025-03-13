<?php

namespace App\Livewire;

use App\Models\Aircraft;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

    // Lists for dropdowns
    public $clients = [];
    public $services = [];
    public $aircrafts = [];
    public $pilots = [];
    public $groundSupports = [];

    protected $rules = [
        'client_id' => 'required|exists:merchants,id',
        'service_id' => 'required|exists:services,id',
        'aircraft_id' => 'required|exists:aircrafts,id',
        'pilot_id' => 'required|exists:users,id',
        'ground_support_id' => 'required|exists:users,id',
        'observations' => 'nullable|string',
    ];

    protected $listeners = ['lotsUpdated' => 'handleLotsUpdated'];

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

        // Load dropdown options
        $this->loadClients();
        $this->loadAircrafts();
        $this->loadStaff();

        // Load services based on selected client (if any)
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
        $this->order = Order::findOrFail($orderId);

        // Map order properties to component properties
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
    }

    public function loadClients()
    {
        // Get clients from merchants table where merchant_type is 'client'
        $this->clients = Merchant::where('merchant_type', 'client')
            ->orderBy('business_name')
            ->get();
    }

    public function loadServices()
    {
        if (!$this->client_id) {
            $this->services = [];
            return;
        }

        // Get services related to the selected client or available to all clients
        $this->services = Service::whereNull('disabled_at')
            ->where(function ($query) {
                $query->where('merchant_id', $this->client_id)
                    ->orWhereNull('merchant_id');
            })
            ->orderBy('name')
            ->get();
    }

    public function loadAircrafts()
    {
        // Get aircrafts related to the tenant
        $tenantId = Auth::user()->merchant_id;

        $this->aircrafts = Aircraft::when(Auth::user()->hasRole('Tenant'), function ($query) use ($tenantId) {
            return $query->where('merchant_id', $tenantId);
        })
            ->orderBy('brand')
            ->get();
    }

    public function loadStaff()
    {
        // Load pilots (users with pilot role)
        $this->pilots = User::role('Pilot')
            ->when(Auth::user()->hasRole('Tenant'), function ($query) {
                return $query->where('merchant_id', Auth::user()->merchant_id);
            })
            ->orderBy('name')
            ->get();

        // Load ground support staff (users with ground support role)
        $this->groundSupports = User::role('Ground Support')
            ->when(Auth::user()->hasRole('Tenant'), function ($query) {
                return $query->where('merchant_id', Auth::user()->merchant_id);
            })
            ->orderBy('name')
            ->get();
    }

    // Lots section
    public $selectedLots = [];
    public $availableLots = [];

    // Update dependent fields when client changes
    public function updated($name, $value)
    {
        if ($name === 'client_id' && $value) {
            $this->loadServices();
            $this->service_id = null;
            $this->dispatch('clientSelected', $value);
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
        // Redirect to client creation page or show modal
        return redirect()->route('clients.merchants.create');
    }

    public function createNewService()
    {
        // Redirect to service creation page or show modal
        return redirect()->route('services.create');
    }

    public function createNewAircraft()
    {
        // Redirect to aircraft creation page or show modal
        return redirect()->route('aircrafts.create');
    }

    public function createNewPilot()
    {
        // Redirect to user creation page with pilot role preselected
        return redirect()->route('users.create', ['role' => 'Pilot']);
    }

    public function submit()
    {
        $this->validate();

        try {
            if ($this->isEditing) {
                // Update existing order
                $this->order->update([
                    'client_id' => $this->client_id,
                    'tenant_id' => Auth::user()->merchant_id,
                    'service_id' => $this->service_id,
                    'aircraft_id' => $this->aircraft_id,
                    'pilot_id' => $this->pilot_id,
                    'ground_support_id' => $this->ground_support_id,
                    'observations' => $this->observations,
                    'status' => $this->status,
                ]);

                // Handle prescription file if uploaded
                if ($this->prescription_file) {
                    $filePath = $this->prescription_file->store('prescriptions', 'public');
                    $this->order->prescription_file = $filePath;
                    $this->order->save();
                }

                $message = 'Orden actualizada correctamente';
            } else {
                // Create new order
                $order = Order::create([
                    'order_number' => $this->order_number,
                    'client_id' => $this->client_id,
                    'tenant_id' => Auth::user()->merchant_id,
                    'service_id' => $this->service_id,
                    'aircraft_id' => $this->aircraft_id,
                    'pilot_id' => $this->pilot_id,
                    'ground_support_id' => $this->ground_support_id,
                    'observations' => $this->observations,
                    'status' => 'draft',
                    'created_by' => Auth::id(),
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

            $this->dispatch('showAlert', [
                'title' => 'Éxito',
                'text' => $message,
                'type' => 'success'
            ]);

            // Redirect to next tab or order details
            return redirect()->route('orders.show', $this->isEditing ? $this->orderId : $this->order->id);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'title' => 'Error',
                'text' => 'Ha ocurrido un error: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function handleLotsUpdated($lots)
    {
        $this->selectedLots = $lots;
    }

    public function handleOpenLotCreation($clientId)
    {
        return redirect()->route('lots.create', ['client_id' => $clientId]);
    }

    public function render()
    {
        return view('livewire.order-form');
    }
}
