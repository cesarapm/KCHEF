<template>
  <div class="dashboard-page">
    <div class="dashboard-shell">
      <header class="dashboard-header">
        <div>
          <h1>Client Dashboard</h1>
          <p>Welcome, {{ user?.name || 'Client' }}</p>
        </div>
        <button class="btn btn-danger" @click="logout">Sign Out</button>
      </header>

      <section class="cards-grid">
        <article class="card">
          <h2>Profile</h2>
          <p><strong>Name:</strong> {{ user?.name || '-' }}</p>
          <p><strong>Email:</strong> {{ user?.email || '-' }}</p>
          <p><strong>Role:</strong> {{ user?.role || '-' }}</p>
        </article>

        <article class="card">
          <h2>Cooking Profile</h2>
          <p v-if="loadingOnboarding">Loading...</p>
          <template v-else>
            <p><strong>Status:</strong> {{ onboarding?.status === 'completado' ? 'Completed' : 'Pending' }}</p>
            <button v-if="onboarding?.status !== 'completado'" class="btn btn-primary" @click="goToOnboarding">
              Complete profile
            </button>
          </template>
        </article>
      </section>

      <section class="card">
        <div class="section-head">
          <div>
            <h2>Step 1: Select your dishes</h2>
            <p class="muted">You must complete this step before moving to service details.</p>
          </div>
          <button class="btn btn-secondary" @click="loadClientOrders" :disabled="loadingOrders">
            {{ loadingOrders ? 'Loading...' : 'Refresh' }}
          </button>
        </div>

        <p v-if="ordersError" class="error">{{ ordersError }}</p>
        <p v-if="!loadingOrders && !orders.length" class="muted">No confirmed orders available yet.</p>

        <div v-for="order in orders" :key="order.id" class="order-card">
          <div class="order-header">
            <h3>Order {{ order.order_number }}</h3>
            <span class="chip" :class="order.selection.meets_minimum ? 'chip-success' : 'chip-warning'">
              {{ order.selection.selected_count }}/{{ order.selection.total_dishes }} selected
            </span>
          </div>

          <p class="muted">
            Required minimum: {{ order.selection.required_count }} dishes ({{ order.selection.min_percent }}%)
          </p>

          <div class="checkbox-grid" v-if="order.dishes.length">
            <label v-for="dish in order.dishes" :key="dish.id" class="dish-option">
              <input
                type="checkbox"
                :checked="isDishChecked(order.id, dish.id)"
                @change="toggleDish(order.id, dish.id, $event.target.checked)"
              />
              <div>
                <strong>{{ dish.title }}</strong>
                <p>{{ dish.description_markdown || 'No description' }}</p>
              </div>
            </label>
          </div>
          <p v-else class="muted">Admin/Chef has not assigned dishes to this order yet.</p>

          <div class="order-actions">
            <button class="btn btn-secondary" @click="saveSelections(order.id)" :disabled="savingOrderId === order.id">
              {{ savingOrderId === order.id ? 'Saving...' : 'Save selection' }}
            </button>
            <button class="btn btn-primary" @click="confirmSelections(order.id)" :disabled="confirmingOrderId === order.id">
              {{ confirmingOrderId === order.id ? 'Confirming...' : 'Confirm selection' }}
            </button>
          </div>
        </div>
      </section>

      <section class="card">
        <h2>Step 2: Services</h2>
        <p class="muted">Services are shown after dish selection reaches the minimum required.</p>

        <template v-for="order in orders" :key="`services-${order.id}`">
          <div class="order-card" v-if="order.selection.meets_minimum">
            <h3>Order {{ order.order_number }} services</h3>
            <p v-if="!order.services.length" class="muted">
              Services are not available yet for this order. Admin/Chef will enable them after setup.
            </p>
            <div class="services-grid">
              <button
                v-for="service in order.services"
                :key="service.id"
                type="button"
                class="service-box service-button"
                :class="{ 'service-box-active': selectedServiceIdByOrder[order.id] === service.id }"
                @click="selectService(order.id, service.id)"
              >
                <h4>Service {{ service.service_number }}</h4>
                <p>{{ service.title || 'Pending title' }}</p>
                <p class="muted">Status: {{ formatStatus(service.status) }}</p>
                <p class="muted">Scheduled: {{ formatDate(service.scheduled_at) }}</p>
                <p class="service-open-hint">Click to preview · Open full page below</p>
              </button>
            </div>

            <div v-if="getSelectedService(order.id)" class="selected-service-panel">
              <h4>Reviewing Service {{ getSelectedService(order.id).service_number }}</h4>
              <p><strong>Title:</strong> {{ getSelectedService(order.id).title || 'Pending title' }}</p>
              <p><strong>Chef:</strong> {{ getSelectedService(order.id).chef_name || 'Not assigned yet' }}</p>
              <p><strong>Status:</strong> {{ formatStatus(getSelectedService(order.id).status) }}</p>
              <p><strong>Scheduled:</strong> {{ formatDate(getSelectedService(order.id).scheduled_at) }}</p>

              <div class="service-section" v-if="getSelectedService(order.id).description">
                <h5>Service Description</h5>
                <p class="text-block">{{ getSelectedService(order.id).description }}</p>
              </div>

              <div class="service-section" v-if="getSelectedService(order.id).free_menu_title || getSelectedService(order.id).free_menu_markdown">
                <h5>Chef Menu Proposal</h5>
                <p><strong>{{ getSelectedService(order.id).free_menu_title || 'Menu' }}</strong></p>
                <pre class="text-block">{{ getSelectedService(order.id).free_menu_markdown || 'No menu details yet.' }}</pre>
              </div>

              <div class="service-section" v-if="getSelectedService(order.id).grocery_list">
                <h5>Grocery List</h5>
                <pre v-if="getSelectedService(order.id).grocery_list.content_markdown" class="text-block">{{ getSelectedService(order.id).grocery_list.content_markdown }}</pre>
                <ul v-if="getSelectedService(order.id).grocery_list.items?.length" class="grocery-items">
                  <li v-for="item in getSelectedService(order.id).grocery_list.items" :key="item.id">
                    <strong>{{ item.item }}</strong>
                    <span v-if="item.quantity"> · {{ item.quantity }}</span>
                    <span v-if="item.description"> — {{ item.description }}</span>
                  </li>
                </ul>
              </div>

              <div class="service-section" v-if="getSelectedService(order.id).chef_notes?.length">
                <h5>Chef Notes</h5>
                <ul class="notes-list">
                  <li v-for="note in getSelectedService(order.id).chef_notes" :key="note.id">
                    <span class="note-priority">
                      {{ note.priority === 'high' ? 'High' : note.priority === 'medium' ? 'Medium' : note.priority === 'low' ? 'Low' : 'Note' }}
                    </span>
                    <span>{{ note.note }}</span>
                  </li>
                </ul>
              </div>

              <div class="service-section">
                <button class="btn btn-primary" @click="openServicePage(getSelectedService(order.id).id)">
                  Open full service page
                </button>
              </div>
            </div>
          </div>
        </template>
      </section>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const user = ref(null)
const onboarding = ref(null)

const loadingOnboarding = ref(false)
const loadingOrders = ref(false)
const orders = ref([])
const ordersError = ref('')
const selectedByOrder = ref({})
const selectedServiceIdByOrder = ref({})
const savingOrderId = ref(null)
const confirmingOrderId = ref(null)

const authHeaders = () => ({
  Accept: 'application/json',
  'Content-Type': 'application/json',
  'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || '',
})

const readApiError = async (response, fallbackMessage) => {
  const contentType = response.headers.get('content-type') || ''
  if (contentType.includes('application/json')) {
    const data = await response.json()
    const validationErrors = data.errors ? Object.values(data.errors).flat().join(' ') : ''
    return validationErrors || data.message || fallbackMessage
  }
  return fallbackMessage
}

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

const loadUser = async () => {
  const response = await fetch('/api/user', {
    credentials: 'include',
    headers: { Accept: 'application/json' },
  })

  if (!response.ok) {
    await router.push('/login')
    return
  }

  user.value = await response.json()
}

const loadOnboarding = async () => {
  if (!user.value?.prospect_id) return
  loadingOnboarding.value = true
  try {
    const response = await fetch(`/api/prospects/${user.value.prospect_id}/onboarding`, {
      credentials: 'include',
      headers: { Accept: 'application/json' },
    })
    if (response.ok) {
      const data = await response.json()
      onboarding.value = data.onboarding
    }
  } finally {
    loadingOnboarding.value = false
  }
}

const loadClientOrders = async () => {
  loadingOrders.value = true
  ordersError.value = ''

  try {
    const response = await fetch('/api/client/orders', {
      credentials: 'include',
      headers: { Accept: 'application/json' },
    })

    if (!response.ok) {
      ordersError.value = await readApiError(response, 'Could not load orders.')
      return
    }

    const data = await response.json()
    orders.value = data.orders || []

    const selections = {}
    const selectedServices = {}
    for (const order of orders.value) {
      selections[order.id] = new Set(
        (order.dishes || []).filter((dish) => dish.is_selected_by_client).map((dish) => dish.id),
      )
      selectedServices[order.id] = order.services?.[0]?.id || null
    }
    selectedByOrder.value = selections
    selectedServiceIdByOrder.value = selectedServices
  } catch (error) {
    ordersError.value = 'Could not load orders.'
  } finally {
    loadingOrders.value = false
  }
}

const isDishChecked = (orderId, dishId) => selectedByOrder.value[orderId]?.has(dishId)

const toggleDish = (orderId, dishId, checked) => {
  if (!selectedByOrder.value[orderId]) {
    selectedByOrder.value[orderId] = new Set()
  }

  if (checked) selectedByOrder.value[orderId].add(dishId)
  else selectedByOrder.value[orderId].delete(dishId)
}

const saveSelections = async (orderId) => {
  savingOrderId.value = orderId
  try {
    const selectedIds = Array.from(selectedByOrder.value[orderId] || [])
    const response = await fetch(`/api/client/orders/${orderId}/selections`, {
      method: 'PATCH',
      credentials: 'include',
      headers: authHeaders(),
      body: JSON.stringify({ selected_order_dish_ids: selectedIds }),
    })

    if (!response.ok) {
      alert(await readApiError(response, 'Could not save your selection.'))
      return
    }

    await loadClientOrders()
  } finally {
    savingOrderId.value = null
  }
}

const confirmSelections = async (orderId) => {
  confirmingOrderId.value = orderId
  try {
    const selectedIds = Array.from(selectedByOrder.value[orderId] || [])
    const response = await fetch(`/api/client/orders/${orderId}/confirm-selections`, {
      method: 'POST',
      credentials: 'include',
      headers: authHeaders(),
      body: JSON.stringify({ selected_order_dish_ids: selectedIds }),
    })

    if (!response.ok) {
      alert(await readApiError(response, 'Could not confirm selection.'))
      return
    }

    await loadClientOrders()
  } finally {
    confirmingOrderId.value = null
  }
}

const logout = async () => {
  await fetch('/logout', {
    method: 'POST',
    credentials: 'include',
    headers: authHeaders(),
  })
  await router.push('/')
}

const goToOnboarding = () => router.push('/onboarding')
const openServicePage = (serviceId) => router.push(`/dashboard/service/${serviceId}`)

const selectService = (orderId, serviceId) => {
  selectedServiceIdByOrder.value[orderId] = serviceId
}

const getSelectedService = (orderId) => {
  const order = orders.value.find((item) => item.id === orderId)
  if (!order?.services?.length) return null

  const selectedId = selectedServiceIdByOrder.value[orderId]
  return order.services.find((service) => service.id === selectedId) || order.services[0]
}

onMounted(async () => {
  await loadUser()
  await Promise.all([loadOnboarding(), loadClientOrders()])
})
</script>

<style scoped>
.dashboard-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
  padding: 28px 24px 24px;
}

.dashboard-shell {
  max-width: 1180px;
  margin: 0 auto;
  display: grid;
  gap: 20px;
}

.dashboard-header {
  background: #fff;
  border-radius: 14px;
  padding: 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 10px 24px rgba(17, 24, 39, 0.08);
}

.dashboard-header h1 {
  margin: 0;
  color: #9a3412;
}

.dashboard-header p {
  margin: 6px 0 0;
  color: #475569;
}

.cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 16px;
}

.card {
  background: #fff;
  border-radius: 14px;
  padding: 20px;
  box-shadow: 0 10px 24px rgba(17, 24, 39, 0.08);
}

.card h2 {
  margin: 0 0 10px;
  color: #9a3412;
}

.section-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 12px;
}

.order-card {
  border: 1px solid #fed7aa;
  background: #fff7ed;
  border-radius: 12px;
  padding: 14px;
  margin-top: 14px;
}

.order-header {
  display: flex;
  justify-content: space-between;
  gap: 12px;
  align-items: center;
}

.order-header h3 {
  margin: 0;
  color: #7c2d12;
}

.checkbox-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 10px;
  margin-top: 10px;
}

.dish-option {
  display: flex;
  gap: 10px;
  border: 1px solid #fdba74;
  background: #fff;
  border-radius: 10px;
  padding: 10px;
}

.dish-option p {
  margin: 4px 0 0;
  color: #6b7280;
  font-size: 13px;
}

.order-actions {
  display: flex;
  gap: 10px;
  margin-top: 12px;
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 10px;
}

.service-box {
  background: #fff;
  border: 1px solid #fed7aa;
  border-radius: 10px;
  padding: 10px;
}

.service-button {
  text-align: left;
  cursor: pointer;
}

.service-button:hover {
  border-color: #f97316;
  box-shadow: 0 4px 12px rgba(249, 115, 22, 0.18);
}

.service-box-active {
  border-color: #ea580c;
  box-shadow: 0 0 0 2px rgba(234, 88, 12, 0.22);
}

.service-box h4 {
  margin: 0;
  color: #9a3412;
}

.selected-service-panel {
  margin-top: 12px;
  background: #fff;
  border: 1px solid #fdba74;
  border-radius: 10px;
  padding: 12px;
}

.selected-service-panel h4 {
  margin: 0 0 8px;
  color: #9a3412;
}

.btn {
  border: none;
  border-radius: 10px;
  padding: 10px 14px;
  font-weight: 600;
  cursor: pointer;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background: #ea580c;
  color: #fff;
}

.btn-secondary {
  background: #334155;
  color: #fff;
}

.btn-danger {
  background: #b91c1c;
  color: #fff;
}

.chip {
  border-radius: 999px;
  padding: 6px 10px;
  font-size: 12px;
  font-weight: 700;
}

.chip-success {
  background: #dcfce7;
  color: #166534;
}

.chip-warning {
  background: #fef3c7;
  color: #92400e;
}

.muted {
  color: #64748b;
  margin: 6px 0 0;
}

.error {
  color: #b91c1c;
  font-weight: 600;
}

@media (max-width: 768px) {
  .dashboard-page {
    padding: 20px 14px 14px;
  }

  .dashboard-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .order-actions {
    flex-direction: column;
  }
}
</style>

