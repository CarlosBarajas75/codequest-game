# 🎮 CodeQuest API - Documentación Completa

## 📋 Resumen
CodeQuest es una API RESTful para un juego educativo de programación donde los usuarios resuelven desafíos de código progresivos. El sistema implementa mecánicas de gamificación como sistema de vidas, progreso por niveles, y ejecución de código en tiempo real.

## ✨ Funcionalidades Principales
- 🔐 **Autenticación completa** con Laravel Sanctum
- ❤️ **Sistema de vidas gamificado** (5 vidas máximo, recuperación automática)
- 🎯 **Ejecución de código en tiempo real** con Piston API
- 📊 **Seguimiento de progreso** individual por usuario
- 🏆 **Estadísticas detalladas** de rendimiento
- 🎮 **Múltiples tipos de desafíos** (puzzle, combat, unlock, etc.)
- 🔄 **Recuperación automática de vidas** cada hora
- 📈 **Métricas de completación** por nivel y desafío

## 🛡️ Autenticación
La API usa **Laravel Sanctum** para autenticación mediante tokens Bearer.

### Obtener Token
```bash
POST /api/login
Content-Type: application/json

{
  "email": "usuario@ejemplo.com",
  "password": "password123"
}
```

### Usar Token
```bash
Authorization: Bearer YOUR_TOKEN_HERE
```

## 🔗 Endpoints Principales

### 🔐 Autenticación

| Método | Endpoint | Descripción | Autenticado | Estado |
|--------|----------|-------------|-------------|---------|
| `POST` | `/api/register` | Registro de usuario con 5 vidas iniciales | ❌ | ✅ |
| `POST` | `/api/login` | Login y obtención de token | ❌ | ✅ |
| `GET` | `/api/me` | Datos del usuario actual | ✅ | ✅ |
| `POST` | `/api/logout` | Cerrar sesión y revocar token | ✅ | ✅ |

### 👤 Usuario

| Método | Endpoint | Descripción | Autenticado | Estado |
|--------|----------|-------------|-------------|---------|
| `GET` | `/api/user` | Perfil completo con estadísticas de progreso | ✅ | ✅ |

### 📚 Niveles

| Método | Endpoint | Descripción | Autenticado | Estado |
|--------|----------|-------------|-------------|---------|
| `GET` | `/api/levels` | Lista niveles con información de progreso | ✅ | ✅ |
| `GET` | `/api/levels/{id}` | Detalles de nivel con challenges y progreso | ✅ | ✅ |

### 🎯 Desafíos

| Método | Endpoint | Descripción | Autenticado | Estado |
|--------|----------|-------------|-------------|---------|
| `GET` | `/api/levels/{levelId}/challenges` | Lista de desafíos de un nivel específico | ✅ | ✅ |
| `GET` | `/api/challenges/{id}` | Detalles completos del desafío | ✅ | ✅ |
| `POST` | `/api/challenges/{id}/submit` | Enviar y ejecutar solución de código | ✅ | ✅ |

### 📊 Progreso

| Método | Endpoint | Descripción | Autenticado | Estado |
|--------|----------|-------------|-------------|---------|
| `GET` | `/api/progress` | Historial completo de progreso del usuario | ✅ | ✅ |
| `GET` | `/api/progress/{challengeId}` | Progreso específico de un desafío | ✅ | ✅ |
| `POST` | `/api/progress` | Crear/actualizar progreso manual | ✅ | ✅ |

### 🔧 Sistema

| Método | Endpoint | Descripción | Autenticado | Estado |
|--------|----------|-------------|-------------|---------|
| `GET` | `/api/health` | Estado de la API con estadísticas | ❌ | ✅ |

### 🛠️ Desarrollo (Solo entorno local)

| Método | Endpoint | Descripción | Autenticado | Estado |
|--------|----------|-------------|-------------|---------|
| `POST` | `/api/dev/reset-database` | Reinicia BD y ejecuta seeders | ❌ | ✅ |

## 🎮 Mecánicas de Juego Implementadas

### Sistema de Vidas
- ❤️ **Máximo**: 5 vidas por usuario
- ⏰ **Recuperación**: 1 vida cada hora automáticamente
- ❌ **Pérdida**: Al fallar un desafío (respuesta incorrecta)
- 🚫 **Restricción**: Sin vidas = no puede enviar código
- 🔄 **Middleware**: Recuperación automática en cada request
- 📊 **Tracking**: Campo `next_life_in` muestra minutos restantes

### Tipos de Desafíos Soportados
- `puzzle` - Acertijos lógicos y matemáticos
- `combat` - Enfrentamientos algorítmicos
- `search` - Algoritmos de búsqueda y ordenación
- `unlock` - Desbloqueo de secretos con lógica
- `logic` - Problemas de lógica pura

### Sistema de Progreso
- 📈 **Tracking individual** por usuario y desafío
- 🎯 **Conteo de intentos** por cada challenge
- ✅ **Estado de completación** persistente
- 💾 **Última submission** guardada para referencia
- 📊 **Estadísticas globales** de rendimiento

### Validaciones Implementadas
- 📝 **Código máximo**: 10,000 caracteres
- 🌐 **Lenguajes soportados**: `python`, `python3`, `javascript`, `java`, `cpp`, `c`
- ⏱️ **Timeout**: 30 segundos por ejecución
- 🔒 **Autenticación requerida** para todos los endpoints protegidos
- 🛡️ **Rate limiting** mediante middleware de vidas

## 📝 Ejemplos de Uso Completos

### 1. Flujo Completo de Registro y Login
```bash
# Registro de usuario
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Pérez",
    "email": "juan@ejemplo.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Respuesta esperada
{
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@ejemplo.com",
    "lives": 5
  },
  "token": "1|abcd1234..."
}
```

### 2. Obtener Perfil con Estadísticas
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN"

# Respuesta esperada
{
  "id": 1,
  "name": "Juan Pérez",
  "email": "juan@ejemplo.com",
  "lives": 4,
  "next_life_in": 45,
  "stats": {
    "completed_challenges": 2,
    "total_attempts": 5,
    "success_rate": 40.0
  }
}
```

### 3. Listar Niveles con Progreso
```bash
curl -X GET http://localhost:8000/api/levels \
  -H "Authorization: Bearer YOUR_TOKEN"

# Respuesta esperada
[
  {
    "id": 1,
    "title": "El Bosque de la Lógica",
    "description": "Explora el bosque resolviendo acertijos...",
    "language": "python",
    "difficulty": "easy",
    "progress_info": {
      "total_challenges": 3,
      "completed_challenges": 1,
      "completion_percentage": 33.33
    }
  }
]
```

### 4. Enviar Solución de Código (Funcionalidad Principal)
```bash
curl -X POST http://localhost:8000/api/challenges/1/submit \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "code": "a = 3\nb = 5\nresultado = a + b\nprint(resultado)",
    "language": "python3"
  }'

# Respuesta exitosa
{
  "success": true,
  "output": "8",
  "expected": "8",
  "lives_remaining": 4,
  "progress": {
    "id": 1,
    "challenge_id": 1,
    "completed": true,
    "attempts": 3,
    "last_submission": "a = 3\nb = 5\nresultado = a + b\nprint(resultado)",
    "success_output": "8"
  }
}

# Respuesta con error (pierde vida)
{
  "success": false,
  "output": "10",
  "expected": "8",
  "lives_remaining": 3,
  "progress": {
    "id": 1,
    "challenge_id": 1,
    "completed": false,
    "attempts": 4,
    "last_submission": "print(10)",
    "success_output": "10"
  }
}
```

### 5. Verificar Estado del Sistema
```bash
curl -X GET http://localhost:8000/api/health

# Respuesta
{
  "status": "ok",
  "timestamp": "2025-06-12T19:50:14.557525Z",
  "version": "1.0.0",
  "environment": "local",
  "database": {
    "users_count": 2,
    "levels_count": 1,
    "challenges_count": 3
  }
}
```

### 6. Obtener Historial de Progreso
```bash
curl -X GET http://localhost:8000/api/progress \
  -H "Authorization: Bearer YOUR_TOKEN"

# Respuesta
[
  {
    "id": 1,
    "challenge_id": 1,
    "completed": true,
    "attempts": 3,
    "last_submission": "print(a + b)",
    "success_output": "8",
    "created_at": "2025-06-12T19:00:00.000000Z",
    "updated_at": "2025-06-12T19:30:00.000000Z"
  }
]
```

## ⚡ Estados de Respuesta

### ✅ Éxito (200-299)
- `200` - Operación exitosa
- `201` - Recurso creado

### ❌ Error (400-499)
- `400` - Datos inválidos
- `401` - No autenticado
- `403` - Sin vidas / Sin permisos
- `404` - Recurso no encontrado
- `422` - Errores de validación

### 🔥 Error Servidor (500-599)
- `500` - Error interno del servidor

## 🗃️ Modelos de Datos Actualizados

### User (Usuario)
```json
{
  "id": 1,
  "name": "Juan Pérez",
  "email": "juan@ejemplo.com",
  "lives": 3,
  "next_life_in": 45,
  "created_at": "2025-06-12T10:00:00.000000Z",
  "updated_at": "2025-06-12T15:30:00.000000Z",
  "stats": {
    "completed_challenges": 5,
    "total_attempts": 12,
    "success_rate": 41.67
  }
}
```

### Level (Nivel)
```json
{
  "id": 1,
  "title": "El Bosque de la Lógica",
  "description": "Explora el bosque resolviendo acertijos de lógica y programación.",
  "language": "python",
  "difficulty": "easy",
  "created_at": "2025-06-12T10:00:00.000000Z",
  "updated_at": "2025-06-12T10:00:00.000000Z",
  "progress_info": {
    "total_challenges": 3,
    "completed_challenges": 1,
    "completion_percentage": 33.33
  },
  "challenges": [
    {
      "id": 1,
      "title": "Puzzle del Cofre",
      "type": "puzzle",
      "user_progress": {
        "completed": true,
        "attempts": 2,
        "last_submission": "print(a + b)"
      }
    }
  ]
}
```

### Challenge (Desafío)
```json
{
  "id": 1,
  "level_id": 1,
  "title": "Puzzle del Cofre",
  "description": "Suma dos números y abre el cofre mágico.",
  "type": "puzzle",
  "language": "python3",
  "starter_code": "a = 3\nb = 5\n# tu código aquí\nprint(resultado)",
  "input_variables": {"a": 3, "b": 5},
  "expected_output": "8",
  "created_at": "2025-06-12T10:00:00.000000Z",
  "updated_at": "2025-06-12T10:00:00.000000Z",
  "level": {
    "id": 1,
    "title": "El Bosque de la Lógica",
    "difficulty": "easy"
  }
}
```

### Progress (Progreso)
```json
{
  "id": 1,
  "user_id": 1,
  "challenge_id": 1,
  "completed": true,
  "attempts": 3,
  "last_submission": "a = 3\nb = 5\nresultado = a + b\nprint(resultado)",
  "success_output": "8",
  "created_at": "2025-06-12T10:00:00.000000Z",
  "updated_at": "2025-06-12T15:30:00.000000Z"
}
```

## 🏗️ Arquitectura Técnica

### Stack
- **Framework**: Laravel 12
- **BD**: SQLite (desarrollo) / PostgreSQL (producción)
- **Autenticación**: Laravel Sanctum
- **Ejecución de Código**: Piston API (emkc.org)

### Middleware
- `auth:sanctum` - Autenticación requerida
- `check.and.recover.lives` - Recuperación automática de vidas

### Validaciones
- Código máximo 10,000 caracteres
- Lenguajes soportados: `python`, `python3`, `javascript`, `java`, `cpp`, `c`

## 🔄 Flujo de Ejecución de Código (Piston API)

### Proceso Completo:
1. **Validación inicial**: Usuario autenticado y con vidas disponibles
2. **Preparación de variables**: Se extraen las `input_variables` del challenge
3. **Construcción del stdin**: Variables formateadas para el lenguaje específico
4. **Llamada a Piston API**: Envío del código con timeout de 30 segundos
5. **Procesamiento de respuesta**: Captura de stdout, stderr y manejo de errores
6. **Comparación de resultados**: Output vs expected_output (trim y case-sensitive)
7. **Actualización de estado**: Progreso, intentos, vidas y estadísticas
8. **Respuesta al cliente**: Resultado completo con métricas

### Detalles Técnicos de la Integración:
```php
// Estructura de la llamada a Piston API
$response = Http::timeout(30)->post('https://emkc.org/api/v2/piston/execute', [
    'language' => $request->language,  // ej: 'python3'
    'source' => $request->code,        // código del usuario
    'stdin' => $stdin                  // variables de entrada
]);

// Manejo de respuesta
$output = trim($response['run']['stdout'] ?? '');
$stderr = trim($response['run']['stderr'] ?? '');
$expected = trim($challenge->expected_output);
$success = $output === $expected;
```

## 🌐 Configuración de Piston API

### ¿Qué es Piston API?
Piston es un servicio gratuito de ejecución de código que soporta múltiples lenguajes de programación. Es mantenido por Engineer Man y la comunidad.

### URL del Servicio:
- **Endpoint principal**: `https://emkc.org/api/v2/piston/execute`
- **Documentación**: [https://piston.readthedocs.io/](https://piston.readthedocs.io/)
- **Repositorio**: [https://github.com/engineer-man/piston](https://github.com/engineer-man/piston)

### Lenguajes Soportados:
```bash
# Verificar lenguajes disponibles
curl https://emkc.org/api/v2/piston/runtimes

# Respuesta incluye:
- python (3.10.x)
- python3 (3.10.x) 
- javascript (Node.js 18.x)
- java (OpenJDK 17)
- cpp (GCC 12.x)
- c (GCC 12.x)
- y muchos más...
```

### Configuración en CodeQuest:

#### 1. **Variables de Entorno** (opcional)
```bash
# En .env (para configuración avanzada)
PISTON_API_URL=https://emkc.org/api/v2/piston/execute
PISTON_TIMEOUT=30
PISTON_MAX_OUTPUT=1000
```

#### 2. **Configuración en el Código**
```php
// En ChallengeController.php
try {
    $response = Http::timeout(30)  // 30 segundos timeout
                   ->post('https://emkc.org/api/v2/piston/execute', [
        'language' => $request->language,
        'source' => $request->code,
        'stdin' => $stdin,
        'args' => [],           // argumentos CLI (opcional)
        'compile_timeout' => 10, // timeout de compilación
        'run_timeout' => 5,     // timeout de ejecución
        'compile_memory_limit' => 128000000, // límite memoria compilación
        'run_memory_limit' => 128000000      // límite memoria ejecución
    ]);
} catch (\Exception $e) {
    // Manejo de errores de conectividad
    Log::error('Piston API failed', ['error' => $e->getMessage()]);
}
```

### Estructura de Request a Piston:
```json
{
  "language": "python3",
  "source": "a = 3\nb = 5\nprint(a + b)",
  "stdin": "",
  "args": [],
  "compile_timeout": 10,
  "run_timeout": 5
}
```

### Estructura de Response de Piston:
```json
{
  "language": "python3",
  "version": "3.10.0",
  "run": {
    "stdout": "8\n",
    "stderr": "",
    "code": 0,
    "signal": null
  },
  "compile": {
    "stdout": "",
    "stderr": "",
    "code": 0,
    "signal": null
  }
}
```

### Manejo de Errores Implementado:
1. **Timeout de conexión**: 30 segundos máximo
2. **Errores de sintaxis**: Capturados en stderr
3. **Errores de ejecución**: Manejados sin crash del sistema
4. **Límites de memoria**: Configurables por request
5. **Logging completo**: Errores registrados para debugging

### Alternativas a Piston (si necesario):
- **Judge0 API**: `https://api.judge0.com/`
- **Sphere Engine**: Servicio premium
- **HackerEarth API**: Con límites de rate
- **Compilex**: Para instalación local

## 🛠️ Instalación y Desarrollo

### Requisitos del Sistema:
- **PHP**: 8.2 o superior
- **Composer**: Para gestión de dependencias
- **SQLite**: Base de datos (incluida)
- **Conexión a Internet**: Para Piston API

### Instalación Paso a Paso:

```bash
# 1. Clonar proyecto
git clone [repository-url]
cd codequest-api

# 2. Instalar dependencias PHP
composer install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Configurar base de datos
# El archivo database.sqlite se crea automáticamente

# 5. Ejecutar migraciones
php artisan migrate

# 6. Ejecutar seeders (datos de ejemplo)
php artisan db:seed --class=LevelWithChallengesSeeder

# 7. Iniciar servidor de desarrollo
php artisan serve
# Servidor disponible en: http://localhost:8000
```

### Comandos Útiles de Desarrollo:

```bash
# Verificar estado de la API
curl http://localhost:8000/api/health

# Ejecutar demostración completa
php demo.php

# Verificar estructura de datos
php artisan debug:data

# Ejecutar pruebas automatizadas
php artisan test

# Resetear base de datos (desarrollo)
curl -X POST http://localhost:8000/api/dev/reset-database

# Ver rutas disponibles
php artisan route:list --except-vendor

# Limpiar y remigar BD
php artisan migrate:fresh --seed
```

### Configuración Avanzada:

#### Variables de Entorno (.env):
```bash
APP_NAME="CodeQuest API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de datos (SQLite por defecto)
DB_CONNECTION=sqlite
# DB_DATABASE=/ruta/absoluta/database.sqlite

# Para producción (PostgreSQL recomendado)
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=codequest
# DB_USERNAME=username
# DB_PASSWORD=password

# Configuración de Piston API (opcional)
PISTON_API_URL=https://emkc.org/api/v2/piston/execute
PISTON_TIMEOUT=30

# Sanctum (autenticación)
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
```

### Estructura de Archivos Principales:
```
codequest-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php      # Autenticación
│   │   │   ├── ChallengeController.php # Ejecución de código
│   │   │   ├── LevelController.php     # Gestión de niveles
│   │   │   ├── ProgressController.php  # Seguimiento progreso
│   │   │   ├── UserController.php      # Perfil de usuario
│   │   │   └── DevController.php       # Herramientas desarrollo
│   │   └── Middleware/
│   │       └── CheckAndRecoverLives.php # Sistema de vidas
│   └── Models/
│       ├── User.php                    # Modelo usuario
│       ├── Level.php                   # Modelo nivel
│       ├── Challenge.php               # Modelo desafío
│       └── Progress.php                # Modelo progreso
├── database/
│   ├── migrations/                     # Estructura BD
│   ├── seeders/                        # Datos ejemplo
│   └── database.sqlite                 # Base de datos
├── routes/
│   ├── api.php                         # Rutas API
│   └── console.php                     # Comandos Artisan
├── tests/
│   └── Feature/
│       └── CodeQuestAPITest.php        # Pruebas automatizadas
├── demo.php                            # Script demostración
├── API_DOCUMENTATION.md               # Esta documentación
└── PROJECT_SUMMARY.md                 # Resumen del proyecto
```

### Despliegue en Producción:

#### Preparación:
```bash
# Optimizar aplicación
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Instalar dependencias de producción
composer install --optimize-autoloader --no-dev

# Configurar variables de entorno
APP_ENV=production
APP_DEBUG=false
```

#### Servidor Web (Nginx ejemplo):
```nginx
server {
    listen 80;
    server_name codequest-api.com;
    root /var/www/codequest-api/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 🚀 Funcionalidades Adicionales Implementadas

### 🔄 Middleware Automático de Vidas
El sistema incluye un middleware que automáticamente:
- Verifica las vidas del usuario en cada request
- Recupera 1 vida cada hora si es necesario
- Actualiza el campo `next_life_at` dinámicamente
- No requiere configuración manual o cron jobs

### 📊 Estadísticas en Tiempo Real
- **Tasa de éxito** calculada automáticamente
- **Conteo de intentos** por desafío y global
- **Progreso porcentual** por nivel
- **Tiempo transcurrido** desde último intento

### 🛡️ Seguridad Implementada
- **Validación de entrada** robusta en todos los endpoints
- **Rate limiting** natural mediante sistema de vidas
- **Sanitización de código** antes del envío a Piston
- **Logging completo** de errores y actividad sospechosa
- **Tokens Sanctum** con expiración configurable

### 🔧 Herramientas de Debugging
- **Comando `debug:data`**: Verifica integridad de datos
- **Script `demo.php`**: Prueba completa de funcionalidades
- **Endpoint `/api/health`**: Monitoreo del sistema
- **Suite de tests**: Validación automatizada

### 📈 Escalabilidad
- **Base de datos optimizada** con índices apropiados
- **Queries eficientes** con relaciones Eloquent
- **Cache de rutas y configuración** para producción
- **Arquitectura modular** para futuras extensiones

## 🔗 Integración con Frontend

### Headers Requeridos:
```javascript
// Para requests autenticados
const headers = {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
};
```

### Ejemplo de Integración React:
```javascript
// Servicio de API
class CodeQuestAPI {
    constructor(baseURL = 'http://localhost:8000/api') {
        this.baseURL = baseURL;
        this.token = localStorage.getItem('codequest_token');
    }

    async submitCode(challengeId, code, language) {
        const response = await fetch(`${this.baseURL}/challenges/${challengeId}/submit`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ code, language })
        });
        return response.json();
    }

    async getUserProfile() {
        const response = await fetch(`${this.baseURL}/user`, {
            headers: { 'Authorization': `Bearer ${this.token}` }
        });
        return response.json();
    }
}
```

## 📋 Checklist de Funcionalidades

### ✅ Core Features Implementados:
- [x] Sistema de autenticación completo (registro/login/logout)
- [x] Gestión de usuarios con sistema de vidas
- [x] Creación y gestión de niveles y desafíos
- [x] Ejecución de código en tiempo real con Piston API
- [x] Sistema de progreso y estadísticas
- [x] Middleware de recuperación automática de vidas
- [x] Validaciones robustas y manejo de errores
- [x] API RESTful completamente funcional
- [x] Documentación completa
- [x] Suite de pruebas automatizadas

### 🔮 Futuras Mejoras Sugeridas:
- [ ] Sistema de logros y badges
- [ ] Leaderboards y rankings
- [ ] Chat y foros comunitarios
- [ ] Notificaciones push
- [ ] Análisis avanzado de código
- [ ] Hints y ayudas contextuales
- [ ] Modo competitivo entre usuarios
- [ ] Integración con GitHub/GitLab
- [ ] Certificaciones y diplomas
- [ ] Modo offline limitado

---

📅 **Última actualización**: 12 de diciembre de 2025  
🔗 **Repositorio**: [GitHub Repository URL]  
👨‍💻 **Desarrollado por**: CodeQuest Team  
📧 **Soporte**: support@codequest.com  
🌐 **Sitio web**: [https://codequest.com](https://codequest.com)

**🎯 ¡CodeQuest API está listo para transformar la educación en programación!** 🚀
