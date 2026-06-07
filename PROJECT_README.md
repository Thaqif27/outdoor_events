# 🏃‍♂️🚴‍♀️🥾 Outdoor Events Malaysia

A comprehensive Laravel-based web application for managing and discovering outdoor events in Malaysia, including running, cycling, and hiking activities.

## 🌟 Features

### For Users
- 🔍 **Event Discovery** - Browse and search outdoor events by category, location, and date
- 🗺️ **Map Integration** - View events on an interactive Google Maps interface
- 📍 **Location-based Search** - Find events near you with radius filtering
- ⭐ **Favorites** - Save favorite events for quick access
- 📅 **Personal Timetable** - View your registered events in calendar format
- 💬 **Reviews & Ratings** - Rate and review events you've attended
- 👤 **User Profiles** - Manage your personal information and preferences

### For Administrators
- 🎯 **Event Management** - Create, edit, and delete events
- 👥 **User Management** - Manage user accounts
- 🕷️ **Web Scraper** - Import events from external sources:
  - JomRun
  - Ticket2U
  - Eventbrite
  - Meetup
  - Checkpoint Spot
  - Finishers
  - SGTrek
- 📊 **Dashboard** - Overview of events and user statistics
- 🔧 **Category Management** - Automatic event categorization

## 🛠️ Technology Stack

- **Framework**: Laravel 10
- **PHP**: 8.1+
- **Database**: MySQL
- **Frontend**: Blade Templates, Bootstrap 5, Vite
- **Map Integration**: Google Maps JavaScript API
- **Web Scraping**: Puppeteer (Node.js), Browsershot, Symfony DomCrawler
- **Authentication**: Laravel Sanctum
- **Image Processing**: Intervention Image

## 📋 Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL >= 5.7 or MariaDB
- Google Maps API Key

## 🚀 Quick Start

### 1. Clone and Install
```bash
# Clone the repository
git clone <repository-url>
cd outdoor-events

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 2. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_DATABASE=outdoor_events
DB_USERNAME=root
DB_PASSWORD=

# Add Google Maps API Key
GOOGLE_MAPS_API_KEY=your_api_key_here
```

### 3. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed admin user
php artisan db:seed --class=AdminSeeder
```

### 4. Storage Setup
```bash
# Create storage symlink
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

### 5. Start Development Server
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev

# Visit: http://localhost:8000
```

### Default Admin Credentials
```
Email: admin@outdoor-events.com
Password: Admin@123456
```
⚠️ **Change this password immediately!**

## 📖 Documentation

- **[QUICK_START.md](QUICK_START.md)** - Quick reference guide
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Production deployment guide
- **[SECURITY_CHECKLIST.md](SECURITY_CHECKLIST.md)** - Security guidelines

## 🏗️ Project Structure

```
outdoor-events/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   └── User/           # User-facing controllers
│   │   └── Middleware/         # Custom middleware
│   ├── Models/                 # Eloquent models
│   └── Services/               # Business logic & scrapers
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/              # Admin panel views
│   │   ├── auth/               # Auth views
│   │   └── user/               # User views
│   ├── css/                    # Stylesheets
│   └── js/                     # JavaScript files
├── routes/
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes
└── public/                     # Public assets
```

## 🔐 Security Features

- ✅ CSRF Protection
- ✅ SQL Injection Protection (Eloquent ORM)
- ✅ XSS Protection (Blade templating)
- ✅ Password Hashing (bcrypt)
- ✅ Rate Limiting (Login, Registration, API endpoints)
- ✅ Role-based Access Control (Admin/User)
- ✅ Secure CORS Configuration
- ✅ Input Validation

## 🗺️ API Endpoints

### Geocoding (Rate Limited: 60/min)
- `POST /api/geocode` - Convert address to coordinates
- `POST /api/reverse-geocode` - Convert coordinates to address

### Authentication (Rate Limited)
- `POST /login` - Login (5 attempts/min)
- `POST /register` - Register (3 attempts/min)

## 🕷️ Web Scraper Services

The application includes scrapers for popular Malaysian event platforms:

| Service | Status | Category Detection |
|---------|--------|-------------------|
| JomRun | ✅ Active | Automatic |
| Ticket2U | ✅ Active | Automatic |
| Eventbrite | ✅ Active | Automatic |
| Meetup | ✅ Active | Automatic |
| Checkpoint Spot | ✅ Active | Running/Cycling |
| Finishers | ✅ Active | Running |
| SGTrek | ✅ Active | Hiking |

### Event Filtering
Events are automatically categorized as:
- **Running** - Marathons, fun runs, trail runs
- **Cycling** - Road cycling, MTB, criteriums
- **Hiking** - Mountain treks, nature walks, expeditions

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=EventTest
```

## 📦 Building for Production

```bash
# Build assets
npm run build

# Optimize Laravel
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License.

## 🐛 Known Issues

- Scraper services require Node.js and Puppeteer
- Google Maps API requires billing to be enabled
- Some external event sources may change their HTML structure

## 📞 Support

For issues and questions:
- Check the documentation files
- Review `storage/logs/laravel.log`
- Create an issue in the repository

## 🎯 Roadmap

- [ ] Email notifications for event reminders
- [ ] Two-factor authentication
- [ ] Mobile responsive improvements
- [ ] Export calendar (iCal format)
- [ ] Social media integration
- [ ] Event recommendations based on history

---

**Built with ❤️ for the Malaysian outdoor community**

*Last Updated: February 1, 2026*
