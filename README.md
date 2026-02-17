# Sistema de Gestión de Coolers

Sistema integral de gestión para cámaras frigoríficas (coolers) desarrollado con Laravel. Permite administrar frutas, embarcaciones, recepciones, conservación, cobros y múltiples aspectos operativos de coolers.

## Características

- **Gestión de Coolers**: Administración completa de cámaras frigoríficas
- **Control de Frutas**: Registro y seguimiento de variedades de frutas
- **Embarcaciones**: Gestión de transporte y embarques
- **Recepciones**: Control de ingreso y detalle de productos
- **Conservación**: Monitoreo de procesos de preservación
- **Cobranzas**: Sistema de facturación y cobros
- **Comercializadora**: Gestión de comercialización
- **Tarifas**: Control de precios y presentaciones
- **Usuarios**: Sistema de permisos y roles personalizados
- **Reportes**: Múltiples vistas y análisis de datos

## Requisitos

- PHP 8.1 o superior
- Composer
- MySQL 8.0 o superior
- Node.js 16+ (para Vite/assets)
- XAMPP/Apache

## Instalación

1. **Clonar el repositorio**
   ```bash
   git clone <repository-url>
   cd coolers
   ```

2. **Instalar dependencias PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias de Node**
   ```bash
   npm install
   ```

4. **Configurar archivo .env**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurar base de datos**
   - Editar `.env` con credenciales de MySQL
   - Importar: `bd_cooleroficial (10).sql`

6. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

7. **Compilar assets**
   ```bash
   npm run build
   ```

## Uso

### Modo desarrollo
```bash
# Terminal 1: Iniciar servidor Laravel
php artisan serve

# Terminal 2: Compilar assets en tiempo real
npm run dev
```

### Modo producción
```bash
php artisan optimize
npm run build
```

## Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/     # Controladores de la aplicación
│   └── Middleware/      # Middlewares personalizados
├── Models/              # Modelos Eloquent
├── Services/            # Servicios de negocio
├── Traits/              # Traits reutilizables
└── Helpers/             # Funciones auxiliares
```

## Modelos Principales

- `Cooler` - Cámaras frigoríficas
- `Fruta` - Variedades de frutas
- `Embarcacion` - Transporte de productos
- `Recepcion` - Entrada de productos
- `Contrato` - Contratos comerciales
- `Cobranza` - Facturación y pagos
- `User` - Usuarios del sistema
- `Permission` - Permisos de acceso
- `RolUsuario` - Roles personalizados

## Sistema de Permisos

El proyecto implementa un sistema de permisos basado en roles:
- Asignar permisos a usuarios
- Control granular de acceso
- Ver ejemplo en: `EJEMPLO_CONTROLADOR_CON_PERMISOS.php`

## Base de Datos

El modelo de datos completo se encuentra documentado en `modelodedatos`.

Para restaurar la base de datos:
```bash
mysql -u root -p coolers < bd_cooleroficial\ \(10\).sql
```

## Testing

```bash
# Ejecutar pruebas
php artisan test

# Con cobertura
php artisan test --coverage
```

## Contribución

Este proyecto fue desarrollado como plantilla base adaptable. Los contribuyentes son bienvenidos para mejoras y extensiones.

## Notas de Desarrollo

- Consultar documentación de Laravel en [laravel.com](https://laravel.com)
- Sistema de permisos: Ver `app/Traits/HasPermissions.php`
- Servicios de negocio: `app/Services/`
- Helper de permisos: `app/Helpers/PermissionHelper.php`

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
