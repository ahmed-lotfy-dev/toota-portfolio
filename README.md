# 🎨 Toota Art Portfolio

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)

A high-performance, dynamic portfolio application designed to showcase artistic projects with elegance and speed. Built with a modern TALL stack (Tailwind, Alpine, Laravel, Livewire) and optimized for seamless content management.

---

## 🚀 Why & What

### The "Why"

Artists need a platform that doesn't just store their work but **elevates** it. Generic website builders often lack the performance or the specific customizability needed for a truly professional portfolio. Toota Art was built to solve this by providing a blazing-fast, SEO-optimized, and easy-to-manage solution.

### The "What"

**Toota Art** is a full-stack web application that serves as both a public-facing portfolio and a private content management system. It allows for:

-   **Effortless Management**: A custom dashboard to manage projects, categories, and testimonials without touching code.
-   **Optimized Performance**: Images are automatically optimized and served via Cloudflare R2 for instant loading.
-   **Dynamic Experience**: Smooth transitions and interactive elements powered by Livewire and Alpine.js.

---

## ✨ Key Features

### 1. 🖼️ Dynamic Portfolio Showcase

A visually stunning frontend that displays projects in a responsive grid.

-   **Filtering**: Filter projects by category instantly.
-   **Detail View**: Rich project details with support for multiple images and descriptions.
-   **Masonry Layout**: Adapts to different image aspect ratios.

> ![Screenshot: Portfolio Home Page](PLACEHOLDER_IMAGE_HOME_PAGE) > _The main landing page showcasing the art collection._

### 2. 🎛️ Admin Dashboard

A secure, feature-rich dashboard for managing all site content.

-   **Project Management**: Create, edit, and delete projects using dedicated Actions (`CreateProject`, `UpdateProject`, `DeleteProject`).
-   **Status Control**: Toggle "Published" or "Featured" status with a single click.
-   **Category & Testimonial Management**: Organize work and display social proof.

> ![Screenshot: Admin Dashboard](PLACEHOLDER_IMAGE_DASHBOARD) > _The centralized hub for managing your portfolio._

### 3. ☁️ Smart Image Management

Forget about slow uploads or broken images.

-   **Cloudflare R2 Integration**: Images are stored securely in the cloud, not on the server.
-   **Drag & Drop Uploader**: A custom-built Livewire component for easy multi-image uploads.
-   **Auto-Optimization**: Images are converted to WebP and resized for performance.
-   **Replace Strategy**: Updating a project automatically cleans up old images to prevent storage clutter.

> ![Screenshot: Image Uploader](PLACEHOLDER_IMAGE_UPLOADER) > _Custom drag-and-drop uploader with preview and deletion capabilities._

### 4. 🧩 Reusable UI Kit

Built with maintainability in mind. The project uses a set of custom Blade components (`x-ui.*`) for consistent design across the application.

-   **Components**: Buttons, Inputs, Badges, Modals, and more.
-   **Dark Mode**: Native support for dark mode styling.

---

## 🛠️ Tech Stack

We chose the **TALL Stack** for its perfect balance of developer experience and performance.

| Technology        | Purpose           | Why we used it                                                                                                   |
| :---------------- | :---------------- | :--------------------------------------------------------------------------------------------------------------- |
| **Laravel 11**    | Backend Framework | The industry standard for PHP. Provides robust routing, security, and database management.                       |
| **Livewire 3**    | Dynamic Frontend  | Allows us to build dynamic interfaces (like the dashboard) without the complexity of a separate SPA (React/Vue). |
| **Alpine.js**     | Interactivity     | Adds lightweight JavaScript behavior (modals, dropdowns) directly in the HTML.                                   |
| **Tailwind CSS**  | Styling           | Utility-first CSS for rapid, custom UI development without fighting framework defaults.                          |
| **Cloudflare R2** | Storage           | S3-compatible object storage that eliminates egress fees and ensures fast global delivery.                       |

---

## 📂 Project Structure

The project follows a clean, domain-driven structure:

```
app/
├── Actions/           # Business logic (CreateProject, UpdateProject, DeleteProject)
├── Http/
│   ├── Controllers/   # Request handling
│   └── Livewire/      # Reactive components (Dashboard, Forms)
├── Models/            # Eloquent models (Project, Category)
└── Services/          # External integrations
resources/
└── views/
    ├── components/ui/ # Reusable UI Kit (Button, Input, Modal)
    └── livewire/      # Page-specific views
```

---

## ⚡ Getting Started

### Prerequisites

-   PHP 8.2+
-   Composer
-   Node.js & NPM
-   MySQL

### Installation

1.  **Clone the repository**

    ```bash
    git clone https://github.com/ahmed-lotfy-dev/toota-portfolio.git
    cd toota-portfolio
    ```

2.  **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    _Configure your database and Cloudflare R2 credentials in `.env`._

4.  **Run Migrations**

    ```bash
    php artisan migrate
    ```

5.  **Start Development Server**
    ```bash
    npm run dev
    php artisan serve
    ```

---

## 🔒 Security

-   **Authentication**: Powered by Laravel Breeze/Jetstream.
-   **Authorization**: Role-based access control for the dashboard.
-   **Sanitization**: All inputs are validated and sanitized to prevent XSS and SQL injection.

---

Made with ❤️ by [Ahmed Lotfy](https://github.com/ahmed-lotfy-dev)
