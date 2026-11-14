import { watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const metaTagsConfig = {
  '/': {
    title: 'Kaczmarek Food Services | Private Chef Services',
    description: 'Personalized private chef services designed around your family, your lifestyle, and the moments that matter most.',
    keywords: 'private chef, personal chef, meal planning, home chef, food services, catering, family meals',
    image: '/og-kaczmarek-home.png',
  },
  '/prospecto': {
    title: 'Register as Prospect | Kaczmarek Food Services',
    description: 'Join our community and become a prospect for personalized private chef services.',
    keywords: 'register prospect, private chef services, meal planning services',
    image: '/og-kaczmarek-prospect.png',
    index: false,
  },
  '/login': {
    title: 'Sign In | Kaczmarek Food Services',
    description: 'Sign in to your Kaczmarek account to manage your private chef services.',
    keywords: 'login, sign in, account, private chef',
    image: '/og-kaczmarek-login.png',
    index: false,
  },
  '/dashboard': {
    title: 'Dashboard | Kaczmarek Food Services',
    description: 'Manage your private chef services from your personal dashboard.',
    keywords: 'dashboard, account, private chef management',
    image: '/og-kaczmarek-dashboard.png',
    index: false,
  },
  '/onboarding': {
    title: 'Onboarding | Kaczmarek Food Services',
    description: 'Complete your onboarding to start your personalized private chef experience.',
    keywords: 'onboarding, setup, private chef services',
    image: '/og-kaczmarek-onboarding.png',
    index: false,
  },
  '/nosotros': {
    title: 'About Kaczmarek | Our Story & Philosophy',
    description: 'Learn about Kaczmarek Food Services, Chef Adam, and Lorena\'s approach to exceptional private chef services.',
    keywords: 'about Kaczmarek, chef Adam, Lorena, private chef philosophy',
    image: '/og-kaczmarek-about.png',
  },
  '/contacto': {
    title: 'Contact Kaczmarek | Book Your Consultation',
    description: 'Get in touch with Kaczmarek Food Services to schedule your consultation.',
    keywords: 'contact, consultation, book, private chef',
    image: '/og-kaczmarek-contact.png',
  },
}

export const useMetaTags = () => {
  const router = useRouter()
  const route = useRoute()

  const updateMetaTags = (path) => {
    const config = metaTagsConfig[path] || metaTagsConfig['/']

    // Actualizar title
    document.title = config.title

    // Actualizar o crear meta tags
    updateOrCreateMetaTag('meta', 'name', 'description', config.description)
    updateOrCreateMetaTag('meta', 'name', 'keywords', config.keywords)
    updateOrCreateMetaTag('meta', 'property', 'og:title', config.title)
    updateOrCreateMetaTag('meta', 'property', 'og:description', config.description)
    updateOrCreateMetaTag('meta', 'property', 'og:image', config.image)
    updateOrCreateMetaTag('meta', 'property', 'og:url', window.location.href)
    updateOrCreateMetaTag('meta', 'name', 'twitter:title', config.title)
    updateOrCreateMetaTag('meta', 'name', 'twitter:description', config.description)
    updateOrCreateMetaTag('meta', 'name', 'twitter:image', config.image)

    // Robots meta tag
    if (config.index === false) {
      updateOrCreateMetaTag('meta', 'name', 'robots', 'noindex, follow')
    } else {
      removeMetaTag('meta', 'name', 'robots')
    }

    // Canonical URL
    updateOrCreateMetaTag('link', 'rel', 'canonical', window.location.href, true)
  }

  const updateOrCreateMetaTag = (tagName, attrName, attrValue, content, isLink = false) => {
    let tag = document.querySelector(`${tagName}[${attrName}="${attrValue}"]`)

    if (!tag) {
      tag = document.createElement(tagName)
      tag.setAttribute(attrName, attrValue)
      document.head.appendChild(tag)
    }

    if (isLink) {
      tag.setAttribute('href', content)
    } else {
      tag.setAttribute('content', content)
    }
  }

  const removeMetaTag = (tagName, attrName, attrValue) => {
    const tag = document.querySelector(`${tagName}[${attrName}="${attrValue}"]`)
    if (tag) {
      tag.remove()
    }
  }

  // Watch route changes
  watch(() => route.path, (newPath) => {
    updateMetaTags(newPath)
  })

  // Update on mount
  updateMetaTags(route.path)

  return {
    updateMetaTags,
  }
}
