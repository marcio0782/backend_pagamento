CREATE TABLE IF NOT EXISTS payments (
    id TEXT PRIMARY KEY,
    amount NUMERIC(12, 2) NOT NULL,
    currency CHAR(3) NOT NULL,
    status TEXT NOT NULL,
    created_at TIMESTAMPTZ NOT NULL
);

