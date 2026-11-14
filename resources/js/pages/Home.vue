<template>
  <div class="chef-home">
    <!-- Hero Section -->
    <section class="hero" id="home">
      <div class="hero-container">
        <div class="hero-content">
          <h1>
            More Time Together.
            <br>
            <span class="italic">We'll Take Care of the Rest.</span>
          </h1>
          <p class="tagline">Less time cooking. More time together.</p>
          <p class="subtitle">
            Personalized private chef services designed around your family, your lifestyle, and the moments that matter most.
          </p>
          <p class="description">
            From thoughtful menu planning and grocery shopping to professionally prepared meals in the comfort of your home, we take care of the details so you can spend less time worrying about what's for dinner—and more time enjoying the people around your table.
          </p>
          <div class="hero-buttons">
            <a href="#consultation" class="btn btn-primary">Book Your Consultation</a>
            <router-link to="/services" class="btn btn-secondary">Explore Our Services</router-link>
          </div>
          <p class="micro-copy">Let's create a service designed around your family.</p>
        </div>
      </div>
    </section>

    <!-- Value Proposition Section -->
    <section class="value-prop">
      <div class="value-prop-container">
        <h2>Restaurant-quality cuisine. The comfort of home. Service that feels personal.</h2>
        <div class="value-prop-text">
          <p>
            At Kaczmarek Food Services, we believe exceptional hospitality goes beyond preparing a great meal.
          </p>
          <p>
            It means understanding your family, anticipating your needs, respecting your preferences, and taking care of the details that make everyday life easier.
          </p>
          <p>
            Chef Adam brings more than two decades of culinary experience into your home, while Lorena ensures every part of your experience feels seamless, personal, and genuinely cared for.
          </p>
          <p>
            <strong>Because to us, you're never just another client—you become part of our extended family.</strong>
          </p>
        </div>
      </div>
    </section>

    <!-- Problem/Promise Section -->
    <section class="problem-section" id="services">
      <div class="problem-container">
        <div class="problem-top">
          <div class="problem-content">
            <h2 class="section-subtitle">A Different Way to Experience Home</h2>
            <h3>
              Life Is Busy.
              <br>
              <span class="italic">Dinner Shouldn't Feel Like Another Task.</span>
            </h3>
            <p class="problem-text">
              Between work, family, schedules, errands, and everything in between, planning and preparing meals can easily become one more responsibility on an already full day.
            </p>
            <p class="problem-text">
              At Kaczmarek Food Services, we believe mealtime should feel different. It should be a moment to slow down, reconnect, and enjoy the people around you.
            </p>
            <p class="promise">
              Good food. Genuine care.
              <br>
              More time together.
            </p>
            <a href="#consultation" class="problem-cta">Discover How It Works</a>
          </div>
          <div class="problem-image">
            <img :src="'/images/portadas/portadanosotros.webp'" alt="Family enjoying dinner together" />
          </div>
        </div>

        <div class="problem-features">
          <div class="problem-feature">
            <div class="problem-icon">👥</div>
            <div>
              <h4>Personalized Around You</h4>
              <p>Your family, preferences, dietary needs, and lifestyle are at the heart of every menu and every service.</p>
            </div>
          </div>
          <div class="problem-feature">
            <div class="problem-icon">🍽️</div>
            <div>
              <h4>Restaurant-Quality, At Home</h4>
              <p>Chef Adam brings more than two decades of culinary experience to your kitchen, creating thoughtfully prepared meals without sacrificing the comfort of home.</p>
            </div>
          </div>
          <div class="problem-feature">
            <div class="problem-icon">💝</div>
            <div>
              <h4>Hospitality That Goes Beyond the Meal</h4>
              <p>From the first conversation to every detail of your service, Lore ensures your experience feels seamless, personal, and genuinely cared for.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- How It Works Section -->
    <section class="how-it-works-section" id="how-it-works">
      <div class="how-it-works-container">
        <div class="how-it-works-head">
          <p class="how-it-works-kicker">HOW IT WORKS</p>
          <h2>From Our First Conversation<br>to<br>Your Table.</h2>
          <p class="how-it-works-subtitle">A personal chef experience designed around you.</p>
          <p class="how-it-works-intro">
            Every family is different—and your service should be too.
          </p>
          <p class="how-it-works-intro">
            We begin by getting to know you, your lifestyle, your preferences, and what would make life a little easier.
            From there, we create a personalized culinary experience designed specifically for your home and your family.
          </p>
        </div>

        <div class="how-it-works-steps">
          <article class="how-it-works-step">
            <div class="step-number-wrap">
              <span class="step-line"></span>
              <span class="step-number">01</span>
              <span class="step-line"></span>
            </div>
            <img :src="'/images/portadas/portadanosotros.webp'" alt="Lore consulting with a family" />
            <h3>Let’s Get to Know You</h3>
            <p>
              Book a complimentary consultation so we can learn about your family, lifestyle, culinary preferences,
              dietary needs, and what you're looking for in a personal chef service.
            </p>
          </article>

          <article class="how-it-works-step">
            <div class="step-number-wrap">
              <span class="step-line"></span>
              <span class="step-number">02</span>
              <span class="step-line"></span>
            </div>
            <img :src="'/images/portadas/portadahome.webp'" alt="Menu planning and ingredients" />
            <h3>We Create Your Experience</h3>
            <p>
              Based on your needs, we design a personalized service plan and begin building menus around your tastes,
              preferences, dietary requirements, and schedule.
            </p>
          </article>

          <article class="how-it-works-step">
            <div class="step-number-wrap">
              <span class="step-line"></span>
              <span class="step-number">03</span>
              <span class="step-line"></span>
            </div>
            <img :src="'/images/portadas/portadahome.webp'" alt="Chef Adam cooking in home kitchen" />
            <h3>Chef Adam Takes Care of the Details</h3>
            <p>
              From menu planning and grocery shopping to preparing your meals in your own kitchen, Chef Adam takes care
              of the culinary details with the quality and attention you would expect from a restaurant—right at home.
            </p>
          </article>

          <article class="how-it-works-step">
            <div class="step-number-wrap">
              <span class="step-line"></span>
              <span class="step-number">04</span>
              <span class="step-line"></span>
            </div>
            <img :src="'/images/portadas/portadanosotros.webp'" alt="Family enjoying dinner together at table" />
            <h3>You Simply Enjoy</h3>
            <p>
              Your meals are ready. Your kitchen is cared for. And the time that would have been spent planning,
              shopping, and cooking is yours again.
            </p>
            <p class="step-highlight">Sit down. Slow down. Enjoy more time together.</p>
          </article>
        </div>

        <div class="how-it-works-cta">
          <h3>Ready to bring more time<br>back to your table?</h3>
          <a href="#consultation" class="btn btn-primary">Book Your Consultation</a>
          <p>Let’s create a service that feels like it was made for your family—because it is.</p>
        </div>
      </div>
    </section>

    <!-- Consultation CTA Section -->
    <section v-if="!isAuthenticated" class="consultation-section" id="consultation">
      <div class="consultation-container">
        <h2>Ready to Experience the Difference?</h2>
        <p>Schedule a consultation with Chef Adam and Lorena to discuss your family's unique needs.</p>

        <div class="form-container">
          <div v-if="successMessage" class="alert alert-success">
            ✅ Thank you! We've received your request. We'll be in touch within 24 hours.
          </div>
          <div v-if="errorMessage" class="alert alert-danger">
            ❌ {{ errorMessage }}
          </div>

          <form @submit.prevent="submitForm">
            <div class="form-row">
              <div class="form-group">
                <label for="full_name">Full Name *</label>
                <input
                  v-model="form.full_name"
                  type="text"
                  id="full_name"
                  placeholder="Your full name"
                  required
                />
              </div>
              <div class="form-group">
                <label for="email">Email *</label>
                <input
                  v-model="form.email"
                  type="email"
                  id="email"
                  placeholder="your@email.com"
                  required
                />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="phone">Phone *</label>
                <input
                  v-model="form.phone"
                  type="tel"
                  id="phone"
                  placeholder="+1 (555) 000-0000"
                  required
                />
              </div>
              <div class="form-group">
                <label for="city">City</label>
                <input
                  v-model="form.city"
                  type="text"
                  id="city"
                  placeholder="Your city"
                />
              </div>
            </div>

            <div class="form-group">
              <label for="message">Tell us about your family and your needs (Optional)</label>
              <textarea
                v-model="form.message"
                id="message"
                rows="4"
                placeholder="Share your family size, dietary preferences, any allergies, or special occasions we should know about..."
              ></textarea>
            </div>

            <button type="submit" class="form-submit" :disabled="loading">
              {{ loading ? '⏳ Sending...' : 'Book Your Consultation' }}
            </button>
          </form>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthState } from '../composables/useAuthState'

const { isAuthenticated } = useAuthState()

const form = ref({
  full_name: '',
  email: '',
  phone: '',
  city: '',
  message: ''
})

const loading = ref(false)
const successMessage = ref(false)
const errorMessage = ref('')

const submitForm = async () => {
  loading.value = true
  successMessage.value = false
  errorMessage.value = ''

  try {
    const response = await fetch('/api/prospects', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(form.value),
    })

    const data = await response.json()

    if (response.ok) {
      successMessage.value = true
      form.value = {
        full_name: '',
        email: '',
        phone: '',
        city: '',
        message: ''
      }
      setTimeout(() => {
        successMessage.value = false
      }, 5000)
    } else {
      errorMessage.value = data.message || 'An error occurred. Please try again.'
    }
  } catch (error) {
    errorMessage.value = 'Unable to connect. Please check your connection and try again.'
    console.error('Error:', error)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.chef-home {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
}

.hero {
  padding: 100px 40px;
  text-align: left;
  min-height: 1260px;
  display: flex;
  align-items: center;
  background-image: url('/images/portadas/portadahome.webp');
  background-size: cover;
  background-position: center right;
  background-repeat: no-repeat;
  background-attachment: scroll;
  position: relative;
}

.hero::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    90deg,
    rgba(248, 245, 236, 0.95) 0%,
    rgba(248, 245, 236, 0.9) 34%,
    rgba(248, 245, 236, 0.55) 47%,
    rgba(248, 245, 236, 0) 62%
  );
  z-index: 1;
  pointer-events: none;
}

.hero-container {
  display: flex;
  align-items: center;
  max-width: 1400px;
  margin: 0 auto;
  width: 100%;
  z-index: 2;
  position: relative;
}

.hero-content {
  flex: 0 0 46%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.hero-content h1 {
  font-size: 4rem;
  font-weight: 300;
  margin-bottom: 10px;
  line-height: 1.1;
}

.hero-content h1 .italic {
  font-style: italic;
  font-weight: 300;
}

.tagline {
  font-size: 1.1rem;
  color: #666;
  font-weight: 500;
  margin-bottom: 20px;
}

.subtitle {
  font-size: 1.2rem;
  color: #555;
  margin-bottom: 20px;
  max-width: 600px;
}

.description {
  font-size: 1rem;
  color: #666;
  line-height: 1.6;
  margin-bottom: 30px;
  max-width: 600px;
}

.hero-buttons {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
}

.btn {
  padding: 14px 32px;
  border-radius: 30px;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
  display: inline-block;
}

.btn-primary {
  background-color: #4a6741;
  color: white;
}

.btn-primary:hover {
  background-color: #3a5530;
  transform: translateY(-2px);
}

.btn-secondary {
  background-color: transparent;
  color: #4a6741;
  border: 2px solid #4a6741;
}

.btn-secondary:hover {
  background-color: #4a6741;
  color: white;
}

.micro-copy {
  font-style: italic;
  color: #999;
  font-size: 0.95rem;
}

.value-prop {
  padding: 80px 40px;
  background-color: #f9f9f9;
  text-align: center;
}

.value-prop h2 {
  font-size: 2.5rem;
  font-weight: 400;
  margin-bottom: 40px;
  max-width: 900px;
  margin-left: auto;
  margin-right: auto;
}

.value-prop-text p {
  font-size: 1.1rem;
  line-height: 1.8;
  color: #555;
  margin-bottom: 20px;
  max-width: 900px;
  margin-left: auto;
  margin-right: auto;
}

.problem-section {
  padding: 80px 40px;
  text-align: left;
  background-color: #f8f5ec;
}

.problem-container {
  max-width: 1480px;
  margin: 0 auto;
  border: 1px solid #e7dcc8;
  background: #fdfaf4;
}

.problem-top {
  display: grid;
  grid-template-columns: 1fr 1.15fr;
  align-items: stretch;
}

.problem-content {
  padding: 64px 52px;
}

.problem-image {
  min-height: 560px;
}

.problem-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.section-subtitle {
  font-size: 0.82rem;
  color: #8f7a57;
  text-transform: uppercase;
  letter-spacing: 3px;
  margin-bottom: 20px;
}

.problem-section h3 {
  font-size: 4rem;
  font-weight: 400;
  margin-bottom: 24px;
  line-height: 1.1;
  color: #2f271e;
}

.problem-section h3 .italic {
  font-style: italic;
  font-weight: 500;
}

.problem-text {
  font-size: 1.08rem;
  line-height: 1.5;
  color: #51483d;
  margin-bottom: 14px;
  max-width: 520px;
}

.promise {
  font-size: 2.35rem;
  color: #7c5a32;
  font-style: italic;
  line-height: 1.05;
  margin-top: 20px;
  margin-bottom: 24px;
}

.problem-cta {
  display: inline-block;
  padding: 14px 30px;
  border-radius: 30px;
  background-color: #1d3a32;
  color: #fff;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
}

.problem-cta:hover {
  background-color: #162c23;
  transform: translateY(-1px);
}

.problem-features {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  border-top: 1px solid #e7dcc8;
}

.problem-feature {
  display: flex;
  gap: 18px;
  padding: 32px 28px;
  align-items: flex-start;
}

.problem-feature + .problem-feature {
  border-left: 1px solid #e7dcc8;
}

.problem-icon {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  border: 1px solid #e2d5be;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.4rem;
  flex-shrink: 0;
  color: #8c6f4a;
}

.problem-feature h4 {
  font-size: 2rem;
  line-height: 1.05;
  margin: 2px 0 10px;
  color: #2f271e;
  font-weight: 400;
}

.problem-feature p {
  font-size: 1rem;
  line-height: 1.55;
  color: #51483d;
  margin: 0;
}

.how-it-works-section {
  padding: 90px 40px 80px;
  background: #f8f5ec;
}

.how-it-works-container {
  max-width: 1480px;
  margin: 0 auto;
  background: #fdfaf4;
  border: 1px solid #e7dcc8;
}

.how-it-works-head {
  text-align: center;
  padding: 52px 40px 34px;
}

.how-it-works-kicker {
  margin: 0 0 20px;
  font-size: 0.82rem;
  letter-spacing: 6px;
  color: #8f7a57;
  text-transform: uppercase;
}

.how-it-works-head h2 {
  margin: 0;
  font-size: 4rem;
  line-height: 1.02;
  color: #23201a;
  font-weight: 500;
}

.how-it-works-subtitle {
  margin: 18px 0 12px;
  font-size: 3rem;
  font-style: italic;
  color: #8a5c33;
}

.how-it-works-intro {
  margin: 8px auto 0;
  max-width: 980px;
  font-size: 1.04rem;
  line-height: 1.48;
  color: #4f473d;
}

.how-it-works-steps {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  border-top: 1px solid #e7dcc8;
}

.how-it-works-step {
  padding: 0 14px 26px;
}

.how-it-works-step + .how-it-works-step {
  border-left: 1px solid #e7dcc8;
}

.step-number-wrap {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 14px 0 14px;
}

.step-line {
  flex: 1;
  height: 1px;
  background: #d9c8ab;
}

.step-number {
  font-size: 3rem;
  font-style: italic;
  color: #81572f;
  line-height: 1;
}

.how-it-works-step img {
  width: 100%;
  height: 190px;
  object-fit: cover;
  margin-bottom: 14px;
}

.how-it-works-step h3 {
  margin: 0 0 8px;
  font-size: 3rem;
  line-height: 1.04;
  font-weight: 500;
  color: #2a251f;
}

.how-it-works-step p {
  margin: 0;
  font-size: 0.95rem;
  line-height: 1.45;
  color: #4f473d;
}

.how-it-works-step .step-highlight {
  margin-top: 10px;
  font-size: 2.7rem;
  font-style: italic;
  color: #7d532e;
  line-height: 1.1;
}

.how-it-works-cta {
  text-align: center;
  border-top: 1px solid #e7dcc8;
  padding: 40px 24px 46px;
}

.how-it-works-cta h3 {
  margin: 0 0 18px;
  font-size: 4rem;
  line-height: 1.06;
  color: #23201a;
}

.how-it-works-cta p {
  margin: 14px 0 0;
  font-size: 0.95rem;
  color: #5d5449;
}

.consultation-section {
  padding: 80px 40px;
  background-color: white;
}

.consultation-container {
  max-width: 800px;
  margin: 0 auto;
  text-align: center;
}

.consultation-section h2 {
  font-size: 2.5rem;
  font-weight: 400;
  margin-bottom: 15px;
}

.consultation-section > p {
  font-size: 1.1rem;
  color: #666;
  margin-bottom: 40px;
}

.form-container {
  background-color: #f9f9f9;
  padding: 40px;
  border-radius: 8px;
  margin-top: 30px;
}

.alert {
  padding: 15px 20px;
  border-radius: 4px;
  margin-bottom: 20px;
  font-weight: 500;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-danger {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

form {
  display: flex;
  flex-direction: column;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 20px;
}

.form-row.full {
  grid-template-columns: 1fr;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  margin-bottom: 8px;
  font-weight: 500;
  color: #333;
}

.form-group input,
.form-group textarea {
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-family: inherit;
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #4a6741;
  box-shadow: 0 0 0 3px rgba(74, 103, 65, 0.1);
}

.form-submit {
  padding: 14px 32px;
  background-color: #4a6741;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 20px;
}

.form-submit:hover:not(:disabled) {
  background-color: #3a5530;
  transform: translateY(-2px);
}

.form-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@media (max-width: 1024px) {
  .hero-content {
    flex: 0 0 60%;
  }

  .problem-feature h4 {
    font-size: 1.5rem;
  }

  .how-it-works-step h3 {
    font-size: 2.2rem;
  }

  .problem-section h3 {
    font-size: 3rem;
  }
}

@media (max-width: 768px) {
  .hero {
    padding: 50px 20px;
    min-height: 600px;
    background-position: center;
  }

  .hero::before {
    background: linear-gradient(
      90deg,
      rgba(248, 245, 236, 0.98) 0%,
      rgba(248, 245, 236, 0.95) 50%,
      rgba(248, 245, 236, 0.8) 100%
    );
  }

  .hero-content {
    flex: 0 0 100%;
  }

  .hero-content h1 {
    font-size: 2.2rem;
    line-height: 1.2;
  }

  .tagline {
    font-size: 1rem;
  }

  .subtitle {
    font-size: 1rem;
    margin-bottom: 16px;
  }

  .description {
    font-size: 0.95rem;
    margin-bottom: 24px;
  }

  .hero-container {
    max-width: 100%;
  }

  .hero-buttons {
    flex-direction: column;
    gap: 12px;
  }

  .btn {
    padding: 12px 24px;
    font-size: 0.95rem;
    text-align: center;
  }

  .micro-copy {
    font-size: 0.85rem;
  }

  .value-prop {
    padding: 50px 20px;
  }

  .value-prop h2 {
    font-size: 1.8rem;
  }

  .value-prop-text p {
    font-size: 0.95rem;
  }

  .problem-section {
    padding: 40px 20px;
  }

  .problem-container {
    border: none;
  }

  .problem-section h3 {
    font-size: 1.8rem;
    line-height: 1.3;
  }

  .problem-top {
    grid-template-columns: 1fr;
    gap: 0;
  }

  .problem-content {
    padding: 24px 20px;
    border: 1px solid #e7dcc8;
    border-bottom: none;
  }

  .problem-text {
    max-width: 100%;
    font-size: 0.95rem;
  }

  .promise {
    font-size: 1.4rem;
  }

  .problem-cta {
    font-size: 0.95rem;
    padding: 12px 24px;
  }

  .problem-image {
    min-height: 240px;
    border: 1px solid #e7dcc8;
    border-top: none;
  }

  .section-subtitle {
    font-size: 0.7rem;
    letter-spacing: 2px;
  }

  .problem-features {
    grid-template-columns: 1fr;
    border: 1px solid #e7dcc8;
    border-top: none;
  }

  .problem-feature {
    padding: 24px 20px;
    gap: 14px;
    border-left: 0 !important;
    border-top: 1px solid #e7dcc8;
  }

  .problem-feature:first-child {
    border-top: none;
  }

  .problem-icon {
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
  }

  .problem-feature h4 {
    font-size: 1.2rem;
  }

  .problem-feature p {
    font-size: 0.9rem;
  }

  .how-it-works-section {
    padding: 40px 20px;
  }

  .how-it-works-container {
    border: none;
  }

  .how-it-works-head {
    padding: 24px 20px;
    text-align: center;
  }

  .how-it-works-kicker {
    font-size: 0.7rem;
    letter-spacing: 2px;
    margin-bottom: 12px;
  }

  .how-it-works-head h2 {
    font-size: 1.8rem;
    line-height: 1.3;
  }

  .how-it-works-subtitle {
    font-size: 1.4rem;
    margin: 12px 0 8px;
  }

  .how-it-works-intro {
    font-size: 0.95rem;
    line-height: 1.4;
  }

  .how-it-works-steps {
    grid-template-columns: 1fr;
    border: 1px solid #e7dcc8;
  }

  .how-it-works-step {
    padding: 24px 20px;
    border-left: 0 !important;
    border-top: 1px solid #e7dcc8;
    border-bottom: none;
  }

  .how-it-works-step:first-child {
    border-top: none;
  }

  .how-it-works-step img {
    height: 160px;
    margin-bottom: 12px;
  }

  .how-it-works-step h3 {
    font-size: 1.4rem;
    margin-bottom: 8px;
  }

  .how-it-works-step p {
    font-size: 0.9rem;
  }

  .step-number {
    font-size: 2rem;
  }

  .step-number-wrap {
    margin: 12px 0;
    gap: 8px;
  }

  .how-it-works-step .step-highlight {
    font-size: 1.1rem;
    margin-top: 8px;
  }

  .how-it-works-cta {
    border: 1px solid #e7dcc8;
    border-top: 1px solid #e7dcc8;
    padding: 28px 20px 32px;
  }

  .how-it-works-cta h3 {
    font-size: 1.6rem;
    line-height: 1.3;
    margin-bottom: 12px;
  }

  .how-it-works-cta p {
    font-size: 0.9rem;
    margin-top: 10px;
  }

  .consultation-section {
    padding: 40px 20px;
  }

  .consultation-container {
    max-width: 100%;
  }

  .consultation-section h2 {
    font-size: 1.6rem;
  }

  .consultation-section > p {
    font-size: 0.95rem;
    margin-bottom: 24px;
  }

  .form-container {
    padding: 24px 16px;
    background-color: white;
    border: 1px solid #ddd;
  }

  .form-row {
    grid-template-columns: 1fr;
    gap: 16px;
    margin-bottom: 16px;
  }

  .form-group label {
    font-size: 0.9rem;
    margin-bottom: 6px;
  }

  .form-group input,
  .form-group textarea {
    font-size: 1rem;
    padding: 10px 12px;
  }

  .form-submit {
    padding: 12px 24px;
    font-size: 0.95rem;
    margin-top: 16px;
  }
}

@media (max-width: 480px) {
  .hero {
    padding: 40px 16px;
    min-height: 500px;
  }

  .hero-content h1 {
    font-size: 1.7rem;
    line-height: 1.25;
    margin-bottom: 8px;
  }

  .tagline {
    font-size: 0.9rem;
    margin-bottom: 12px;
  }

  .subtitle {
    font-size: 0.9rem;
    margin-bottom: 12px;
  }

  .description {
    font-size: 0.85rem;
    line-height: 1.5;
    margin-bottom: 16px;
  }

  .btn {
    padding: 10px 20px;
    font-size: 0.9rem;
  }

  .micro-copy {
    font-size: 0.75rem;
  }

  .value-prop {
    padding: 32px 16px;
  }

  .value-prop h2 {
    font-size: 1.4rem;
    margin-bottom: 24px;
  }

  .value-prop-text p {
    font-size: 0.85rem;
    line-height: 1.6;
  }

  .problem-section {
    padding: 32px 16px;
  }

  .problem-section h3 {
    font-size: 1.4rem;
  }

  .problem-content {
    padding: 20px 16px;
  }

  .section-subtitle {
    font-size: 0.65rem;
    letter-spacing: 1.5px;
  }

  .problem-text {
    font-size: 0.85rem;
    margin-bottom: 12px;
  }

  .promise {
    font-size: 1.1rem;
    margin-top: 16px;
    margin-bottom: 16px;
  }

  .problem-image {
    min-height: 200px;
  }

  .problem-icon {
    width: 36px;
    height: 36px;
    font-size: 1rem;
  }

  .problem-feature {
    padding: 20px 16px;
  }

  .problem-feature h4 {
    font-size: 1rem;
    margin-bottom: 6px;
  }

  .problem-feature p {
    font-size: 0.8rem;
    line-height: 1.5;
  }

  .how-it-works-section {
    padding: 32px 16px;
  }

  .how-it-works-head {
    padding: 20px 16px;
  }

  .how-it-works-head h2 {
    font-size: 1.4rem;
  }

  .how-it-works-subtitle {
    font-size: 1.1rem;
  }

  .how-it-works-intro {
    font-size: 0.85rem;
  }

  .how-it-works-step {
    padding: 20px 16px;
  }

  .how-it-works-step img {
    height: 140px;
  }

  .how-it-works-step h3 {
    font-size: 1.1rem;
    margin-bottom: 6px;
  }

  .how-it-works-step p {
    font-size: 0.8rem;
  }

  .step-number {
    font-size: 1.6rem;
  }

  .how-it-works-step .step-highlight {
    font-size: 0.9rem;
  }

  .how-it-works-cta {
    padding: 20px 16px;
  }

  .how-it-works-cta h3 {
    font-size: 1.2rem;
    margin-bottom: 10px;
  }

  .how-it-works-cta p {
    font-size: 0.8rem;
  }

  .consultation-section {
    padding: 32px 16px;
  }

  .consultation-section h2 {
    font-size: 1.4rem;
  }

  .consultation-section > p {
    font-size: 0.85rem;
  }

  .form-container {
    padding: 20px 12px;
  }

  .form-group label {
    font-size: 0.85rem;
  }

  .form-group input,
  .form-group textarea {
    font-size: 16px;
    padding: 10px;
  }

  .form-submit {
    padding: 10px 20px;
    font-size: 0.9rem;
  }
}
</style>
