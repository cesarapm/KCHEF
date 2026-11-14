import { createRouter, createWebHistory } from 'vue-router';
import { checkAuthentication } from '../composables/useAuthState';

// Importar las páginas/vistas
import Home from '../pages/Home.vue';
import Services from '../pages/Services.vue';
import About from '../pages/About.vue';
import Contact from '../pages/Contact.vue';
import MeetTheTeam from '../pages/MeetTheTeam.vue';
import WhyFamiliesChooseUs from '../pages/WhyFamiliesChooseUs.vue';

import Prospecto from '../pages/Prospecto.vue';
import Login from '../pages/Login.vue';
import CompletarRegistro from '../pages/CompletarRegistro.vue';
import Onboarding from '../pages/Onboarding.vue';
import Dashboard from '../pages/Dashboard.vue';

const clientMetaConfig = {
  '/': {
    title: 'KChef Services | Personalized Private Chef Services',
    description: 'Personalized private chef services designed around your family, your lifestyle, and the moments that matter most.',
    keywords: 'private chef, personal chef, meal planning, home chef, private chef services, family meals',
    image: '/images/portadas/portadahome.webp',
    index: true,
  },
  '/services': {
    title: 'Our Services | KChef Services',
    description: 'Meals made around your family. Personalized weekly private chef services and thoughtful options designed for your home.',
    keywords: 'private chef services, weekly meals, personalized meals, family chef service',
    image: '/images/portadas/portadahome.webp',
    index: true,
  },
  '/meet-the-team': {
    title: 'Meet Lore & Adam | KChef Services',
    description: 'Meet the people behind KChef Services. Chef Adam and Lore bring culinary expertise and genuine hospitality to every family.',
    keywords: 'chef adam, lore, private chef team, hospitality, personalized service',
    image: '/images/meet/adams.webp',
    index: true,
  },
  '/why-families-choose-us': {
    title: 'Why Families Choose Us | KChef Services',
    description: 'The difference is in the details. Discover why families choose KChef Services for truly personalized meals and genuine hospitality.',
    keywords: 'why choose us, private chef, personalized chef service, family meals',
    image: '/images/portadas/portadanosotros.webp',
    index: true,
  },
  '/contacto': {
    title: 'Contact KChef Services | Book Your Consultation',
    description: 'Get in touch with KChef Services to schedule your consultation and create a private chef experience designed around your family.',
    keywords: 'contact, consultation, private chef, family services',
    image: '/images/portadas/portadahome.webp',
    index: true,
  },
  '/nosotros': {
    title: 'About KChef Services | Chef Adam & Lore',
    description: 'Learn about KChef Services, Chef Adam’s culinary expertise, and Lore’s commitment to genuine hospitality.',
    keywords: 'about kchef services, chef adam, lore, culinary excellence',
    image: '/images/portadas/portadanosotros.webp',
    index: true,
  },
  '/prospecto': {
    title: 'Register as Prospect | KChef Services',
    description: 'Join KChef Services and start your personalized private chef journey.',
    keywords: 'register prospect, private chef consultation',
    image: '/images/portadas/portadanosotros.webp',
    index: false,
  },
  '/login': {
    title: 'Sign In | KChef Services',
    description: 'Sign in to your KChef Services account to manage your private chef experience.',
    keywords: 'login, sign in, private chef account',
    image: '/images/portadas/portadahome.webp',
    index: false,
  },
  '/dashboard': {
    title: 'Dashboard | KChef Services',
    description: 'Manage your private chef service details from your dashboard.',
    keywords: 'dashboard, private chef account',
    image: '/images/portadas/portadahome.webp',
    index: false,
  },
  '/onboarding': {
    title: 'Onboarding | KChef Services',
    description: 'Complete onboarding to personalize your private chef service.',
    keywords: 'onboarding, private chef preferences',
    image: '/images/portadas/portadanosotros.webp',
    index: false,
  },
  '/completar-registro': {
    title: 'Complete Registration | KChef Services',
    description: 'Complete your registration to begin your private chef consultation.',
    keywords: 'complete registration, private chef',
    image: '/images/portadas/portadanosotros.webp',
    index: false,
  },
};

const defaultClientMeta = clientMetaConfig['/'];

function resolveClientMeta(path) {
  if (clientMetaConfig[path]) return clientMetaConfig[path];
  if (path.startsWith('/completar-registro/')) return clientMetaConfig['/completar-registro'];
  return defaultClientMeta;
}

function upsertMetaTag(attr, key, content) {
  let tag = document.head.querySelector(`meta[${attr}="${key}"]`);
  if (!tag) {
    tag = document.createElement('meta');
    tag.setAttribute(attr, key);
    document.head.appendChild(tag);
  }
  tag.setAttribute('content', content);
}

function upsertCanonical(href) {
  let link = document.head.querySelector('link[rel="canonical"]');
  if (!link) {
    link = document.createElement('link');
    link.setAttribute('rel', 'canonical');
    document.head.appendChild(link);
  }
  link.setAttribute('href', href);
}

function applyClientMeta(to) {
  const meta = resolveClientMeta(to.path);
  const origin = window.location.origin;
  const canonical = `${origin}${to.fullPath}`;
  const image = meta.image.startsWith('http') ? meta.image : `${origin}${meta.image}`;

  document.title = meta.title;

  upsertMetaTag('name', 'description', meta.description);
  upsertMetaTag('name', 'keywords', meta.keywords);
  upsertMetaTag('name', 'robots', meta.index === false ? 'noindex, follow' : 'index, follow');

  upsertMetaTag('property', 'og:type', 'website');
  upsertMetaTag('property', 'og:title', meta.title);
  upsertMetaTag('property', 'og:description', meta.description);
  upsertMetaTag('property', 'og:image', image);
  upsertMetaTag('property', 'og:url', canonical);
  upsertMetaTag('property', 'og:site_name', 'KChef Services');

  upsertMetaTag('name', 'twitter:card', 'summary_large_image');
  upsertMetaTag('name', 'twitter:title', meta.title);
  upsertMetaTag('name', 'twitter:description', meta.description);
  upsertMetaTag('name', 'twitter:image', image);
  upsertMetaTag('name', 'twitter:site', '@kchefservices');

  upsertCanonical(canonical);
}

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/prospecto',
    name: 'Prospecto',
    component: Prospecto
  },
  {
    path: '/services',
    name: 'Services',
    component: Services
  },
  {
    path: '/completar-registro/:token',
    name: 'CompletarRegistro',
    component: CompletarRegistro
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/onboarding',
    name: 'Onboarding',
    component: Onboarding,
    meta: { requiresAuth: true }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },

  {
    path: '/nosotros',
    name: 'About',
    component: About
  },
  {
    path: '/meet-the-team',
    name: 'MeetTheTeam',
    component: MeetTheTeam
  },
  {
    path: '/why-families-choose-us',
    name: 'WhyFamiliesChooseUs',
    component: WhyFamiliesChooseUs
  },
  {
    path: '/contacto',
    name: 'Contact',
    component: Contact
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }

    if (to.hash) {
      return {
        el: to.hash,
        top: 90,
        behavior: 'smooth'
      }
    }

    return { top: 0, behavior: 'smooth' }
  },
})

// Guard global de autenticación
router.beforeEach(async (to, from, next) => {
  const requiresAuth = to.meta.requiresAuth;

  if (requiresAuth) {
    try {
      // Usar checkAuthentication que tiene caché integrado
      const isAuth = await checkAuthentication();
      if (isAuth) {
        next();
      } else {
        next({ name: 'Login', query: { redirect: to.fullPath } });
      }
    } catch (error) {
      console.warn('Auth check error:', error.message);
      next({ name: 'Login', query: { redirect: to.fullPath } });
    }
  } else {
    next();
  }
});

router.afterEach((to) => {
  applyClientMeta(to);
});

export default router;
