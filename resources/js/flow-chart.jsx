import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import ReactFlow, { Background, Controls } from 'reactflow';
import 'reactflow/dist/style.css';

function buildGraph(departments) {
    const nodes = [];
    const edges = [];
    const DEPT_X = 80;
    const USER_X = 360;
    const NODE_H = 50;
    const GAP = 24;
    let currentY = 40;

    departments.forEach((dept) => {
        const deptId = `dept-${dept.id}`;
        const users = dept.users || [];
        const blockHeight = Math.max(NODE_H, users.length * (NODE_H + GAP));
        const deptY = currentY + (blockHeight / 2) - (NODE_H / 2);

        // Department node
        nodes.push({
            id: deptId,
            data: { label: dept.name },
            position: { x: DEPT_X, y: deptY },
            style: {
                background: '#2d2d2d',
                color: '#ffffff',
                border: '2px solid #2d2d2d',
                borderRadius: 0,
                padding: '10px 20px',
                fontSize: '11px',
                fontWeight: 'bold',
                letterSpacing: '0.12em',
                minWidth: '140px',
                textAlign: 'center',
                textTransform: 'uppercase',
            }
        });

        let userY = currentY;
        users.forEach((user) => {
            const userId = `user-${user.id}`;
            const hasTicket = user.active_tickets_count > 0;

            // User node
            nodes.push({
                id: userId,
                data: {
                    label: (
                        React.createElement('div', null,
                            React.createElement('div', { style: { fontWeight: 'bold', fontSize: '12px' } }, user.name),
                            hasTicket && React.createElement('div', {
                                style: { fontSize: '10px', marginTop: '3px', opacity: 0.9 }
                            }, `● ${user.active_tickets_count} active ticket${user.active_tickets_count > 1 ? 's' : ''}`)
                        )
                    )
                },
                position: { x: USER_X, y: userY },
                style: {
                    background: hasTicket ? '#991b1b' : '#ffffff',
                    color: hasTicket ? '#ffffff' : '#2d2d2d',
                    border: hasTicket ? '2px solid #7f1d1d' : '1px solid #d4d4d4',
                    borderRadius: 0,
                    padding: '10px 16px',
                    fontSize: '12px',
                    minWidth: '180px',
                    textAlign: 'center',
                    boxShadow: hasTicket ? '0 0 12px rgba(153,27,27,0.3)' : 'none',
                }
            });

            edges.push({
                id: `e-${deptId}-${userId}`,
                source: deptId,
                target: userId,
                style: {
                    stroke: hasTicket ? '#991b1b' : '#bbbbbb',
                    strokeWidth: hasTicket ? 2 : 1,
                },
                type: 'smoothstep',
            });

            userY += NODE_H + GAP;
        });

        currentY += blockHeight + 60;
    });

    return { nodes, edges };
}

function FlowChart({ departments }) {
    const { nodes, edges } = buildGraph(departments);

    return React.createElement(
        ReactFlow,
        {
            nodes,
            edges,
            fitView: true,
            fitViewOptions: { padding: 0.15 },
            nodesDraggable: false,
            nodesConnectable: false,
            elementsSelectable: false,
            style: { background: '#fafafa', width: '100%', height: '100%' },
        },
        React.createElement(Background, { color: '#e5e5e5', gap: 16 }),
        React.createElement(Controls, { showInteractive: false })
    );
}

// Expose globally so the Blade @script block can call it
window.renderUserFlow = function (containerId, departments) {
    const container = document.getElementById(containerId);
    if (!container) {
        console.error('[UserFlow] Container not found:', containerId);
        return;
    }
    const root = createRoot(container);
    root.render(React.createElement(FlowChart, { departments }));
};

console.log('[UserFlow] window.renderUserFlow registered.');
