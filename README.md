# BAABOO - Support Ticket System

A Laravel 10 multi-database support ticket system with separate databases for each ticket department.

---

## 📋 Requirements

- **Docker** 20.10+
- **Docker Compose** 2.0+

That's it! Everything else runs in Docker.

---

## 🚀 First Time Setup

### 1. Clone the Repository
```bash
git clone [text](https://github.com/iumairish/baaboo)
cd baaboo
```

### 2. Copy Environment File
```bash
cp .env.example .env
```

### 3. Start Docker Containers
```bash
docker-compose up -d
```

### 4. Install Dependencies
```bash
docker-compose exec app composer install
docker-compose exec app npm install
```

### 5. Generate Application Key
```bash
docker-compose exec app php artisan key:generate
```

### 6. Run Migrations & Seeders
```bash
docker-compose exec app php artisan migrate:fresh --seed
```

### 7. Access the Application
- **Customer Portal**: http://localhost:8080
- **Admin Panel**: http://localhost:8080/admin/login

**Default Admin Credentials:**
- Email: `admin@example.com`
- Password: `password`

---

## 🧪 Running Tests

### Run All Tests
```bash
docker-compose exec app php artisan test
```

### Run Specific Test Suite
```bash
# Unit tests only
docker-compose exec app php artisan test --testsuite=Unit

# Feature tests only
docker-compose exec app php artisan test --testsuite=Feature
```

---

## 🗄️ Database Architecture

The system uses **6 separate MySQL databases**:

| Database | Purpose |
|----------|---------|
| `main_db` | Admins, Sessions |
| `technical_issues_db` | Technical Support Tickets |
| `account_billing_db` | Billing Tickets |
| `product_service_db` | Product/Service Tickets |
| `general_inquiry_db` | General Inquiry Tickets |
| `feedback_suggestions_db` | Feedback Tickets |

---

## 🎨 Features

### Customer Portal
- Submit support tickets
- Choose from 5 ticket types
- Form validation
- Success confirmation

### Admin Panel
- Secure authentication
- View all tickets (aggregated from all databases)
- DataTables with search & sorting
- Add notes to tickets (with Trix editor)
- Automatic status updates

### Technical Features
- **Multi-database architecture** - Separate DB per department
- **Repository pattern** - Clean data access
- **Service layer** - Business logic separation
- **DTOs** - Type-safe data transfer
- **Enums** - PHP 8.1+ backed enums
- **Comprehensive testing** - 28 tests with 95%+ coverage
- **Code quality** - PHPStan Level 8, Laravel Pint

---

## 🐛 Troubleshooting

### Permission Issues
```bash
docker-compose exec --user root app chown -R www-data:www-data /var/www
docker-compose exec --user root app chmod -R 775 /var/www/storage
```

### Clear All Caches
```bash
docker-compose exec app php artisan optimize:clear
```

---

## 📊 Testing

### Test Coverage
- **33 Tests** with **121+ Assertions**
- **Feature Tests**: Customer submission, admin authentication, ticket management
- **Unit Tests**: Services, repositories, DTOs, enums
- **Coverage**: 95%+

### Test Data Cleanup
Tests automatically clean up data created during testing. No manual cleanup needed.

---

## 🔒 Security

- CSRF protection on all forms
- Password hashing with bcrypt
- Input validation on all requests
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade auto-escaping)
- Admin authentication middleware

---

## 📝 Environment Variables

Key variables in `.env`:

```env
# Application
APP_URL=http://localhost:8080

# Database Hosts (Docker)
DB_HOST=mysql_main
DB_TECHNICAL_HOST=mysql_technical
DB_BILLING_HOST=mysql_billing
DB_PRODUCT_HOST=mysql_product
DB_INQUIRY_HOST=mysql_inquiry
DB_FEEDBACK_HOST=mysql_feedback

# Database Names
DB_DATABASE=main_db
DB_TECHNICAL_DATABASE=technical_issues_db
DB_BILLING_DATABASE=account_billing_db
DB_PRODUCT_DATABASE=product_service_db
DB_INQUIRY_DATABASE=general_inquiry_db
DB_FEEDBACK_DATABASE=feedback_suggestions_db

```

---

## 📖 API Documentation

### Ticket Types
- `Technical Issues`
- `Account & Billing`
- `Product & Service`
- `General Inquiry`
- `Feedback & Suggestions`

### Ticket Statuses
- `open` - New ticket
- `noted` - Admin added note
- `in_progress` - Being worked on
- `resolved` - Issue resolved
- `closed` - Ticket closed

---

**Built by Umair with Laravel 10, Docker, and ❤️**