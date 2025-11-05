<x-app-layout>
  <x-slot name="header"><h2>Activity Logs</h2></x-slot>
  <div class="p-6">
    <div class="bg-white p-4 rounded shadow">
      <table class="w-full">
        <thead><tr><th>User</th><th>Type</th><th>Description</th><th>Customer</th><th>When</th></tr></thead>
        <tbody>
          @foreach($logs as $log)
          <tr class="border-t">
            <td>{{ $log->user?->name ?? 'System' }}</td>
            <td>{{ $log->type }}</td>
            <td>{{ $log->description }}</td>
            <td>{{ $log->customer?->name }}</td>
            <td>{{ $log->created_at->diffForHumans() }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="mt-4">{{ $logs->links() }}</div>
    </div>
  </div>
</x-app-layout>
