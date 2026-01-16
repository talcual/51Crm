# 51Crm

Un CRM moderno con capacidades de IA, construido con Laravel 10.

## Características

### Sistema de Gestión de Usuarios y Permisos
- Autenticación completa con Laravel Breeze
- Sistema de roles y permisos con Spatie Permission
- Roles predefinidos: Admin, Manager, Sales Rep, Support
- Gestión granular de permisos para cada módulo

### Módulos del CRM

#### 1. Gestión de Leads y Clientes
- Registro y seguimiento de leads
- Conversión de leads a clientes
- Gestión completa de información de clientes
- Asignación de leads/clientes a usuarios
- Estados y fuentes de leads

#### 2. Pipeline de Ventas
- Gestión de deals/oportunidades
- Etapas personalizables del pipeline
- Seguimiento del valor y probabilidad de cierre
- Visualización del estado de cada deal
- Fechas de cierre esperadas

#### 3. Cotizaciones
- Creación y gestión de cotizaciones
- Items con cantidades y precios
- Cálculo automático de subtotales, impuestos y descuentos
- Generación de PDFs con DomPDF
- Estados: borrador, enviada, aceptada, rechazada, expirada
- Versionado de cotizaciones

#### 4. Seguimiento Automático con IA
- Sistema de follow-ups/tareas
- Tipos: llamada, email, reunión, tarea, generada por IA
- Prioridades y estados
- Fechas de vencimiento
- Sistema preparado para sugerencias de IA
- Follow-ups polimórficos (pueden asociarse a leads, clientes, deals)

#### 5. Pagos / Financiación
- Registro de pagos
- Múltiples métodos de pago
- Sistema de financiación/cuotas
- Referencias de transacciones
- Estados de pago

#### 6. Fidelización Básica
- Sistema de puntos de lealtad
- Otorgamiento y redención de puntos
- Puntos con fecha de expiración
- Tracking de puntos por cliente

## Requisitos

- PHP 8.1 o superior
- Composer
- MySQL 5.7+ o PostgreSQL 10+
- Node.js y NPM (para compilar assets)

## Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/talcual/51Crm.git
cd 51Crm
```

2. **Instalar dependencias**
```bash
composer install
npm install
```

3. **Configurar el entorno**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar la base de datos**

Editar `.env` con tus credenciales de base de datos:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=51crm
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

5. **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

Esto creará:
- Todas las tablas del CRM
- Roles y permisos
- Etapas del pipeline por defecto
- Usuario administrador por defecto

6. **Compilar assets**
```bash
npm run build
# o para desarrollo:
npm run dev
```

7. **Iniciar el servidor**
```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## Credenciales por Defecto

- **Email**: admin@51crm.com
- **Password**: password

**IMPORTANTE**: Cambiar estas credenciales en producción.

## Estructura de Roles

### Admin
- Acceso completo al sistema
- Gestión de usuarios, roles y permisos

### Manager
- Gestión de leads, clientes, deals, cotizaciones
- Gestión de pagos y programa de lealtad
- No puede gestionar usuarios ni roles

### Sales Rep
- Crear y editar leads, clientes, deals
- Crear y enviar cotizaciones
- Gestión de follow-ups

### Support
- Ver información (solo lectura en la mayoría de módulos)
- Crear y gestionar follow-ups
- Ver información de lealtad

## Módulos y Funcionalidades

### Dashboard
Panel principal con resumen de actividades y métricas del CRM.

### Leads
- Formulario de captura de leads
- Estados: nuevo, contactado, calificado, perdido, convertido
- Seguimiento de valor estimado
- Conversión automática a cliente

### Clientes
- Perfil completo del cliente
- Historial de deals y cotizaciones
- Tracking de pagos
- Puntos de lealtad acumulados

### Pipeline de Ventas
- Visualización tipo Kanban (preparado)
- Etapas personalizables
- Probabilidad de cierre
- Fechas y valores

### Cotizaciones
- Editor de items
- Cálculos automáticos
- Generación de PDF
- Control de versiones

### Pagos
- Registro de transacciones
- Soporte para cuotas/financiación
- Estados de pago

### Follow-ups con IA
- Sistema de tareas y recordatorios
- Preparado para integración con IA
- Campo para razonamiento de IA

### Programa de Lealtad
- Acumulación automática de puntos
- Reglas de redención
- Historial de transacciones

## Próximos Pasos - Desarrollo Futuro

### Integración de IA
- Conectar con OpenAI o similar para sugerencias de follow-ups
- Análisis predictivo de conversión de leads
- Sugerencias inteligentes de productos/servicios

### Interfaz de Usuario
- Implementar vistas completas con Blade
- Dashboard interactivo con gráficos
- Pipeline visual tipo Kanban
- Formularios mejorados

### Notificaciones
- Email notifications
- Sistema de alertas
- Recordatorios de follow-ups

### Reportes
- Dashboard de ventas
- Reportes de conversión
- Análisis de pipeline
- Métricas de lealtad

### Integraciones
- Pasarelas de pago (Stripe, PayPal)
- Servicios de email (SendGrid, Mailgun)
- Calendarios (Google Calendar)
- WhatsApp Business API

## Testing

```bash
php artisan test
```

## Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Recrear base de datos
php artisan migrate:fresh --seed

# Ver rutas disponibles
php artisan route:list

# Crear nuevo usuario admin
php artisan tinker
>>> $user = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);
>>> $user->assignRole('admin');
```

## Tecnologías Utilizadas

- **Laravel 10**: Framework PHP
- **Laravel Breeze**: Autenticación
- **Laravel Sanctum**: API authentication
- **Spatie Permission**: Roles y permisos
- **DomPDF**: Generación de PDFs
- **Tailwind CSS**: Framework CSS
- **Blade**: Motor de plantillas

## Estructura del Proyecto

```
51Crm/
├── app/
│   ├── Http/Controllers/
│   │   ├── LeadController.php
│   │   ├── ClientController.php
│   │   ├── DealController.php
│   │   ├── QuoteController.php
│   │   ├── PaymentController.php
│   │   └── LoyaltyController.php
│   └── Models/
│       ├── Lead.php
│       ├── Client.php
│       ├── Deal.php
│       ├── PipelineStage.php
│       ├── Quote.php
│       ├── QuoteItem.php
│       ├── FollowUp.php
│       ├── Payment.php
│       └── LoyaltyPoint.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── RolesAndPermissionsSeeder.php
│       └── PipelineStagesSeeder.php
└── routes/
    └── web.php
```

## Licencia

Este proyecto es de código abierto.

## Soporte

Para soporte y preguntas, contactar al equipo de desarrollo.
