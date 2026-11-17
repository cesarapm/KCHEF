<template>
  <div class="meet-team">
    <section class="meet-hero">
      <div class="meet-hero-copy">
        <p class="meet-kicker">MEET LORE & ADAM</p>
        <h1>
          Two People.
          <br>
          <span class="italic">One Shared Philosophy of Hospitality.</span>
        </h1>
        <p class="intro-lead">Great food is only part of what makes a memorable experience.</p>
        <p>
          At Kaczmarek Food Services, culinary expertise and genuine hospitality come together to create
          something deeply personal.
        </p>
        <p>
          Adam brings the food to life. Lore makes sure every detail around it feels effortless,
          thoughtful, and cared for.
        </p>
      </div>
    </section>

    <section class="meet-team-section">
      <div class="team-container">
        <article class="team-member lore">
          <div class="member-image">
            <img
              :src="'/images/meet/lore.webp'"
              alt="Lore - Hospitality Expert"
              class="member-img"
            />
          </div>
          <div class="member-content">
            <h3>LORE</h3>
            <p class="member-subtitle">The Heart Behind the Experience</p>
            <p>
              Lore brings the personal side of hospitality to every family they serve.
            </p>
            <p>
              From the first conversation forward, she helps make sure the experience feels seamless, warm, and genuinely personal—listening carefully, understanding each household's needs, and helping coordinate the details that make the service feel effortless.
            </p>
            <p class="member-philosophy">
              Because exceptional hospitality isn't simply about what arrives at the table.
            </p>
            <p class="member-quote">
              <strong>It's about how you feel along the way.</strong>
            </p>
          </div>
        </article>

        <article class="manifesto-card">
          <div class="manifesto-leaf">🌿</div>
          <h2>More Than<br>a Personal<br>Chef Service.</h2>
          <p>We believe the best moments often happen around a table.</p>
          <p>A conversation that lasts a little longer.</p>
          <p>A family finally sitting down together.</p>
          <p>A busy evening that suddenly feels easier.</p>
          <p class="manifesto-highlight">Those are the moments we want to help create.</p>
          <p class="manifesto-tagline"><em>Less time cooking.<br>More time together.</em></p>

          <div class="manifesto-actions">
            <router-link to="/services" class="btn btn-primary">Our Story</router-link>
            <router-link to="/why-families-choose-us" class="btn btn-secondary">Meet Chef Adam</router-link>
          </div>
        </article>

        <article class="team-member adam">
          <div class="member-image">
            <img
              :src="'/images/meet/adam.webp'"
              alt="Chef Adam - Culinary Expert"
              class="member-img"
            />
          </div>
          <div class="member-content">
            <h3>ADAM</h3>
            <p class="member-subtitle">The Craft Behind Every Meal</p>
            <p>
              With more than two decades of culinary experience, Chef Adam brings restaurant-level expertise into the comfort of your home.
            </p>
            <p>
              But for Adam, great cooking has never been only about technique. It's about understanding the people he cooks for—their tastes, their routines, their traditions, and the small details that turn a meal into something meaningful.
            </p>
            <p class="member-philosophy">
              His philosophy has remained simple throughout his career:
            </p>
            <p class="member-quote">
              <strong>"Cook good food for good people."</strong>
            </p>
          </div>
        </article>
      </div>
    </section>
    <section class="meet-values">
      <div class="values-container">
        <div class="value-item">
          <div class="value-icon">👥</div>
          <p>Personalized<br>Service</p>
        </div>
        <div class="value-item">
          <div class="value-icon">👨‍🍳</div>
          <p>Culinary<br>Expertise</p>
        </div>
        <div class="value-item">
          <div class="value-icon">❤️</div>
          <p>Genuine<br>Hospitality</p>
        </div>
        <div class="value-item">
          <div class="value-icon">🍃</div>
          <p>A Better<br>Everyday</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
// No additional logic needed for this page
</script>

<style scoped>
.meet-team {
  color: #2f271e;
  background: #f8f5ec;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  padding: 24px;
}

.meet-kicker {
  font-size: 0.74rem;
  letter-spacing: 5px;
  color: #8f7a57;
  text-transform: uppercase;
  margin: 0 0 12px;
}

.meet-hero {
  max-width: 1480px;
  margin: 0 auto;
  border: 1px solid #e7dcc8;
  position: relative;
  overflow: hidden;
  min-height: 620px;
  background: url('/images/portadas/portadapareja.webp') 68% center / cover no-repeat;
  display: flex;
  align-items: center;
  padding: 36px;
}

.meet-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(
    90deg,
    rgba(253, 250, 244, 0.92) 0%,
    rgba(253, 250, 244, 0.86) 24%,
    rgba(253, 250, 244, 0.56) 42%,
    rgba(253, 250, 244, 0.14) 58%,
    rgba(253, 250, 244, 0) 70%
  );
}

.meet-hero-copy {
  position: relative;
  z-index: 1;
  max-width: 500px;
  padding: 34px 30px 28px;
  background: rgba(253, 250, 244, 0.78);
  backdrop-filter: blur(1.5px);
  box-shadow: 0 16px 28px rgba(29, 31, 28, 0.12);
  border: 1px solid #e7dcc8;
}

.meet-hero-copy h1 {
  margin: 0 0 16px;
  font-size: clamp(2.4rem, 4.4vw, 5rem);
  line-height: 0.95;
  font-family: 'Playfair Display', Georgia, serif;
  font-weight: 500;
  letter-spacing: -0.015em;
}

.meet-hero-copy .italic {
  font-style: italic;
  color: #8a5c33;
}

.meet-hero-copy p {
  margin: 0 0 10px;
  line-height: 1.42;
  color: #51483d;
  max-width: 42ch;
}

.intro-lead {
  font-size: 1.25rem;
  margin-bottom: 14px;
}

.meet-team-section {
  max-width: 1480px;
  margin: 0 auto;
  border: 1px solid #e7dcc8;
  border-top: 0;
  background: #fff;
}

.team-container {
  display: grid;
  grid-template-columns: 1fr 0.82fr 1fr;
}

.team-member {
  border-right: 1px solid #e7dcc8;
  display: flex;
  flex-direction: column;
}

.team-member:last-child {
  border-right: 0;
}

.member-image {
  width: 100%;
  height: 540px;
  overflow: hidden;
  background: #f0ebe1;
}

.member-content {
  padding: 20px 22px 22px;
}

.team-member h3 {
  margin: 0 0 2px;
  font-size: 0.85rem;
  letter-spacing: 3px;
  text-transform: uppercase;
}

.member-subtitle {
  margin: 0 0 10px;
  font-size: clamp(1.8rem, 2.4vw, 2.9rem);
  line-height: 0.95;
  font-family: 'Playfair Display', Georgia, serif;
  font-weight: 500;
}

.member-content p {
  margin: 0 0 9px;
  font-size: 0.98rem;
  line-height: 1.38;
  color: #51483d;
}

.member-philosophy {
  margin-top: 12px;
  color: #5d5245;
}

.member-quote {
  margin: 8px 0 0;
  font-size: 2.2rem;
  line-height: 0.92;
  color: #8a5c33;
  font-family: 'Playfair Display', Georgia, serif;
  font-weight: 500;
  font-style: italic;
}

.manifesto-card {
  border-right: 1px solid #e7dcc8;
  border-left: 1px solid #e7dcc8;
  background: #fdfaf4;
  padding: 26px 22px;
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.manifesto-leaf {
  font-size: 2.2rem;
  margin-bottom: 6px;
}

.manifesto-card h2 {
  margin: 0 0 14px;
  font-size: clamp(2.1rem, 2.6vw, 3rem);
  line-height: 0.95;
  font-family: 'Playfair Display', Georgia, serif;
  font-weight: 500;
  color: #8a5c33;
}

.manifesto-card p {
  margin: 0 0 8px;
  line-height: 1.34;
  color: #51483d;
}

.manifesto-highlight {
  margin-top: 12px;
}

.manifesto-tagline {
  margin-top: 16px;
  color: #8a5c33;
  font-size: 2.35rem;
  line-height: 0.9;
  font-family: 'Playfair Display', Georgia, serif;
}

.manifesto-actions {
  margin-top: 20px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.btn {
  display: inline-block;
  text-decoration: none;
  padding: 12px 18px;
  border-radius: 26px;
  font-weight: 500;
  font-size: 0.95rem;
}

.btn-primary {
  background: #1d3a32;
  color: #fff;
}

.btn-secondary {
  background: transparent;
  color: #2f271e;
  border: 1px solid #e7dcc8;
}

.meet-values {
  max-width: 1480px;
  margin: 0 auto;
  background: #1d3a32;
  color: white;
  border: 1px solid #173028;
  border-top: 0;
  padding: 18px 26px;
}

.values-container {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0;
  text-align: center;
}

.value-item {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 12px;
  padding: 6px 10px;
  border-right: 1px solid rgba(232, 220, 200, 0.26);
}

.value-item:last-child {
  border-right: 0;
}

.value-icon {
  font-size: 1.8rem;
  line-height: 1;
}

.value-item p {
  font-size: 1.06rem;
  line-height: 1.2;
  margin: 0;
  color: #e8dcc8;
  text-align: left;
}

@media (max-width: 1024px) {
  .meet-team {
    padding: 16px;
  }

  .team-container {
    grid-template-columns: 1fr 1fr;
  }

  .values-container {
    grid-template-columns: repeat(2, 1fr);
    gap: 10px 0;
  }

  .value-item:nth-child(2n) {
    border-right: 0;
  }

  .value-item:nth-child(-n + 2) {
    border-bottom: 1px solid rgba(232, 220, 200, 0.26);
  }
}

@media (max-width: 768px) {
  .meet-hero-copy {
    max-width: 100%;
    padding: 24px 18px 20px;
    box-shadow: 0 10px 20px rgba(29, 31, 28, 0.1);
  }

  .meet-hero {
    min-height: 560px;
    padding: 14px;
    background-position: 62% center;
  }

  .meet-hero::before {
    background: rgba(253, 250, 244, 0.76);
  }

  .team-container {
    grid-template-columns: 1fr;
  }

  .manifesto-card,
  .team-member {
    border-right: 0;
    border-bottom: 1px solid #e7dcc8;
  }

  .team-member:last-child {
    border-bottom: 0;
  }

  .member-image {
    height: 450px;
  }

  .member-content {
    padding: 18px;
  }

  .member-quote {
    font-size: 1.85rem;
  }

  .manifesto-card {
    padding: 22px 16px;
  }

  .manifesto-tagline {
    font-size: 1.95rem;
  }

  .meet-values {
    padding: 12px 10px;
  }

  .values-container {
    grid-template-columns: 1fr;
  }

  .value-item {
    border-right: 0;
    border-bottom: 1px solid rgba(232, 220, 200, 0.26);
    justify-content: flex-start;
    padding: 10px 12px;
  }

  .value-item:nth-child(-n + 2) {
    border-bottom: 1px solid rgba(232, 220, 200, 0.26);
  }

  .value-item:last-child {
    border-bottom: 0;
  }
}
</style>
