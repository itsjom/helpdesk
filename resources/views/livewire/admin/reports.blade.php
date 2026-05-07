<div>
    <div class="flex justify-between items-center mb-12">
        <h1 class="text-[24px] font-semibold text-[#2d2d2d] uppercase tracking-widest">Reports</h1>
        <div class="flex items-center gap-4">
            <select wire:model.live="period" class="input-field text-[13px] py-1.5 px-3">
                <option value="day">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
            </select>
            <button wire:click="downloadPdf" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Export PDF
            </button>
        </div>
    </div>

    <div class="bg-white border border-[#e5e5e5] p-8 rounded-none mb-12">
        <div class="mb-8">
            <h3 class="text-[14px] font-semibold text-[#2d2d2d] uppercase tracking-widest border-b border-[#e5e5e5] pb-4 mb-6">
                Overview ({{ ucfirst($period) }})
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-[#f7f7f7] p-6 border border-[#e5e5e5] rounded-none">
                    <div class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-2">Total</div>
                    <div class="text-[32px] font-semibold text-[#2d2d2d]">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-[#2d2d2d] p-6 border border-[#2d2d2d] rounded-none">
                    <div class="text-[11px] font-medium text-white/50 uppercase tracking-widest mb-2">Pending</div>
                    <div class="text-[32px] font-semibold text-white">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-[#f7f7f7] p-6 border border-[#e5e5e5] rounded-none">
                    <div class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-2">OnQueue</div>
                    <div class="text-[32px] font-semibold text-[#2d2d2d]">{{ $stats['approved'] }}</div>
                </div>
                <div class="bg-[#f7f7f7] p-6 border border-[#e5e5e5] rounded-none">
                    <div class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-2">In Progress</div>
                    <div class="text-[32px] font-semibold text-[#2d2d2d]">{{ $stats['in_progress'] }}</div>
                </div>
                <div class="bg-[#f7f7f7] p-6 border border-[#e5e5e5] rounded-none">
                    <div class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-2">Resolved</div>
                    <div class="text-[32px] font-semibold text-[#2d2d2d]">{{ $stats['resolved'] }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-[#e5e5e5] pt-8">
            <div>
                <h4 class="text-[12px] font-semibold text-[#2d2d2d] uppercase tracking-widest mb-4">By Service Type</h4>
                <div class="space-y-3">
                    @forelse($stats['by_service'] as $row)
                        <div class="flex justify-between items-center bg-[#f7f7f7] p-3 border border-[#e5e5e5]">
                            <span class="text-[13px] text-[#555555]">{{ $row['label'] }}</span>
                            <span class="text-[13px] font-semibold text-[#2d2d2d]">{{ $row['count'] }}</span>
                        </div>
                    @empty
                        <div class="text-[13px] text-[#999999] italic">No data available for this period.</div>
                    @endforelse
                </div>
            </div>
            
            <div>
                <h4 class="text-[12px] font-semibold text-[#2d2d2d] uppercase tracking-widest mb-4">Other Statuses</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center bg-[#f7f7f7] p-3 border border-[#e5e5e5]">
                        <span class="text-[13px] text-[#555555]">Disapproved</span>
                        <span class="text-[13px] font-semibold text-[#2d2d2d]">{{ $stats['disapproved'] }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-[#f7f7f7] p-3 border border-[#e5e5e5]">
                        <span class="text-[13px] text-[#555555]">Cancelled</span>
                        <span class="text-[13px] font-semibold text-[#2d2d2d]">{{ $stats['cancelled'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
