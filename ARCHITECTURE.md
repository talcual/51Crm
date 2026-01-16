# 51CRM Architecture & Structure

This document provides an overview of the application architecture, database design, and project structure.

## Technology Stack

- **Backend Framework**: Laravel 10
- **Authentication**: Laravel Breeze (Blade)
- **Authorization**: Spatie Laravel Permission
- **Database**: MySQL / PostgreSQL
- **Frontend**: Blade Templates + Tailwind CSS
- **PDF Generation**: DomPDF
- **API Authentication**: Laravel Sanctum

## Database Architecture

### Core Tables

#### Users & Permissions
- `users` - System users
- `roles` - User roles (admin, manager, sales_rep, support)
- `permissions` - Granular permissions
- `model_has_roles` - User-role assignments
- `model_has_permissions` - User-permission assignments
- `role_has_permissions` - Role-permission assignments

#### CRM Core
- `leads` - Potential customers
- `clients` - Converted customers
- `deals` - Sales opportunities
- `pipeline_stages` - Sales pipeline stages
- `quotes` - Customer quotations
- `quote_items` - Line items for quotes
- `payments` - Payment records
- `loyalty_points` - Customer loyalty program
- `follow_ups` - Tasks and reminders (polymorphic)

### Entity Relationships

```
User (1) ─── (N) Lead
User (1) ─── (N) Client
User (1) ─── (N) Deal
User (1) ─── (N) Quote

Lead (1) ─── (1) Client [conversion]
Client (1) ─── (N) Deal
Client (1) ─── (N) Quote
Client (1) ─── (N) Payment
Client (1) ─── (N) LoyaltyPoint

Deal (1) ─── (N) PipelineStage
Deal (1) ─── (N) Quote
Deal (1) ─── (N) Payment

Quote (1) ─── (N) QuoteItem

Payment (1) ─── (N) LoyaltyPoint

FollowUp (polymorphic) ─── Lead/Client/Deal
```

## Application Modules

### 1. Authentication & Authorization

**Location**: `app/Http/Controllers/Auth/`, `routes/auth.php`

**Features**:
- User registration and login
- Password reset
- Email verification
- Profile management

**Roles**:
- **Admin**: Full system access
- **Manager**: Manage CRM data, no user management
- **Sales Rep**: Create and edit leads, clients, deals, quotes
- **Support**: Read-only access, manage follow-ups

### 2. Lead Management

**Location**: `app/Models/Lead.php`, `app/Http/Controllers/LeadController.php`

**Features**:
- Lead capture and tracking
- Status management (new, contacted, qualified, lost, converted)
- Source tracking
- Lead assignment
- Conversion to client

**Fields**:
- Contact information (name, email, phone)
- Company details
- Estimated value
- Source (website, referral, social media, etc.)
- Status and notes
- Assigned user

### 3. Client Management

**Location**: `app/Models/Client.php`, `app/Http/Controllers/ClientController.php`

**Features**:
- Client profile management
- Complete contact information
- Associated deals and quotes
- Payment history
- Loyalty points tracking

**Fields**:
- Personal/company information
- Contact details
- Address information
- Client type (individual/company)
- Notes

### 4. Sales Pipeline

**Location**: `app/Models/Deal.php`, `app/Models/PipelineStage.php`

**Features**:
- Deal/opportunity tracking
- Pipeline stage management
- Value and probability tracking
- Expected close dates
- Deal status (open, won, lost)

**Pipeline Stages** (default):
1. Lead
2. Qualified
3. Proposal
4. Negotiation
5. Closed Won
6. Closed Lost

### 5. Quotations

**Location**: `app/Models/Quote.php`, `app/Models/QuoteItem.php`

**Features**:
- Quote creation and management
- Line item management
- Automatic calculations (subtotal, tax, discount, total)
- Status tracking (draft, sent, accepted, rejected, expired)
- PDF generation
- Quote validity period

### 6. AI-Powered Follow-ups

**Location**: `app/Models/FollowUp.php`

**Features**:
- Task and reminder system
- Multiple types (call, email, meeting, task, AI-generated)
- Priority levels
- Status tracking
- Polymorphic associations (can attach to leads, clients, deals)
- AI suggestion fields (ready for integration)

### 7. Payments & Financing

**Location**: `app/Models/Payment.php`

**Features**:
- Payment tracking
- Multiple payment methods
- Installment/financing plans (JSON field)
- Transaction references
- Payment status management

### 8. Customer Loyalty

**Location**: `app/Models/LoyaltyPoint.php`

**Features**:
- Points earning system
- Points redemption
- Expiration tracking
- Transaction history
- Automatic point calculation

## Directory Structure

```
51Crm/
├── app/
│   ├── Console/               # Artisan commands
│   ├── Exceptions/            # Exception handlers
│   ├── Http/
│   │   ├── Controllers/       # Application controllers
│   │   │   ├── Auth/         # Authentication controllers
│   │   │   ├── ClientController.php
│   │   │   ├── DealController.php
│   │   │   ├── LeadController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── QuoteController.php
│   │   │   └── LoyaltyController.php
│   │   ├── Middleware/        # HTTP middleware
│   │   └── Requests/          # Form requests
│   ├── Models/                # Eloquent models
│   │   ├── Client.php
│   │   ├── Deal.php
│   │   ├── FollowUp.php
│   │   ├── Lead.php
│   │   ├── LoyaltyPoint.php
│   │   ├── Payment.php
│   │   ├── PipelineStage.php
│   │   ├── Quote.php
│   │   ├── QuoteItem.php
│   │   └── User.php
│   └── Providers/             # Service providers
├── bootstrap/                 # Bootstrap files
├── config/                    # Configuration files
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   └── permission.php
├── database/
│   ├── factories/             # Model factories
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
│       ├── RolesAndPermissionsSeeder.php
│       └── PipelineStagesSeeder.php
├── public/                    # Public assets
├── resources/
│   ├── css/                   # CSS files
│   ├── js/                    # JavaScript files
│   └── views/                 # Blade templates
│       ├── auth/
│       ├── clients/
│       ├── deals/
│       ├── leads/
│       ├── loyalty/
│       ├── payments/
│       ├── quotes/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── guest.blade.php
│       │   └── navigation.blade.php
│       └── dashboard.blade.php
├── routes/
│   ├── api.php                # API routes
│   ├── auth.php               # Authentication routes
│   ├── channels.php           # Broadcast channels
│   ├── console.php            # Console commands
│   └── web.php                # Web routes
├── storage/                   # Application storage
├── tests/                     # Automated tests
└── vendor/                    # Composer dependencies
```

## Key Design Patterns

### 1. Model-View-Controller (MVC)
Laravel's implementation of MVC pattern separates business logic, presentation, and data.

### 2. Repository Pattern (Ready for Implementation)
Can be implemented for complex queries and business logic abstraction.

### 3. Service Layer (Ready for Implementation)
Complex business logic can be extracted into service classes.

### 4. Observer Pattern
Laravel Events and Listeners can be used for:
- Sending notifications
- Logging activities
- Triggering AI suggestions

### 5. Polymorphic Relationships
Used in FollowUp model to attach tasks to different entities (leads, clients, deals).

## Security Features

1. **Authentication**: Laravel Breeze with secure password hashing
2. **Authorization**: Role-based access control with Spatie Permission
3. **CSRF Protection**: Built-in CSRF tokens for forms
4. **SQL Injection Prevention**: Eloquent ORM with prepared statements
5. **XSS Protection**: Blade template escaping
6. **Password Hashing**: Bcrypt hashing algorithm
7. **API Authentication**: Sanctum for API tokens

## Performance Considerations

### Implemented
- Eager loading for relationships (N+1 prevention)
- Pagination on list views
- Database indexes on foreign keys

### Recommended for Production
- Redis for caching and sessions
- Queue workers for background jobs
- Database query optimization
- CDN for static assets
- Opcache for PHP

## Extensibility

### AI Integration Points
The system is prepared for AI integration:

1. **Follow-up Suggestions**
   - `ai_suggested` field in follow_ups table
   - `ai_reasoning` field for AI explanation
   
2. **Lead Scoring** (Ready to implement)
   - Can be added to leads table
   - AI can predict conversion probability

3. **Sales Insights** (Ready to implement)
   - Pattern recognition in deals
   - Revenue predictions

### API Development
The application is ready for API development:
- Laravel Sanctum is installed
- RESTful routes can be added to `routes/api.php`
- API versioning can be implemented

### Customization
- New modules can be added following existing patterns
- Pipeline stages are fully customizable
- Roles and permissions can be extended
- Forms and validations can be customized

## Future Enhancements

### Short Term
- Complete CRUD views for all modules
- Advanced search and filtering
- Data export functionality
- Email notifications
- Activity logging

### Medium Term
- Kanban-style pipeline view
- Chart and analytics dashboard
- Report generation
- Calendar integration
- Document management

### Long Term
- Mobile application
- Advanced AI features
- Third-party integrations (payment gateways, email services)
- Multi-language support
- Multi-tenant architecture

## Development Guidelines

### Code Standards
- Follow PSR-12 coding standards
- Use Laravel best practices
- Write descriptive commit messages
- Document complex logic

### Testing
- Write unit tests for models
- Write feature tests for controllers
- Test authentication and authorization
- Test business logic

### Database
- Always use migrations for schema changes
- Use seeders for test data
- Name conventions: snake_case for tables and columns
- Add indexes for foreign keys and frequently queried columns

## Contributing

When contributing to the project:
1. Follow the existing code structure
2. Write tests for new features
3. Update documentation
4. Use feature branches
5. Submit pull requests for review

## Support & Documentation

- Laravel Documentation: https://laravel.com/docs/10.x
- Spatie Permission: https://spatie.be/docs/laravel-permission/v5
- Tailwind CSS: https://tailwindcss.com/docs
