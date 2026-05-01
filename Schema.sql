-- ============================================================
-- IT Ticketing System — MySQL Schema
-- Stack: Laravel 11 + Inertia.js + Svelte
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS ticket_logs;
DROP TABLE IF EXISTS tickets;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- USERS
-- ============================================================
CREATE TABLE users (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100)  NOT NULL,
    username      VARCHAR(50)   NOT NULL UNIQUE,
    password      VARCHAR(255)  NOT NULL,
    role          ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    department    VARCHAR(100)  NULL,
    remember_token VARCHAR(100) NULL,
    created_at    TIMESTAMP     NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP     NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TICKETS
-- ============================================================
CREATE TABLE tickets (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Auto-generated: TKT-YYYYMMDD-XXXX
    ticket_no       VARCHAR(20)   NOT NULL UNIQUE,

    -- Who submitted the ticket
    user_id         BIGINT UNSIGNED NOT NULL,

    -- Service details
    service_type    ENUM('network', 'printer', 'ups', 'desktop_laptop', 'other') NOT NULL,
    description     TEXT          NOT NULL,

    -- Priority & SLA
    priority        ENUM('high', 'medium', 'low') NOT NULL,
    due_date        DATETIME      NOT NULL,
    -- SLA rules (auto-calculated on creation):
    --   high   → +4 hours from created_at
    --   medium → +1 day from created_at
    --   low    → +3 days from created_at

    -- Status flow: pending → approved → in_progress → resolved
    --              pending → disapproved
    status          ENUM('pending', 'approved', 'in_progress', 'resolved', 'disapproved', 'cancelled')
                    NOT NULL DEFAULT 'pending',

    -- Admin actions
    assigned_to     BIGINT UNSIGNED NULL,   -- FK to users (admin/personnel)
    admin_remarks   TEXT          NULL,      -- Required when disapproving

    created_at      TIMESTAMP     NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP     NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_tickets_user
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

    CONSTRAINT fk_tickets_assigned
        FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- TICKET LOGS (Audit Trail)
-- ============================================================
CREATE TABLE ticket_logs (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id   BIGINT UNSIGNED NOT NULL,

    -- Who made the change (admin or user)
    changed_by  BIGINT UNSIGNED NOT NULL,

    old_status  ENUM('pending', 'approved', 'in_progress', 'resolved', 'disapproved', 'cancelled') NULL,
    new_status  ENUM('pending', 'approved', 'in_progress', 'resolved', 'disapproved', 'cancelled') NOT NULL,

    -- Optional note logged alongside the change
    remarks     TEXT NULL,

    created_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_logs_ticket
        FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,

    CONSTRAINT fk_logs_user
        FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- INDEXES (for common queries)
-- ============================================================
CREATE INDEX idx_tickets_status       ON tickets(status);
CREATE INDEX idx_tickets_priority     ON tickets(priority);
CREATE INDEX idx_tickets_service_type ON tickets(service_type);
CREATE INDEX idx_tickets_user_id      ON tickets(user_id);
CREATE INDEX idx_tickets_assigned_to  ON tickets(assigned_to);
CREATE INDEX idx_ticket_logs_ticket   ON ticket_logs(ticket_id);

-- ============================================================
-- SEED DATA — Default Admin Account
-- Password: 'password' (hashed via bcrypt — replace before production)
-- ============================================================
INSERT INTO users (name, username, password, role, department) VALUES
(
    'System Administrator',
    'admin',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- bcrypt of 'password'
    'admin',
    'IT Department'
),
(
    'Juan dela Cruz',
    'jdelacruz',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'user',
    'Finance'
),
(
    'Maria Santos',
    'msantos',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'user',
    'HR'
);