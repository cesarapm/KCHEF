# Authentication Session Persistence Fix

## Problema Original
Cuando recargabas la página `/dashboard`, te redirigía a `/login?redirect=/dashboard` aunque la sesión estuviera activa.

## Causa Raíz
1. **Caché agresivo (60 segundos)**: El composable `useAuthState.js` cacheable la verificación de autenticación durante 60 segundos
2. **Sin revalidación en recarga**: Cuando recargabas la página, el sistema usaba el caché incluso si la sesión del servidor había expirado
3. **Desajuste cliente-servidor**: El estado de autenticación en el navegador no se sincronizaba con la sesión del servidor

## Solución Implementada

### 1. Mejora en `useAuthState.js`
- Se agregó parámetro `forceRefresh` a `checkAuthentication()`
- Se añadió `cache: 'no-store'` a las peticiones fetch para evitar caché HTTP
- Se resetean los flags de caché en `logoutUser()` para garantizar validación en próximo login

### 2. Mejora en `router/index.js`
- Se detecta si es una **recarga de página** (cuando `from.name` es null)
- En recarga de página, se fuerza validación con `forceRefresh: true`
- En navegación interna entre rutas, se sigue usando caché de 60 segundos

## Comportamiento Ahora

### Recarga de página (F5 o escribir URL)
```
/dashboard (recarga)
  → from.name = null → forceRefresh = true
  → checkAuthentication(true) fuerza validación
  → fetch(/api/user) sin caché
  → Si sesión expiró: redirige a /login
  → Si sesión válida: accede a /dashboard
```

### Navegación interna (router-link o programática)
```
/home → /dashboard (click en enlace)
  → from.name = "Home" → forceRefresh = false
  → Usa caché de 60 segundos si está válido
  → Más rápido, menos peticiones
```

## Cambios de Código

### `resources/js/composables/useAuthState.js`
- Parámetro `forceRefresh` en `checkAuthentication()`
- Lógica: si `forceRefresh` es true, ignora el caché completamente
- Se añadió `cache: 'no-store'` a todas las peticiones fetch
- Se resetean flags de caché en logout

### `resources/js/router/index.js`
- Detecta recarga: `const isPageReload = !from.name`
- Pasa `forceRefresh` a `checkAuthentication(isPageReload)`
- En recarga: fuerza validación remota
- En navegación: usa caché local para mejor performance

## Pruebas Recomendadas

1. **Login e inmediatamente acceder a /dashboard**
   - ✓ Debe funcionar
   
2. **En /dashboard, presionar F5 (recarga)**
   - ✓ Debe validar con servidor y mantener sesión
   
3. **En /dashboard, escribir la URL en la barra y Enter**
   - ✓ Debe validar con servidor y mantener sesión
   
4. **Cierre de navegador > reapertura**
   - ✓ Si sesión expiró en servidor: redirige a login
   - ✓ Si sesión aún válida: accede directo a dashboard
   
5. **Logout + login inmediato**
   - ✓ Debe resetear cache y aceptar nuevo login

## Notas Técnicas

- **Session Driver**: Configurado en `config/session.php` como `database`
- **Session Lifetime**: 120 minutos (valor en `.env`)
- **Cookie**: Sesión viaja en cookie HTTP con `credentials: 'include'`
- **Cache Duration**: 60 segundos para navegación interna
- **HTTP Cache**: Se desactiva con `cache: 'no-store'` para evitar duplicar comportamiento

## Posibles Mejoras Futuras

1. Agregar verificación de sesión periódica (ej: cada 5 minutos en background)
2. Mostrar notificación cuando la sesión esté a punto de expirar
3. Agregar refresh automático de sesión antes de expirar
4. Implementar token refresh si se migra a JWT
