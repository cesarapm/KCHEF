<template>
  <div style="
    min-height: 100vh;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    padding: 40px 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  ">
    <!-- HEADER -->
    <div style="
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 40px;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    ">
      <div>
        <h1 style="
          margin: 0;
          font-size: 28px;
          font-weight: 700;
          color: #dc2626;
        ">
          🍽️ My Dashboard
        </h1>
        <p style="
          margin: 8px 0 0 0;
          color: #666;
          font-size: 14px;
        ">
          Welcome, {{ user?.name || 'User' }}
        </p>
      </div>

      <button
        @click="logout"
        style="
          padding: 12px 24px;
          background: #dc2626;
          color: white;
          border: none;
          border-radius: 8px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
        "
        @mouseenter="$event.target.style.backgroundColor = '#b91c1c'"
        @mouseleave="$event.target.style.backgroundColor = '#dc2626'"
      >
        🚪 Sign Out
      </button>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div style="
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
    ">
      <!-- TARJETA: PERFIL -->
      <div style="
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
      "
      @mouseenter="$event.currentTarget.style.transform = 'translateY(-5px)'; $event.currentTarget.style.boxShadow = '0 12px 30px rgba(0, 0, 0, 0.15)'"
      @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)'"
      >
        <h2 style="
          margin: 0 0 20px 0;
          font-size: 20px;
          color: #dc2626;
          display: flex;
          align-items: center;
          gap: 10px;
        ">
          👤 My Profile
        </h2>
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <p style="margin: 0; color: #333;"><strong>Email:</strong> {{ user?.email }}</p>
          <p style="margin: 0; color: #333;"><strong>Name:</strong> {{ user?.name }}</p>
          <p style="margin: 0; color: #666; font-size: 13px;">Member since {{ new Date().toLocaleDateString('en-US') }}</p>
        </div>
      </div>

      <!-- TARJETA: ONBOARDING -->
      <div style="
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
      "
      @mouseenter="$event.currentTarget.style.transform = 'translateY(-5px)'; $event.currentTarget.style.boxShadow = '0 12px 30px rgba(0, 0, 0, 0.15)'"
      @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)'"
      >
        <h2 style="
          margin: 0 0 20px 0;
          font-size: 20px;
          color: #dc2626;
          display: flex;
          align-items: center;
          gap: 10px;
        ">
          🍽️ Cooking Profile
        </h2>
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <p v-if="loading" style="margin: 0; color: #666;">⏳ Loading...</p>
          <div v-else>
            <p style="margin: 0; color: #333;"><strong>Status:</strong>
              <span v-if="onboarding?.status === 'completado'" style="color: #10b981; font-weight: bold;">✅ Completed</span>
              <span v-else style="color: #f97316; font-weight: bold;">⏳ Pending</span>
            </p>
            <p v-if="onboarding?.completed_at" style="margin: 8px 0 0 0; color: #666; font-size: 13px;">
              Completed: {{ formatDate(onboarding.completed_at) }}
            </p>
            <button
              v-if="onboarding?.status !== 'completado'"
              @click="goToOnboarding"
              style="
                width: 100%;
                margin-top: 15px;
                padding: 10px 16px;
                background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%);
                color: white;
                border: none;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
              "
              @mouseenter="$event.target.style.transform = 'scale(1.02)'"
              @mouseleave="$event.target.style.transform = 'scale(1)'"
            >
              📝 Complete Profile
            </button>
          </div>
        </div>
      </div>

      <!-- TARJETA: PEDIDOS -->
      <div style="
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
      "
      @mouseenter="$event.currentTarget.style.transform = 'translateY(-5px)'; $event.currentTarget.style.boxShadow = '0 12px 30px rgba(0, 0, 0, 0.15)'"
      @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)'"
      >
        <h2 style="
          margin: 0 0 20px 0;
          font-size: 20px;
          color: #dc2626;
          display: flex;
          align-items: center;
          gap: 10px;
        ">
          📦 My Orders
        </h2>
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <p style="margin: 0; color: #666;">You don’t have any orders yet</p>
          <button
            @click="goToProducts"
            style="
              width: 100%;
              padding: 10px 16px;
              background: #3b82f6;
              color: white;
              border: none;
              border-radius: 8px;
              font-weight: 600;
              cursor: pointer;
              transition: all 0.3s ease;
            "
            @mouseenter="$event.target.style.transform = 'scale(1.02)'"
            @mouseleave="$event.target.style.transform = 'scale(1)'"
          >
            🛍️ View Products
          </button>
        </div>
      </div>

      <!-- TARJETA: MENÚS PERSONALIZADOS -->
      <div style="
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px dashed #f59e0b;
      "
      @mouseenter="$event.currentTarget.style.transform = 'translateY(-5px)'; $event.currentTarget.style.boxShadow = '0 12px 30px rgba(0, 0, 0, 0.15)'"
      @mouseleave="$event.currentTarget.style.transform = 'translateY(0)'; $event.currentTarget.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)'"
      >
        <h2 style="
          margin: 0 0 20px 0;
          font-size: 20px;
          color: #b45309;
          display: flex;
          align-items: center;
          gap: 10px;
        ">
          🍽️ Personalized Menus
        </h2>
        <div style="display: flex; flex-direction: column; gap: 12px; align-items: center; text-align: center;">
          <div style="font-size: 48px;">⏳</div>
          <p style="margin: 0; color: #92400e; font-weight: 600;">Coming Soon</p>
          <p style="margin: 8px 0 0 0; color: #b45309; font-size: 13px;">
            Once you complete your cooking profile, you’ll receive personalized menus based on your dietary preferences
          </p>
          <div style="
            margin-top: 15px;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 8px;
            font-size: 13px;
            color: #92400e;
            border-left: 3px solid #f59e0b;
          ">
            ✨ Menus will be sent weekly to your email
          </div>
        </div>
      </div>
    </div>

    <!-- ADDITIONAL INFORMATION -->
    <div style="
      max-width: 1200px;
      margin: 40px auto 0;
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    ">
      <h2 style="
        margin: 0 0 20px 0;
        font-size: 20px;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 10px;
      ">
        ℹ️ Your Account Information
      </h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div>
          <p style="margin: 0 0 8px 0; color: #666; font-size: 12px; font-weight: 600;">Verified Email</p>
          <p style="margin: 0; color: #333; font-size: 14px;">{{ user?.email }}</p>
        </div>
        <div>
          <p style="margin: 0 0 8px 0; color: #666; font-size: 12px; font-weight: 600;">Role</p>
          <p style="margin: 0; color: #333; font-size: 14px;">{{ user?.role === 'cliente' ? '👤 Customer' : '🔐 ' + user?.role }}</p>
        </div>
        <div>
          <p style="margin: 0 0 8px 0; color: #666; font-size: 12px; font-weight: 600;">Status</p>
          <p style="margin: 0; color: #10b981; font-size: 14px; font-weight: 600;">✅ Active</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const user = ref(null)
const onboarding = ref(null)
const loading = ref(true)

const readApiError = async (response, fallbackMessage) => {
  const contentType = response.headers.get('content-type') || ''

  if (contentType.includes('application/json')) {
    const data = await response.json()
    return data.message || fallbackMessage
  }

  const text = await response.text()
  if (text && text.trim().startsWith('<!DOCTYPE')) {
    return `${fallbackMessage} (respuesta HTML del servidor)`
  }

  return fallbackMessage
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const loadData = async () => {
  try {
    loading.value = true

    // Obtener datos del usuario
    const userRes = await fetch('/api/user', {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
    if (userRes.ok) {
      const userData = await userRes.json()
      user.value = userData

      // Obtener datos del onboarding (sin fetch si no hay prospect_id)
      if (userData.prospect_id) {
        try {
          const onboardingRes = await fetch(`/api/prospects/${userData.prospect_id}/onboarding`, {
            credentials: 'include',
            headers: { 'Accept': 'application/json' }
          })
          if (onboardingRes.ok) {
            const onboardingData = await onboardingRes.json()
            onboarding.value = onboardingData.onboarding
          }
        } catch (err) {
          console.warn('Could not load onboarding data:', err)
        }
      }
    }
  } catch (err) {
    console.error('Error loading data:', err)
  } finally {
    loading.value = false
  }
}

const logout = async () => {
  try {
    await fetch('/logout', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content,
      }
    })

    // Redirigir al home
    router.push('/')
  } catch (err) {
    console.error('Error logging out:', err)
  }
}

const goToOnboarding = () => {
  router.push('/onboarding')
}

const goToProducts = () => {
  router.push('/productos')
}

onMounted(() => {
  loadData()
})
</script>
