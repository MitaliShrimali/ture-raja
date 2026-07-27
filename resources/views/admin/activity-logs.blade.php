@extends('layouts.admin')

@section('admin_title', 'Activity Logs')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <a href="{{ url('admin/settings') }}" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">System Activity Logs</h2>
            </div>
            <p class="text-sm text-gray-500 font-medium max-w-2xl leading-relaxed pl-9">
                Audit trail of all administrative events, password modifications, settings updates, and system operations.
            </p>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-[32px] border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider w-16">Event</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Activity</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider w-36">IP Address</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider w-48">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                        @php
                            $icon = 'activity';
                            $colorClass = 'bg-gray-100 text-gray-500';
                            $actLower = strtolower($log->activity);
                            if (str_contains($actLower, 'login')) {
                                $icon = 'log-in';
                                $colorClass = 'bg-amber-50 text-amber-500';
                            } elseif (str_contains($actLower, 'settings') || str_contains($actLower, 'preference')) {
                                $icon = 'sliders';
                                $colorClass = 'bg-blue-50 text-blue-500';
                            } elseif (str_contains($actLower, 'password') || str_contains($actLower, 'security')) {
                                $icon = 'shield-check';
                                $colorClass = 'bg-red-50 text-red-500';
                            } elseif (str_contains($actLower, 'profile')) {
                                $icon = 'user';
                                $colorClass = 'bg-green-50 text-green-500';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-full {{ $colorClass }} flex items-center justify-center">
                                    <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                {{ $log->user_name }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                {{ $log->activity }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-gray-400">
                                {{ $log->details }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-gray-500 font-mono">
                                {{ $log->ip_address ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-400">
                                <div class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y • h:i A') }}</div>
                                <div>{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm font-bold">
                                No activity logs recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/20">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
