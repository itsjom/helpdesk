# GEMINI.md — Helpdesk

## Project Overview
This is an Helpdesk built with **Laravel 11 + Livewire v3 + Tailwind CSS + MySQL**.
It allows users to submit IT service requests (tickets) and admins to manage, approve, assign, and resolve them.
There is no separate JavaScript framework — all reactivity is handled by Livewire and Alpine.js.

---

## Tech Stack
| Layer           | Technology                        |
|-----------------|-----------------------------------|
| Backend         | Laravel 11                        |
| Frontend        | Blade Templates                   |
| Reactivity      | Livewire v3                       |
| UI Interactions | Alpine.js (bundled with Livewire) |
| Styling         | Tailwind CSS                      |
| Database        | MySQL                             |
| Auth            | Laravel Breeze (Livewire stack)   |

---

## Roles
- **Admin** — Full access: manage tickets, approve/disapprove, assign personnel, view dashboard
- **User** — Limited access: submit tickets, view own tickets, track status, cancel pending tickets

---

## Directory Structure
```
/app
  /Http/Controllers
    AuthController.php            (handled by Breeze)
  /Livewire
    /Admin
      Dashboard.php               (stats + charts)
      TicketTable.php             (data table, filters, approve/disapprove)
    /User
      TicketForm.php              (submit new ticket)
      MyTickets.php               (view, track, cancel own tickets)
  /Models
    User.php
    Ticket.php
    TicketLog.php
/resources
  /views
    /layouts
      app.blade.php               (main layout with navbar)
    /livewire
      /admin
        dashboard.blade.php
        ticket-table.blade.php
      /user
        ticket-form.blade.php
        my-tickets.blade.php
    /components
      status-badge.blade.php
      priority-badge.blade.php
/routes
  web.php
/database
  /migrations
  schema.sql
```

---

## Database Tables
- `users` — id, name, username, password, role (admin/user), department, timestamps
- `tickets` — id, ticket_no, user_id, service_type, priority, description, status, assigned_to, admin_remarks, due_date, timestamps
- `ticket_logs` — id, ticket_id, changed_by, old_status, new_status, remarks, timestamps

---

## Ticket Rules & Logic

### Service Types
`network` | `printer` | `ups` | `desktop_laptop` | `other`

### Priority & SLA (Due Date)
*Determined by Admin upon review*

| Priority | SLA     |
|----------|---------|
| High     | 4 hours |
| Medium   | 1 day   |
| Low      | 3 days  |

### Status Flow
```
Pending → OnQueue → In Progress → Resolved
       ↘ Disapproved
       ↘ Cancelled (by user, only when Pending)
```
- Only **Admin** can move status forward or disapprove
- **User** can only cancel a ticket while status is `Pending`
- Every status change must be recorded in `ticket_logs`

---

## Key Conventions

### Laravel
- Use **Eloquent ORM** for all database queries — no raw SQL in controllers or Livewire components
- All routes in `routes/web.php` — name them clearly (e.g. `admin.tickets`, `user.tickets`)
- Use **Form Requests** for validation where applicable (e.g. `StoreTicketRequest.php`)
- Middleware: `auth`, `role:admin`, `role:user`
- Ticket numbers are auto-generated on creation: format `TKT-YYYYMMDD-XXXX` (e.g. `TKT-20250326-0001`)
- SLA/due date is auto-calculated on ticket creation based on priority

### Livewire v3
- Each Livewire component has a PHP class in `/app/Livewire/` and a Blade view in `/resources/views/livewire/`
- Use `#[On]` and `dispatch()` for inter-component communication
- Use `wire:model` for reactive form fields
- Use `wire:confirm` for destructive actions (cancel, disapprove)
- Use `wire:loading` to show loading states on buttons and tables
- Wrap modals with Alpine.js `x-show` / `x-data` for open/close state — trigger from Livewire via `dispatch`
- Paginate ticket tables using Livewire's built-in `WithPagination` trait

### Blade & Tailwind
- Use Blade components (`/resources/views/components/`) for reusable UI like `status-badge`, `priority-badge`
- Use Tailwind utility classes only — no custom CSS files unless absolutely necessary
- Flash messages (success/error) via Laravel session with Alpine.js auto-dismiss

### General
- Never hardcode role strings — use a `RoleEnum` or constants
- Always log status changes to `ticket_logs` — never skip this step
- Admin remarks are **required** when disapproving a ticket
- Do not expose sensitive user data in Blade views unnecessarily

---

## Pages Summary

### Admin
| Page      | Route              | Livewire Component  | Description                                   |
|-----------|--------------------|---------------------|-----------------------------------------------|
| Dashboard | `/admin/dashboard` | `Admin\Dashboard`   | Stats: total, open, resolved; charts by type  |
| Tickets   | `/admin/tickets`   | `Admin\TicketTable` | Table, approve/disapprove, assign, remarks    |
| Disposal  | `/admin/disposal`  | —                   | Placeholder — not yet implemented             |
| Report    | `/admin/report`    | —                   | Placeholder — not yet implemented             |

### User
| Page       | Route             | Livewire Component | Description                                    |
|------------|-------------------|--------------------|------------------------------------------------|
| Request    | `/tickets/create` | `User\TicketForm`  | Submit new ticket, preview SLA due date        |
| My Tickets | `/tickets`        | `User\MyTickets`   | View own tickets, track status, cancel pending |

---

## What NOT to Do
- Do not use `routes/api.php` — this is a full-stack Livewire app, not a separate API
- Do not write JavaScript for things Livewire or Alpine.js can handle natively
- Do not skip audit logging on every status change
- Do not allow users to access admin routes — always enforce middleware
- Do not use raw `$_POST` / `$_GET` — always use Laravel's `$request` or Livewire properties
- Do not forget `wire:key` when rendering lists with `@foreach` in Livewire components