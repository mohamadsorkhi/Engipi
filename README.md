# EngPis-MVP

MVP for EngPis platform — a skill-matching system connecting service providers (specialists) with customers based on skills and project needs.

---

## 🚀 Overview

EngPis is a platform designed to match users based on skills.  
Customers can create requests/projects, and specialists can respond based on their expertise.

---

## 🎯 Features

- User authentication (login/register)
- Role-based system (User / Worker / Employer / Specialist)
- Skill management system
- Project/request creation
- Matching system between users and specialists
- Ticket system (basic support)
- Dashboard for different roles

---

## 🛠️ Tech Stack

- Backend: Laravel
- Frontend: Blade (Laravel views)
- Database: MySQL
- Build Tool: Vite

---

## 📂 Project Structure (Simplified)

- `app/` → Core application logic (Controllers, Models)
- `routes/` → Route definitions (web, api, roles)
- `resources/views/` → Blade templates
- `database/` → Migrations & seeders
- `public/` → Public assets

---

## ⚙️ Installation

```bash
git clone https://github.com/mohamadsorkhi/EngPis-MVP.git
cd EngPis-MVP
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serveسسسمم