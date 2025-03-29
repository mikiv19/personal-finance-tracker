-- Create test user first
INSERT INTO users (email, password_hash) 
VALUES ('test@example.com', 'placeholder_hash')
RETURNING id;

-- Then add transactions using the generated user_id
INSERT INTO transactions (user_id, amount, category, transaction_date)
VALUES 
(currval('users_id_seq'), 2000.00, 'Salary', NOW()),
(currval('users_id_seq'), -150.00, 'Food', NOW());