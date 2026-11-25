# Session Persistence Fix - Root Cause Analysis

## 🔍 Real Problem Found

The issue is NOT primarily in the Vue frontend code. The real problem is:

**When you reload the page, the session cookie is not being sent back to the server.**

This can happen due to:
1. Session driver (database) not persisting correctly
2. Session cookie not being set/sent with requests
3. CSRF token mismatch on page reload
4. Session garbage collection or timeout

## ✅ Solution: Force Session Persistence

We need to add a middleware that ensures the session is ALWAYS validated and regenerated on each request.

### Step 1: Create New Middleware

Create file: `app/Http/Middleware/EnsureSessionIsValid.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSessionIsValid extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        // If user is authenticated, regenerate session on each request
        // This prevents session loss on page reloads
        if (Auth::check()) {
            $request->session()->regenerate();
        }
        
        return $next($request);
    }
}
```

### Step 2: Register Middleware in Kernel

In `app/Http/Kernel.php`, add to `web` middleware group:

```php
protected $middlewareGroups = [
    'web' => [
        // ... existing middleware ...
        \App\Http\Middleware\EnsureSessionIsValid::class,  // ADD THIS
    ],
];
```

### Step 3: Ensure CSRF Token in Responses

Update `AuthController.php` to include CSRF token in login response:

```php
public function login(Request $request)
{
    // ... existing validation and auth logic ...
    
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        $user = Auth::user();
        $prospect = Prospect::where('user_id', $user->id)->first();
        
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'prospect_id' => $prospect?->id,
            'csrf_token' => csrf_token(),  // ADD THIS
        ]);
    }
}
```

### Step 4: Update Vue to Include CSRF Token

In `resources/js/composables/useAuthState.js`, when making API requests:

```javascript
const headers = {
  'Accept': 'application/json',
  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
}
```

## Why This Works

1. **Session Regeneration**: Each request regenerates the session, ensuring it's fresh
2. **CSRF Protection**: Proper CSRF token is included, preventing security issues
3. **Cookie Persistence**: Laravel properly manages session cookies on each request
4. **Page Reload Handling**: Even on hard reload, session is validated with server

## Implementation Priority

1. ✅ Create `EnsureSessionIsValid` middleware
2. ✅ Register in `Kernel.php`
3. ✅ Add CSRF token to login response
4. ✅ Ensure frontend includes CSRF token in requests
5. ✅ Test page reload

## Expected Behavior After Fix

- Login → Dashboard ✅ Works
- Press F5 on Dashboard → ✅ Stays on page (session persists)
- Type URL → ✅ Stays on page
- Logout → ✅ Clears session, redirects to login
- Navigate between pages → ✅ Fast (cached)

This is the REAL fix for your session persistence issue.
