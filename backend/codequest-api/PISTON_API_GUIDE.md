# 🔧 Guía Completa de Configuración de Piston API

## 📋 ¿Qué es Piston API?

**Piston** es un servicio gratuito de ejecución de código que permite ejecutar código en múltiples lenguajes de programación de forma segura y aislada. Es la solución perfecta para plataformas educativas como CodeQuest.

### ✨ Características Principales:
- 🆓 **Completamente gratuito**
- 🌐 **Sin autenticación requerida**
- 🔒 **Ejecución segura en contenedores aislados**
- ⚡ **Respuesta rápida** (típicamente < 3 segundos)
- 🚀 **50+ lenguajes soportados**
- 📡 **API REST simple**
- 🛡️ **Rate limiting justo** (no documentado oficialmente)

## 🌐 Información del Servicio

### URLs Importantes:
- **API Principal**: `https://emkc.org/api/v2/piston/execute`
- **Lista de Runtimes**: `https://emkc.org/api/v2/piston/runtimes`
- **Documentación**: [https://piston.readthedocs.io/](https://piston.readthedocs.io/)
- **GitHub**: [https://github.com/engineer-man/piston](https://github.com/engineer-man/piston)
- **Discord**: [Engineer Man Discord](https://discord.gg/engineerman)

## 🔍 Verificar Disponibilidad

### 1. Test Básico de Conectividad:
```bash
# Verificar que el servicio responde
curl -X GET https://emkc.org/api/v2/piston/runtimes

# Respuesta esperada: JSON con lista de lenguajes disponibles
```

### 2. Test de Ejecución Simple:
```bash
curl -X POST https://emkc.org/api/v2/piston/execute \
  -H "Content-Type: application/json" \
  -d '{
    "language": "python3",
    "source": "print(\"Hello, World!\")"
  }'

# Respuesta esperada:
# {
#   "language": "python3",
#   "version": "3.10.0",
#   "run": {
#     "stdout": "Hello, World!\n",
#     "stderr": "",
#     "code": 0,
#     "signal": null
#   }
# }
```

## 🛠️ Configuración en CodeQuest

### 1. Variables de Entorno (`.env`):
```bash
# Configuración básica de Piston
PISTON_API_URL=https://emkc.org/api/v2/piston/execute
PISTON_RUNTIMES_URL=https://emkc.org/api/v2/piston/runtimes
PISTON_TIMEOUT=30
PISTON_MAX_RETRIES=3

# Configuración avanzada (opcional)
PISTON_COMPILE_TIMEOUT=10
PISTON_RUN_TIMEOUT=5
PISTON_MEMORY_LIMIT=128000000
PISTON_MAX_OUTPUT_SIZE=1024
```

### 2. Configuración en el Código (`ChallengeController.php`):

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChallengeController extends Controller
{
    private function executePistonCode($code, $language, $stdin = '')
    {
        $pistonUrl = config('app.piston_url', 'https://emkc.org/api/v2/piston/execute');
        $timeout = config('app.piston_timeout', 30);
        
        try {
            $response = Http::timeout($timeout)
                           ->retry(3, 1000) // 3 reintentos con 1s de delay
                           ->post($pistonUrl, [
                'language' => $language,
                'source' => $code,
                'stdin' => $stdin,
                'args' => [],
                'compile_timeout' => 10,  // 10 segundos para compilar
                'run_timeout' => 5,       // 5 segundos para ejecutar
                'compile_memory_limit' => 128000000, // 128MB
                'run_memory_limit' => 128000000      // 128MB
            ]);

            if (!$response->successful()) {
                throw new \Exception("Piston API returned status: " . $response->status());
            }

            return $response->json();

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Piston API connection failed', [
                'error' => $e->getMessage(),
                'language' => $language,
                'code_length' => strlen($code)
            ]);
            
            throw new \Exception('No se pudo conectar al servicio de ejecución de código. Intenta más tarde.');
            
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('Piston API request failed', [
                'error' => $e->getMessage(),
                'response' => $e->response?->body()
            ]);
            
            throw new \Exception('Error al ejecutar el código. Verifica tu sintaxis.');
            
        } catch (\Exception $e) {
            Log::error('Piston API general error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
```

### 3. Configuración Avanzada en `config/services.php`:
```php
<?php

return [
    // ... otras configuraciones
    
    'piston' => [
        'url' => env('PISTON_API_URL', 'https://emkc.org/api/v2/piston/execute'),
        'runtimes_url' => env('PISTON_RUNTIMES_URL', 'https://emkc.org/api/v2/piston/runtimes'),
        'timeout' => env('PISTON_TIMEOUT', 30),
        'max_retries' => env('PISTON_MAX_RETRIES', 3),
        'compile_timeout' => env('PISTON_COMPILE_TIMEOUT', 10),
        'run_timeout' => env('PISTON_RUN_TIMEOUT', 5),
        'memory_limit' => env('PISTON_MEMORY_LIMIT', 128000000),
        'max_output_size' => env('PISTON_MAX_OUTPUT_SIZE', 1024),
        
        // Lenguajes permitidos (seguridad)
        'allowed_languages' => [
            'python3', 'python', 'javascript', 'node',
            'java', 'cpp', 'c', 'go', 'rust', 'php',
            'ruby', 'swift', 'kotlin', 'csharp'
        ]
    ]
];
```

## 🚀 Lenguajes Soportados

### Verificar Lenguajes Disponibles:
```bash
curl https://emkc.org/api/v2/piston/runtimes | jq '.[] | {language: .language, version: .version}'
```

### Lenguajes Principales para CodeQuest:
```json
{
  "python3": "3.10.0",
  "javascript": "18.15.0", 
  "java": "17.0.0",
  "cpp": "12.2.0",
  "c": "12.2.0",
  "go": "1.19.0",
  "rust": "1.68.0",
  "php": "8.2.0"
}
```

## 📊 Estructura de Request/Response

### Request a Piston:
```json
{
  "language": "python3",
  "source": "x = int(input())\ny = int(input())\nprint(x + y)",
  "stdin": "5\n3",
  "args": [],
  "compile_timeout": 10,
  "run_timeout": 5,
  "compile_memory_limit": 128000000,
  "run_memory_limit": 128000000
}
```

### Response de Piston:
```json
{
  "language": "python3",
  "version": "3.10.0",
  "compile": {
    "stdout": "",
    "stderr": "",
    "code": 0,
    "signal": null
  },
  "run": {
    "stdout": "8\n",
    "stderr": "",
    "code": 0,
    "signal": null
  }
}
```

## 🛡️ Limitaciones y Consideraciones

### Limitaciones del Servicio:
- **Tiempo de ejecución**: Máximo ~10 segundos por request
- **Memoria**: Limitada a ~128MB por defecto
- **Tamaño de código**: Máximo ~100KB
- **Operaciones de red**: Bloqueadas por seguridad
- **Acceso a archivos**: Limitado al sistema temporal
- **Rate limiting**: No documentado, pero existe

### Mejores Prácticas:
1. **Timeout apropiado**: 30 segundos máximo
2. **Reintentos**: Máximo 3 intentos con delay
3. **Validación previa**: Verificar sintaxis básica antes del envío
4. **Logging completo**: Registrar errores para debugging
5. **Fallback graceful**: Manejar errores sin crash del sistema

## 🔧 Troubleshooting

### Problemas Comunes:

#### 1. **Error de Conectividad**:
```bash
# Verificar conectividad a internet
ping emkc.org

# Test directo con curl
curl -v https://emkc.org/api/v2/piston/runtimes
```

#### 2. **Timeout de Ejecución**:
```php
// Aumentar timeout en Laravel
Http::timeout(60)->post(...)

// Verificar timeout de PHP
ini_get('max_execution_time')
```

#### 3. **Error de Lenguaje No Soportado**:
```bash
# Verificar lenguajes disponibles
curl https://emkc.org/api/v2/piston/runtimes | grep -i "python"
```

#### 4. **Rate Limiting**:
```php
// Implementar delays entre requests
sleep(1); // 1 segundo entre llamadas

// Cache de resultados para evitar llamadas repetidas
Cache::remember("piston_result_{$hash}", 3600, function() {
    return $this->executePistonCode($code, $language);
});
```

## 🌐 Alternativas a Piston

### Si Piston no está disponible:

#### 1. **Judge0 API** (Freemium):
```bash
# URL: https://judge0-ce.p.rapidapi.com/
# Requiere API key
curl -X POST https://judge0-ce.p.rapidapi.com/submissions \
  -H "X-RapidAPI-Key: YOUR_API_KEY"
```

#### 2. **HackerEarth API** (Freemium):
```bash
# URL: https://api.hackerearth.com/v4/
# Requiere registro y API key
```

#### 3. **Sphere Engine** (Premium):
```bash
# URL: https://sphere-engine.com/
# Servicio de pago con alta disponibilidad
```

#### 4. **Instalación Local de Piston**:
```bash
# Clonar repositorio
git clone https://github.com/engineer-man/piston.git
cd piston

# Ejecutar con Docker
docker-compose up -d

# API local disponible en http://localhost:2000
```

## 📈 Monitoreo y Métricas

### Implementar Métricas de Piston:
```php
// En un Service Provider o Middleware
class PistonMetrics
{
    public static function recordExecution($language, $duration, $success)
    {
        Log::info('Piston execution', [
            'language' => $language,
            'duration_ms' => $duration,
            'success' => $success,
            'timestamp' => now()
        ]);
        
        // Opcional: enviar a sistema de métricas
        // Prometheus, New Relic, etc.
    }
}
```

### Dashboard de Monitoreo:
```sql
-- Queries útiles para análisis
SELECT language, COUNT(*) as executions, AVG(duration_ms) as avg_duration
FROM piston_logs 
WHERE created_at > NOW() - INTERVAL 24 HOUR
GROUP BY language;

SELECT success, COUNT(*) as count
FROM piston_logs 
WHERE created_at > NOW() - INTERVAL 1 HOUR
GROUP BY success;
```

---

## 🎯 Conclusión

Piston API es una solución excelente para CodeQuest porque:
- ✅ **Sin costos** ni autenticación
- ✅ **Fácil integración** con Laravel
- ✅ **Múltiples lenguajes** soportados
- ✅ **Ejecución segura** en contenedores
- ✅ **Comunidad activa** y soporte

Con esta configuración, CodeQuest puede ejecutar código de usuarios de forma segura y eficiente. El sistema está preparado para manejar errores gracefully y proporcionar una experiencia de usuario fluida.

**🚀 ¡Tu plataforma de programación educativa está lista para despegar!**
