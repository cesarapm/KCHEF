import { ref } from 'vue'

// Estado compartido de autenticación
const isAuthenticated = ref(false)
const currentUser = ref(null)
const authToken = ref(localStorage.getItem('authToken') || null)
const csrfToken = ref(document.querySelector('meta[name="csrf-token"]')?.content || null)

// Cache para evitar llamadas repetidas
let lastCheckTime = 0
let isCheckingAuth = false
let authCheckPromise = null
const CACHE_DURATION = 60000 // 60 segundos de cache

// Flag para saber si ya hemos verificado al menos una vez
let hasCheckedOnce = false

// Helper para leer respuestas de forma segura
const safeReadJson = async (response) => {
  const contentType = response.headers.get('content-type') || ''

  if (!contentType.includes('application/json')) {
    const text = await response.text()
    if (text && text.trim().startsWith('<!DOCTYPE')) {
      return null // HTML response, auth failed
    }
    throw new Error('Invalid response format')
  }

  try {
    return await response.json()
  } catch (err) {
    console.error('JSON parse error:', err)
    return null
  }
}

// Helper para obtener headers con CSRF token
const getHeaders = (includeJson = true) => {
  const headers = {
    'Accept': 'application/json',
  }

  if (includeJson) {
    headers['Content-Type'] = 'application/json'
  }

  // Incluir CSRF token desde meta tag o variable de módulo
  const tokenElement = document.querySelector('meta[name="csrf-token"]')
  if (tokenElement?.content) {
    headers['X-CSRF-TOKEN'] = tokenElement.content
  } else if (csrfToken.value) {
    headers['X-CSRF-TOKEN'] = csrfToken.value
  }

  return headers
}

// Función para verificar autenticación actual con cache inteligente
export const checkAuthentication = async (forceRefresh = false) => {
  const now = Date.now()

//   console.log(`[AUTH CHECK] forceRefresh=${forceRefresh}, cached=${hasCheckedOnce && (now - lastCheckTime) < CACHE_DURATION}`)

  // Si forceRefresh está activado, saltarse caché completamente
  if (!forceRefresh && hasCheckedOnce && (now - lastCheckTime) < CACHE_DURATION) {
    // console.log(`[AUTH CHECK] Usando cache: isAuthenticated=${isAuthenticated.value}`)
    return isAuthenticated.value
  }

  // Si ya se está verificando, ESPERAR la promesa en lugar de retornar falso
  if (isCheckingAuth) {
    // console.log(`[AUTH CHECK] Ya se está verificando, esperando promesa...`)
    return authCheckPromise
  }

  isCheckingAuth = true
  authCheckPromise = performAuthCheck(now)

  try {
    return await authCheckPromise
  } finally {
    isCheckingAuth = false
    authCheckPromise = null
  }
}

async function performAuthCheck(now) {
  try {
    const headers = getHeaders()
    // console.log(`[AUTH CHECK] Headers:`, Object.keys(headers))

    // Si hay token, agregarlo al header
    if (authToken.value) {
      headers['Authorization'] = `Bearer ${authToken.value}`
    }

    // console.log(`[AUTH CHECK] Fetching /api/user con credentials: include`)
    const res = await fetch('/api/user', {
      method: 'GET',
      headers,
      credentials: 'include', // Importante para sesiones
      cache: 'no-store' // No usar caché HTTP
    })

    // console.log(`[AUTH CHECK] Response status: ${res.status}`)

    if (res.ok) {
      const userData = await safeReadJson(res)
      if (userData) {
        // console.log(`[AUTH CHECK] ✅ Autenticado como: ${userData.email}`)
        currentUser.value = userData
        isAuthenticated.value = true
        lastCheckTime = Date.now()
        hasCheckedOnce = true
        return true
      } else {
        // console.log(`[AUTH CHECK] ❌ No se pudo leer JSON`)
        isAuthenticated.value = false
        currentUser.value = null
        authToken.value = null
        localStorage.removeItem('authToken')
        lastCheckTime = Date.now()
        hasCheckedOnce = true
        return false
      }
    } else {
    //   console.log(`[AUTH CHECK] ❌ No autenticado (status: ${res.status})`)
      isAuthenticated.value = false
      currentUser.value = null
      authToken.value = null
      localStorage.removeItem('authToken')
      lastCheckTime = Date.now()
      hasCheckedOnce = true
      return false
    }
  } catch (err) {
    console.warn(`[AUTH CHECK] Error:`, err.message)
    isAuthenticated.value = false
    currentUser.value = null
    lastCheckTime = Date.now()
    hasCheckedOnce = true
    return false
  }
}

// Función para hacer logout - invalida cache e borra token
export const logoutUser = async () => {
  try {
    const headers = getHeaders()

    if (authToken.value) {
      headers['Authorization'] = `Bearer ${authToken.value}`
    }

    await fetch('/logout', {
      method: 'POST',
      headers,
      credentials: 'include', // Importante para sesiones
      cache: 'no-store'
    })

    isAuthenticated.value = false
    currentUser.value = null
    authToken.value = null
    lastCheckTime = 0 // Invalida cache
    hasCheckedOnce = false
    localStorage.removeItem('authToken')
    return true
  } catch (err) {
    console.error('Logout error:', err)
    // Aún así, limpiar el estado local
    isAuthenticated.value = false
    currentUser.value = null
    authToken.value = null
    localStorage.removeItem('authToken')
    return false
  }
}

// Función para hacer login - fuerza actualización después de login
export const loginUser = async (email, password) => {
  try {
    const response = await fetch('/login', {
      method: 'POST',
      headers: getHeaders(true),
      credentials: 'include', // Importante para sesiones
      cache: 'no-store',
      body: JSON.stringify({
        email,
        password,
      })
    })

    // console.log(`[LOGIN] Response status: ${response.status}`)

    if (response.ok) {
      const data = await safeReadJson(response)
      if (data && data.user) {
        // console.log(`[LOGIN] ✅ Login exitoso como: ${data.user.email}`)
        // Usar datos del login response directamente
        currentUser.value = data.user
        isAuthenticated.value = true

        // Actualizar CSRF token si viene en la respuesta
        if (data.csrf_token) {
          csrfToken.value = data.csrf_token
        }

        // Guardar token en localStorage si viene en la respuesta
        if (data.token) {
          authToken.value = data.token
          localStorage.setItem('authToken', data.token)
        }

        lastCheckTime = Date.now() // Cachear el resultado del login
        hasCheckedOnce = true
        return true
      }
    }

    // console.log(`[LOGIN] ❌ Login fallido`)
    isAuthenticated.value = false
    currentUser.value = null
    authToken.value = null
    localStorage.removeItem('authToken')
    return false
  } catch (error) {
    console.error('Login error:', error)
    isAuthenticated.value = false
    currentUser.value = null
    authToken.value = null
    localStorage.removeItem('authToken')
    return false
  }
}

// Exportar el estado reactivo
export const useAuthState = () => ({
  isAuthenticated,
  currentUser,
  authToken,
  csrfToken,
  checkAuthentication,
  logoutUser,
  loginUser
})
