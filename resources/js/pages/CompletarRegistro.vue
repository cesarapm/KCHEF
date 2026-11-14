<template>
  <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); padding: 64px 16px; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <div style="width: 100%; max-width: 500px;">
      <!-- Logo/Encabezado premium -->
      <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-600 to-red-700 text-white rounded-full mb-6 shadow-lg">
          <span class="text-5xl">👨‍🍳</span>
        </div>
        <h1 class="text-4xl font-black text-gray-900 mb-2 tracking-tight">CHEF</h1>
        <p class="text-lg text-gray-600 font-medium">Completa tu acceso a la plataforma</p>
      </div>

      <!-- Tarjeta principal -->
      <div style="background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
        <!-- Header rojo con degradado -->
        <div style="background: linear-gradient(90deg, #dc2626 0%, #ef4444 50%, #dc2626 100%); padding: 40px 32px; color: white; position: relative; overflow: hidden;">
          <!-- Decoración de fondo -->
          <div style="position: absolute; top: 0; right: 0; width: 160px; height: 160px; background: rgba(255,255,255,0.1); border-radius: 50%; margin-right: -80px; margin-top: -80px;"></div>

          <div style="position: relative; z-index: 10;">
            <div style="display: inline-block; background: rgba(220,38,38,0.8); padding: 8px 16px; border-radius: 20px; margin-bottom: 16px;">
              <span style="font-size: 11px; font-weight: bold; letter-spacing: 1.5px;">PASO 1 DE 1</span>
            </div>
            <h2 style="font-size: 28px; font-weight: 900; margin-top: 12px; line-height: 1.2; margin: 0;">Crear tu Contraseña</h2>
            <p style="color: rgba(255,255,255,0.95); margin-top: 12px; font-size: 14px; font-weight: 500; margin: 0; padding-top: 12px;">Establece una contraseña segura para acceder a tu cuenta</p>
          </div>
        </div>

        <!-- Contenido del formulario -->
        <div style="padding: 40px 32px 48px;">
          <!-- Mensajes de error -->
          <div v-if="errors.length" style="background: #fee2e2; border-left: 4px solid #dc2626; border-radius: 12px; margin-bottom: 24px; padding: 16px; display: flex; gap: 12px;">
            <span style="font-size: 20px;">⚠️</span>
            <div style="flex: 1;">
              <p style="font-weight: bold; font-size: 14px; margin: 0 0 8px 0; color: #991b1b;">Oops, hay un problema</p>
              <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px;">
                <li v-for="error in errors" :key="error" style="display: flex; align-items: center; margin-bottom: 4px; color: #b91c1c;">
                  <span style="display: inline-block; width: 6px; height: 6px; background: #dc2626; border-radius: 50%; margin-right: 8px;"></span>
                  {{ error }}
                </li>
              </ul>
            </div>
          </div>

          <!-- Mensaje de éxito -->
          <div v-if="successMessage" style="background: #dcfce7; border-left: 4px solid #16a34a; border-radius: 12px; margin-bottom: 24px; padding: 16px; display: flex; gap: 12px;">
            <span style="font-size: 20px;">✅</span>
            <p style="color: #15803d; font-weight: 500; margin: 0;">{{ successMessage }}</p>
          </div>

          <!-- Información del prospecto -->
          <div style="margin-bottom: 48px; padding-bottom: 48px; border-bottom: 2px solid #f3f4f6;">
            <p style="font-size: 12px; font-weight: bold; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 16px 0;">Tu Información</p>
            <div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb;">
              <p style="font-size: 18px; font-weight: bold; color: #111827; margin: 0;">{{ prospectName }}</p>
              <p style="font-size: 13px; color: #6b7280; margin-top: 8px; font-family: monospace; word-break: break-all;">{{ prospectEmail }}</p>
            </div>
          </div>

          <!-- Contraseña -->
          <div style="margin-bottom: 48px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 16px; margin: 0 0 16px 0;">
              Contraseña <span style="color: #dc2626;">*</span>
            </label>
            <div style="position: relative;">
              <input
                v-model="form.password"
                type="password"
                placeholder="Mínimo 8 caracteres"
                style="width: 100%; padding: 16px 20px 16px 20px; font-size: 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); box-sizing: border-box;"
                @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 12px rgba(220,38,38,0.2)'"
                @blur="$event.target.style.borderColor = '#e5e7eb'; $event.target.style.boxShadow = '0 2px 8px rgba(0,0,0,0.05)'"
                @mouseover="$event.target.style.borderColor = '#d1d5db'"
              />
              <span style="position: absolute; right: 16px; top: 16px; font-size: 20px;">🔐</span>
            </div>

            <!-- Validaciones -->
            <div style="margin-top: 20px; padding-left: 16px; border-left: 4px solid #e5e7eb;">
              <div style="display: flex; align-items: center; margin-bottom: 16px; font-size: 14px;">
                <span :style="{ fontSize: '18px', marginRight: '12px', color: form.password.length >= 8 ? '#16a34a' : '#d1d5db' }">
                  {{ form.password.length >= 8 ? '✓' : '○' }}
                </span>
                <span :style="{ color: form.password.length >= 8 ? '#15803d' : '#4b5563', fontWeight: form.password.length >= 8 ? '600' : '400' }">
                  Mínimo 8 caracteres
                </span>
              </div>
              <div style="display: flex; align-items: center; margin-bottom: 16px; font-size: 14px;">
                <span :style="{ fontSize: '18px', marginRight: '12px', color: form.password.match(/[0-9]/) ? '#16a34a' : '#d1d5db' }">
                  {{ form.password.match(/[0-9]/) ? '✓' : '○' }}
                </span>
                <span :style="{ color: form.password.match(/[0-9]/) ? '#15803d' : '#4b5563', fontWeight: form.password.match(/[0-9]/) ? '600' : '400' }">
                  Al menos un número
                </span>
              </div>
              <div style="display: flex; align-items: center; font-size: 14px;">
                <span :style="{ fontSize: '18px', marginRight: '12px', color: form.password.match(/[!@#$%^&*]/) ? '#16a34a' : '#d1d5db' }">
                  {{ form.password.match(/[!@#$%^&*]/) ? '✓' : '○' }}
                </span>
                <span :style="{ color: form.password.match(/[!@#$%^&*]/) ? '#15803d' : '#4b5563', fontWeight: form.password.match(/[!@#$%^&*]/) ? '600' : '400' }">
                  Carácter especial (!@#$%^&*)
                </span>
              </div>
            </div>
          </div>

          <!-- Confirmar Contraseña -->
          <div style="margin-bottom: 48px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 16px; margin: 0 0 16px 0;">
              Confirmar Contraseña <span style="color: #dc2626;">*</span>
            </label>
            <div style="position: relative;">
              <input
                v-model="form.password_confirmation"
                type="password"
                placeholder="Repite tu contraseña"
                style="width: 100%; padding: 16px 20px 16px 20px; font-size: 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); box-sizing: border-box;"
                @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 12px rgba(220,38,38,0.2)'"
                @blur="$event.target.style.borderColor = '#e5e7eb'; $event.target.style.boxShadow = '0 2px 8px rgba(0,0,0,0.05)'"
                @mouseover="$event.target.style.borderColor = '#d1d5db'"
              />
              <span style="position: absolute; right: 16px; top: 16px; font-size: 20px;">
                {{ passwordMatch && form.password_confirmation ? '✅' : '🔐' }}
              </span>
            </div>

            <!-- Validación de coincidencia -->
            <transition name="fade">
              <div v-if="passwordMatch && form.password_confirmation" style="margin-top: 20px; padding: 16px; background: #dcfce7; border-left: 4px solid #16a34a; border-radius: 8px;">
                <p style="font-size: 14px; color: #166534; font-weight: bold; margin: 0;">✓ Las contraseñas coinciden perfectamente</p>
              </div>
              <div v-else-if="form.password_confirmation && !passwordMatch" style="margin-top: 20px; padding: 16px; background: #fee2e2; border-left: 4px solid #dc2626; border-radius: 8px;">
                <p style="font-size: 14px; color: #991b1b; font-weight: bold; margin: 0;">✗ Las contraseñas no coinciden</p>
              </div>
            </transition>
          </div>

          <!-- Indicador de fortaleza -->
          <div v-if="form.password" style="margin-bottom: 48px; padding-bottom: 48px; border-bottom: 2px solid #f3f4f6;">
            <p style="font-size: 12px; font-weight: bold; color: #4b5563; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 12px 0;">Fortaleza de la contraseña</p>
            <div style="width: 100%; background: #e5e7eb; border-radius: 20px; height: 12px; overflow: hidden; margin-bottom: 12px;">
              <div
                :style="{
                  width: passwordStrength.percentage + '%',
                  background: passwordStrength.color,
                  height: '100%',
                  borderRadius: '20px',
                  transition: 'all 0.5s ease'
                }"
              ></div>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <p :style="{
                fontSize: '14px',
                fontWeight: 'bold',
                color:
                  passwordStrength.label === 'Débil' ? '#dc2626' :
                  passwordStrength.label === 'Regular' ? '#ca8a04' :
                  passwordStrength.label === 'Buena' ? '#2563eb' :
                  '#16a34a',
                margin: 0
              }">
                {{ passwordStrength.label }}
              </p>
              <span style="font-size: 12px; color: #9ca3af;">{{ Math.round(passwordStrength.percentage) }}%</span>
            </div>
          </div>

          <!-- Botón Enviar -->
          <button
            @click="submitForm"
            :disabled="loading || !isFormComplete"
            style="width: 100%; padding: 16px 24px; background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%); color: white; font-weight: bold; font-size: 16px; border: none; border-radius: 12px; cursor: pointer; transition: all 0.3s; box-shadow: 0 8px 24px rgba(220,38,38,0.3); margin-bottom: 32px; display: flex; align-items: center; justify-content: center; gap: 12px; box-sizing: border-box;"
            :style="{
              opacity: loading || !isFormComplete ? 0.5 : 1,
              cursor: loading || !isFormComplete ? 'not-allowed' : 'pointer'
            }"
            @mouseover="!loading && !isFormComplete ? null : $event.target.style.boxShadow = '0 12px 32px rgba(220,38,38,0.4)'"
            @mouseleave="$event.target.style.boxShadow = '0 8px 24px rgba(220,38,38,0.3)'"
          >
            <span v-if="loading" style="display: inline-flex; align-items: center;">
              <svg style="width: 20px; height: 20px; margin-right: 8px; animation: spin 1s linear infinite;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 2a10 10 0 0 1 10 10"></path>
              </svg>
              Registrando...
            </span>
            <span v-else>
              🚀 Completar Registro
            </span>
          </button>

          <!-- Términos y condiciones -->
          <p style="text-align: center; font-size: 12px; color: #6b7280; line-height: 1.6; margin: 0 0 16px 0;">
            Al registrarte aceptas nuestros
            <a href="#" style="color: #dc2626; text-decoration: none; font-weight: bold;">términos y condiciones</a>
          </p>

          <!-- Seguridad -->
          <p style="text-align: center; font-size: 12px; color: #111827; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; margin: 0;">
            🔒 Conexión 100% segura | Tus datos están protegidos
          </p>
        </div>
      </div>

      <!-- Soporte y badges -->
      <div style="margin-top: 48px; text-align: center;">
        <p style="font-size: 14px; color: #374151; margin: 0;">
          ¿Necesitas ayuda?
          <a href="mailto:soporte@chef.com" style="color: #dc2626; font-weight: bold; text-decoration: none;">Contactar soporte</a>
        </p>
      </div>

      <div style="margin-top: 32px; display: flex; justify-content: center; gap: 24px; font-size: 12px; color: #9ca3af;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <span>🛡️</span>
          <span>Seguridad SSL</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
          <span>✓</span>
          <span>Certificado</span>
        </div>
      </div>
    </div>
  </div>

</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const token = route.params.token

const form = ref({
  password: '',
  password_confirmation: '',
})

const errors = ref([])
const successMessage = ref('')
const loading = ref(false)
const prospectName = ref('')
const prospectEmail = ref('')

const passwordMatch = computed(() => {
  return form.value.password === form.value.password_confirmation && form.value.password.length > 0
})

const isFormComplete = computed(() => {
  return form.value.password &&
         form.value.password.length >= 8 &&
         form.value.password === form.value.password_confirmation
})

const passwordStrength = computed(() => {
  const pwd = form.value.password
  let strength = 0
  let label = 'Débil'
  let color = '#ef4444'

  if (pwd.length >= 8) strength++
  if (pwd.match(/[a-z]/)) strength++
  if (pwd.match(/[A-Z]/)) strength++
  if (pwd.match(/[0-9]/)) strength++
  if (pwd.match(/[!@#$%^&*]/)) strength++

  if (strength <= 2) {
    label = 'Débil'
    color = '#ef4444'
  } else if (strength <= 3) {
    label = 'Regular'
    color = '#eab308'
  } else if (strength <= 4) {
    label = 'Buena'
    color = '#3b82f6'
  } else {
    label = 'Muy fuerte'
    color = '#22c55e'
  }

  return {
    percentage: (strength / 5) * 100,
    label,
    color
  }
})

onMounted(async () => {
  // Verificar token y obtener información del prospecto
  try {
    const response = await fetch(`/api/prospect-tokens/verify`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ token })
    })

    if (!response.ok) {
      errors.value = ['Token inválido o expirado. Por favor solicita uno nuevo.']
      return
    }

    const data = await response.json()
    prospectName.value = data.prospect.full_name
    prospectEmail.value = data.prospect.email
  } catch (error) {
    errors.value = ['Error al verificar token. Por favor intenta de nuevo.']
  }
})

const submitForm = async () => {
  errors.value = []
  loading.value = true

  // Validaciones básicas
  if (form.value.password.length < 8) {
    errors.value.push('La contraseña debe tener al menos 8 caracteres')
    loading.value = false
    return
  }

  if (form.value.password !== form.value.password_confirmation) {
    errors.value.push('Las contraseñas no coinciden')
    loading.value = false
    return
  }

  try {
    const response = await fetch(`/api/prospect-tokens/register`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        token: token,
        password: form.value.password,
        password_confirmation: form.value.password_confirmation,
      })
    })

    if (response.ok) {
      successMessage.value = '✅ Registro completado correctamente'
      setTimeout(() => {
        router.push({ name: 'Home' })
      }, 2000)
    } else {
      const data = await response.json()
      errors.value = [data.message || 'Error al completar el registro']
    }
  } catch (error) {
    errors.value = ['Error de conexión. Por favor intenta de nuevo.']
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.fade-enter-to, .fade-leave-from {
  opacity: 1;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
