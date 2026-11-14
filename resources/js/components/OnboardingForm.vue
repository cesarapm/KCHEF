<template>
  <div class="onboarding-form">
    <div class="card">
      <div class="card-header">
        <h2>Proceso de Onboarding</h2>
        <p>Carga los documentos requeridos para completar tu registro</p>
      </div>

      <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>

      <div v-if="successMessage" class="alert alert-success">
        {{ successMessage }}
      </div>

      <div v-if="loading" class="loading">
        <p>Cargando requisitos...</p>
      </div>

      <div v-else-if="onboarding" class="form-container">
        <div class="onboarding-info">
          <h3>Estado del Onboarding</h3>
          <p>
            <strong>Estado Actual:</strong>
            <span class="badge" :class="getStatusClass(onboarding.status)">
              {{ formatStatus(onboarding.status) }}
            </span>
          </p>
          <p v-if="onboarding.started_at">
            <strong>Iniciado:</strong> {{ formatDate(onboarding.started_at) }}
          </p>
        </div>

        <div class="requirements-container">
          <div
            v-for="requirement in onboarding.requirements"
            :key="requirement.id"
            class="requirement-card"
          >
            <div class="requirement-header">
              <h4>{{ requirement.title }}</h4>
              <span v-if="requirement.required" class="badge-required">Requerido</span>
            </div>

            <p v-if="requirement.description" class="requirement-description">
              {{ requirement.description }}
            </p>

            <div v-if="requirement.allowed_formats" class="requirement-meta">
              <small>
                <strong>Formatos permitidos:</strong>
                {{ formatAllowedFormats(requirement.allowed_formats) }}
              </small>
            </div>

            <div v-if="requirement.max_file_size" class="requirement-meta">
              <small>
                <strong>Tamaño máximo:</strong> {{ formatFileSize(requirement.max_file_size) }}
              </small>
            </div>

            <div class="documents-list">
              <div
                v-for="document in requirement.documents"
                :key="document.id"
                class="document-item"
              >
                <div class="document-info">
                  <p class="document-name">{{ document.file_name }}</p>
                  <p class="document-meta">
                    Cargado: {{ formatDate(document.uploaded_at) }}
                  </p>
                </div>
                <div class="document-status">
                  <span class="badge" :class="getDocumentStatusClass(document.status)">
                    {{ formatDocumentStatus(document.status) }}
                  </span>
                </div>

                <div v-if="document.revisions && document.revisions.length > 0" class="revisions">
                  <div
                    v-for="revision in document.revisions"
                    :key="revision.id"
                    class="revision-item"
                  >
                    <p class="revision-status">
                      <strong>{{ formatRevisionStatus(revision.status) }}</strong>
                    </p>
                    <p v-if="revision.comment" class="revision-comment">
                      {{ revision.comment }}
                    </p>
                    <p class="revision-date">
                      {{ formatDate(revision.created_at) }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="upload-section">
              <label :for="`file-${requirement.id}`" class="upload-label">
                <input
                  :id="`file-${requirement.id}`"
                  type="file"
                  @change="(e) => handleFileSelect(requirement.id, e)"
                  :accept="getAcceptAttribute(requirement.allowed_formats)"
                  class="file-input"
                />
                <span class="upload-button">
                  <i class="icon">📁</i> Seleccionar Archivo
                </span>
              </label>

              <div v-if="selectedFiles[requirement.id]" class="file-preview">
                <p>{{ selectedFiles[requirement.id].name }}</p>
                <button
                  type="button"
                  @click="uploadFile(requirement.id)"
                  class="btn btn-primary btn-sm"
                  :disabled="uploading[requirement.id]"
                >
                  <span v-if="uploading[requirement.id]">Cargando...</span>
                  <span v-else>Cargar Documento</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()

const loading = ref(false)
const errorMessage = ref('')
const successMessage = ref('')
const onboarding = ref(null)
const selectedFiles = reactive({})
const uploading = reactive({})

const prospectId = route.params.prospectId

onMounted(() => {
  fetchOnboarding()
})

const fetchOnboarding = async () => {
  loading.value = true
  errorMessage.value = ''

  try {
    const response = await axios.get(`/api/prospects/${prospectId}/onboarding`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
      },
    })

    onboarding.value = response.data.onboarding
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error al cargar onboarding'
  } finally {
    loading.value = false
  }
}

const handleFileSelect = (requirementId, event) => {
  const file = event.target.files[0]
  if (file) {
    selectedFiles[requirementId] = file
  }
}

const uploadFile = async (requirementId) => {
  const file = selectedFiles[requirementId]
  if (!file) return

  uploading[requirementId] = true
  errorMessage.value = ''

  try {
    const formData = new FormData()
    formData.append('requirement_id', requirementId)
    formData.append('file', file)

    const response = await axios.post('/api/onboarding-documents/upload', formData, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        'Content-Type': 'multipart/form-data',
      },
    })

    successMessage.value = 'Documento cargado exitosamente'
    delete selectedFiles[requirementId]

    // Recargar onboarding
    await fetchOnboarding()
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Error al cargar documento'
  } finally {
    uploading[requirementId] = false
  }
}

const getStatusClass = (status) => {
  const classes = {
    'configuracion_pendiente': 'badge-warning',
    'registro_pendiente': 'badge-info',
    'documentos_pendientes': 'badge-warning',
    'en_revision': 'badge-info',
    'requiere_correcciones': 'badge-danger',
    'completado': 'badge-success',
  }
  return classes[status] || 'badge-secondary'
}

const getDocumentStatusClass = (status) => {
  const classes = {
    'pendiente': 'badge-light',
    'cargado': 'badge-warning',
    'en_revision': 'badge-info',
    'aprobado': 'badge-success',
    'rechazado': 'badge-danger',
  }
  return classes[status] || 'badge-secondary'
}

const formatStatus = (status) => {
  const statuses = {
    'configuracion_pendiente': 'Configuración Pendiente',
    'registro_pendiente': 'Registro Pendiente',
    'documentos_pendientes': 'Documentos Pendientes',
    'en_revision': 'En Revisión',
    'requiere_correcciones': 'Requiere Correcciones',
    'completado': 'Completado',
  }
  return statuses[status] || status
}

const formatDocumentStatus = (status) => {
  const statuses = {
    'pendiente': 'Pendiente',
    'cargado': 'Cargado',
    'en_revision': 'En Revisión',
    'aprobado': 'Aprobado',
    'rechazado': 'Rechazado',
  }
  return statuses[status] || status
}

const formatRevisionStatus = (status) => {
  const statuses = {
    'aprobado': '✓ Aprobado',
    'rechazado': '✗ Rechazado',
    'requiere_correccion': '! Requiere Corrección',
  }
  return statuses[status] || status
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i]
}

const formatAllowedFormats = (formats) => {
  if (typeof formats === 'string') {
    formats = JSON.parse(formats)
  }
  return formats.join(', ').toUpperCase()
}

const getAcceptAttribute = (formats) => {
  if (typeof formats === 'string') {
    formats = JSON.parse(formats)
  }
  return '.' + formats.join(',.')
}
</script>

<style scoped>
.onboarding-form {
  max-width: 800px;
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

.form-container {
  padding: 2rem;
}

.loading {
  text-align: center;
  padding: 3rem;
  color: #666;
}

.onboarding-info {
  background-color: #f8f9fa;
  padding: 1.5rem;
  border-radius: 4px;
  margin-bottom: 2rem;
  border-left: 4px solid #007bff;
}

.onboarding-info h3 {
  margin-top: 0;
}

.requirements-container {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.requirement-card {
  border: 1px solid #dee2e6;
  border-radius: 4px;
  padding: 1.5rem;
  background-color: #fff;
}

.requirement-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.requirement-header h4 {
  margin: 0;
  color: #333;
}

.badge-required {
  background-color: #dc3545;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 600;
}

.requirement-description {
  color: #666;
  margin: 0.5rem 0;
}

.requirement-meta {
  color: #999;
  font-size: 0.9rem;
  margin: 0.25rem 0;
}

.documents-list {
  margin: 1rem 0;
}

.document-item {
  background-color: #f8f9fa;
  padding: 1rem;
  margin-bottom: 1rem;
  border-radius: 4px;
  border-left: 3px solid #007bff;
}

.document-info {
  margin-bottom: 0.5rem;
}

.document-name {
  margin: 0;
  font-weight: 500;
  color: #333;
}

.document-meta {
  margin: 0.25rem 0 0 0;
  font-size: 0.85rem;
  color: #999;
}

.document-status {
  margin-bottom: 1rem;
}

.revisions {
  margin-top: 1rem;
  border-top: 1px solid #dee2e6;
  padding-top: 1rem;
}

.revision-item {
  background-color: #fff;
  padding: 0.75rem;
  margin-bottom: 0.5rem;
  border-left: 3px solid #ffc107;
}

.revision-status {
  margin: 0 0 0.5rem 0;
  color: #333;
}

.revision-comment {
  margin: 0.25rem 0;
  color: #666;
  font-style: italic;
}

.revision-date {
  margin: 0.5rem 0 0 0;
  font-size: 0.85rem;
  color: #999;
}

.upload-section {
  margin-top: 1rem;
}

.upload-label {
  display: block;
  cursor: pointer;
}

.file-input {
  display: none;
}

.upload-button {
  display: inline-block;
  background-color: #007bff;
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  text-decoration: none;
  transition: background-color 0.2s;
}

.upload-button:hover {
  background-color: #0056b3;
}

.file-preview {
  margin-top: 1rem;
  padding: 1rem;
  background-color: #f8f9fa;
  border-radius: 4px;
}

.file-preview p {
  margin: 0 0 0.5rem 0;
  color: #333;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #0056b3;
}

.btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.85rem;
}

.btn:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

.alert {
  padding: 1rem;
  margin-bottom: 1rem;
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

.badge {
  display: inline-block;
  padding: 0.35rem 0.65rem;
  border-radius: 4px;
  font-size: 0.85rem;
  font-weight: 600;
}

.badge-warning {
  background-color: #ffc107;
  color: #333;
}

.badge-info {
  background-color: #17a2b8;
  color: white;
}

.badge-success {
  background-color: #28a745;
  color: white;
}

.badge-danger {
  background-color: #dc3545;
  color: white;
}

.badge-light {
  background-color: #e2e3e5;
  color: #333;
}

.badge-secondary {
  background-color: #6c757d;
  color: white;
}
</style>
