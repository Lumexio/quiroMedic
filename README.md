# QuiroMedic

QuiroMedic is a multi-tenant web application designed for ergonomists and chiropractic practitioners to register and track patient body measurements. It enables medical professionals to manage patients and record precise measurements for different body zones, making it easy to track progress over time.

![QuiroMedic Dashboard](https://example.com/dashboard-screenshot.png)

## Features

- **Multi-tenant Architecture**: Each organization has isolated access to its own patients and measurements
- **Patient Management**: Track patient details including personal information and lifestyle factors
- **Body Measurements**: Record precise measurements for specific body zones with customizable units
- **Visual Body Map**: Intuitive interface to select body areas for measurement
- **User Roles & Permissions**: Administrative controls with different access levels (admin, medic)
- **Responsive Design**: Works on desktop and mobile devices

## Technology Stack

- **Backend**: Laravel PHP Framework
- **Frontend**: Vue.js 3 with TypeScript
- **UI Components**: Tailwind CSS with custom components
- **Database**: MySQL
- **Authentication**: Laravel Fortify

## Local Development Setup

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- npm or yarn
- MySQL 8.0+

### Installation Steps

1. **Clone the repository**

```bash
git clone https://github.com/yourusername/quiromedic.git
cd quiromedic
```

2. **Install PHP dependencies**

```bash
composer install
```

3. **Install JavaScript dependencies**

```bash
npm install
```

4. **Environment Configuration**

```bash
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file to set up your database connection:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quiromedic
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run database migrations and seeders**

```bash
php artisan migrate
php artisan db:seed
```

This will create demo organizations, users, patients, and measurements for testing.

6. **Start the development server**

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server for frontend
npm run dev
```

7. **Access the application**

Open your browser and navigate to: `http://localhost:8000`

### Demo Accounts

After running seeders, you can log in with the following demo accounts:

- **Admin User**:

    - Email: admin@example.com
    - Password: password

- **Medical User**:
    - Email: medic@example.com
    - Password: password

## Deployment

For production deployment, please follow these additional steps:

```bash
# Build frontend assets
npm run build

# Optimize Laravel
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## License

[MIT License](LICENSE)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request
