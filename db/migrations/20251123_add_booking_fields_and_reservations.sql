-- Migration: add booking fields and reservation/status-history tables

-- 1) Add extra columns to bookings (if not exists)
ALTER TABLE bookings
  ADD COLUMN IF NOT EXISTS pax_count INT DEFAULT 1,
  ADD COLUMN IF NOT EXISTS booking_type VARCHAR(32) DEFAULT 'individual',
  ADD COLUMN IF NOT EXISTS special_requests TEXT DEFAULT NULL;

-- 2) Create booking_status_history table
CREATE TABLE IF NOT EXISTS booking_status_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL,
  old_status VARCHAR(64) DEFAULT NULL,
  new_status VARCHAR(64) NOT NULL,
  changed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  changed_by VARCHAR(128) DEFAULT NULL,
  INDEX (booking_id),
  CONSTRAINT fk_bsh_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3) Create departure_reservations table for temporary holds
CREATE TABLE IF NOT EXISTS departure_reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  departure_id INT NOT NULL,
  booking_id INT NOT NULL,
  pax_count INT NOT NULL DEFAULT 1,
  reserved_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at DATETIME DEFAULT NULL,
  status ENUM('reserved','expired','confirmed') NOT NULL DEFAULT 'reserved',
  INDEX (departure_id),
  INDEX (booking_id),
  CONSTRAINT fk_dr_departure FOREIGN KEY (departure_id) REFERENCES departures(id) ON DELETE CASCADE,
  CONSTRAINT fk_dr_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Note: If your MySQL version doesn't support ADD COLUMN IF NOT EXISTS, run the ALTER statements manually.
