<x-app-layout>
  {{-- ✅ Alpine scope wraps everything that needs state --}}
  <div x-data="{ addModal: false }">
    {{-- ✅ Header --}}
    <x-slot name="header">
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"> Customers </h2>
      </div>
    </x-slot>
    {{-- ✅ Add Customer Button --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4 flex justify-end">
      <button @click="addModal = true" class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 text-white rounded shadow-sm text-sm hover:bg-indigo-700 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg> Add Customer </button>
    </div>
    {{-- ✅ Page Content --}}
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Alerts --}} @if(session('success')) <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">
          {{ session('success') }}
        </div> @endif @if(session('error')) <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded">
          {{ session('error') }}
        </div> @endif @if($errors->any()) <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded">
          <ul class="list-disc pl-5"> @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach </ul>
        </div> @endif {{-- Customer Table --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
          <div class="px-6 py-4 border-b">
            <form method="GET" action="{{ route('customers.index') }}" class="flex items-center gap-2">
              <input name="q" type="text" value="{{ request('q') }}" placeholder="Search name / phone / email" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
              <select name="status" class="border rounded px-2 py-2 text-sm">
                <option value="">All Status</option>
                <option value="hot" {{ request('status')=='hot' ? 'selected' : '' }}>Hot</option>
                <option value="warm" {{ request('status')=='warm' ? 'selected' : '' }}>Warm</option>
                <option value="cold" {{ request('status')=='cold' ? 'selected' : '' }}>Cold</option>
              </select>
              <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm">Filter</button>
            </form>
          </div>
          <div class="p-4">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y">
                <thead>
                  <tr class="text-left text-sm text-gray-600">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Contact</th>
                    <th class="px-4 py-2">Company</th>
                    <th class="px-4 py-2">Lead</th>
                    <th class="px-4 py-2">Notes</th>
                    <th class="px-4 py-2">Added</th>
                    <th class="px-4 py-2">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y"> @forelse($customers as $customer) <tr class="text-sm">
                    <td class="px-4 py-3">
                      <div class="font-medium text-gray-800">{{ $customer->name }}</div>
                      <div class="text-xs text-gray-500">{{ $customer->email }}</div>
                    </td>
                    <td class="px-4 py-3">{{ $customer->phone }}</td>
                    <td class="px-4 py-3">{{ $customer->company }}</td>
                    <td class="px-4 py-3"> @include('components.lead-badge', ['status' => $customer->lead_status]) </td>
                    <td class="px-4 py-3">
                      <div class="text-xs text-gray-600 truncate max-w-xs">{{ $customer->notes }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                      {{ $customer->created_at->format('d M, Y') }}
                    </td>
                    <td class="px-4 py-3 text-sm space-x-2">
                      {{-- ✅ View Customer Modal --}}
                      <div x-data="{ viewModal: false, customer: {} }" x-cloak class="inline">
                        <button @click="customer = {{ $customer->toJson() }}; viewModal = true" class="text-indigo-600 hover:underline"> View </button>
                        <div x-show="viewModal" x-transition class="fixed inset-0 z-40 flex items-center justify-center p-4 bg-black bg-opacity-50">
                          <div @click.away="viewModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
                            <div class="px-6 py-4 border-b flex items-center justify-between">
                              <h3 class="text-lg font-medium">Customer Details</h3>
                              <button @click="viewModal = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                            </div>
                            <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                              <div>
                                <strong>Name:</strong>
                                <span x-text="customer.name"></span>
                              </div>
                              <div>
                                <strong>Phone:</strong>
                                <span x-text="customer.phone"></span>
                              </div>
                              <div>
                                <strong>Email:</strong>
                                <span x-text="customer.email"></span>
                              </div>
                              <div>
                                <strong>Company:</strong>
                                <span x-text="customer.company"></span>
                              </div>
                              <div class="md:col-span-2">
                                <strong>Notes:</strong>
                                <span x-text="customer.notes"></span>
                              </div>
                              <div>
                                <strong>Lead Status:</strong>
                                <span x-text="customer.lead_status"></span>
                              </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                              <button @click="viewModal = false" class="px-4 py-2 border rounded text-sm">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      {{-- ✅ Edit Customer Modal --}}
                      <div x-data="{ editModal: false, customer: {} }" x-cloak class="inline">
                        <button @click="customer = {{ $customer->toJson() }}; editModal = true" class="text-yellow-600 hover:underline"> Edit </button>
                        <div x-show="editModal" x-transition class="fixed inset-0 z-40 flex items-center justify-center p-4 bg-black bg-opacity-50">
                          <div @click.away="editModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
                            <div class="px-6 py-4 border-b flex items-center justify-between">
                              <h3 class="text-lg font-medium">Edit Customer</h3>
                              <button @click="editModal = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                            </div>
                            <form :action="`/customers/${customer.id}`" method="POST" class="px-6 py-6"> @csrf @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                  <label class="block text-sm text-gray-700">Name</label>
                                  <input name="name" x-model="customer.name" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                  <label class="block text-sm text-gray-700">Phone</label>
                                  <input name="phone" x-model="customer.phone" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                  <label class="block text-sm text-gray-700">Email</label>
                                  <input name="email" x-model="customer.email" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                  <label class="block text-sm text-gray-700">Company</label>
                                  <input name="company" x-model="customer.company" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500" />
                                </div>
                                <div class="md:col-span-2">
                                  <label class="block text-sm text-gray-700">Notes</label>
                                  <textarea name="notes" x-model="customer.notes" rows="3" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500"></textarea>
                                </div>
                                <div>
                                  <label class="block text-sm text-gray-700">Lead Status</label>
                                  <select name="lead_status" x-model="customer.lead_status" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500">
                                    <option value="hot">Hot</option>
                                    <option value="warm">Warm</option>
                                    <option value="cold">Cold</option>
                                  </select>
                                </div>
                              </div>
                              <div class="mt-6 flex items-center justify-end space-x-2">
                                <button type="button" @click="editModal = false" class="px-4 py-2 border rounded text-sm">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded text-sm">Update Customer</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                   <div x-data="{ calling: false, message: '', error: false, smsModal: false, smsCustomer: null }" class="inline">

    {{-- SMS Button --}}
    <button
        @click="smsCustomer = {{ $customer->id }}; smsModal = true"
        class="text-green-600 hover:underline"
    >
        SMS
    </button>

    {{-- SMS Modal --}}
    <div
        x-show="smsModal"
        x-transition
        x-cloak
        class="fixed inset-0 z-40 flex items-center justify-center p-4 bg-black bg-opacity-50"
    >
        <div @click.away="smsModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h3 class="text-lg font-medium">Send SMS</h3>
                <button @click="smsModal = false" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <form :action="`/customers/${smsCustomer}/send-sms`" method="POST" class="px-6 py-6">
                @csrf
                <div>
                    <label class="block text-sm text-gray-700">Message <span class="text-red-500">*</span></label>
                    <textarea name="message" rows="4" required class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500"></textarea>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" @click="smsModal = false" class="px-4 py-2 border rounded text-sm">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded text-sm">Send SMS</button>
                </div>
            </form>
        </div>
    </div>

</div>

                      <div x-data="{ calling: false, message: '', error: false }" class="inline">
                        <button @click.prevent="
            calling = true;
            message = '';
            error = false;
            
            fetch('{{ route('customers.call', $customer) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
            })
            .then(res => res.json())
            .then(res => {
                if(res.success){
                    message = res.success;
                    error = false;
                } else if(res.error){
                    message = res.error;
                    error = true;
                }
                calling = false;
            })
            .catch(err => {
                message = 'Call failed, please try again';
                error = true;
                calling = false;
            });
        " x-text="calling ? 'Calling...' : 'Call'" :class="calling ? 'text-gray-400 cursor-not-allowed' : 'text-red-600 hover:underline'" class="font-medium"></button>
                        <div x-show="message" x-text="message" :class="error ? 'text-red-500 text-xs mt-1' : 'text-green-500 text-xs mt-1'"></div>
                      </div>
                      <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Delete this customer?');"> @csrf @method('DELETE') <button type="submit" class="text-gray-600 hover:underline">Delete</button>
                      </form>
                      </td>
                  </tr> @empty <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500"> No customers found. </td>
                  </tr> @endforelse </tbody>
              </table>
            </div>
            <div class="mt-4">
              {{ $customers->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- ✅ Add Customer Modal --}}
    <div x-show="addModal" x-transition x-cloak class="fixed inset-0 z-40 flex items-center justify-center p-4 bg-black bg-opacity-50">
      <div @click.away="addModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
        <div class="px-6 py-4 border-b flex items-center justify-between">
          <h3 class="text-lg font-medium">Add Customer</h3>
          <button @click="addModal = false" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <form action="{{ route('customers.store') }}" method="POST" class="px-6 py-6"> @csrf <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-gray-700">Name <span class="text-red-500">*</span>
              </label>
              <input name="name" required value="{{ old('name') }}" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500" />
            </div>
            <div>
              <label class="block text-sm text-gray-700">Phone</label>
              <input name="phone" value="{{ old('phone') }}" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500" />
            </div>
            <div>
              <label class="block text-sm text-gray-700">Email</label>
              <input name="email" value="{{ old('email') }}" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500" />
            </div>
            <div>
              <label class="block text-sm text-gray-700">Company</label>
              <input name="company" value="{{ old('company') }}" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500" />
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm text-gray-700">Notes</label>
              <textarea name="notes" rows="3" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500">{{ old('notes') }}</textarea>
            </div>
            <div>
              <label class="block text-sm text-gray-700">Lead Status</label>
              <select name="lead_status" class="mt-1 block w-full border rounded px-3 py-2 focus:ring-indigo-500">
                <option value="hot">Hot</option>
                <option value="warm" selected>Warm</option>
                <option value="cold">Cold</option>
              </select>
            </div>
          </div>
          <div class="mt-6 flex items-center justify-end space-x-2">
            <button type="button" @click="addModal = false" class="px-4 py-2 border rounded text-sm">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded text-sm">Save Customer</button>
          </div>
        </form>
      </div>
    </div>
  </div> {{-- Alpine scope ends here --}}
  {{-- ✅ Alpine.js CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-app-layout>