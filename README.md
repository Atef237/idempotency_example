# Laravel API Idempotency Example 🛡️

This project demonstrates a simple yet effective implementation of **API Idempotency** in Laravel. Idempotency ensures that making the same API request multiple times has the same effect as making it once, preventing accidental duplicate operations (like double-charging a customer or applying a discount twice).

---

## 🚀 How it Works

The implementation uses a custom Middleware (`IdempotencyMiddleware`) that:
1.  **Checks** for a unique `X-Idempotency-Key` in the request headers.
2.  **Intercepts** the request if the key has been processed before, returning the cached response immediately.
3.  **Processes** the request if it's new, caches the successful response, and returns it to the client.

### Key Logic:
- **Cache Hit:** If the key exists, return the cached result with a custom header `x-cache: HIT-IDENTICAL`.
- **Cache Miss:** If the key is new, execute the controller logic and store the result in the cache for 10 hours.

---

## 🛠️ Getting Started

Follow these steps to set up and run the example locally:

### 1. Clone & Install
```bash
git clone https://github.com/your-username/Idempotency-example.git
cd Idempotency-example
composer install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```
*Note: Ensure your database connection is configured in the `.env` file.*

### 3. Database & Seeding
Run migrations and seed the database with an initial order:
```bash
php artisan migrate --seed
```

### 4. Run the Server
```bash
php artisan serve
```

---

## 🧪 How to Test

You can test the idempotency logic using **Postman** or **cURL**.

### A. Using Postman (Recommended)
1.  Import the provided collection: `Idempotency-example.postman_collection.json`.
2.  Send the **"Apply Discount"** request.
3.  **First Request:** You will see the discount applied (e.g., `discunt_amount: 10`).
4.  **Second Request (Same Key):** You will see the **exact same response**, but with a header `x-cache: HIT-IDENTICAL`. The database is **not** updated a second time.
5.  **New Request (Different Key):** The discount will be applied again because it's treated as a new unique operation.

### B. Using cURL
```bash
curl -X POST http://localhost:8000/api/order/1/discount \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -H "X-Idempotency-Key: unique-key-123"
```

---

## 📁 Key Files
- `app/Http/Middleware/IdempotencyMiddleware.php`: The core logic for handling idempotency.
- `routes/api.php`: Route definition using the `idempotent` middleware.
- `app/Http/Controllers/OrderController.php`: Example logic that modifies data (applying a discount).

---

## 💡 Why use Idempotency?
- **Network Interruptions:** If a client doesn't receive a response due to a timeout, they can safely retry the request.
- **User Error:** Prevents accidental double-clicks on "Submit" buttons from creating duplicate records.
- **Safety:** Essential for financial transactions and resource-intensive operations.

---
Developed with ❤️ using Laravel.
