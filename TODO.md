# TODO.md — IT Ticketing System

## Legend
- [ ] Not started
- [~] In progress
- [x] Done

---

## 🛠️ Project Setup
- [ ] Create Laravel 11 project (`laravel new ticketing-system`)
- [ ] Install Laravel Breeze with Livewire stack (`php artisan breeze:install livewire`)
- [ ] Install and configure Tailwind CSS (comes with Breeze)
- [ ] Set up `.env` with MySQL credentials
- [ ] Run initial migrations (`php artisan migrate`)
- [ ] Seed database with test admin and user accounts (`php artisan db:seed`)

---

## 🗄️ Database
- [ ] Create `users` table migration — add `role` (admin/user) and `department` columns
- [ ] Create `tickets` table migration (ticket_no, user_id, service_type, priority, description, status, assigned_to, admin_remarks, due_date)
- [ ] Create `ticket_logs` table migration (ticket_id, changed_by, old_status, new_status, remarks)
- [ ] Write `DatabaseSeeder` — seed 1 admin + 2 test users

---

## 🔐 Authentication
- [ ] Customize Breeze login to use `username` instead of `email`
- [ ] Update `LoginRequest.php` to authenticate by username
- [ ] Role-based middleware — create `CheckRole` middleware (`role:admin`, `role:user`)
- [ ] Register middleware in `bootstrap/app.php`
- [ ] Redirect after login based on role (admin → `/admin/dashboard`, user → `/tickets`)

---

## 📦 Models
- [ ] `User` model — add `role`, `department` fields; relationships to tickets
- [ ] `Ticket` model — relationships: `belongsTo(User)`, `belongsTo(User, 'assigned_to')`, `hasMany(TicketLog)`
- [ ] `TicketLog` model — relationships: `belongsTo(Ticket)`, `belongsTo(User, 'changed_by')`
- [ ] Add `boot()` method on `Ticket` model — auto-generate `ticket_no` and `due_date` on creating

---

## 🎫 Ticket Logic (Backend)
- [ ] Auto-generate ticket number on creation: `TKT-YYYYMMDD-XXXX`
- [ ] Auto-calculate `due_date` on creation based on priority:
  - High → +4 hours
  - Medium → +1 day
  - Low → +3 days
- [ ] Create `TicketService.php` for shared ticket logic (status transitions, logging)
- [ ] Log every status change to `ticket_logs` (never skip)

---

## 🖥️ Admin — Livewire Components

### Dashboard (`Admin\Dashboard`)
- [ ] Total tickets count
- [ ] Open tickets count (pending + approved + in_progress)
- [ ] Resolved tickets count
- [ ] Disapproved tickets count
- [ ] Tickets by service type (chart using Chart.js or CSS bars)
- [ ] Tickets by priority (chart)
- [ ] Recent 5 tickets table

### Ticket Table (`Admin\TicketTable`)
- [ ] Paginated data table (columns: Ticket No., Requested By, Date, Service Type, Priority, Assigned To, Status)
- [ ] Search by ticket no. or requester name (`wire:model.live`)
- [ ] Filter by status dropdown (`wire:model.live`)
- [ ] Filter by priority dropdown (`wire:model.live`)
- [ ] Filter by service type dropdown (`wire:model.live`)
- [ ] View ticket details modal (description, audit log)
- [ ] Approve action — updates status to `approved`, logs change
- [ ] Disapprove action — requires admin remarks, updates status to `disapproved`, logs change
- [ ] Assign personnel dropdown — updates `assigned_to`
- [ ] Mark as In Progress action
- [ ] Mark as Resolved action
- [ ] `wire:loading` spinner on table during filter/search

### Placeholder Pages
- [ ] Disposal page — simple "Coming Soon" Blade view
- [ ] Recommendation page — simple "Coming Soon" Blade view
- [ ] Report page — simple "Coming Soon" Blade view

---

## 👤 User — Livewire Components

### Ticket Form (`User\TicketForm`)
- [ ] Fields: Service Type, Priority, Description
- [ ] Live SLA preview — show calculated due date when priority is selected (`wire:model.live`)
- [ ] Validation (all fields required, description min 10 chars)
- [ ] On submit: create ticket, generate ticket_no, calculate due_date, log as `pending`
- [ ] Redirect to My Tickets with success flash message

### My Tickets (`User\MyTickets`)
- [ ] Paginated table: Ticket No., Service Type, Priority, Status, Due Date, Date Requested
- [ ] Color-coded `status-badge` component
- [ ] Color-coded `priority-badge` component
- [ ] View ticket details modal (description, admin remarks, full audit log/history)
- [ ] Cancel button — visible only when status is `Pending`
- [ ] Cancel confirmation (`wire:confirm`) — updates status to `cancelled`, logs change

---

## 🧩 Blade Components
- [ ] `status-badge.blade.php` — color-coded pill for each status
- [ ] `priority-badge.blade.php` — color-coded pill for High/Medium/Low
- [ ] `app.blade.php` layout — navbar with role-aware links, flash message area

---

## 🔔 UI Polish
- [ ] Flash/toast notifications (success, error) — Alpine.js auto-dismiss after 3s
- [ ] `wire:loading` states on all form submit buttons
- [ ] Empty state messages when no tickets found
- [ ] Responsive layout (mobile-friendly tables)

---

## 🧪 Testing & QA
- [ ] Test login as Admin (redirect to dashboard)
- [ ] Test login as User (redirect to my tickets)
- [ ] Test ticket creation (all service types and priorities)
- [ ] Verify ticket_no format: `TKT-YYYYMMDD-XXXX`
- [ ] Verify SLA due dates: High=4h, Medium=1d, Low=3d
- [ ] Test full approval flow: Pending → Approved → In Progress → Resolved
- [ ] Test disapproval (remarks required, blank remarks should fail)
- [ ] Test user cancel (Pending only — Approved tickets cannot be cancelled)
- [ ] Verify `ticket_logs` records every status change with correct user
- [ ] Test role middleware (user cannot access `/admin/*` routes)
- [ ] Test search and filters on admin ticket table

---

## 🚀 Deployment (Future)
- [ ] Set up production `.env`
- [ ] Run `php artisan config:cache && php artisan route:cache`
- [ ] Run `npm run build`
- [ ] Configure web server (Apache/Nginx) with correct document root (`/public`)
- [ ] Set up MySQL on production server and import `schema.sql`
- [ ] Set correct file permissions on `storage/` and `bootstrap/cache/`