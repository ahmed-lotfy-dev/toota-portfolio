# Toota Art - Portfolio & Gallery

A sophisticated, modern, and fully-featured portfolio website built with the TALL stack (Tailwind, Alpine.js, Livewire, Laravel). This project serves as a complete solution for artists to showcase their work, manage their portfolio, and engage with their audience. It features a beautiful, responsive public-facing gallery and a powerful, secure admin dashboard.

---

### Showcase

*(This is where you can embed screenshots of your application. Replace the placeholder links with your actual images.)*

| Landing Page                               | Project Detail                               | Admin Dashboard                              |
| ------------------------------------------ | -------------------------------------------- | -------------------------------------------- |
| ![Landing Page](https://via.placeholder.com/400x300.png?text=Landing+Page+Screenshot) | ![Project Detail](https://via.placeholder.com/400x300.png?text=Project+Detail+Screenshot) | ![Admin Dashboard](https://via.placeholder.com/400x300.png?text=Dashboard+Screenshot) |

---

## ✨ Features

### Public-Facing Website
- **Dynamic Project Gallery:** A beautiful, responsive gallery to showcase projects, filterable by category.
- **Project Detail Pages:** Each project has its own dedicated, SEO-friendly page with multiple images and a detailed description.
- **Multi-Language Support:** Fully bilingual (English & Arabic) with a simple language switcher.
- **Contact Form:** A seamless way for visitors to get in touch, integrated with email notifications.
- **Newsletter Subscription:** A component for visitors to subscribe to a newsletter.
- **Engaging UI/UX:** Built with modern design principles and smooth transitions.

### Administrative Dashboard
- **Secure Authentication:** Robust user authentication powered by Laravel Fortify, including Two-Factor Authentication (2FA) and Google OAuth login options.
- **Full Project Management (CRUD):** A powerful interface to create, read, update, and delete projects.
- **Image Management:** Easy drag-and-drop image uploads for projects, with support for primary images and custom ordering.
- **Dynamic Content Management:** Manage project categories and testimonials directly from the dashboard.
- **Project Status Control:** Toggle projects between `Published` and `Draft` states, and mark projects as `Featured`.
- **User Profile Management:** Admins can manage their profile information, password, and security settings.

---

## 🚀 Technology Stack

This project is built with a modern, efficient, and powerful technology stack.

- **Backend:**
  - **[Laravel 12](https://laravel.com/)**: A web application framework with expressive, elegant syntax.
  - **[PHP 8.4](https://www.php.net/)**: A popular general-purpose scripting language that is especially suited to web development.
- **Frontend:**
  - **[Livewire 3](https://livewire.laravel.com/)**: A full-stack framework for Laravel that makes building dynamic interfaces simple, without leaving the comfort of PHP.
  - **[Tailwind CSS](https://tailwindcss.com/)**: A utility-first CSS framework for rapidly building custom designs.
  - **[Alpine.js](https://alpinejs.dev/)**: A rugged, minimal framework for composing JavaScript behavior in your markup.
  - **[Vite](https://vitejs.dev/)**: A next-generation frontend tooling that provides a faster and leaner development experience.
- **Database:**
  - **PostgreSQL**: A powerful, open-source object-relational database system.
- **External Services:**
  - **Cloudflare R2**: For scalable and reliable object storage for project images.
  - **Google OAuth**: For secure, passwordless authentication.

---

## 🏛️ Architecture Highlights

- **Component-Based with Livewire:** The UI is built using reactive, stateful Livewire components, allowing for a Single Page Application (SPA) feel with the simplicity of a traditional server-rendered application.
- **Separation of Concerns:** The project follows best practices for separating business logic, presentation, and data persistence, making the codebase clean and maintainable.
- **Service Layer:** Business logic, such as project deletion (including its associated images in Cloudflare R2), is encapsulated within Service classes (`ProjectService`) to keep controllers and components lean.
- **RESTful Principles:** The application design adheres to RESTful principles, with clean, predictable URL structures for resources like projects.
- **Blade Components:** Reusable UI elements (like navigation, footers, and form inputs) are abstracted into Blade components for consistency and maintainability.

---

## 🏁 Getting Started

Follow these instructions to get the project up and running on your local machine for development and testing purposes.

### Prerequisites

- PHP >= 8.4
- Composer
- Node.js & npm
- PostgreSQL (or another database of your choice, but you will need to configure it)

### Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/toota-art.git
    cd toota-art
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**
    ```bash
    npm install
    ```

4.  **Set up your environment file:**
    ```bash
    cp .env.example .env
    ```
    Next, generate your application key:
    ```bash
    php artisan key:generate
    ```

5.  **Configure your `.env` file:**
    Open the `.env` file and update the database credentials (`DB_*`), Google OAuth credentials (`GOOGLE_*`), and Cloudflare R2 settings (`R2_*`).

6.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```

### Running the Application

1.  **Build frontend assets and watch for changes:**
    ```bash
    npm run dev
    ```

2.  **Serve the application:**
    Open another terminal window and run:
    ```bash
    php artisan serve
    ```
    Your application will be available at `http://localhost:8000`.

---

## 🧪 Running Tests

This project uses Pest for testing. To run the full test suite, use the following command:

```bash
./vendor/bin/pest
```

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).