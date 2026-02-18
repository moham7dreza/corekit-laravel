# Avinar - Laravel Platform

**Avinar** is an online healthcare & counseling platform (MVP Laravel + React) focused on providing confidential mental health, sexual health, and general health consultations. It supports subscriptions, wallet system, appointments, and a professional admin panel.

---

## 🚀 Features (Phase 1 MVP)

- User authentication & role management (Patient, Provider, Admin)
- Provider profiles & verification
- Category & service management
- Appointment booking & scheduling
- Provider services & pricing
- Admin panel powered by Filament
- Basic React frontend for user interactions
- Payment & subscription integration (Stripe / Laravel Cashier)
- Notification & messaging placeholders

Future Features:
- Wallet system for users & providers
- Gamification & badges
- Multimedia health articles
- Advanced treatment tracking
- Video calls & live chat
- Mobile apps

---

## ⚙️ Tech Stack

- Backend: Laravel 10
- Frontend: React + Inertia.js (Starter Kit)
- Database: MySQL / Postgres
- Caching & Queues: Redis
- Admin Panel: Filament
- Payment: Laravel Cashier (Stripe / PayPal)
- Docker for development & deployment

---

## 📦 Installation

1. Clone the repo:

```bash
git clone https://github.com/yourusername/avinar-laravel.git
cd avinar-laravel
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install Node dependencies:

```bash
npm install
npm run dev
```

4. Copy `.env` and generate key:

```bash
cp .env.example .env
php artisan key:generate
```

5. Set your database & mail configuration in `.env`.

6. Run migrations and seeders:

```bash
php artisan migrate --seed
```

7. Start the development server:

```bash
php artisan serve
```

Access: `http://localhost:8000`

---

## 📚 Project Structure (Phase 1)

```
app/
 ├─ Models/
 ├─ Services/          # Business logic
 ├─ Repositories/      # Data access
 ├─ Http/
 │    ├─ Controllers/
 │    ├─ Requests/
 │    └─ Resources/
 ├─ Events/
 └─ Listeners/

database/
 ├─ migrations/
 └─ seeders/

resources/
 ├─ js/                # React frontend
 └─ views/

routes/
 ├─ web.php
 └─ api.php
```

---

## 🎨 Branding

- **Project Name:** Avinar
- **Tagline:** Talk. Heal. Grow.
- **Primary Color:** #5DA9E9 (Soft Blue)
- **Secondary Color:** #7FB069 (Sage Green)

---

## 📌 Contributing

- Follow PSR-12 code style
- Use feature branches
- Test before push
- Submit PRs to `develop` branch

---

## 📜 License

This project is licensed under MIT License.

