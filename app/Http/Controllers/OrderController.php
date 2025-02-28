<?php

namespace App\Http\Controllers;

use App\DataTables\OrderDataTable;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('pages.orders.index');
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        return view('pages.orders.form');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        return view('pages.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        return view('pages.orders.form', compact('order'));
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        // Check if there are related records that should be deleted first
        if ($order->flights()->count() > 0) {
            return redirect()->route('orders.index')->with('error', 'No se puede eliminar la orden porque tiene vuelos asociados.');
        }

        // Attempt to delete the order
        try {
            $order->delete();
            return redirect()->route('orders.index')->with('success', 'Orden eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Error al eliminar la orden: ' . $e->getMessage());
        }
    }
}
