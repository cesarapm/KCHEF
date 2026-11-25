# Session Authentication - Testing Guide

## Quick Test (5 minutes)

### 1. Fresh Login Test
```bash
1. Open browser DevTools (F12)
2. Go to http://127.0.0.1:8000/login
3. Clear all cookies/storage (Ctrl+Shift+Delete)
4. Login with valid credentials
5. ✅ Should be redirected to /dashboard
```

### 2. Page Reload Test (The Main Fix)
```bash
1. While on /dashboard, press F5
2. ⚠️ WITHOUT FIX: Redirect to /login?redirect=/dashboard
3. ✅ WITH FIX: Stay on /dashboard (page reloads)
```

### 3. Direct URL Test
```bash
1. While on /dashboard
2. Click address bar
3. Press Enter (no URL change)
4. ⚠️ WITHOUT FIX: Redirect to /login?redirect=/dashboard
5. ✅ WITH FIX: Stay on /dashboard
```

## Detailed Test Suite (15 minutes)

### Test A: Initial Authentication ✓
**Objective**: Verify login sets up session correctly

**Steps**:
1. Clear all browser storage: Ctrl+Shift+Delete
2. Navigate to http://127.0.0.1:8000
3. Click login link or go to `/login`
4. Enter valid email and password
5. Submit form

**Expected Result**:
- ✅ Redirected to `/dashboard`
- ✅ User data displayed (name, email)
- ✅ No console errors
- ✅ Session cookie present in DevTools → Application → Cookies

**Check in DevTools**:
```javascript
// Open Console and run:
document.cookie
// Should show: chef_adams_session=[token]
```

---

### Test B: F5 Page Reload ✓
**Objective**: Verify page reload doesn't break session

**Steps**:
1. Successfully logged in and on `/dashboard`
2. Press F5 (refresh)
3. Wait for page to load

**Expected Result**:
- ✅ Page reloads without redirect
- ✅ Dashboard content visible
- ✅ User still logged in
- ✅ No login page appears

**Network Tab Check**:
- Should see GET `/api/user` returning 200 OK
- Response includes user data

---

### Test C: Ctrl+R Soft Refresh ✓
**Objective**: Test harder refresh scenario

**Steps**:
1. On `/dashboard`
2. Press Ctrl+R (soft refresh)
3. Wait for load

**Expected Result**:
- ✅ Same as Test B - stays on dashboard

---

### Test D: Direct URL Entry ✓
**Objective**: Test typing URL directly in address bar

**Steps**:
1. On `/dashboard`
2. Click address bar (Ctrl+L)
3. Press Backspace or select all
4. Press Enter (or type same URL and Enter)
5. Wait for load

**Expected Result**:
- ✅ Stays on `/dashboard`
- ✅ No redirect to login
- ✅ Dashboard loads normally

---

### Test E: Internal Navigation (Performance) ✓
**Objective**: Verify cache works for internal navigation

**Steps**:
1. On `/dashboard`
2. Click link to go to `/` (home)
3. Click link back to `/dashboard`

**Expected Result**:
- ✅ Navigation is fast (cached auth)
- ✅ No loading spinner
- ✅ Permission check happens within ~1ms

**Network Tab Check**:
- Should NOT see `/api/user` request (uses cache)
- Only when cache expires (after 60s) should request be made

---

### Test F: Cache Expiration ✓
**Objective**: Verify cache expires and re-validates

**Steps**:
1. Login and go to `/dashboard`
2. Wait 70+ seconds
3. Click link to another page
4. Click link back to `/dashboard`

**Expected Result**:
- ✅ Access permitted
- ✅ Fresh validation with server occurs (cache expired)

**Network Tab Check**:
- Should see `/api/user` request (cache expired after 60s)
- Should return 200 OK with fresh user data

---

### Test G: Logout and Re-Login ✓
**Objective**: Verify cache doesn't persist across logout

**Steps**:
1. On `/dashboard`
2. Click logout button
3. Wait for redirect to `/login`
4. Login again with same or different account

**Expected Result**:
- ✅ Logout clears all state
- ✅ Login page appears
- ✅ New login succeeds
- ✅ Access to `/dashboard` works with new session

**Console Check**:
```javascript
// After logout
localStorage.getItem('authToken')
// Should be: null

// After re-login
localStorage.getItem('authToken')
// Should be: null or new token value
```

---

### Test H: Browser Close/Reopen ✓
**Objective**: Verify session persists across browser restart

**Steps**:
1. Login and access `/dashboard`
2. Close entire browser (not just tab)
3. Reopen browser
4. Navigate to http://127.0.0.1:8000/dashboard

**Expected Result**:
- **If session still valid (< 120 min)**: ✅ Access dashboard directly
- **If session expired (> 120 min)**: ✅ Redirect to login

**Note**: Session lifetime is 120 minutes (from `config/session.php`)

---

### Test I: Expired Session Handling ✓
**Objective**: Verify behavior when server session expires

**Prerequisites**:
- Session lifetime must be short (modify `SESSION_LIFETIME` in .env to 1 minute)
- Or manually delete the database session record

**Steps**:
1. Login successfully
2. Wait for session to expire (or delete session in database)
3. Try to access `/dashboard` or press F5

**Expected Result**:
- ✅ Redirected to `/login?redirect=/dashboard`
- ✅ Error message or redirect, not blank page
- ✅ Can login again successfully

---

### Test J: Multiple Tabs ✓
**Objective**: Verify consistency across multiple tabs

**Steps**:
1. Login in Tab 1
2. Open same URL in Tab 2 (`http://127.0.0.1:8000/dashboard`)
3. Logout in Tab 1
4. Try to access content in Tab 2

**Expected Result**:
- **Tab 1**: Successfully logged out
- **Tab 2**: 
  - **Immediately**: May show cached content (if within 60s)
  - **After F5**: ✅ Redirected to login (session expired)
  - **After 60s cache expires**: ✅ Redirected to login

---

## Debugging Tips

### View Console Logs
```javascript
// Open Browser DevTools → Console
// Look for messages like:
// - "Auth check error: ..."
// - "Login error: ..."
// - POST /login responses
// - GET /api/user responses
```

### Check Network Requests
```
DevTools → Network Tab
Filters: XHR
Look for:
  - POST /login (200 OK when successful)
  - GET /api/user (200 OK when authenticated)
  - 401 responses when not authenticated
```

### Check Session Cookie
```
DevTools → Application → Cookies → http://127.0.0.1:8000
Look for:
  - Name: chef_adams_session
  - Value: [long token string]
  - HttpOnly: ✓ (should be checked)
  - Secure: (depends on HTTPS)
  - SameSite: Lax (from config/session.php)
```

### Manual Cache Testing
```javascript
// In Browser Console:
import { checkAuthentication } from '/resources/js/composables/useAuthState.js'

// Test without force refresh (uses cache)
await checkAuthentication(false)
// Should return true quickly if recently checked

// Test with force refresh (server validation)
await checkAuthentication(true)
// Should make HTTP request to /api/user
```

---

## Expected Behavior Summary

| Scenario | Before Fix | After Fix |
|----------|-----------|-----------|
| Press F5 on /dashboard | ❌ Redirect to login | ✅ Stay on dashboard |
| Type URL in address bar | ❌ Redirect to login | ✅ Stay on dashboard |
| Click internal link | ✅ Works (cached) | ✅ Works (cached) |
| Session expires on server | ❓ Unclear | ✅ Redirect to login |
| Logout + Login | ❌ May cache old | ✅ Fresh session |
| Close browser + reopen | ✅ Works | ✅ Works |
| Multiple tabs | ❌ May desync | ✅ Consistent |

---

## Performance Expectations

| Operation | Latency | Note |
|-----------|---------|------|
| Login → Dashboard | 800ms | Redirect delay |
| Press F5 on dashboard | 150-250ms | Server validation |
| Click internal link | <50ms | Cache hit |
| First link after 60s | 100-200ms | Cache expired, fresh check |
| Logout | <300ms | Clear state |

---

## If Tests Fail

### Redirect Loop on /dashboard
**Problem**: Keeps redirecting to login
**Solutions**:
1. Check database session table: `SELECT COUNT(*) FROM sessions;`
2. Verify Laravel logs: `tail -f storage/logs/laravel.log`
3. Clear cookies and try fresh login
4. Check if SESSION_DRIVER is 'database' in config/session.php

### Stuck on /login after login
**Problem**: Login button doesn't redirect
**Solutions**:
1. Check browser console for errors
2. Check Network tab for `/login` response (should be 200)
3. Verify user credentials are correct
4. Check if /api/user endpoint is accessible

### Cache not working
**Problem**: Always makes server requests
**Solutions**:
1. Wait 1-2 seconds between navigation
2. Check CACHE_DURATION value (should be 60000ms)
3. Verify localStorage has 'authToken' set (optional)
4. Check browser console for auth check errors

### Session expires immediately
**Problem**: Can't stay logged in
**Solutions**:
1. Check SESSION_LIFETIME in config/session.php
2. Check if cookies are being set (DevTools → Application → Cookies)
3. Check if Same-Site cookie policy is too strict
4. Verify database can store sessions (PRAGMA table_info(sessions))

---

## Success Criteria

You'll know the fix is working when:

✅ Login → /dashboard (works)  
✅ Press F5 on /dashboard (stays)  
✅ Type URL in bar (stays)  
✅ Click internal links (fast)  
✅ Session expires → redirect (proper)  
✅ Logout → Login (fresh)  
✅ No console errors  
✅ No redirect loops  

**Congratulations! The authentication system is working correctly.** 🎉
