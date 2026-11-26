<template>
  <div style="background: linear-gradient(135deg, #e7f3ef 0%, #d5e9e2 100%); padding: 64px 16px; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <div style="width: 100%; max-width: 500px;">
      <!-- Main Card -->
      <div style="background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
        <!-- Red Header -->
        <div style="background: linear-gradient(90deg, #1d3a32 0%, #2b4e43 50%, #1d3a32 100%); padding: 40px 32px; color: white; position: relative; overflow: hidden;">
          <!-- Background decoration -->
          <div style="position: absolute; top: 0; right: 0; width: 160px; height: 160px; background: rgba(255,255,255,0.1); border-radius: 50%; margin-right: -80px; margin-top: -80px;"></div>

          <div style="position: relative; z-index: 10;">
            <div style="text-align: center;">
              <h1 style="font-size: 28px; font-weight: 900; margin: 0; line-height: 1.2;">CHEF</h1>
              <p style="color: rgba(255,255,255,0.95); margin: 12px 0 0 0; font-size: 14px; font-weight: 500;">Sign in to your account</p>
            </div>
          </div>
        </div>

        <!-- Form Content -->
        <form @submit.prevent="submitForm" style="padding: 40px 32px 48px;">
          <!-- Error Messages -->
          <div v-if="errors.length" style="background: #fef2f2; border-left: 4px solid #b42318; border-radius: 12px; margin-bottom: 24px; padding: 16px; display: flex; gap: 12px;">
            <span style="font-size: 20px;">⚠️</span>
            <div style="flex: 1;">
              <p style="font-weight: bold; font-size: 14px; margin: 0 0 8px 0; color: #7a271a;">Sign in failed</p>
              <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px;">
                <li v-for="error in errors" :key="error" style="display: flex; align-items: center; margin-bottom: 4px; color: #9f2d21;">
                  <span style="display: inline-block; width: 6px; height: 6px; background: #b42318; border-radius: 50%; margin-right: 8px;"></span>
                  {{ error }}
                </li>
              </ul>
            </div>
          </div>

          <!-- Success Message -->
          <div v-if="successMessage" style="background: #e7f7ee; border-left: 4px solid #1d7a46; border-radius: 12px; margin-bottom: 24px; padding: 16px; display: flex; gap: 12px;">
            <span style="font-size: 20px;">✅</span>
            <p style="color: #1a6a3d; font-weight: 500; margin: 0;">{{ successMessage }}</p>
          </div>

          <!-- Email -->
          <div style="margin-bottom: 24px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 16px; margin: 0 0 12px 0;">
              Email <span style="color: #1d3a32;">*</span>
            </label>
            <div style="position: relative;">
              <input
                v-model="form.email"
                type="email"
                placeholder="your@email.com"
                style="width: 100%; padding: 16px 20px 16px 20px; font-size: 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); box-sizing: border-box;"
                @focus="$event.target.style.borderColor = '#1d3a32'; $event.target.style.boxShadow = '0 0 12px rgba(29,58,50,0.2)'"
                @blur="$event.target.style.borderColor = '#e5e7eb'; $event.target.style.boxShadow = '0 2px 8px rgba(0,0,0,0.05)'"
                @mouseover="$event.target.style.borderColor = '#d1d5db'"
              />
              <span style="position: absolute; right: 16px; top: 16px; font-size: 20px;">📧</span>
            </div>
          </div>

          <!-- Password -->
          <div style="margin-bottom: 32px;">
            <label style="display: block; font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 16px; margin: 0 0 12px 0;">
              Password <span style="color: #1d3a32;">*</span>
            </label>
            <div style="position: relative;">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Your password"
                style="width: 100%; padding: 16px 20px 16px 20px; font-size: 16px; border: 2px solid #e5e7eb; border-radius: 12px; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); box-sizing: border-box;"
                @focus="$event.target.style.borderColor = '#1d3a32'; $event.target.style.boxShadow = '0 0 12px rgba(29,58,50,0.2)'"
                @blur="$event.target.style.borderColor = '#e5e7eb'; $event.target.style.boxShadow = '0 2px 8px rgba(0,0,0,0.05)'"
                @mouseover="$event.target.style.borderColor = '#d1d5db'"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                style="position: absolute; right: 16px; top: 16px; background: none; border: none; cursor: pointer; font-size: 20px; padding: 0;"
              >
                {{ showPassword ? '👁️' : '👁️‍🗨️' }}
              </button>
            </div>
          </div>

          <!-- Login Button -->
          <button
            type="submit"
            :disabled="loading || !form.email || !form.password"
            style="width: 100%; padding: 16px 24px; background: linear-gradient(90deg, #1d3a32 0%, #2f5a4f 100%); color: white; font-weight: bold; font-size: 16px; border: none; border-radius: 12px; cursor: pointer; transition: all 0.3s; box-shadow: 0 8px 24px rgba(29,58,50,0.3); margin-bottom: 24px; display: flex; align-items: center; justify-content: center; gap: 12px; box-sizing: border-box;"
            :style="{
              opacity: loading || !form.email || !form.password ? 0.5 : 1,
              cursor: loading || !form.email || !form.password ? 'not-allowed' : 'pointer'
            }"
          >
            <span v-if="loading" style="display: inline-flex; align-items: center;">
              <svg style="width: 20px; height: 20px; margin-right: 8px; animation: spin 1s linear infinite;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 2a10 10 0 0 1 10 10"></path>
              </svg>
              Signing in...
            </span>
            <span v-else>
              🔓 Sign In
            </span>
          </button>

          <!-- Recovery Link -->
          <div style="text-align: center;">
            <p style="font-size: 13px; color: #6b7280; margin: 0 0 8px 0;">
              Forgot your password?
              <a href="#" style="color: #1d3a32; text-decoration: none; font-weight: bold;">Reset it here</a>
            </p>
            <p style="font-size: 13px; color: #6b7280; margin: 0;">
              Don't have an account yet?
              <router-link to="/prospecto" style="color: #1d3a32; text-decoration: none; font-weight: bold;">Register as a prospect</router-link>
            </p>
          </div>

          <!-- Security -->
          <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e5e7eb; text-align: center;">
            <p style="text-align: center; font-size: 12px; color: #111827; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; margin: 0;">
              🔒 100% Secure Connection | Your data is protected
            </p>
          </div>
        </form>
      </div>

      <!-- Support Link -->
      <div style="margin-top: 48px; text-align: center;">
        <p style="font-size: 14px; color: #374151; margin: 0;">
          Need help?
          <a href="mailto:support@kchefservices.com" style="color: #1d3a32; font-weight: bold; text-decoration: none;">Contact support</a>
        </p>
      </div>
    </div>

    <div
      v-if="loading"
      style="position: fixed; inset: 0; background: rgba(17,24,39,0.45); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 2000; padding: 16px;"
      role="dialog"
      aria-modal="true"
      aria-label="Verifying your account"
    >
      <div style="width: 100%; max-width: 380px; background: white; border-radius: 14px; box-shadow: 0 20px 50px rgba(0,0,0,0.2); padding: 28px 24px; text-align: center;">
        <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
          <svg style="width: 28px; height: 28px; animation: spin 1s linear infinite; color: #1d3a32;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M12 2a10 10 0 0 1 10 10"></path>
          </svg>
        </div>
        <p style="margin: 0; font-size: 18px; font-weight: 700; color: #111827;">Signing in...</p>
        <p style="margin: 8px 0 0; color: #6b7280;">Verifying your account. Please wait.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthState, loginUser } from '../composables/useAuthState'

const router = useRouter()
const { currentUser } = useAuthState()

const form = ref({
  email: '',
  password: '',
})

const errors = ref([])
const successMessage = ref('')
const loading = ref(false)
const showPassword = ref(false)

const submitForm = async () => {
  errors.value = []
  loading.value = true

  // Basic validations
  if (!form.value.email) {
    errors.value.push('Email is required')
    loading.value = false
    return
  }

  if (!form.value.password) {
    errors.value.push('Password is required')
    loading.value = false
    return
  }

  try {
    // Use the composable for login
    const success = await loginUser(form.value.email, form.value.password)

    if (success) {
      successMessage.value = '✅ Signing in...'

      // Redirect after 800ms
      setTimeout(async () => {
        try {
          const userData = currentUser.value
        //   console.log('User data:', userData)

          // If has prospect_id, check onboarding
          if (userData && userData.prospect_id) {
            try {
              const onboardingRes = await fetch(`/api/prospects/${userData.prospect_id}/onboarding`)
              if (onboardingRes.ok) {
                const onboardingData = await onboardingRes.json()
                // If onboarding is not completed, go to form
                if (onboardingData.onboarding && onboardingData.onboarding.status !== 'completed') {
                  router.push('/onboarding')
                  return
                }
              }
            } catch (err) {
              console.warn('Could not fetch onboarding status, redirecting to dashboard:', err)
            }
          }

          // By default, go to dashboard
          router.push('/dashboard')
        } catch (err) {
          console.error('Redirect error:', err)
          router.push('/dashboard')
        }
      }, 800)
    } else {
      errors.value = ['Email or password incorrect']
    }
  } catch (error) {
    console.error('Login error:', error)
    errors.value = ['Connection error. Please try again.']
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
