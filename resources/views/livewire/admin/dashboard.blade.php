<div>
    <div class="mb-12 flex items-center justify-between">
        <h1 class="text-[24px] font-semibold text-[#2d2d2d] uppercase tracking-widest">Dashboard</h1>
        <a href="{{ route('user.tickets.create') }}" wire:navigate class="btn-primary !bg-[#2563eb] hover:!bg-[#1d4ed8]">
            Request Ticket
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-[#2d2d2d] rounded-none p-5 text-white">
            <div class="text-[11px] font-medium opacity-50 uppercase tracking-widest mb-1">Total Tickets</div>
            <div class="text-[24px] font-semibold">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-[#f7f7f7] rounded-none p-5">
            <div class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-1">Open</div>
            <div class="text-[24px] font-semibold text-[#2d2d2d]">{{ $stats['open'] }}</div>
        </div>
        <div class="bg-[#f7f7f7] rounded-none p-5">
            <div class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-1">Resolved</div>
            <div class="text-[24px] font-semibold text-[#2d2d2d]">{{ $stats['resolved'] }}</div>
        </div>
        <div class="bg-[#f7f7f7] rounded-none p-5">
            <div class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-1">Disapproved</div>
            <div class="text-[24px] font-semibold text-[#2d2d2d]">{{ $stats['disapproved'] }}</div>
        </div>
    </div>

    {{-- Unified Network Section --}}
    <div class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-[16px] font-semibold text-[#2d2d2d] uppercase tracking-wide border-b border-[#e5e5e5] pb-3 flex-1">System Network Overview</h2>
            <div class="flex items-center gap-6 text-[11px] text-[#999999] ml-6 pb-3 border-b border-[#e5e5e5]">
                <span class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 bg-[#2d2d2d]"></span> Central System
                </span>
                <span class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 bg-[#991b1b]"></span> Active Nodes
                </span>
            </div>
        </div>

        <div class="bg-white border border-[#e5e5e5] p-8 overflow-x-auto">
            <div id="unified-network-flow" class="w-full min-w-[800px]" data-all-depts='@json($departments)'>
                {{-- Single Unified SVG will be injected here --}}
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
        <!-- Types Section -->
        <section>
            <h2 class="text-[16px] font-semibold text-[#2d2d2d] mb-8 border-b border-[#e5e5e5] pb-3 uppercase tracking-wide">Volume by Type</h2>
            <div class="space-y-8">
                @foreach($types as $type)
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[13px] font-medium text-[#2d2d2d]">{{ $type['label'] }}</span>
                            <span class="text-[11px] text-[#999999]">{{ $type['count'] }}</span>
                        </div>
                        <div class="h-[4px] bg-[#f0f0f0] rounded-none overflow-hidden">
                            <div class="h-full bg-[#2d2d2d]" style="width: {{ $type['percent'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Activity Section -->
        <section>
            <h2 class="text-[16px] font-semibold text-[#2d2d2d] mb-8 border-b border-[#e5e5e5] pb-3 uppercase tracking-wide">Recent Activity</h2>
            <div class="space-y-8">
                @forelse($recentActivity as $log)
                    @php
                        $actor = $log->user;
                        $actorLabel = $actor?->name ?: $actor?->username ?? 'Unknown';
                        $actorInitial = $actorLabel !== '' ? mb_strtoupper(mb_substr($actorLabel, 0, 1)) : '?';
                    @endphp
                    <div class="flex gap-4">
                        @if($actor && $actor->profilePhotoUrl())
                            <img
                                src="{{ $actor->profilePhotoUrl() }}"
                                alt=""
                                class="h-9 w-9 shrink-0 rounded-none object-cover border border-[#e5e5e5]"
                                loading="lazy"
                            />
                        @else
                            <div class="w-9 h-9 shrink-0 rounded-none bg-[#f7f7f7] flex items-center justify-center text-[11px] font-semibold text-[#2d2d2d] border border-[#e5e5e5]">
                                {{ $actorInitial }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <p class="text-[13px] text-[#2d2d2d]">
                                    <span class="font-semibold">{{ $actorLabel }}</span>
                                    @if($log->isNewSubmission())
                                        <span class="text-[#555555]"> requested </span>
                                    @elseif($log->isCancellation())
                                        <span class="text-[#555555]"> cancelled </span>
                                    @else
                                        <span class="text-[#555555]"> set </span>
                                    @endif
                                    <span class="font-mono text-[12px] bg-[#f7f7f7] px-1.5 py-0.5 rounded-none border border-[#e5e5e5]">{{ $log->ticket->ticket_no }}</span>
                                    @if(! $log->isNewSubmission() && ! $log->isCancellation())
                                        <span class="text-[#555555]"> to </span>
                                        <span class="font-medium text-[#2d2d2d]">{{ $log->newStatusLabel() }}</span>
                                    @endif
                                </p>
                                <span class="text-[11px] text-[#999999]">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="mt-3 flex items-center gap-3">
                                <x-status-badge :status="$log->new_status" />
                                @if($log->remarks)
                                    <span class="text-[12px] text-[#999999] italic leading-none">— {{ Str::limit($log->remarks, 40) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-[13px] text-[#999999] italic">No recent activity found.</p>
                @endforelse
            </div>
        </section>
    </div>

    @script
    <script>
    (function initUnifiedNetwork() {
        const container = document.getElementById('unified-network-flow');
        if (!container) return;

        const depts = JSON.parse(container.dataset.allDepts);
        const containerW = container.clientWidth || 1000;
        
        // Layout constants
        const ROW_HEIGHT = 220;
        const COL_LEFT_X = containerW * 0.25;
        const COL_RIGHT_X = containerW * 0.75;
        const ROOT_Y = 40;
        const START_Y = 120;
        
        const rowCount = Math.ceil(depts.length / 2);
        const totalHeight = START_Y + (rowCount * ROW_HEIGHT) + 40;
        
        let svg = `<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="${totalHeight}" viewBox="0 0 ${containerW} ${totalHeight}" style="display:block; background:#fafafa;">`;

        // 1. Draw Root Node (HELPDESK CENTRAL)
        const rootX = containerW / 2;
        svg += `
            <rect x="${rootX - 50}" y="${ROOT_Y - 20}" width="100" height="40" fill="#2d2d2d" stroke="#000" stroke-width="1.5"/>
            <text x="${rootX}" y="${ROOT_Y + 5}" text-anchor="middle" fill="#fff" font-size="10" font-weight="bold" letter-spacing="1">HELPDESK</text>
        `;

        // 2. Draw Main Vertical Trunk
        svg += `<line x1="${rootX}" y1="${ROOT_Y + 20}" x2="${rootX}" y2="${START_Y - 40}" stroke="#2d2d2d" stroke-width="1.5" stroke-dasharray="4,2" opacity="0.3"/>`;

        // 3. Draw Departments in 2 Columns
        depts.forEach((dept, index) => {
            const isLeft = index % 2 === 0;
            const rowIndex = Math.floor(index / 2);
            const deptX = isLeft ? COL_LEFT_X : COL_RIGHT_X;
            const deptY = START_Y + (rowIndex * ROW_HEIGHT);

            // Connection path from root/center trunk
            const trunkY = deptY - 40;
            if (rowIndex === 0) {
                // First row connects directly from root area
                svg += `<path d="M ${rootX} ${ROOT_Y + 20} C ${rootX} ${deptY - 60}, ${deptX} ${deptY - 60}, ${deptX} ${deptY - 20}" fill="none" stroke="#2d2d2d" stroke-width="1.2" opacity="0.2"/>`;
            } else {
                // Subsequent rows connect from the vertical space above
                svg += `<line x1="${deptX}" y1="${deptY - ROW_HEIGHT + 20}" x2="${deptX}" y2="${deptY - 20}" stroke="#2d2d2d" stroke-width="1.2" stroke-dasharray="4,4" opacity="0.15"/>`;
            }

            // Department Node
            svg += `
                <rect x="${deptX - 70}" y="${deptY - 20}" width="140" height="36" fill="#f7f7f7" stroke="#e5e5e5" stroke-width="1"/>
                <text x="${deptX}" y="${deptY - 4}" text-anchor="middle" fill="#2d2d2d" font-size="9" font-weight="bold">${escXml(dept.name.toUpperCase())}</text>
                <text x="${deptX}" y="${deptY + 8}" text-anchor="middle" fill="#999" font-size="7">${dept.total_users_count} TOTAL STAFF</text>
            `;

            // Active Users Branching
            const activeUsers = (dept.users || []).sort((a, b) => b.active_tickets_count - a.active_tickets_count);
            const limit = 3;
            const displayUsers = activeUsers.slice(0, limit);
            const hasMore = activeUsers.length > limit;

            displayUsers.forEach((user, ui) => {
                const userOffX = isLeft ? -110 : 80;
                const userX = deptX + userOffX;
                const userY = deptY + (ui * 35) + 30;

                // Connector to user
                svg += `<path d="M ${deptX} ${deptY + 16} L ${deptX} ${userY + 12} L ${userX + (isLeft ? 90 : 0)} ${userY + 12}" fill="none" stroke="#991b1b" stroke-width="1" opacity="0.3"/>`;

                // User Node
                const displayName = user.name.split(' ')[0];
                svg += `
                    <a href="/admin/tickets?search=${encodeURIComponent(user.username)}" style="cursor:pointer;">
                        <rect x="${userX}" y="${userY}" width="90" height="24" fill="#991b1b" stroke="#7f1d1d" stroke-width="1"/>
                        <text x="${userX + 45}" y="${userY + 10}" text-anchor="middle" fill="#fff" font-size="8" font-weight="bold">${escXml(displayName)}</text>
                        <text x="${userX + 45}" y="${userY + 19}" text-anchor="middle" fill="#ffbbbb" font-size="7">● ${user.active_tickets_count} tickets</text>
                    </a>
                `;
            });

            if (hasMore) {
                const moreCount = activeUsers.length - limit;
                const moreY = deptY + (displayUsers.length * 35) + 30;
                const userOffX = isLeft ? -110 : 80;
                const userX = deptX + userOffX;

                svg += `<path d="M ${deptX} ${deptY + 16} L ${deptX} ${moreY + 12} L ${userX + (isLeft ? 90 : 0)} ${moreY + 12}" fill="none" stroke="#999" stroke-width="1" stroke-dasharray="2,2" opacity="0.3"/>`;
                svg += `
                    <rect x="${userX}" y="${moreY}" width="90" height="24" fill="#fff" stroke="#e5e5e5" stroke-width="1" stroke-dasharray="2,2"/>
                    <text x="${userX + 45}" y="${moreY + 14}" text-anchor="middle" fill="#999" font-size="8" font-weight="bold">+${moreCount} OTHERS</text>
                `;
            }
        });

        svg += '</svg>';
        container.innerHTML = svg;

        function escXml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
    })();
    </script>
    @endscript
</div>
