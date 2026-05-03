<div>
    <div class="mb-12">
        <h1 class="text-[24px] font-semibold text-[#2d2d2d]">Dashboard</h1>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-[#2d2d2d] rounded-none p-5 text-white">
            <div class="text-[11px] font-medium opacity-50 uppercase tracking-widest mb-1">Total Tickets</div>
            <div class="text-[24px] font-semibold">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-[#f7f7f7] rounded-[10px] p-5">
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

    {{-- Network Chart --}}
    <div class="mb-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-[16px] font-semibold text-[#2d2d2d] uppercase tracking-wide border-b border-[#e5e5e5] pb-3 flex-1">System Network</h2>
            <div class="flex items-center gap-6 text-[11px] text-[#999999] ml-6 pb-3 border-b border-[#e5e5e5]">
                <span class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 bg-[#2d2d2d]"></span> Department
                </span>
                <span class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 bg-white border border-[#d4d4d4]"></span> No Ticket
                </span>
                <span class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 bg-[#991b1b]"></span> Active Ticket
                </span>
            </div>
        </div>
        <div class="bg-white border border-[#e5e5e5] overflow-x-auto rounded-none">
            <div id="flow-svg-wrapper" class="min-h-[160px] flex items-center justify-center p-4">
                <p class="text-[12px] text-[#999999]">Building network map...</p>
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
                                class="h-9 w-9 shrink-0 rounded-full object-cover border border-[#e5e5e5]"
                                loading="lazy"
                            />
                        @else
                            <div class="w-9 h-9 shrink-0 rounded-none bg-[#f7f7f7] flex items-center justify-center text-[11px] font-bold text-[#2d2d2d] border border-[#e5e5e5]">
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
    (function drawFlowChart() {
        const data = @json($departments);

        // Layout constants
        const NODE_W      = 100;
        const NODE_H      = 28;
        const DEPT_Y      = 20;
        const USER_Y      = 100;
        const COL_GAP     = 12;
        const DEPT_GAP    = 40;
        const PADDING     = 20;

        // Build layout
        let deptBlocks = [];

        data.forEach((dept, di) => {
            const users = dept.users || [];
            const deptRow = { type: 'dept', label: dept.name, di };
            const userRows = users.map(u => ({
                type: 'user',
                label: u.name,
                username: u.username,
                hasTicket: u.active_tickets_count > 0,
                ticketCount: u.active_tickets_count,
                di
            }));
            deptBlocks.push({ deptRow, userRows });
        });

        // Calculate X positions and total SVG width
        let currentX = PADDING;
        const blockMeta = deptBlocks.map(block => {
            const userCount = block.userRows.length;
            const usersTotalW = userCount > 0 ? (userCount * NODE_W + (userCount - 1) * COL_GAP) : 0;
            const blockW = Math.max(NODE_W, usersTotalW);
            const startX = currentX;
            currentX += blockW + DEPT_GAP;
            return { startX, blockW, userCount, usersTotalW };
        });

        const SVG_W = Math.max(currentX - DEPT_GAP + PADDING, 200);
        const SVG_H = USER_Y + NODE_H + PADDING * 2;

        // Build SVG
        let svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${SVG_W}" height="${SVG_H}" viewBox="0 0 ${SVG_W} ${SVG_H}" style="font-family:Inter,system-ui,sans-serif;display:block;margin:0 auto;">`;

        deptBlocks.forEach((block, bi) => {
            const meta = blockMeta[bi];
            const deptX = meta.startX + meta.blockW / 2 - NODE_W / 2;

            // Dept node
            svg += `
                <rect x="${deptX}" y="${DEPT_Y}" width="${NODE_W}" height="${NODE_H}"
                    fill="#2d2d2d" stroke="#2d2d2d" stroke-width="1"/>
                <text x="${deptX + NODE_W/2}" y="${DEPT_Y + 17}" text-anchor="middle"
                    fill="#ffffff" font-size="9" font-weight="bold" letter-spacing="0.5">${block.deptRow.label.toUpperCase()}</text>`;

            const deptMidX = deptX + NODE_W / 2;
            const deptMidY = DEPT_Y + NODE_H;

            // User nodes
            if (meta.userCount > 0) {
                let userX = meta.startX + meta.blockW / 2 - meta.usersTotalW / 2;

                block.userRows.forEach((user, ui) => {
                    const fill = user.hasTicket ? '#991b1b' : '#ffffff';
                    const stroke = user.hasTicket ? '#7f1d1d' : '#d4d4d4';
                    const strokeW = user.hasTicket ? 2 : 1;
                    const textColor = user.hasTicket ? '#ffffff' : '#2d2d2d';

                    const userMidX = userX + NODE_W / 2;
                    const userTopY = USER_Y;

                    // Connector line
                    const midY = (deptMidY + userTopY) / 2;
                    svg += `<path d="M ${deptMidX} ${deptMidY} C ${deptMidX} ${midY}, ${userMidX} ${midY}, ${userMidX} ${userTopY}"
                        fill="none" stroke="${user.hasTicket ? '#991b1b' : '#cccccc'}" stroke-width="${strokeW}"/>`;

                    // User box
                    let userBoxSvg = `
                        <rect x="${userX}" y="${userTopY}" width="${NODE_W}" height="${NODE_H}"
                            fill="${fill}" stroke="${stroke}" stroke-width="${strokeW}"/>`;

                    if (user.hasTicket) {
                        userBoxSvg += `
                            <text x="${userMidX}" y="${userTopY + 12}" text-anchor="middle"
                                fill="${textColor}" font-size="9" font-weight="bold">${escXml(user.label)}</text>
                            <text x="${userMidX}" y="${userTopY + 22}" text-anchor="middle"
                                fill="#ffbbbb" font-size="7">● ${user.ticketCount} ticket${user.ticketCount > 1 ? 's' : ''}</text>`;
                        
                        svg += `<a href="/admin/tickets?search=${encodeURIComponent(user.username)}" style="cursor:pointer;" class="hover:opacity-90 transition-opacity" title="View tickets for ${escXml(user.label)}">
                            ${userBoxSvg}
                        </a>`;
                    } else {
                        userBoxSvg += `
                            <text x="${userMidX}" y="${userTopY + 17}" text-anchor="middle"
                                fill="${textColor}" font-size="9">${escXml(user.label)}</text>`;
                        svg += userBoxSvg;
                    }

                    userX += NODE_W + COL_GAP;
                });
            }
        });

        svg += '</svg>';

        function escXml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        const wrapper = document.getElementById('flow-svg-wrapper');
        if (wrapper) {
            if (deptBlocks.length === 0) {
                wrapper.innerHTML = '<p style="color:#999;font-size:12px;">No departments or users found.</p>';
            } else {
                wrapper.innerHTML = svg;
            }
        }
    })();
    </script>
    @endscript
</div>
