<template>
  <div class="services-page">
    <section class="services-hero">
      <div class="services-hero-copy">
        <p class="kicker">OUR SERVICES</p>
        <h1>
          Not Just Meals Made for Your Family.
          <br>
          <span>Meals Made Around Your Family.</span>
        </h1>
        <p class="subtitle">
          Personalized weekly meal preparation designed around your tastes, dietary needs, schedule, and lifestyle.
        </p>
        <p>
          Your meals should fit your life—not the other way around.
        </p>
        <p>
          From menu planning and grocery shopping to cooking, packaging, labeling, and kitchen clean-up, we take care of
          the details so your week feels a little easier.
        </p>
      </div>
    </section>

    <section class="weekly-service">
      <p class="kicker">WEEKLY PERSONAL CHEF SERVICE</p>
      <h2>One visit. A week of meals designed around you.</h2>

      <div class="plans-grid">
        <article class="plan-card">
          <img :src="'/images/meet/3meels.webp'" alt="Three-meal plan" />
          <div class="plan-body">
            <h3>THREE-MEAL PLAN</h3>
            <p>3 complete meals</p>
            <p>2 portions per household member</p>
          </div>
          <div class="plan-footer">3 Meals</div>
        </article>

        <article class="plan-card">
          <img :src="'/images/meet/4meels.webp'" alt="Four-meal plan" />
          <div class="plan-body">
            <h3>FOUR-MEAL PLAN</h3>
            <p>4 complete meals</p>
            <p>2 portions per household member</p>
          </div>
          <div class="plan-footer">4 Meals</div>
        </article>

        <article class="plan-card">
          <img :src="'/images/meet/5meels.webp'" alt="Five-meal plan" />
          <div class="plan-body">
            <h3>FIVE-MEAL PLAN</h3>
            <p>5 complete meals</p>
            <p>1 portion per household member</p>
          </div>
          <div class="plan-footer">5 Meals</div>
        </article>
      </div>

      <div class="center-cta">
        <router-link to="/contacto" class="cta-button">Explore Meal Plans →</router-link>
      </div>
    </section>

    <section class="services-summary">
      <div class="summary-item">
        <h3>Weekly Personal Chef Service</h3>
        <p>→ 3 Meals | 4 Meals | 5 Meals</p>
      </div>
      <div class="summary-item">
        <h3>Personalized to You</h3>
        <p>→ Dietary preferences | Nutrition goals | Favorite cuisines | Kids' preferences</p>
      </div>
      <div class="summary-item">
        <h3>A Little Extra</h3>
        <p>→ Pastry | Side Salad | Fruit &amp; Vegetable Prep | Child Portions</p>
      </div>
      <div class="summary-item">
        <h3>Delivery Option</h3>
        <p>→ Limited availability based on schedule/location</p>
      </div>
    </section>

    <section class="personalized-row">
      <div class="personalized-item">Your Tastes</div>
      <div class="personalized-item">Your Dietary Needs</div>
      <div class="personalized-item">Your Lifestyle</div>
      <div class="personalized-item">Your Family</div>
      <div class="personalized-item">Your Schedule</div>
    </section>

    <section class="extras-section">
      <p class="kicker">MAKE IT YOUR OWN</p>
      <h2>A Little Extra, When You Need It.</h2>
      <p>Complete your weekly service with thoughtful additions designed around your household.</p>

      <div class="extras-grid">
        <article class="extra-card">
          <img :src="'/images/meet/meet-extra1.webp'" alt="Fresh pastry" />
          <h3>Fresh Pastry</h3>
        </article>
        <article class="extra-card">
          <img :src="'/images/meet/meet-extra2.webp'" alt="Side salad" />
          <h3>Side Salad</h3>
        </article>
        <article class="extra-card">
          <img :src="'/images/meet/meet-extra3.webp'" alt="Fruit and vegetable prep" />
          <h3>Fruit &amp; Vegetable Prep</h3>
        </article>
        <article class="extra-card">
          <img :src="'/images/meet/meet-extra4.webp'" alt="Additional child portions" />
          <h3>Additional Child Portions</h3>
        </article>
      </div>
    </section>

    <section class="delivery-section">
      <div class="delivery-overlay">
        <p class="kicker">MEAL DELIVERY OPTION</p>
        <h2>Prefer Delivery?</h2>
        <p>
          For clients who prefer the convenience of having their meals delivered, this option may be available based on
          our schedule and your location.
        </p>
        <router-link to="/contacto" class="delivery-button">Learn More About Delivery</router-link>
      </div>
    </section>
  </div>
</template>

<style scoped>
.services-page {
  background: #f8f5ec;
  color: #2a251f;
}


.weekly-service,
.extras-section,
.services-summary {
  max-width: 1480px;
  margin: 0 auto;
  padding: 56px 40px;
  background: #fdfaf4;

}

.services-hero {
  border-top: 1px solid #e7dcc8;
  position: relative;
  overflow: hidden;
  width: 100%;
  height: 1100px;
  padding: 56px 200px;
  min-height: auto;
  background: url('/images/portadas/portada-our.webp') right center / cover no-repeat;
  display: flex;
  align-items: center;
}

.services-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(
    90deg,
    rgba(253, 250, 244, 0.98) 0%,
    rgba(253, 250, 244, 0.95) 34%,
    rgba(253, 250, 244, 0.58) 48%,
    rgba(253, 250, 244, 0) 64%
  );
}

.services-hero-copy {
  position: relative;
  z-index: 1;
  max-width: 46%;
}

.services-hero-copy h1 {
  font-size: 4rem;
  line-height: 1.02;
  margin: 0 0 12px;
}

.services-hero-copy h1 span {
  font-style: italic;
  color: #8a5c33;
}

.services-hero-copy p {
  margin: 8px 0;
  line-height: 1.45;
  color: #4f473d;
}

.kicker {
  margin: 0 0 14px;
  letter-spacing: 5px;
  font-size: 0.8rem;
  color: #8f7a57;
  text-transform: uppercase;
}

.subtitle {
  font-size: 1.18rem;
}

.weekly-service h2,
.extras-section h2,
.delivery-section h2 {
  margin: 0 0 18px;
  font-size: 2.3rem;
}

.plans-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px;
}

.plan-card {
  border: 1px solid #e7dcc8;
  background: white;
}

.plan-card img {
  width: 100%;
  height: 320px;
  object-fit: cover;
}

.plan-body {
  padding: 14px;
}

.plan-body h3 {
  margin: 0 0 8px;
  font-size: 1rem;
  letter-spacing: 1px;
}

.plan-body p {
  margin: 5px 0;
  color: #4f473d;
}

.plan-footer {
  background: #1d3a32;
  color: white;
  text-align: center;
  padding: 10px;
  font-weight: 600;
}

.center-cta {
  text-align: center;
  margin-top: 24px;
}

.services-summary {
  display: grid;
  grid-template-columns: 1fr;
  gap: 14px;
}

.summary-item h3 {
  margin: 0 0 4px;
  font-size: 1.35rem;
  color: #2a251f;
}

.summary-item p {
  margin: 0;
  color: #5a5146;
  font-size: 1rem;
}

.cta-button,
.delivery-button {
  display: inline-block;
  background: #1d3a32;
  color: white;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: 28px;
  font-weight: 500;
}

.personalized-row {
  max-width: 1480px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  border: 1px solid #e7dcc8;
  border-top: 0;
  background: #fdfaf4;
}

.personalized-item {
  text-align: center;
  padding: 20px 10px;
  border-right: 1px solid #e7dcc8;
  color: #51483d;
}

.personalized-item:last-child {
  border-right: 0;
}

.extras-section > p {
  margin: 0 0 16px;
  color: #4f473d;
}

.extras-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
}

.extra-card {
  background: white;
  border: 1px solid #e7dcc8;
}

.extra-card img {
  width: 100%;
  height: 175px;
  object-fit: cover;
}

.extra-card h3 {
  margin: 0;
  text-align: center;
  padding: 10px;
  font-size: 1.15rem;
}

.delivery-section {
  max-width: 1480px;
  margin: 0 auto 40px;
  background: url('/images/portadas/portadahome.webp') center/cover no-repeat;
  min-height: 500px;
  border: 1px solid #e7dcc8;
  border-top: 0;
}

.delivery-overlay {
  max-width: 560px;
  min-height: 500px;
  background: linear-gradient(90deg, rgba(20, 38, 31, 0.88) 0%, rgba(20, 38, 31, 0.72) 60%, rgba(20, 38, 31, 0) 100%);
  color: white;
  padding: 40px;
}

.delivery-overlay p {
  color: #eef3ef;
}

@media (max-width: 1024px) {
  .services-hero {
    background-position: center;
    height: auto;
    min-height: 560px;
    padding: 48px 48px;
  }

  .services-hero::before {
    background: rgba(253, 250, 244, 0.84);
  }

  .services-hero-copy {
    max-width: 100%;
  }

  .plans-grid {
    grid-template-columns: 1fr;
  }

  .extras-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .personalized-row {
    grid-template-columns: 1fr;
  }

  .personalized-item {
    border-right: 0;
    border-bottom: 1px solid #e7dcc8;
  }

  .personalized-item:last-child {
    border-bottom: 0;
  }
}

@media (max-width: 768px) {
  .weekly-service,
  .extras-section,
  .services-summary {
    padding: 40px 20px;
  }

  .services-hero {
    min-height: 500px;
    padding: 40px 20px;
    align-items: flex-end;
  }

  .services-hero-copy h1 {
    font-size: 2.1rem;
    line-height: 1.1;
  }

  .subtitle {
    font-size: 1rem;
  }

  .kicker {
    letter-spacing: 3px;
    font-size: 0.72rem;
  }

  .extras-grid {
    grid-template-columns: 1fr;
  }

  .delivery-overlay {
    padding: 28px 20px;
  }
}
</style>
