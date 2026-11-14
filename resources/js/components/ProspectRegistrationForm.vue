<template>
  <div class="prospect-registration-form">
    <div class="card">
      <div class="card-header">
        <h2>Formulario de Prospecto</h2>
        <p>Completa el siguiente formulario para registrarte como prospecto</p>
      </div>

      <div v-if="successMessage" class="alert alert-success">
        {{ successMessage }}
      </div>

      <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>

      <form @submit.prevent="submitForm" v-if="!submitted">
        <div class="form-group">
          <label for="full_name">Nombre Completo *</label>
          <input
            type="text"
            id="full_name"
            v-model="form.full_name"
            class="form-control"
            :class="{ 'is-invalid': errors.full_name }"
            required
          />
          <div v-if="errors.full_name" class="invalid-feedback">
            {{ errors.full_name[0] }}
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email *</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            class="form-control"
            :class="{ 'is-invalid': errors.email }"
            required
          />
          <div v-if="errors.email" class="invalid-feedback">
            {{ errors.email[0] }}
          </div>
        </div>

        <div class="form-group">
          <label for="phone">Teléfono *</label>
          <input
            type="tel"
            id="phone"
            v-model="form.phone"
            class="form-control"
            :class="{ 'is-invalid': errors.phone }"
            required
          />
          <div v-if="errors.phone" class="invalid-feedback">
            {{ errors.phone[0] }}
          </div>
        </div>

        <div class="form-group">
          <label for="city">Ciudad</label>
          <input
            type="text"
            id="city"
            v-model="form.city"
            class="form-control"
          />
        </div>

        <button
          type="submit"
          class="btn btn-primary"
          :disabled="loading"
        >
          <span v-if="loading">Cargando...</span>
          <span v-else>Registrarse como Prospecto</span>
        </button>
      </form>

      <div v-if="submitted" class="success-message">
        <div class="alert alert-success">
          <h3>¡Solicitud Enviada Exitosamente!</h3>
          <p>Tu prospecto ha sido registrado. Próximamente te contactaremos con un enlace de invitación.</p>
          <p><strong>ID de Prospecto:</strong> {{ prospectId }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'

const form = reactive({
  full_name: '',
  email: '',
  phone: '',
  city: '',
})

const errors = reactive({})
const loading = ref(false)
const errorMessage = ref('')
const successMessage = ref('')
const submitted = ref(false)
const prospectId = ref(null)

const submitForm = async () => {
  loading.value = true
  errors.value = {}
  errorMessage.value = ''

  try {
    const response = await axios.post('/api/prospects', form)

    submitted.value = true
    prospectId.value = response.data.prospect_id
    successMessage.value = response.data.message

    // Limpiar formulario
    form.full_name = ''
    form.email = ''
    form.phone = ''
    form.city = ''
  } catch (error) {
    if (error.response?.data?.errors) {
      Object.assign(errors, error.response.data.errors)
    } else {
      errorMessage.value = error.response?.data?.message || 'Error al enviar formulario'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.prospect-registration-form {
  max-width: 500px;
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

.form-group {
  margin-bottom: 1.5rem;
  padding: 0 2rem;
}

.form-group:first-of-type {
  padding-top: 2rem;
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

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
  width: 100%;
  margin: 2rem;
  margin-top: 0;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #0056b3;
}

.btn-primary:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

.alert {
  padding: 1rem;
  margin: 1rem 2rem;
  border-radius: 4px;
  border: 1px solid;
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
