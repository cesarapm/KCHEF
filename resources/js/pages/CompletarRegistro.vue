<template>
  <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8" style="background: linear-gradient(135deg, #f8f5ec 0%, #fdfaf4 100%);">
    <div class="max-w-md w-full mx-auto">
      <!-- Premium logo/header -->
      <div class="text-center mb-8 sm:mb-10">
        <!-- <div class="inline-flex items-center justify-center w-20 h-20 text-white rounded-full mb-6 shadow-lg hover:shadow-xl transition-shadow duration-300" style="background: linear-gradient(135deg, #4a6741 0%, #3a5530 100%);">
          <span class="text-5xl">👨‍🍳</span>
        </div> -->
        <h1 class="text-4xl sm:text-5xl font-black text-gray-900 mb-2 tracking-tight">CHEF</h1>
        <p class="text-base sm:text-lg text-gray-600 font-medium">Complete your platform access</p>
      </div>

      <!-- Main card -->
      <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Red gradient header -->
        <div class="relative overflow-hidden px-6 py-8 sm:px-8 sm:py-10" style="background: linear-gradient(90deg, #4a6741 0%, #5b7b52 50%, #4a6741 100%);">
          <!-- Background decoration -->
          <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
          <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16"></div>

          <div class="relative z-10">
            <div class="inline-block bg-white/20 backdrop-blur px-3 py-1 rounded-full mb-4">
              <span class="text-xs font-bold letter-spacing-wider text-white">STEP 1 OF 1</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-white mb-2 leading-tight">Create Your Password</h2>
            <p class="text-sm font-medium" style="color: #eef5eb;">Set a secure password to access your account</p>
          </div>
        </div>

        <!-- Form content -->
        <div style="padding: 40px 32px 48px;">
          <!-- Error messages -->
          <div v-if="errors.length" style="background: #fee2e2; border-left: 4px solid #dc2626; border-radius: 12px; margin-bottom: 24px; padding: 16px; display: flex; gap: 12px;">
            <span style="font-size: 20px;">⚠️</span>
            <div style="flex: 1;">
              <p style="font-weight: bold; font-size: 14px; margin: 0 0 8px 0; color: #991b1b;">Oops, there is a problem</p>
              <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px;">
                <li v-for="error in errors" :key="error" style="display: flex; align-items: center; margin-bottom: 4px; color: #b91c1c;">
                  <span style="display: inline-block; width: 6px; height: 6px; background: #dc2626; border-radius: 50%; margin-right: 8px;"></span>
                  {{ error }}
                </li>
              </ul>
            </div>
          </div>

          <!-- Success message -->
          <div v-if="successMessage" style="background: #dcfce7; border-left: 4px solid #16a34a; border-radius: 12px; margin-bottom: 24px; padding: 16px; display: flex; gap: 12px;">
            <span style="font-size: 20px;">✅</span>
            <p style="color: #15803d; font-weight: 500; margin: 0;">{{ successMessage }}</p>
          </div>

          <!-- Prospect info -->
          <div style="margin-bottom: 48px; padding-bottom: 48px; border-bottom: 2px solid #f3f4f6;">
            <p style="font-size: 12px; font-weight: bold; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 16px 0;">Your Information</p>
            <div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb;">
              <p style="font-size: 18px; font-weight: bold; color: #111827; margin: 0;">{{ prospectName }}</p>
              <p style="font-size: 13px; color: #6b7280; margin-top: 8px; font-family: monospace; word-break: break-all;">{{ prospectEmail }}</p>
            </div>
          </div>

          <!-- Password -->
          <div style="margin-bottom: 48px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 16px; margin: 0 0 16px 0;">
              Password <span style="color: #dc2626;">*</span>
            </label>
            <div style="position: relative;">
              <input
                v-model="form.password"
                type="password"
                placeholder="Minimum 8 characters"
                style="width: 100%; padding: 16px 20px 16px 20px; font-size: 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); box-sizing: border-box;"
                @focus="$event.target.style.borderColor = '#4a6741'; $event.target.style.boxShadow = '0 0 12px rgba(74,103,65,0.2)'"
                @blur="$event.target.style.borderColor = '#e5e7eb'; $event.target.style.boxShadow = '0 2px 8px rgba(0,0,0,0.05)'"
                @mouseover="$event.target.style.borderColor = '#d1d5db'"
              />
              <span style="position: absolute; right: 16px; top: 16px; font-size: 20px;">🔐</span>
            </div>

            <!-- Validation -->
            <div style="margin-top: 20px; padding-left: 16px; border-left: 4px solid #e5e7eb;">
              <div style="display: flex; align-items: center; margin-bottom: 16px; font-size: 14px;">
                <span :style="{ fontSize: '18px', marginRight: '12px', color: form.password.length >= 8 ? '#16a34a' : '#d1d5db' }">
                  {{ form.password.length >= 8 ? '✓' : '○' }}
                </span>
                <span :style="{ color: form.password.length >= 8 ? '#15803d' : '#4b5563', fontWeight: form.password.length >= 8 ? '600' : '400' }">
                  Minimum 8 characters
                </span>
              </div>
              <div style="display: flex; align-items: center; margin-bottom: 16px; font-size: 14px;">
                <span :style="{ fontSize: '18px', marginRight: '12px', color: form.password.match(/[0-9]/) ? '#16a34a' : '#d1d5db' }">
                  {{ form.password.match(/[0-9]/) ? '✓' : '○' }}
                </span>
                <span :style="{ color: form.password.match(/[0-9]/) ? '#15803d' : '#4b5563', fontWeight: form.password.match(/[0-9]/) ? '600' : '400' }">
                  At least one number
                </span>
              </div>
              <div style="display: flex; align-items: center; font-size: 14px;">
                <span :style="{ fontSize: '18px', marginRight: '12px', color: form.password.match(/[!@#$%^&*]/) ? '#16a34a' : '#d1d5db' }">
                  {{ form.password.match(/[!@#$%^&*]/) ? '✓' : '○' }}
                </span>
                <span :style="{ color: form.password.match(/[!@#$%^&*]/) ? '#15803d' : '#4b5563', fontWeight: form.password.match(/[!@#$%^&*]/) ? '600' : '400' }">
                  Special character (!@#$%^&*)
                </span>
              </div>
            </div>
          </div>

          <!-- Confirm Password -->
          <div style="margin-bottom: 48px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 16px; margin: 0 0 16px 0;">
              Confirm Password <span style="color: #dc2626;">*</span>
            </label>
            <div style="position: relative;">
              <input
                v-model="form.password_confirmation"
                type="password"
                placeholder="Repeat your password"
                style="width: 100%; padding: 16px 20px 16px 20px; font-size: 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); box-sizing: border-box;"
                @focus="$event.target.style.borderColor = '#4a6741'; $event.target.style.boxShadow = '0 0 12px rgba(74,103,65,0.2)'"
                @blur="$event.target.style.borderColor = '#e5e7eb'; $event.target.style.boxShadow = '0 2px 8px rgba(0,0,0,0.05)'"
                @mouseover="$event.target.style.borderColor = '#d1d5db'"
              />
              <span style="position: absolute; right: 16px; top: 16px; font-size: 20px;">
                {{ passwordMatch && form.password_confirmation ? '✅' : '🔐' }}
              </span>
            </div>

            <!-- Match validation -->
            <transition name="fade">
              <div v-if="passwordMatch && form.password_confirmation" style="margin-top: 20px; padding: 16px; background: #dcfce7; border-left: 4px solid #16a34a; border-radius: 8px;">
                <p style="font-size: 14px; color: #166534; font-weight: bold; margin: 0;">✓ Passwords match perfectly</p>
              </div>
              <div v-else-if="form.password_confirmation && !passwordMatch" style="margin-top: 20px; padding: 16px; background: #fee2e2; border-left: 4px solid #dc2626; border-radius: 8px;">
                <p style="font-size: 14px; color: #991b1b; font-weight: bold; margin: 0;">✗ Passwords do not match</p>
              </div>
            </transition>
          </div>

          <!-- Password strength indicator -->
          <div v-if="form.password" style="margin-bottom: 48px; padding-bottom: 48px; border-bottom: 2px solid #f3f4f6;">
            <p style="font-size: 12px; font-weight: bold; color: #4b5563; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 12px 0;">Password strength</p>
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
                  passwordStrength.label === 'Weak' ? '#dc2626' :
                  passwordStrength.label === 'Fair' ? '#ca8a04' :
                  passwordStrength.label === 'Good' ? '#2563eb' :
                  '#16a34a',
                margin: 0
              }">
                {{ passwordStrength.label }}
              </p>
              <span style="font-size: 12px; color: #9ca3af;">{{ Math.round(passwordStrength.percentage) }}%</span>
            </div>
          </div>

          <!-- Submit button -->
          <button
            @click="submitForm"
            :disabled="loading || !isFormComplete"
            style="width: 100%; padding: 16px 24px; background: linear-gradient(90deg, #4a6741 0%, #3a5530 100%); color: white; font-weight: bold; font-size: 16px; border: none; border-radius: 12px; cursor: pointer; transition: all 0.3s; box-shadow: 0 8px 24px rgba(74,103,65,0.3); margin-bottom: 32px; display: flex; align-items: center; justify-content: center; gap: 12px; box-sizing: border-box;"
            :style="{
              opacity: loading || !isFormComplete ? 0.5 : 1,
              cursor: loading || !isFormComplete ? 'not-allowed' : 'pointer'
            }"
            @mouseover="!loading && !isFormComplete ? null : $event.target.style.boxShadow = '0 12px 32px rgba(74,103,65,0.4)'"
            @mouseleave="$event.target.style.boxShadow = '0 8px 24px rgba(74,103,65,0.3)'"
          >
            <span v-if="loading" style="display: inline-flex; align-items: center;">
              <svg style="width: 20px; height: 20px; margin-right: 8px; animation: spin 1s linear infinite;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 2a10 10 0 0 1 10 10"></path>
              </svg>
              Registering...
            </span>
            <span v-else>
              🚀 Complete Registration
            </span>
          </button>

          <!-- Terms and conditions -->
          <p style="text-align: center; font-size: 12px; color: #6b7280; line-height: 1.6; margin: 0 0 16px 0;">
            By registering you accept our
            <a href="#" style="color: #4a6741; text-decoration: none; font-weight: bold;">terms and conditions</a>
          </p>

          <!-- Security -->
          <p style="text-align: center; font-size: 12px; color: #111827; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; margin: 0;">
            🔒 100% secure connection | Your data is protected
          </p>
        </div>
      </div>

      <!-- Support and badges -->
      <div style="margin-top: 48px; text-align: center;">
        <p style="font-size: 14px; color: #374151; margin: 0;">
          Need help?
          <a href="mailto:soporte@chef.com" style="color: #4a6741; font-weight: bold; text-decoration: none;">Contact support</a>
        </p>
      </div>

      <div style="margin-top: 32px; display: flex; justify-content: center; gap: 24px; font-size: 12px; color: #9ca3af;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <span>🛡️</span>
          <span>SSL Security</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
          <span>✓</span>
          <span>Verified</span>
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
  let label = 'Weak'
  let color = '#ef4444'

  if (pwd.length >= 8) strength++
  if (pwd.match(/[a-z]/)) strength++
  if (pwd.match(/[A-Z]/)) strength++
  if (pwd.match(/[0-9]/)) strength++
  if (pwd.match(/[!@#$%^&*]/)) strength++

  if (strength <= 2) {
    label = 'Weak'
    color = '#ef4444'
  } else if (strength <= 3) {
    label = 'Fair'
    color = '#eab308'
  } else if (strength <= 4) {
    label = 'Good'
    color = '#3b82f6'
  } else {
    label = 'Very strong'
    color = '#22c55e'
  }

  return {
    percentage: (strength / 5) * 100,
    label,
    color
  }
})

onMounted(async () => {
  // Verify token and retrieve prospect info
  try {
    const response = await fetch(`/api/prospect-tokens/verify`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ token })
    })

    if (!response.ok) {
      errors.value = ['Invalid or expired token. Please request a new one.']
      return
    }

    const data = await response.json()
    prospectName.value = data.prospect.full_name
    prospectEmail.value = data.prospect.email
  } catch (error) {
    errors.value = ['Error verifying token. Please try again.']
  }
})

const submitForm = async () => {
  errors.value = []
  loading.value = true

  // Basic validations
  if (form.value.password.length < 8) {
    errors.value.push('Password must be at least 8 characters long')
    loading.value = false
    return
  }

  if (form.value.password !== form.value.password_confirmation) {
    errors.value.push('Passwords do not match')
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
      successMessage.value = '✅ Registration completed successfully'
      setTimeout(() => {
        router.push({ name: 'Home' })
      }, 2000)
    } else {
      const data = await response.json()
      errors.value = [data.message || 'Error completing registration']
    }
  } catch (error) {
    errors.value = ['Connection error. Please try again.']
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
