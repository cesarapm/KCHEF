<template>
  <div class="why-page">
    <section class="cover-hero">
      <div class="cover-left">
        <h2>
          More Time Together.
          <br>
          <span>We'll Take Care of the Rest.</span>
        </h2>
        <p class="cover-script">Less time cooking. More time together.</p>
        <p>
          Personalized private chef services designed around your family, your lifestyle, and the moments that matter most.
        </p>
        <p>
          From thoughtful menu planning and grocery shopping to professionally prepared meals in the comfort of your home, we
          take care of the details so you can spend less time worrying about what's for dinner—and more time enjoying the people
          around your table.
        </p>
        <div class="cover-actions">
          <router-link to="/contacto" class="cover-btn primary">Book Your Consultation</router-link>
          <router-link to="/services" class="cover-btn secondary">Explore Our Services</router-link>
        </div>
        <p class="cover-micro">Let's create a service designed around your family.</p>
      </div>
      <div class="cover-right"></div>
    </section>

    <section class="cover-benefits">
      <article>
        <span class="benefit-icon">🍴</span>
        <div>
          <h3>Restaurant-quality cuisine.</h3>
          <p>PROFESSIONAL FLAVOR. REAL INGREDIENTS.</p>
        </div>
      </article>
      <article>
        <span class="benefit-icon">⌂</span>
        <div>
          <h3>The comfort of home.</h3>
          <p>NOURISHING MEALS IN YOUR OWN KITCHEN.</p>
        </div>
      </article>
      <article>
        <span class="benefit-icon">♡</span>
        <div>
          <h3>Service that feels personal.</h3>
          <p>A THOUGHTFUL EXPERIENCE DESIGNED AROUND YOU.</p>
        </div>
      </article>
    </section>

    <section class="why-hero">
      <div class="why-hero-inner">
        <p class="kicker">WHY FAMILIES CHOOSE US</p>
        <h1>
          The Difference
          <br>
          Is in the <span>Details.</span>
        </h1>
        <p class="lead">Great service isn't about doing one big thing perfectly.</p>
        <p>It's about caring for all the little things that make your experience feel effortless.</p>
        <p>
          From remembering how your family likes their meals to understanding dietary preferences, favorite cuisines,
          children's tastes, and the rhythm of your household, we take the time to learn what matters to you.
        </p>
        <p class="closing">Because personalized service should actually feel personal.</p>
      </div>
    </section>

    <section class="difference-grid">
      <article class="difference-card">
        <div class="difference-top">
          <span class="index">01</span>
          <span class="icon">♡</span>
        </div>
        <img :src="'/images/meet/lore.webp'" alt="Truly personalized service" />
        <h2>Truly Personalized</h2>
        <h3>Built around your household.</h3>
        <p>
          Your preferences, dietary needs, favorite cuisines, family routines, and even the tastes of individual
          family members help shape your service.
        </p>
      </article>

      <article class="difference-card">
        <div class="difference-top">
          <span class="index">02</span>
          <span class="icon">♨</span>
        </div>
        <img :src="'/images/portadas/portadahome.webp'" alt="Thoughtfully prepared meals" />
        <h2>Thoughtfully Prepared</h2>
        <h3>Restaurant-level expertise, made for real life.</h3>
        <p>
          Chef Adam brings more than two decades of culinary experience to meals created not for a restaurant dining
          room—but for the way your family actually eats and lives.
        </p>
      </article>

      <article class="difference-card">
        <div class="difference-top">
          <span class="index">03</span>
          <span class="icon">⌂</span>
        </div>
        <img :src="'/images/portadas/portadanosotros.webp'" alt="More than cooking details" />
        <h2>More Than Cooking</h2>
        <h3>We take care of the details.</h3>
        <p>
          Menu planning, grocery shopping, preparation, packaging, labeling, and kitchen clean-up are thoughtfully
          handled as part of the experience.
        </p>
      </article>

      <article class="difference-card">
        <div class="difference-top">
          <span class="index">04</span>
          <span class="icon">✦</span>
        </div>
        <img :src="'/images/meet/adams.webp'" alt="Genuine hospitality" />
        <h2>Genuine Hospitality</h2>
        <h3>You're never just another client.</h3>
        <p>
          Lore and Adam believe great hospitality begins with listening, understanding, and building relationships with
          the families they serve.
        </p>
      </article>
    </section>

    <section class="details-strip">
      <div class="strip-images">
        <img :src="'/images/portadas/portadanosotros.webp'" alt="Prepared meal container" />
        <img :src="'/images/portadas/portadahome.webp'" alt="Fresh ingredients and prep" />
        <img :src="'/images/meet/lore.webp'" alt="Personalized menu notes" />
        <img :src="'/images/meet/adams.webp'" alt="Clean home kitchen and plated meals" />
      </div>

      <div class="strip-copy">
        <p>It's the <span>meal.</span></p>
        <p>It's the <span>details.</span></p>
        <p>It's the feeling of <span>being cared for.</span></p>
      </div>

      <div class="strip-cta">
        <p>See what it feels like to have dinner taken care of.</p>
        <router-link to="/contacto" class="cta-btn">Hear From Our Families</router-link>
      </div>
    </section>
  </div>
</template>

<style scoped>
.why-page {
  background: #07271f;
  color: #f4efe4;
  padding: 24px;
}

.why-hero,
.difference-grid,
.details-strip,
.cover-hero,
.cover-benefits {
  max-width: 1480px;
  margin: 0 auto;
}

.cover-hero {
  border: 1px solid rgba(222, 197, 157, 0.28);
  background: #f8f5ec;
  display: grid;
  grid-template-columns: 0.41fr 0.59fr;
  min-height: 620px;
}

.cover-left {
  padding: 52px 36px 34px;
  background:
    linear-gradient(90deg, rgba(248, 245, 236, 0.98) 0%, rgba(248, 245, 236, 0.94) 74%, rgba(248, 245, 236, 0) 100%);
  color: #2f271e;
}

.cover-left h2 {
  margin: 0 0 14px;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: clamp(3rem, 5.5vw, 5.4rem);
  line-height: 0.93;
}

.cover-left h2 span {
  color: #8a5c33;
  font-style: italic;
}

.cover-script {
  margin: 0 0 12px;
  font-family: 'Playfair Display', Georgia, serif;
  color: #8a5c33;
  font-style: italic;
  font-size: 2rem;
}

.cover-left p {
  margin: 0 0 10px;
  font-size: 1.12rem;
  line-height: 1.4;
  max-width: 48ch;
}

.cover-actions {
  display: flex;
  gap: 12px;
  margin-top: 16px;
}

.cover-btn {
  text-decoration: none;
  border-radius: 999px;
  padding: 12px 20px;
  font-weight: 600;
}

.cover-btn.primary {
  background: #17362e;
  color: #f8f5ec;
}

.cover-btn.secondary {
  border: 1px solid #17362e;
  color: #17362e;
  background: transparent;
}

.cover-micro {
  margin-top: 12px;
  font-size: 0.95rem;
  color: #5e5549;
}

.cover-right {
  background: url('/images/portadas/portadahome.webp') center / cover no-repeat;
}

.cover-benefits {
  border: 1px solid rgba(222, 197, 157, 0.28);
  border-top: 0;
  background: #f8f5ec;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
}

.cover-benefits article {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 16px 18px;
  border-right: 1px solid rgba(47, 39, 30, 0.15);
  color: #2f271e;
}

.cover-benefits article:last-child {
  border-right: 0;
}

.benefit-icon {
  font-size: 1.6rem;
  color: #c08945;
}

.cover-benefits h3 {
  margin: 0 0 2px;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: 2rem;
  line-height: 0.95;
}

.cover-benefits p {
  margin: 0;
  font-size: 0.82rem;
  letter-spacing: 1.1px;
}

.why-hero {
  border: 1px solid rgba(222, 197, 157, 0.28);
  background:
    radial-gradient(circle at 88% 8%, rgba(207, 170, 112, 0.2) 0%, rgba(7, 39, 31, 0) 36%),
    linear-gradient(180deg, #0b362b 0%, #08261f 100%);
  padding: 60px 48px 56px;
  text-align: center;
}

.why-hero-inner {
  max-width: 980px;
  margin: 0 auto;
}

.kicker {
  margin: 0 0 18px;
  letter-spacing: 5px;
  font-size: 0.8rem;
  color: #d8bb8f;
  text-transform: uppercase;
}

.why-hero h1 {
  margin: 0 0 18px;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: clamp(3rem, 7.2vw, 5.6rem);
  line-height: 0.95;
  letter-spacing: -0.02em;
}

.why-hero h1 span,
.closing {
  color: #d7aa6e;
  font-style: italic;
}

.why-hero p {
  margin: 8px auto;
  max-width: 82ch;
  color: #ece1cb;
  line-height: 1.45;
  font-size: 1.2rem;
}

.lead {
  margin-top: 14px;
  font-size: 1.34rem;
}

.closing {
  margin-top: 18px;
  font-size: 1.8rem;
  font-family: 'Playfair Display', Georgia, serif;
}

.difference-grid {
  border: 1px solid rgba(222, 197, 157, 0.28);
  border-top: 0;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
}

.difference-card {
  padding: 16px 16px 18px;
  border-right: 1px solid rgba(222, 197, 157, 0.2);
  background: #0c3328;
}

.difference-card:last-child {
  border-right: 0;
}

.difference-top {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  margin-bottom: 12px;
  color: #d7aa6e;
}

.difference-top .index {
  font-size: 1.15rem;
}

.difference-top .icon {
  font-size: 1.45rem;
}

.difference-card img {
  width: 100%;
  height: 160px;
  object-fit: cover;
  border: 1px solid rgba(222, 197, 157, 0.25);
}

.difference-card h2 {
  margin: 14px 0 4px;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: 2.12rem;
  line-height: 0.95;
  text-align: center;
}

.difference-card h3 {
  margin: 0 0 10px;
  text-align: center;
  font-size: 0.95rem;
  letter-spacing: 1.2px;
  color: #d7aa6e;
  text-transform: uppercase;
}

.difference-card p {
  margin: 0;
  text-align: center;
  font-size: 1rem;
  line-height: 1.35;
  color: #ece1cb;
}

.details-strip {
  border: 1px solid rgba(222, 197, 157, 0.28);
  border-top: 0;
  background: #0b2f26;
  padding-bottom: 32px;
}

.strip-images {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
}

.strip-images img {
  width: 100%;
  height: 150px;
  object-fit: cover;
}

.strip-copy {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
  padding: 18px 24px 8px;
  text-align: center;
  font-family: 'Playfair Display', Georgia, serif;
  font-size: clamp(1.9rem, 3vw, 3.1rem);
  line-height: 0.95;
}

.strip-copy span {
  color: #d7aa6e;
  font-style: italic;
}

.strip-copy p {
  margin: 0;
}

.strip-cta {
  text-align: center;
  margin-top: 12px;
}

.strip-cta p {
  margin: 0 0 14px;
  color: #ece1cb;
  font-size: 1.12rem;
}

.cta-btn {
  display: inline-block;
  padding: 14px 36px;
  border-radius: 999px;
  background: #f8f5ec;
  color: #17362e;
  text-decoration: none;
  font-weight: 600;
}

@media (max-width: 1180px) {
  .cover-hero {
    grid-template-columns: 1fr;
    min-height: auto;
  }

  .cover-right {
    min-height: 380px;
  }

  .cover-benefits {
    grid-template-columns: 1fr;
  }

  .cover-benefits article {
    border-right: 0;
    border-bottom: 1px solid rgba(47, 39, 30, 0.15);
  }

  .cover-benefits article:last-child {
    border-bottom: 0;
  }

  .difference-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .difference-card:nth-child(2n) {
    border-right: 0;
  }

  .difference-card:nth-child(-n + 2) {
    border-bottom: 1px solid rgba(222, 197, 157, 0.2);
  }
}

@media (max-width: 900px) {
  .strip-images {
    grid-template-columns: repeat(2, 1fr);
  }

  .strip-copy {
    grid-template-columns: 1fr;
    font-size: 2rem;
  }
}

@media (max-width: 768px) {
  .why-page {
    padding: 14px;
  }

  .cover-left {
    padding: 30px 20px 24px;
  }

  .cover-script {
    font-size: 1.5rem;
  }

  .cover-left p {
    font-size: 1rem;
  }

  .cover-actions {
    flex-direction: column;
  }

  .cover-btn {
    text-align: center;
  }

  .why-hero {
    padding: 40px 20px;
  }

  .why-hero p {
    font-size: 1rem;
  }

  .lead {
    font-size: 1.08rem;
  }

  .closing {
    font-size: 1.45rem;
  }

  .difference-grid {
    grid-template-columns: 1fr;
  }

  .difference-card {
    border-right: 0;
    border-bottom: 1px solid rgba(222, 197, 157, 0.2);
  }

  .difference-card:last-child {
    border-bottom: 0;
  }

  .difference-card img {
    height: 220px;
  }

  .strip-images {
    grid-template-columns: 1fr;
  }

  .strip-images img {
    height: 190px;
  }
}
</style>
