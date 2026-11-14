<template>
  <div class="prospecto-page">
    <!-- Header -->
    <section style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); padding: 40px 20px; color: white;">
      <div class="container mx-auto max-w-2xl">
        <router-link to="/" style="color: white; text-decoration: none; font-size: 0.9rem; margin-bottom: 15px; display: inline-block;">
          ← Volver a inicio
        </router-link>
        <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 10px;">Registro de Prospecto</h1>
        <p style="font-size: 1.1rem; opacity: 0.95;">Completa este formulario para comenzar a trabajar con nosotros</p>
      </div>
    </section>

    <!-- Form Section -->
    <section style="padding: 60px 20px; background: #f9f9f9;">
      <div class="container mx-auto max-w-2xl">
        <form @submit.prevent="submitForm" style="background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 15px rgba(0,0,0,0.1);">

          <!-- Success Message -->
          <div v-if="successMessage" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            ✅ {{ successMessage }}
          </div>

          <!-- Error Message -->
          <div v-if="errorMessage" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            ❌ {{ errorMessage }}
          </div>

          <!-- Form Group: Nombre -->
          <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
              Nombre Completo <span style="color: #ff6b6b;">*</span>
            </label>
            <input
              v-model="form.full_name"
              type="text"
              placeholder="Juan Pérez"
              required
              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; font-family: inherit;"
              @focus="$event.target.style.borderColor = '#ff6b6b'"
              @blur="$event.target.style.borderColor = '#ddd'"
            />
          </div>

          <!-- Form Group: Email -->
          <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
              Email <span style="color: #ff6b6b;">*</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              placeholder="juan@example.com"
              required
              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; font-family: inherit;"
              @focus="$event.target.style.borderColor = '#ff6b6b'"
              @blur="$event.target.style.borderColor = '#ddd'"
            />
          </div>

          <!-- Form Group: Teléfono -->
          <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
              Teléfono <span style="color: #ff6b6b;">*</span>
            </label>
            <input
              v-model="form.phone"
              type="tel"
              placeholder="+1234567890"
              required
              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; font-family: inherit;"
              @focus="$event.target.style.borderColor = '#ff6b6b'"
              @blur="$event.target.style.borderColor = '#ddd'"
            />
          </div>

          <!-- Form Group: Ciudad -->
          <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
              Ciudad <span style="color: #999; font-size: 0.9rem;">(opcional)</span>
            </label>
            <input
              v-model="form.city"
              type="text"
              placeholder="Bogotá"
              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; font-family: inherit;"
              @focus="$event.target.style.borderColor = '#ff6b6b'"
              @blur="$event.target.style.borderColor = '#ddd'"
            />
          </div>

          <!-- Form Group: Comentarios -->
          <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333;">
              Comentarios <span style="color: #999; font-size: 0.9rem;">(opcional)</span>
            </label>
            <textarea
              v-model="form.message"
              placeholder="Cuéntanos sobre tu negocio y cómo podemos ayudarte..."
              rows="4"
              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; font-family: inherit; resize: vertical;"
              @focus="$event.target.style.borderColor = '#ff6b6b'"
              @blur="$event.target.style.borderColor = '#ddd'"
            ></textarea>
          </div>

          <!-- Submit Button -->
          <div style="margin-bottom: 20px;">
            <button
              type="submit"
              :disabled="loading"
              style="width: 100%; padding: 15px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: white; border: none; border-radius: 5px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease;"
              @mouseenter="$event.target.style.transform = 'translateY(-2px)'; $event.target.style.boxShadow = '0 8px 25px rgba(0,0,0,0.2)'"
              @mouseleave="$event.target.style.transform = 'translateY(0)'; $event.target.style.boxShadow = 'none'"
            >
              {{ loading ? 'Cargando...' : 'Enviar Solicitud' }}
            </button>
          </div>

          <!-- Additional Info -->
          <div style="background: #f0f0f0; padding: 15px; border-radius: 5px; font-size: 0.9rem; color: #666; text-align: center;">
            <p style="margin: 0;">
              🔒 Tu información está protegida y segura<br>
              📧 Te contactaremos en 24 horas<br>
              ✅ Confirmación de correo será enviada inmediatamente
            </p>
          </div>
        </form>

        <!-- Info Cards -->
        <div style="margin-top: 60px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
          <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
            <div style="font-size: 2.5rem; margin-bottom: 10px;">⚡</div>
            <h3 style="font-size: 1.1rem; margin-bottom: 10px; color: #333;">Proceso Rápido</h3>
            <p style="color: #666; font-size: 0.9rem;">Registro en menos de 5 minutos</p>
          </div>
          <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
            <div style="font-size: 2.5rem; margin-bottom: 10px;">💼</div>
            <h3 style="font-size: 1.1rem; margin-bottom: 10px; color: #333;">Soporte Personalizado</h3>
            <p style="color: #666; font-size: 0.9rem;">Equipo dedicado a tu negocio</p>
          </div>
          <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
            <div style="font-size: 2.5rem; margin-bottom: 10px;">🎁</div>
            <h3 style="font-size: 1.1rem; margin-bottom: 10px; color: #333;">Beneficios Exclusivos</h3>
            <p style="color: #666; font-size: 0.9rem;">Acceso a ofertas y promociones</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer style="background: #333; color: white; padding: 40px 20px; text-align: center;">
      <div class="container mx-auto max-w-2xl">
        <h3 style="font-size: 1.5rem; margin-bottom: 15px;">🍳 CHEF</h3>
        <p style="margin-bottom: 15px;">Plataforma de distribución integrada</p>
        <p style="opacity: 0.8; font-size: 0.9rem;">© 2026 CHEF App. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  full_name: '',
  email: '',
  phone: '',
  city: '',
  message: ''
})

const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const submitForm = async () => {
  // Clear messages
  successMessage.value = ''
  errorMessage.value = ''

  // Validate required fields
  if (!form.value.full_name || !form.value.email || !form.value.phone) {
    errorMessage.value = 'Por favor completa todos los campos requeridos'
    return
  }

  // Validate email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!emailRegex.test(form.value.email)) {
    errorMessage.value = 'Por favor ingresa un email válido'
    return
  }

  loading.value = true

  try {
    const response = await fetch('/api/prospects', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        full_name: form.value.full_name,
        email: form.value.email,
        phone: form.value.phone,
        city: form.value.city
      })
    })

    const data = await response.json()

    if (response.ok || response.status === 201) {
      // ✅ Muestra que el onboarding fue creado automáticamente
      successMessage.value = `✅ ¡Bienvenido! Hemos creado tu cuenta de onboarding (ID: ${data.onboarding_id}). Te contactaremos en 24 horas.`

      // Reset form
      form.value = {
        full_name: '',
        email: '',
        phone: '',
        city: '',
        message: ''
      }

      // Redirect to home after 3 seconds
      setTimeout(() => {
        router.push('/')
      }, 3000)
    } else if (response.status === 422) {
      if (data.errors && data.errors.email) {
        errorMessage.value = 'Este email ya está registrado'
      } else {
        errorMessage.value = data.message || 'Error al enviar el formulario'
      }
    } else {
      errorMessage.value = data.message || 'Error al enviar el formulario. Intenta nuevamente.'
    }
  } catch (error) {
    errorMessage.value = 'Error de conexión. Por favor intenta nuevamente.'
    console.error('Error:', error)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.prospecto-page {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container {
  margin: 0 auto;
}

.max-w-2xl {
  max-width: 42rem;
}

@media (max-width: 640px) {
  .prospecto-page h1 {
    font-size: 1.75rem !important;
  }

  .prospecto-page form {
    padding: 20px !important;
  }
}
</style>
