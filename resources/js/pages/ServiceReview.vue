<template>
  <div class="service-page">
    <div class="service-shell">
      <div class="service-header">
        <button class="btn btn-secondary" @click="goBack">← Back to dashboard</button>
        <div>
          <h1>Service {{ service?.service_number || '-' }}</h1>
          <p>Order {{ service?.order_number || '-' }}</p>
        </div>
      </div>

      <p v-if="loading">Loading service...</p>
      <p v-else-if="error" class="error">{{ error }}</p>

      <template v-else-if="service">
        <section class="card">
          <h2>General</h2>
          <p><strong>Title:</strong> {{ service.title || 'Pending title' }}</p>
          <p><strong>Status:</strong> {{ formatStatus(service.status) }}</p>
          <p><strong>Chef:</strong> {{ service.chef_name || 'Not assigned yet' }}</p>
          <p><strong>Scheduled:</strong> {{ formatDate(service.scheduled_at) }}</p>
          <p v-if="service.description"><strong>Description:</strong> {{ service.description }}</p>
        </section>

        <section class="card" v-if="service.free_menu_title || service.free_menu_markdown">
          <h2>Chef Menu</h2>
          <p><strong>{{ service.free_menu_title || 'Menu' }}</strong></p>
          <pre class="text-block">{{ service.free_menu_markdown || 'No menu details yet.' }}</pre>
        </section>

        <section class="card" v-if="service.grocery_list">
          <h2>Grocery List</h2>
          <pre v-if="service.grocery_list.content_markdown" class="text-block">{{ service.grocery_list.content_markdown }}</pre>
          <ul v-if="service.grocery_list.items?.length" class="grocery-items">
            <li v-for="item in service.grocery_list.items" :key="item.id">
              <strong>{{ item.item }}</strong>
              <span v-if="item.quantity"> · {{ item.quantity }}</span>
              <span v-if="item.description"> — {{ item.description }}</span>
            </li>
          </ul>
        </section>

        <section class="card" v-if="service.chef_notes?.length">
          <h2>Chef Notes</h2>
          <ul class="notes-list">
            <li v-for="note in service.chef_notes" :key="note.id">
              <span class="note-priority">{{ formatPriority(note.priority) }}</span>
              <span>{{ note.note }}</span>
            </li>
          </ul>
        </section>
      </template>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const service = ref(null)
const loading = ref(false)
const error = ref('')

const formatDate = (value) => {
  if (!value) return '-'
  return new Date(value).toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const formatStatus = (status) => {
  const map = {
    pendiente_configuracion: 'Pending setup',
    menu_enviado: 'Menu sent',
    pendiente_seleccion: 'Waiting for selection',
    seleccion_confirmada: 'Selection confirmed',
    agendado: 'Scheduled',
    en_preparacion: 'In preparation',
    en_proceso: 'In progress',
    en_entrega: 'Out for delivery',
    home_service: 'Home service',
    completado: 'Completed',
  }
  return map[status] || status
}

const formatPriority = (priority) => {
  const map = { high: 'High', medium: 'Medium', low: 'Low' }
  return map[priority] || priority || 'Note'
}

const loadService = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await fetch(`/api/client/services/${route.params.id}`, {
      credentials: 'include',
      headers: { Accept: 'application/json' },
    })
    if (!response.ok) {
      error.value = 'Could not load service details.'
      return
    }
    const data = await response.json()
    service.value = data.service
  } catch {
    error.value = 'Could not load service details.'
  } finally {
    loading.value = false
  }
}

const goBack = () => router.push('/dashboard')

onMounted(loadService)
</script>

<style scoped>
.service-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
  padding: 28px 24px 24px;
}

.service-shell {
  max-width: 1080px;
  margin: 0 auto;
  display: grid;
  gap: 16px;
}

.service-header {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  align-items: center;
  background: #fff;
  border-radius: 14px;
  padding: 20px;
  box-shadow: 0 10px 24px rgba(17, 24, 39, 0.08);
}

.service-header h1 {
  margin: 0;
  color: #9a3412;
}

.service-header p {
  margin: 6px 0 0;
  color: #64748b;
}

.card {
  background: #fff;
  border-radius: 14px;
  padding: 18px;
  box-shadow: 0 10px 24px rgba(17, 24, 39, 0.08);
}

.card h2 {
  margin: 0 0 10px;
  color: #9a3412;
}

.text-block {
  white-space: pre-wrap;
  font-family: inherit;
  background: #fff7ed;
  border: 1px solid #fdba74;
  border-radius: 10px;
  padding: 10px;
}

.grocery-items,
.notes-list {
  margin: 0;
  padding-left: 18px;
  display: grid;
  gap: 6px;
}

.note-priority {
  display: inline-block;
  min-width: 62px;
  font-weight: 700;
  color: #9a3412;
}

.btn {
  border: none;
  border-radius: 10px;
  padding: 10px 14px;
  font-weight: 600;
  cursor: pointer;
}

.btn-secondary {
  background: #334155;
  color: #fff;
}

.error {
  color: #b91c1c;
  font-weight: 600;
}

@media (max-width: 768px) {
  .service-page {
    padding: 20px 14px 14px;
  }

  .service-header {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>

