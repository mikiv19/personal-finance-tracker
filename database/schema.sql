DROP TABLE IF EXISTS transactions CASCADE;
DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    amount NUMERIC(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    transaction_date DATE NOT NULL
);

INSERT INTO transactions (user_id, amount, category, transaction_date)
VALUES (1, -50.00, 'Food', NOW());