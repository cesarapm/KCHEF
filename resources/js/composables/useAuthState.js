import { ref } from 'vue'

// Estado compartido de autenticación
const isAuthenticated = ref(false)
const currentUser = ref(null)
const authToken = ref(localStorage.getItem('authToken') || null)

// Cache para evitar llamadas repetidas
let lastCheckTime = 0
let isCheckingAuth = false
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

// Función para verificar autenticación actual con cache inteligente
export const checkAuthentication = async (forceRefresh = false) => {
  const now = Date.now()

  // Si hay token en localStorage, considerarse autenticado
  if (authToken.value && !forceRefresh && hasCheckedOnce && (now - lastCheckTime) < CACHE_DURATION) {
    return isAuthenticated.value
  }

  // Si ya se está verificando, esperar y retornar resultado actual
  if (isCheckingAuth) {
    return isAuthenticated.value
  }

  isCheckingAuth = true
  try {
    const headers = {
      'Accept': 'application/json',
    }

    // Si hay token, agregarlo al header
    if (authToken.value) {
      headers['Authorization'] = `Bearer ${authToken.value}`
    }

    const res = await fetch('/api/user', {
      method: 'GET',
      headers,
      credentials: 'include' // Importante para sesiones
    })

    if (res.ok) {
      const userData = await safeReadJson(res)
      if (userData) {
        currentUser.value = userData
        isAuthenticated.value = true
        lastCheckTime = now
        hasCheckedOnce = true
        return true
      } else {
        isAuthenticated.value = false
        currentUser.value = null
        authToken.value = null
        localStorage.removeItem('authToken')
        lastCheckTime = now
        hasCheckedOnce = true
        return false
      }
    } else {
      // 401 es esperado - solo establecer lastCheckTime para cachear
      isAuthenticated.value = false
      currentUser.value = null
      authToken.value = null
      localStorage.removeItem('authToken')
      lastCheckTime = now
      hasCheckedOnce = true
      return false
    }
  } catch (err) {
    console.warn('Auth check failed:', err.message)
    isAuthenticated.value = false
    currentUser.value = null
    lastCheckTime = now
    hasCheckedOnce = true
    return false
  } finally {
    isCheckingAuth = false
  }
}

// Función para hacer logout - invalida cache e borra token
export const logoutUser = async () => {
  try {
    const headers = {
      'Content-Type': 'application/json',
    }

    if (authToken.value) {
      headers['Authorization'] = `Bearer ${authToken.value}`
    }

    await fetch('/logout', {
      method: 'POST',
      headers,
      credentials: 'include' // Importante para sesiones
    })

    isAuthenticated.value = false
    currentUser.value = null
    authToken.value = null
    lastCheckTime = 0 // Invalida cache
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
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      credentials: 'include', // Importante para sesiones
      body: JSON.stringify({
        email,
        password,
      })
    })

    if (response.ok) {
      const data = await safeReadJson(response)
      if (data && data.user) {
        // Usar datos del login response directamente
        currentUser.value = data.user
        isAuthenticated.value = true

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
  checkAuthentication,
  logoutUser,
  loginUser
})
