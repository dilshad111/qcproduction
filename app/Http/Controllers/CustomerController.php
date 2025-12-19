<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'contact_no' => 'nullable|string|max:20',
            'gst_no' => 'nullable|string|max:20',
        ]);

        $customer = new Customer($request->all());
        // Handle optional fields if they come as array/json
        if($request->optional_keys && $request->optional_values) {
             $optional = array_combine($request->optional_keys, $request->optional_values);
             $customer->optional_fields = $optional;
        }
        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        $customer->fill($request->all());
        if($request->optional_keys && $request->optional_values) {
             $optional = array_combine($request->optional_keys, $request->optional_values);
             $customer->optional_fields = $optional;
        }
        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
