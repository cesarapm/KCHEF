<template>
  <v-app>
    <!-- Header Navigation - Light with Dark Text -->
    <header ref="headerRef" class="kaczmarek-header">
      <div class="header-container">
        <router-link to="/" class="logo-section">
          <img
            :src="'/images/logo.webp'"
            alt="KCHEF SERVICES - Private Chef Services"
            class="logo-image"
          />
        </router-link>

        <nav class="header-nav">
          <!-- Desktop Menu -->
          <div class="nav-desktop">
            <a href="#home" @click.prevent="smoothScroll('#home')" class="nav-item">HOME</a>
            <router-link to="/services" class="nav-item">OUR SERVICES</router-link>
            <router-link to="/meet-the-team" class="nav-item">MEET LORE & ADAM</router-link>
            <router-link to="/why-families-choose-us" class="nav-item">WHY FAMILIES CHOOSE US</router-link>


            <template v-if="authReady && !isAuthenticated">
              <router-link to="/login" class="nav-item">SIGN IN</router-link>
            </template>

            <template v-else-if="authReady">
              <div class="auth-nav-group">
                <router-link to="/dashboard" class="nav-item nav-item-auth">Dashboard</router-link>
                <a href="#" @click.prevent="logout" class="nav-item nav-item-auth logout">Sign Out</a>
              </div>
            </template>
            <a href="#consultation" @click.prevent="smoothScroll('#consultation')" class="btn-consultation">Book Your Consultation</a>
          </div>

          <!-- Mobile Menu Toggle -->
          <button class="mobile-menu-toggle" @click="drawer = !drawer">
            <span></span>
            <span></span>
            <span></span>
          </button>
        </nav>
      </div>
    </header>

    <!-- Mobile Drawer -->
    <v-navigation-drawer
      v-model="drawer"
      temporary
      location="right"
      class="mobile-drawer"
    >
      <v-list class="mobile-menu-list">
        <v-list-item
          @click="smoothScroll('#home'); drawer = false"
          class="mobile-list-item"
        >
          <v-list-item-title>HOME</v-list-item-title>
        </v-list-item>
        <v-list-item
          :to="{ name: 'Services' }"
          class="mobile-list-item"
        >
          <v-list-item-title>OUR SERVICES</v-list-item-title>
        </v-list-item>
        <v-list-item
          :to="{ name: 'MeetTheTeam' }"
          class="mobile-list-item"
        >
          <v-list-item-title>MEET LORE & ADAM</v-list-item-title>
        </v-list-item>
        <v-list-item
          :to="{ name: 'WhyFamiliesChooseUs' }"
          class="mobile-list-item"
        >
          <v-list-item-title>WHY FAMILIES CHOOSE US</v-list-item-title>
        </v-list-item>
        <!-- <v-list-item
          :to="{ name: 'WhyFamiliesChooseUs' }"
          class="mobile-list-item"
        >
          <v-list-item-title>WHY FAMILIES CHOOSE US</v-list-item-title>
        </v-list-item> -->
        <v-list-item
          v-if="authReady && !isAuthenticated"
          :to="{ name: 'Login' }"
          class="mobile-list-item"
        >
          <v-list-item-title>SIGN IN</v-list-item-title>
        </v-list-item>
        <v-list-item
          v-if="authReady && isAuthenticated"
          :to="{ name: 'Dashboard' }"
          class="mobile-list-item"
        >
          <v-list-item-title>Dashboard</v-list-item-title>
        </v-list-item>
        <v-list-item
          v-if="authReady && isAuthenticated"
          @click="logout"
          class="mobile-list-item"
        >
          <v-list-item-title>Sign Out</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- Main Content -->
    <v-main class="main-content">
      <router-view></router-view>
    </v-main>

    <!-- Footer -->
    <footer class="kaczmarek-footer">
      <div class="footer-container">
        <div class="footer-content">
          <img
            :src="'/images/logo.webp'"
            alt="KCHEF SERVICES - Private Chef Services"
            class="footer-logo-image"
          />
          <p class="footer-description">Personalized culinary experiences designed around your family.</p>
        </div>
        <div class="footer-links">
          <a href="mailto:hello@kchefservices.com">hello@kchefservices.com</a>
          <p>© 2026 KChef Services. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </v-app>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthState, logoutUser, checkAuthentication } from './composables/useAuthState'

const router = useRouter()
const route = useRoute()
const { isAuthenticated } = useAuthState()
const authReady = ref(false)
const drawer = ref(false)
const headerRef = ref(null)
let lastScrollTop = 0
let ticking = false
const scrollThreshold = 6

onMounted(async () => {
  window.addEventListener('scroll', onScroll, { passive: true })
  handleScroll()
  try {
    await checkAuthentication()
  } finally {
    authReady.value = true
  }
})

onUnmounted(() => {
  window.removeEventListener('scroll', onScroll)
})

const handleScroll = () => {
  const header = headerRef.value
  if (!header) return

  const scrollTop = window.pageYOffset || document.documentElement.scrollTop

  if (scrollTop > 10) {
    header?.classList.add('scrolled')
  } else {
    header?.classList.remove('scrolled')
  }

  if (drawer.value) {
    header.classList.remove('hidden')
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop
    return
  }

  const scrollDifference = Math.abs(scrollTop - lastScrollTop)
  if (scrollDifference < scrollThreshold) {
    return
  }

  if (scrollTop > lastScrollTop && scrollTop > 120) {
    header.classList.add('hidden')
  } else if (scrollTop < lastScrollTop || scrollTop <= 80) {
    header.classList.remove('hidden')
  }

  lastScrollTop = scrollTop <= 0 ? 0 : scrollTop
}

const onScroll = () => {
  if (!ticking) {
    requestAnimationFrame(() => {
      handleScroll()
      ticking = false
    })
    ticking = true
  }
}

watch(drawer, (isOpen) => {
  if (isOpen) {
    headerRef.value?.classList.remove('hidden')
  }
})

const smoothScroll = async (selector) => {
  drawer.value = false

  if (route.path !== '/') {
    await router.push({ path: '/', hash: selector })
    await nextTick()
  } else {
    await router.replace({ path: '/', hash: selector })
    await nextTick()
  }

  const element = document.querySelector(selector)
  if (element) {
    const headerOffset = 90
    const elementPosition = element.getBoundingClientRect().top + window.pageYOffset
    const targetPosition = Math.max(0, elementPosition - headerOffset)
    window.scrollTo({ top: targetPosition, behavior: 'smooth' })
  }
}

const logout = async () => {
  drawer.value = false
  const success = await logoutUser()
  if (success) {
    router.push('/')
  }
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* ===== HEADER STYLES ===== */
.kaczmarek-header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  transform: translateY(0);
  opacity: 1;
  background: rgba(245, 245, 245, 0.9);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(0, 0, 0, 0.08);
  transition:
    transform 0.45s cubic-bezier(0.22, 1, 0.36, 1),
    opacity 0.35s ease,
    background 0.3s ease,
    box-shadow 0.3s ease;
  will-change: transform, opacity;
}

.kaczmarek-header.hidden {
  transform: translateY(-110%);
  opacity: 0;
  pointer-events: none;
}

.kaczmarek-header.scrolled {
  background: rgba(250, 250, 250, 0.95);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
}

.header-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 16px 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo-section {
  text-decoration: none;
  color: #1a1a1a;
  display: flex;
  align-items: center;
  gap: 12px;
  transition: all 0.3s ease;
}

.logo-section:hover {
  transform: translateY(-2px);
}

.logo-image {
  display: block;
  width: clamp(80px, 18vw, 80px);
  height: auto;
}

/* ===== NAVIGATION ===== */
.header-nav {
  display: flex;
  align-items: center;
  gap: 8px;
}

.nav-desktop {
  display: flex;
  align-items: center;
  gap: 32px;
}

.nav-item {
  font-size: 12px;
  letter-spacing: 1px;
  color: #333;
  text-decoration: none;
  text-transform: uppercase;
  font-weight: 500;
  transition: all 0.3s ease;
  position: relative;
  text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
}

.nav-item:hover {
  color: #1a1a1a;
  text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.nav-item::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 0;
  height: 2px;
  background: #1a1a1a;
  transition: width 0.3s ease;
  box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
}

.nav-item:hover::after {
  width: 100%;
}

.nav-item.logout {
  color: #c2410c;
  text-shadow: 0 1px 2px rgba(255, 255, 255, 0.6);
}

.nav-item.logout:hover {
  color: #7c2d12;
  text-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.auth-nav-group {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-left: 2px;
  padding-left: 14px;
  border-left: 1px solid rgba(0, 0, 0, 0.14);
}

.nav-item-auth {
  font-weight: 600;
}

.btn-consultation {
  background: rgba(74, 103, 65, 0.85);
  color: white;
  padding: 10px 20px;
  border-radius: 24px;
  font-size: 12px;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  border: 1px solid rgba(74, 103, 65, 0.3);
  text-decoration: none;
  display: inline-block;
}

.btn-consultation:hover {
  background: rgba(74, 103, 65, 1);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
  transform: translateY(-2px);
}

/* ===== MOBILE MENU TOGGLE ===== */
.mobile-menu-toggle {
  display: none;
  flex-direction: column;
  gap: 5px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 8px;
}

.mobile-menu-toggle span {
  width: 24px;
  height: 2px;
  background: #1a1a1a;
  border-radius: 2px;
  transition: all 0.3s ease;
}

/* ===== MOBILE DRAWER ===== */
.mobile-drawer {
  background: rgba(245, 245, 245, 0.95) !important;
  backdrop-filter: blur(10px);
}

.mobile-menu-list {
  background: transparent;
}

.mobile-list-item {
  color: #333;
  border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}

.mobile-list-item:hover {
  background: rgba(0, 0, 0, 0.05);
  color: #1a1a1a;
}

/* ===== MAIN CONTENT ===== */
.main-content {
  margin-top: 72px;
  background: #f5f5f5;
}

/* ===== FOOTER ===== */
.kaczmarek-footer {
  background: rgba(240, 240, 240, 0.95);
  backdrop-filter: blur(10px);
  border-top: 1px solid rgba(0, 0, 0, 0.08);
  color: #1a1a1a;
  padding: 60px 40px;
  box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.08);
}

.footer-container {
  max-width: 1400px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: start;
}

.footer-logo-image {
  width: min(100%, 90px);
  height: auto;
  margin-bottom: 16px;
}

.footer-description {
  font-size: 14px;
  line-height: 1.6;
  color: #555;
}

.footer-links {
  text-align: right;
}

.footer-links a {
  display: inline-block;
  font-size: 14px;
  color: #333;
  text-decoration: none;
  transition: all 0.3s ease;
  margin-bottom: 12px;
  text-shadow: 0 1px 2px rgba(255, 255, 255, 0.6);
}

.footer-links a:hover {
  color: #1a1a1a;
  text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.footer-links p {
  font-size: 12px;
  color: #888;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .header-container {
    padding: 12px 20px;
  }

  .nav-desktop {
    display: none;
  }

  .mobile-menu-toggle {
    display: flex;
  }

  .footer-container {
    grid-template-columns: 1fr;
    gap: 30px;
    text-align: center;
  }

  .footer-links {
    text-align: center;
  }

  .footer-logo-image {
    display: block;
    margin-left: auto;
    margin-right: auto;
  }

  .kaczmarek-footer {
    padding: 40px 20px;
  }
}

@media (max-width: 480px) {
  .header-container {
    padding: 12px 16px;
  }

  .logo-image {
    width: clamp(60px, 42vw, 60px);
  }
}
</style>
