<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    // Dashboard method (already present earlier)
    public function dashboard()
    {
        $totalCustomers = Customer::count();
        $totalMessages = ActivityLog::where('type','sms_sent')->count();
        $totalCalls = ActivityLog::where('type','call_made')->count();
        $recentCustomers = Customer::latest()->take(5)->get();

        return view('dashboard', compact('totalCustomers','totalMessages','totalCalls','recentCustomers'));
    }

    // List + search + pagination
    public function index(Request $request)
    {
        $q = $request->input('q');
        $status = $request->input('status');

        $customers = Customer::when($q, function($query) use ($q) {
                $query->where('name','like',"%{$q}%")
                      ->orWhere('email','like',"%{$q}%")
                      ->orWhere('phone','like',"%{$q}%");
            })
            ->when($status && in_array($status, ['hot','warm','cold']), function($query) use ($status) {
                $query->where('lead_status', $status);
            })
            ->orderBy('created_at','desc')
            ->paginate(12)
            ->withQueryString();

        return view('customers.index', compact('customers','q','status'));
    }

    // Show create handled by modal, but keep this if you want separate page
    public function create()
    {
        return redirect()->route('customers.index');
    }

    // Store (from Add modal)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'lead_status' => 'nullable|in:hot,warm,cold',
        ]);

        $customer = Customer::create($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'customer_added',
            'description' => 'Added customer: ' . $customer->name,
            'customer_id' => $customer->id,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    // Dedicated edit page
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    // Update
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'lead_status' => 'nullable|in:hot,warm,cold',
        ]);

        $customer->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'customer_updated',
            'description' => 'Updated customer: ' . $customer->name,
            'customer_id' => $customer->id,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer updated.');
    }

    // Show single customer
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    // Delete
    public function destroy(Customer $customer)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'type' => 'customer_deleted',
            'description' => 'Deleted customer: ' . $customer->name,
            'customer_id' => $customer->id,
        ]);

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }
}
