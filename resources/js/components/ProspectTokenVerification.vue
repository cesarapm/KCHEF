<template>
  <div class="token-verification-form">
    <div class="card">
      <div class="card-header">
        <h2>Completar Registro</h2>
        <p>Usa tu enlace de invitación para crear tu cuenta</p>
      </div>

      <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>

      <div v-if="step === 'verify'" class="form-container">
        <div class="alert alert-info">
          <p>El token será extraído de tu enlace de invitación automáticamente.</p>
        </div>

        <div v-if="verifying" class="loading">
          <p>Verificando tu invitación...</p>
        </div>

        <div v-else-if="prospectInfo">
          <div class="prospect-info">
            <h3>Información del Prospecto</h3>
            <p><strong>Nombre:</strong> {{ prospectInfo.full_name }}</p>
            <p><strong>Email:</strong> {{ prospectInfo.email }}</p>
            <p><strong>Teléfono:</strong> {{ prospectInfo.phone }}</p>
          </div>

          <button
            @click="step = 'register'"
            class="btn btn-primary"
          >
            Continuar con Registro
          </button>
        </div>
      </div>

      <form v-if="step === 'register'" @submit.prevent="submitRegistration">
        <div class="prospect-info-display">
          <h3>Datos Personales</h3>
          <p><strong>{{ prospectInfo.full_name }}</strong> ({{ prospectInfo.email }})</p>
        </div>

        <div class="form-group">
          <label for="password">Contraseña *</label>
          <input
            type="password"
            id="password"
            v-model="form.password"
            class="form-control"
            :class="{ 'is-invalid': errors.password }"
            placeholder="Mínimo 8 caracteres"
            required
          />
          <div v-if="errors.password" class="invalid-feedback">
            {{ errors.password[0] }}
          </div>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Confirmar Contraseña *</label>
          <input
            type="password"
            id="password_confirmation"
            v-model="form.password_confirmation"
            class="form-control"
            :class="{ 'is-invalid': errors.password_confirmation }"
            placeholder="Repite tu contraseña"
            required
          />
          <div v-if="errors.password_confirmation" class="invalid-feedback">
            {{ errors.password_confirmation[0] }}
          </div>
        </div>

        <div class="form-actions">
          <button
            type="button"
            @click="step = 'verify'"
            class="btn btn-secondary"
          >
            Volver
          </button>
          <button
            type="submit"
            class="btn btn-primary"
            :disabled="registering"
          >
            <span v-if="registering">Registrando...</span>
            <span v-else>Crear Cuenta</span>
          </button>
        </div>
      </form>

      <div v-if="step === 'success'" class="success-message">
        <div class="alert alert-success">
          <h3>¡Cuenta Creada Exitosamente!</h3>
          <p>Tu cuenta ha sido creada y estás autenticado.</p>
          <p>Serás redirigido al panel en 3 segundos...</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()

const form = reactive({
  password: '',
  password_confirmation: '',
})

const errors = reactive({})
const errorMessage = ref('')
const step = ref('verify')
const verifying = ref(false)
const registering = ref(false)
const prospectInfo = ref(null)
const token = ref(null)

onMounted(() => {
  // Extraer token de la URL
  token.value = route.query.token
  if (token.value) {
    verifyToken()
  } else {
    errorMessage.value = 'Token no proporcionado en la URL'
  }
})

const verifyToken = async () => {
  verifying.value = true
  errorMessage.value = ''

  try {
    const response = await axios.post('/api/prospect-tokens/verify', {
      token: token.value,
    })

    prospectInfo.value = response.data.prospect
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error al verificar token'
  } finally {
    verifying.value = false
  }
}

const submitRegistration = async () => {
  registering.value = true
  errors.value = {}
  errorMessage.value = ''

  try {
    const response = await axios.post('/api/prospect-tokens/register', {
      token: token.value,
      password: form.password,
      password_confirmation: form.password_confirmation,
    })

    // Guardar token de autenticación
    localStorage.setItem('auth_token', response.data.token)

    // Establecer header de axios
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`

    step.value = 'success'

    // Redirigir después de 3 segundos
    setTimeout(() => {
      router.push('/dashboard')
    }, 3000)
  } catch (error) {
    if (error.response?.data?.errors) {
      Object.assign(errors, error.response.data.errors)
    } else {
      errorMessage.value = error.response?.data?.message || 'Error al registrar'
    }
  } finally {
    registering.value = false
  }
}
</script>

<style scoped>
.token-verification-form {
  max-width: 600px;
  margin: 2rem auto;
}

.card {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  border: none;
  border-radius: 8px;
}

.card-header {
  background-color: #f8f9fa;
  padding: 2rem;
  border-bottom: 1px solid #dee2e6;
  border-radius: 8px 8px 0 0;
}

.card-header h2 {
  margin: 0;
  color: #333;
}

.card-header p {
  margin: 0.5rem 0 0 0;
  color: #666;
  font-size: 0.95rem;
}

.form-container,
.form-actions {
  padding: 2rem;
}

.loading {
  text-align: center;
  padding: 3rem 2rem;
  color: #666;
}

.prospect-info,
.prospect-info-display {
  background-color: #f8f9fa;
  padding: 1.5rem;
  border-radius: 4px;
  margin-bottom: 1.5rem;
  border-left: 4px solid #007bff;
}

.prospect-info h3,
.prospect-info-display h3 {
  margin-top: 0;
  color: #333;
}

.prospect-info p,
.prospect-info-display p {
  margin: 0.5rem 0;
  color: #666;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  font-weight: 500;
  margin-bottom: 0.5rem;
  display: block;
  color: #333;
}

.form-control {
  border: 1px solid #ced4da;
  border-radius: 4px;
  padding: 0.75rem;
  font-size: 1rem;
  width: 100%;
  box-sizing: border-box;
}

.form-control:focus {
  outline: none;
  border-color: #80bdff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-control.is-invalid {
  border-color: #dc3545;
}

.invalid-feedback {
  display: block;
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.form-actions {
  display: flex;
  gap: 1rem;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
  flex: 1;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #0056b3;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background-color: #545b62;
}

.btn:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

.alert {
  padding: 1rem;
  margin: 1rem;
  border-radius: 4px;
  border: 1px solid;
}

.alert-info {
  background-color: #d1ecf1;
  border-color: #bee5eb;
  color: #0c5460;
}

.alert-success {
  background-color: #d4edda;
  border-color: #c3e6cb;
  color: #155724;
}

.alert-danger {
  background-color: #f8d7da;
  border-color: #f5c6cb;
  color: #721c24;
}

.success-message {
  padding: 2rem;
}

.success-message h3 {
  color: #155724;
  margin-top: 0;
}
</style>
