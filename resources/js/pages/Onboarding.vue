<template>
  <div style="
    min-height: 100vh;
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  ">
    <div style="
      width: 100%;
      max-width: 900px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      overflow: hidden;
    ">
      <!-- HEADER -->
      <div style="
        background: linear-gradient(90deg, #dc2626 0%, #ef4444 50%, #dc2626 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
      ">
        <h1 style="
          margin: 0 0 10px 0;
          font-size: 32px;
          font-weight: 700;
        ">
          🍽️ Perfil de Cocina
        </h1>
        <p style="
          margin: 0;
          font-size: 16px;
          opacity: 0.95;
          font-weight: 300;
        ">
          Cuéntanos sobre tus preferencias alimenticias para personalizar tu experiencia
        </p>
      </div>

      <!-- CONTENT -->
      <div style="padding: 40px; max-height: 70vh; overflow-y: auto;">
        <!-- LOADING STATE -->
        <div v-if="loading" style="text-align: center; padding: 40px;">
          <div style="
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 4px solid #fee2e2;
            border-top: 4px solid #dc2626;
            border-radius: 50%;
            animation: spin 1s linear infinite;
          "></div>
          <p style="margin-top: 20px; color: #666;">Cargando formulario...</p>
        </div>

        <!-- ERROR STATE -->
        <div v-else-if="error" style="
          background: #fee2e2;
          border: 1px solid #fecaca;
          border-radius: 8px;
          padding: 16px;
          color: #991b1b;
          margin-bottom: 20px;
        ">
          ⚠️ {{ error }}
        </div>

        <!-- FORM CONTENT -->
        <div v-else style="display: flex; flex-direction: column; gap: 30px;">
          <!-- SECCIÓN 1: INFORMACIÓN BÁSICA -->
          <div style="border-bottom: 2px solid #fee2e2; padding-bottom: 30px;">
            <h2 style="
              color: #dc2626;
              font-size: 22px;
              margin: 0 0 20px 0;
              display: flex;
              align-items: center;
              gap: 10px;
            ">
              👤 Información Básica
            </h2>

            <!-- CLIENTES PRINCIPALES -->
            <div style="margin-bottom: 30px; border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9;">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #333; font-size: 16px;">👥 Cliente(s) Principal(es)</h3>
                <button
                  @click="addPrimaryClient"
                  type="button"
                  style="
                    background: #dc2626;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 12px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                  "
                  @mouseenter="$event.target.style.backgroundColor = '#b91c1c'"
                  @mouseleave="$event.target.style.backgroundColor = '#dc2626'"
                >
                  + Agregar Cliente
                </button>
              </div>

              <div v-for="(client, idx) in primaryClients" :key="idx" style="
                background: white;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 10px;
                border: 1px solid #e5e7eb;
                display: grid;
                grid-template-columns: 1fr auto;
                gap: 15px;
                align-items: flex-end;
              ">
                <div>
                  <label style="
                    display: block;
                    font-weight: 600;
                    color: #333;
                    margin-bottom: 6px;
                    font-size: 13px;
                  ">
                    Nombre *
                  </label>
                  <input
                    v-model="client.name"
                    type="text"
                    placeholder="Ej: Juan"
                    style="
                      width: 100%;
                      padding: 10px 12px;
                      border: 1px solid #ddd;
                      border-radius: 6px;
                      font-size: 13px;
                      box-sizing: border-box;
                      transition: all 0.3s ease;
                    "
                    @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                    @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
                  />
                </div>

                <!-- Botón Eliminar -->
                <button
                  @click="removePrimaryClient(idx)"
                  type="button"
                  v-if="primaryClients.length > 1"
                  style="
                    background: #fee2e2;
                    color: #dc2626;
                    border: 1px solid #fecaca;
                    padding: 10px 16px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 12px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                  "
                  @mouseenter="$event.target.style.backgroundColor = '#fecaca'"
                  @mouseleave="$event.target.style.backgroundColor = '#fee2e2'"
                >
                  ✕ Eliminar
                </button>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
              <!-- Start Date -->
              <div>
                <label style="
                  display: block;
                  font-weight: 600;
                  color: #333;
                  margin-bottom: 8px;
                  font-size: 14px;
                ">
                  Fecha de inicio
                </label>
                <input
                  v-model="form.start_date"
                  type="date"
                  style="
                    width: 100%;
                    padding: 12px 14px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    font-size: 14px;
                    box-sizing: border-box;
                    transition: all 0.3s ease;
                  "
                  @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                  @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
                />
              </div>
            </div>

            <!-- Address -->
            <div style="margin-top: 20px;">
              <label style="
                display: block;
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
                font-size: 14px;
              ">
                Dirección del hogar
              </label>
              <input
                v-model="form.household_address"
                type="text"
                placeholder="Ej: 123 Main St, Nueva York, NY 10001"
                style="
                  width: 100%;
                  padding: 12px 14px;
                  border: 1px solid #ddd;
                  border-radius: 8px;
                  font-size: 14px;
                  box-sizing: border-box;
                  transition: all 0.3s ease;
                "
                @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
              />
            </div>

            <!-- Adults & Kids -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 20px;">
              <div>
                <label style="
                  display: block;
                  font-weight: 600;
                  color: #333;
                  margin-bottom: 8px;
                  font-size: 14px;
                ">
                  Adultos en el hogar
                </label>
                <input
                  v-model.number="form.number_of_adults"
                  type="number"
                  min="0"
                  style="
                    width: 100%;
                    padding: 12px 14px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    font-size: 14px;
                    box-sizing: border-box;
                    transition: all 0.3s ease;
                  "
                  @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                  @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
                />
              </div>
              <div>
                <label style="
                  display: block;
                  font-weight: 600;
                  color: #333;
                  margin-bottom: 8px;
                  font-size: 14px;
                ">
                  Niños en el hogar
                </label>
                <input
                  v-model.number="form.number_of_kids"
                  type="number"
                  min="0"
                  style="
                    width: 100%;
                    padding: 12px 14px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    font-size: 14px;
                    box-sizing: border-box;
                    transition: all 0.3s ease;
                  "
                  @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                  @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
                />
              </div>
              <div>
                <label style="
                  display: block;
                  font-weight: 600;
                  color: #333;
                  margin-bottom: 8px;
                  font-size: 14px;
                ">
                  Edades de los niños
                </label>
                <input
                  v-model="form.kids_ages"
                  type="text"
                  placeholder="Ej: 5, 8, 12"
                  style="
                    width: 100%;
                    padding: 12px 14px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    font-size: 14px;
                    box-sizing: border-box;
                    transition: all 0.3s ease;
                  "
                  @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                  @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
                />
              </div>
            </div>
          </div>

          <!-- SECCIÓN 2: OBJETIVOS DEL HOGAR -->
          <div style="border-bottom: 2px solid #fee2e2; padding-bottom: 30px;">
            <h2 style="
              color: #dc2626;
              font-size: 22px;
              margin: 0 0 20px 0;
              display: flex;
              align-items: center;
              gap: 10px;
            ">
              🎯 Objetivos del Hogar
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
              <label v-for="goal in householdGoalsOptions" :key="goal" style="
                display: flex;
                align-items: center;
                gap: 10px;
                cursor: pointer;
                padding: 12px;
                border-radius: 8px;
                transition: all 0.2s ease;
              "
              @mouseenter="$event.currentTarget.style.backgroundColor = '#f3f4f6'"
              @mouseleave="$event.currentTarget.style.backgroundColor = 'transparent'"
              >
                <input
                  type="checkbox"
                  :checked="form.household_goals?.includes(goal)"
                  @change="toggleArrayItem('household_goals', goal)"
                  style="
                    width: 18px;
                    height: 18px;
                    cursor: pointer;
                    accent-color: #dc2626;
                  "
                />
                <span style="font-size: 14px; color: #333;">{{ goal }}</span>
              </label>
            </div>

            <!-- Campo de texto libre para "otros" objetivos -->
            <div style="margin-top: 20px;">
              <label style="
                display: block;
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
                font-size: 14px;
              ">
                ✍️ Otros objetivos
              </label>
              <input
                v-model="form.household_goals_other"
                type="text"
                placeholder="Especifica otros objetivos que tengas..."
                style="
                  width: 100%;
                  padding: 12px 14px;
                  border: 1px solid #ddd;
                  border-radius: 6px;
                  font-size: 14px;
                  font-family: inherit;
                  box-sizing: border-box;
                  transition: all 0.3s ease;
                  color: #333;
                  background-color: #fff;
                "
                @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
              />
            </div>
          </div>

          <!-- SECCIÓN 3: PREFERENCIAS DIETÉTICAS -->
          <div style="border-bottom: 2px solid #fee2e2; padding-bottom: 30px;">
            <h2 style="
              color: #dc2626;
              font-size: 22px;
              margin: 0 0 20px 0;
              display: flex;
              align-items: center;
              gap: 10px;
            ">
              🥗 Preferencias Dietéticas
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
              <label v-for="diet in dietaryPreferencesOptions" :key="diet" style="
                display: flex;
                align-items: center;
                gap: 10px;
                cursor: pointer;
                padding: 12px;
                border-radius: 8px;
                transition: all 0.2s ease;
              "
              @mouseenter="$event.currentTarget.style.backgroundColor = '#f3f4f6'"
              @mouseleave="$event.currentTarget.style.backgroundColor = 'transparent'"
              >
                <input
                  type="checkbox"
                  :checked="form.dietary_preferences?.includes(diet)"
                  @change="toggleArrayItem('dietary_preferences', diet)"
                  style="
                    width: 18px;
                    height: 18px;
                    cursor: pointer;
                    accent-color: #dc2626;
                  "
                />
                <span style="font-size: 14px; color: #333;">{{ diet }}</span>
              </label>
            </div>

            <!-- Campo de texto libre para preferencias dietéticas adicionales -->
            <div style="margin-top: 20px;">
              <label style="
                display: block;
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
                font-size: 14px;
              ">
                ✍️ Otras preferencias dietéticas
              </label>
              <input
                v-model="form.dietary_preferences_other"
                type="text"
                placeholder="Especifica otras preferencias dietéticas..."
                style="
                  width: 100%;
                  padding: 12px 14px;
                  border: 1px solid #ddd;
                  border-radius: 6px;
                  font-size: 14px;
                  font-family: inherit;
                  box-sizing: border-box;
                  transition: all 0.3s ease;
                  color: #333;
                  background-color: #fff;
                "
                @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
              />
            </div>
          </div>

          <!-- SECCIÓN 4: PREFERENCIAS NUTRICIONALES -->
          <div style="border-bottom: 2px solid #fee2e2; padding-bottom: 30px;">
            <h2 style="
              color: #dc2626;
              font-size: 22px;
              margin: 0 0 20px 0;
              display: flex;
              align-items: center;
              gap: 10px;
            ">
              💪 Preferencias Nutricionales
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
              <label v-for="nutrition in nutritionPreferencesOptions" :key="nutrition" style="
                display: flex;
                align-items: center;
                gap: 10px;
                cursor: pointer;
                padding: 12px;
                border-radius: 8px;
                transition: all 0.2s ease;
              "
              @mouseenter="$event.currentTarget.style.backgroundColor = '#f3f4f6'"
              @mouseleave="$event.currentTarget.style.backgroundColor = 'transparent'"
              >
                <input
                  type="checkbox"
                  :checked="form.nutrition_preferences?.includes(nutrition)"
                  @change="toggleArrayItem('nutrition_preferences', nutrition)"
                  style="
                    width: 18px;
                    height: 18px;
                    cursor: pointer;
                    accent-color: #dc2626;
                  "
                />
                <span style="font-size: 14px; color: #333;">{{ nutrition }}</span>
              </label>
            </div>

            <!-- Campo de texto libre para preferencias nutricionales adicionales -->
            <div style="margin-top: 20px;">
              <label style="
                display: block;
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
                font-size: 14px;
              ">
                ✍️ Otras preferencias nutricionales
              </label>
              <input
                v-model="form.nutrition_preferences_other"
                type="text"
                placeholder="Especifica otras preferencias nutricionales..."
                style="
                  width: 100%;
                  padding: 12px 14px;
                  border: 1px solid #ddd;
                  border-radius: 6px;
                  font-size: 14px;
                  font-family: inherit;
                  box-sizing: border-box;
                  transition: all 0.3s ease;
                  color: #333;
                  background-color: #fff;
                "
                @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
              />
            </div>
          </div>

          <!-- SECCIÓN 5: ALIMENTOS A EVITAR -->
          <div style="border-bottom: 2px solid #fee2e2; padding-bottom: 30px;">
            <h2 style="
              color: #dc2626;
              font-size: 22px;
              margin: 0 0 20px 0;
              display: flex;
              align-items: center;
              gap: 10px;
            ">
              ❌ Alimentos a Evitar
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(1fr)); gap: 15px;">
              <div v-for="(item, index) in foodsToAvoid" :key="index">
                <label style="
                  display: block;
                  font-weight: 500;
                  color: #666;
                  margin-bottom: 6px;
                  font-size: 12px;
                ">
                  Alimento {{ index + 1 }}
                </label>
                <input
                  v-model="foodsToAvoid[index]"
                  type="text"
                  placeholder="Ej: Maní"
                  style="
                    width: 100%;
                    padding: 12px 14px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    font-size: 14px;
                    box-sizing: border-box;
                    transition: all 0.3s ease;
                  "
                  @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                  @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
                />
              </div>
            </div>
          </div>

          <!-- SECCIÓN 6: CUISINAS FAVORITAS -->
          <div style="border-bottom: 2px solid #fee2e2; padding-bottom: 30px;">
            <h2 style="
              color: #dc2626;
              font-size: 22px;
              margin: 0 0 20px 0;
              display: flex;
              align-items: center;
              gap: 10px;
            ">
              🍜 Cuisinas Favoritas
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
              <label v-for="cuisine in favoritesCuisinesOptions" :key="cuisine" style="
                display: flex;
                align-items: center;
                gap: 10px;
                cursor: pointer;
                padding: 12px;
                border-radius: 8px;
                transition: all 0.2s ease;
              "
              @mouseenter="$event.currentTarget.style.backgroundColor = '#f3f4f6'"
              @mouseleave="$event.currentTarget.style.backgroundColor = 'transparent'"
              >
                <input
                  type="checkbox"
                  :checked="form.favorite_cuisines?.includes(cuisine)"
                  @change="toggleArrayItem('favorite_cuisines', cuisine)"
                  style="
                    width: 18px;
                    height: 18px;
                    cursor: pointer;
                    accent-color: #dc2626;
                  "
                />
                <span style="font-size: 14px; color: #333;">{{ cuisine }}</span>
              </label>
            </div>

            <!-- Campo de texto libre para cuisinas favoritas adicionales -->
            <div style="margin-top: 20px;">
              <label style="
                display: block;
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
                font-size: 14px;
              ">
                ✍️ Otras cuisinas favoritas
              </label>
              <input
                v-model="form.favorite_cuisines_other"
                type="text"
                placeholder="Especifica otras cuisinas que te gusten..."
                style="
                  width: 100%;
                  padding: 12px 14px;
                  border: 1px solid #ddd;
                  border-radius: 6px;
                  font-size: 14px;
                  font-family: inherit;
                  box-sizing: border-box;
                  transition: all 0.3s ease;
                  color: #333;
                  background-color: #fff;
                "
                @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
              />
            </div>
          </div>

          <!-- SECCIÓN 7: RESTAURANTES FAVORITOS -->
          <div style="padding-bottom: 30px;">
            <h2 style="
              color: #dc2626;
              font-size: 22px;
              margin: 0 0 20px 0;
              display: flex;
              align-items: center;
              gap: 10px;
            ">
              🍽️ Restaurantes Favoritos
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(1fr)); gap: 15px;">
              <div v-for="(item, index) in restaurantFavorites" :key="index">
                <label style="
                  display: block;
                  font-weight: 500;
                  color: #666;
                  margin-bottom: 6px;
                  font-size: 12px;
                ">
                  Restaurante {{ index + 1 }}
                </label>
                <input
                  v-model="restaurantFavorites[index]"
                  type="text"
                  placeholder="Ej: Chipotle"
                  style="
                    width: 100%;
                    padding: 12px 14px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    font-size: 14px;
                    box-sizing: border-box;
                    transition: all 0.3s ease;
                  "
                  @focus="$event.target.style.borderColor = '#dc2626'; $event.target.style.boxShadow = '0 0 0 3px rgba(220, 38, 38, 0.1)'"
                  @blur="$event.target.style.borderColor = '#ddd'; $event.target.style.boxShadow = 'none'"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- FOOTER -->
      <div style="
        background: #f9fafb;
        padding: 30px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 15px;
        justify-content: flex-end;
      ">
        <button
          @click="saveDraft"
          :disabled="saving"
          style="
            padding: 12px 24px;
            border: 2px solid #dc2626;
            background: white;
            color: #dc2626;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
          "
          @mouseenter="$event.target.style.backgroundColor = '#fee2e2'"
          @mouseleave="$event.target.style.backgroundColor = 'white'"
        >
          {{ saving ? '⏳ Guardando...' : '💾 Guardar Borrador' }}
        </button>

        <button
          @click="submitForm"
          :disabled="saving"
          style="
            padding: 12px 32px;
            background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
          "
          @mouseenter="$event.target.style.transform = 'translateY(-2px)'; $event.target.style.boxShadow = '0 10px 20px rgba(220, 38, 38, 0.2)'"
          @mouseleave="$event.target.style.transform = 'translateY(0)'; $event.target.style.boxShadow = 'none'"
        >
          {{ saving ? '⏳ Completando...' : '✨ Completar Perfil' }}
        </button>
      </div>

      <!-- SUCCESS MESSAGE -->
      <transition>
        <div v-if="successMessage" style="
          position: fixed;
          top: 30px;
          right: 30px;
          background: #10b981;
          color: white;
          padding: 20px 30px;
          border-radius: 8px;
          box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
          font-weight: 600;
          animation: slideIn 0.3s ease;
          z-index: 1000;
        ">
          ✅ {{ successMessage }}
        </div>
      </transition>

      <!-- VALIDATION ERROR DIALOG -->
      <transition>
        <div v-if="showValidationError" style="
          position: fixed;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: rgba(0, 0, 0, 0.6);
          display: flex;
          align-items: center;
          justify-content: center;
          z-index: 2000;
        " @click="showValidationError = false">
          <div style="
            background: white;
            border-radius: 16px;
            padding: 40px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 30px 80px rgba(220, 38, 38, 0.3);
            animation: slideDown 0.3s ease;
            border-top: 6px solid #dc2626;
          " @click.stop>
            <div style="
              display: flex;
              align-items: flex-start;
              gap: 16px;
              margin-bottom: 25px;
            ">
              <span style="
                font-size: 40px;
                line-height: 1;
              ">⚠️</span>
              <div>
                <h2 style="
                  margin: 0 0 8px 0;
                  color: #dc2626;
                  font-size: 24px;
                  font-weight: 800;
                ">
                  Campos Incompletos
                </h2>
                <p style="
                  margin: 0;
                  color: #999;
                  font-size: 13px;
                ">
                  Completa estos campos para continuar
                </p>
              </div>
            </div>

            <div style="
              background: #fef2f2;
              border: 2px solid #fee2e2;
              border-radius: 10px;
              padding: 20px;
              margin-bottom: 30px;
            ">
              <div style="
                display: flex;
                flex-direction: column;
                gap: 12px;
              ">
                <div v-for="(field, idx) in validationErrors" :key="idx" style="
                  display: flex;
                  align-items: center;
                  gap: 12px;
                  padding: 12px 16px;
                  background: white;
                  border-left: 4px solid #dc2626;
                  border-radius: 6px;
                  color: #991b1b;
                  font-size: 15px;
                  font-weight: 600;
                ">
                  <span style="
                    width: 24px;
                    height: 24px;
                    background: #dc2626;
                    color: white;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 12px;
                    font-weight: bold;
                    flex-shrink: 0;
                  ">{{ idx + 1 }}</span>
                  <span>{{ field }}</span>
                </div>
              </div>
            </div>

            <div style="
              background: #f0fdf4;
              border: 1px solid #dbeafe;
              border-radius: 8px;
              padding: 16px;
              margin-bottom: 25px;
            ">
              <p style="
                margin: 0;
                color: #166534;
                font-size: 13px;
                line-height: 1.5;
              ">
                💡 <strong>Tip:</strong> Todos los campos son obligatorios. Scrollea hacia arriba en el formulario para encontrar los campos que faltan.
              </p>
            </div>

            <div style="
              display: flex;
              gap: 12px;
            ">
              <button
                @click="showValidationError = false"
                style="
                  flex: 1;
                  padding: 14px 20px;
                  background: #f3f4f6;
                  color: #333;
                  border: none;
                  border-radius: 8px;
                  font-weight: 600;
                  cursor: pointer;
                  transition: all 0.3s ease;
                  font-size: 15px;
                "
                @mouseenter="$event.target.style.backgroundColor = '#e5e7eb'"
                @mouseleave="$event.target.style.backgroundColor = '#f3f4f6'"
              >
                ← Volver a completar
              </button>
              <button
                @click="scrollToFirstIncompleteField"
                style="
                  flex: 1;
                  padding: 14px 20px;
                  background: #dc2626;
                  color: white;
                  border: none;
                  border-radius: 8px;
                  font-weight: 600;
                  cursor: pointer;
                  transition: all 0.3s ease;
                  font-size: 15px;
                "
                @mouseenter="$event.target.style.backgroundColor = '#b91c1c'"
                @mouseleave="$event.target.style.backgroundColor = '#dc2626'"
              >
                📍 Ir al primer campo faltante
              </button>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const loading = ref(true)
const saving = ref(false)
const error = ref(null)
const successMessage = ref(null)
const showValidationError = ref(false)
const validationErrors = ref([])

// Form data
const form = ref({
  primary_clients: [],
  start_date: null,
  household_address: null,
  number_of_adults: 0,
  number_of_kids: 0,
  kids_ages: null,
  household_goals: [],
  household_goals_other: '', // Campo para "otros" personalizado
  dietary_preferences: [],
  dietary_preferences_other: '', // Campo para "otros" personalizado
  nutrition_preferences: [],
  nutrition_preferences_other: '', // Campo para "otros" personalizado
  foods_to_avoid: [],
  favorite_cuisines: [],
  favorite_cuisines_other: '', // Campo para "otros" personalizado
  favorite_restaurants: [],
  children_preferences: null,
  status: 'draft',
})

const primaryClients = ref([
  { name: '' }
])
const foodsToAvoid = ref(['', '', '', '', ''])
const restaurantFavorites = ref(['', '', '', ''])

// Opciones de formulario
const householdGoalsOptions = [
  'Ahorrar tiempo',
  'Comer más saludable',
  'Perder peso',
  'Ganar músculo',
  'Cenas en familia',
  'Más variedad',
  'Menos comida rápida',
  'Conveniencia',
  'Preparación de comidas',
  'Amigable para niños',
  'Necesidades dietéticas especiales'
]

const dietaryPreferencesOptions = [
  'Mediterráneo',
  'Alto en proteína',
  'Bajo en carbohidratos',
  'Keto',
  'Paleo',
  'Vegetariano',
  'Vegano',
  'Pescatariano',
  'Sin lácteos',
  'Sin gluten',
  'Orgánico',
]

const nutritionPreferencesOptions = [
  'Bajo en sodio',
  'Sin azúcar añadido',
  'Alto en proteína',
  'Bajo en grasas',
  'Saludable para el corazón',
  'Granos integrales',
  'Carnes magras',
  'Res alimentada con pasto',
  'Mariscos salvajes',
  'Productos orgánicos',
  'Sin aceites de semillas',
  'Aguacate/Aceite de oliva',
]

const favoritesCuisinesOptions = [
  'Italiana',
  'Mexicana',
  'Mediterránea',
  'Griega',
  'Americana',
  'Francesa',
  'Española',
  'Japonesa',
  'China',
  'Tailandesa',
  'Coreana',
  'India',
  'Oriente Medio',
  'Vietnamita',
  'Sureña',
  'BBQ',
  'Mariscos',
]

// Métodos
const toggleArrayItem = (fieldName, item) => {
  const arr = form.value[fieldName]
  if (!arr) {
    form.value[fieldName] = [item]
  } else {
    const index = arr.indexOf(item)
    if (index > -1) {
      arr.splice(index, 1)
    } else {
      arr.push(item)
    }
  }
  console.log(`${fieldName} actualizado:`, form.value[fieldName])
}

const addPrimaryClient = () => {
  primaryClients.value.push({ name: '' })
}

const removePrimaryClient = (index) => {
  primaryClients.value.splice(index, 1)
}

const readApiError = async (response, fallbackMessage) => {
  const contentType = response.headers.get('content-type') || ''

  if (contentType.includes('application/json')) {
    const data = await response.json()
    return data.message || fallbackMessage
  }

  const text = await response.text()
  if (text && text.trim().startsWith('<!DOCTYPE')) {
    // Extraer información del error HTML si es posible
    console.error('HTML Error Response:', text.substring(0, 500))
    return `${fallbackMessage} (respuesta HTML del servidor - Status: ${response.status})`
  }

  // Si es texto plano
  if (text) {
    console.error('Error Text:', text.substring(0, 200))
    return text.substring(0, 200)
  }

  return fallbackMessage
}

const scrollToFirstIncompleteField = () => {
  showValidationError.value = false
  
  setTimeout(() => {
    // Scroll al inicio del formulario
    const formContainer = document.querySelector('[style*="max-width"]')
    if (formContainer) {
      formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' })
    }
  }, 100)
}

const loadForm = async () => {
  try {
    loading.value = true
    error.value = null

    // Obtener el onboarding_id del usuario autenticado
    const userResponse = await fetch('/api/user', {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
    if (!userResponse.ok) {
      throw new Error('No autorizado - Por favor inicia sesión')
    }
    const userData = await userResponse.json()

    if (!userData.prospect_id) {
      throw new Error('No se encontró el prospect. Por favor intenta de nuevo.')
    }

    // Obtener el onboarding existente
    const onboardingResponse = await fetch(`/api/prospects/${userData.prospect_id}/onboarding`, {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
    if (!onboardingResponse.ok) {
      const message = await readApiError(onboardingResponse, 'Error cargando onboarding')
      throw new Error(message)
    }
    const onboardingData = await onboardingResponse.json()

    if (!onboardingData.onboarding) {
      throw new Error('No se encontró el onboarding')
    }

    // Obtener el formulario
    const formResponse = await fetch(`/api/onboarding/${onboardingData.onboarding.id}/form`, {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
    if (formResponse.ok) {
      const formData = await formResponse.json()
      if (formData.form) {
        // Llenar el formulario con datos existentes
        form.value = {
          ...form.value,
          ...formData.form,
        }

        // Restaurar arrays separados
        if (formData.form.primary_clients && formData.form.primary_clients.length > 0) {
          primaryClients.value = formData.form.primary_clients
        }
        if (formData.form.foods_to_avoid && formData.form.foods_to_avoid.length > 0) {
          foodsToAvoid.value = formData.form.foods_to_avoid
        }
        if (formData.form.favorite_restaurants && formData.form.favorite_restaurants.length > 0) {
          restaurantFavorites.value = formData.form.favorite_restaurants
        }
      }
    }
  } catch (err) {
    console.error('Error loading form:', err)
    error.value = err.message || 'Error cargando formulario'
  } finally {
    loading.value = false
  }
}

const saveDraft = async () => {
  await submitForm(false)
}

const validateForm = () => {
  const errors = []

  // Validar campos requeridos
  if (!form.value.household_address || form.value.household_address.trim() === '') {
    errors.push('📍 Dirección del hogar')
  }
  
  if (!form.value.number_of_adults || form.value.number_of_adults <= 0) {
    errors.push('👥 Número de adultos')
  }
  
  if (form.value.number_of_kids > 0 && (!form.value.kids_ages || form.value.kids_ages.trim() === '')) {
    errors.push('👶 Edades de los niños')
  }
  
  if (!form.value.household_goals || form.value.household_goals.length === 0) {
    errors.push('🎯 Objetivos del hogar (selecciona al menos uno)')
  }
  
  if (!form.value.dietary_preferences || form.value.dietary_preferences.length === 0) {
    errors.push('🥗 Preferencias dietéticas (selecciona al menos una)')
  }
  
  if (!form.value.nutrition_preferences || form.value.nutrition_preferences.length === 0) {
    errors.push('💪 Preferencias nutricionales (selecciona al menos una)')
  }
  
  if (!form.value.favorite_cuisines || form.value.favorite_cuisines.length === 0) {
    errors.push('🍜 Cuisinas favoritas (selecciona al menos una)')
  }

  return errors
}

const submitForm = async (complete = true) => {
  try {
    saving.value = true
    error.value = null

    // Validar formulario antes de enviar
    const errors = validateForm()
    console.log('Errores de validación:', errors)
    if (errors.length > 0) {
      validationErrors.value = errors
      showValidationError.value = true
      saving.value = false
      console.log('Modal mostrado con errores:', validationErrors.value)
      return
    }

    // Obtener el onboarding_id
    const userResponse = await fetch('/api/user', {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
    if (!userResponse.ok) {
      throw new Error('No autorizado')
    }
    const userData = await userResponse.json()

    if (!userData.prospect_id) {
      throw new Error('No se encontró el prospect')
    }

    const onboardingResponse = await fetch(`/api/prospects/${userData.prospect_id}/onboarding`, {
      credentials: 'include',
      headers: { 'Accept': 'application/json' }
    })
    if (!onboardingResponse.ok) {
      const message = await readApiError(onboardingResponse, 'Error obteniendo onboarding')
      throw new Error(message)
    }
    const onboardingData = await onboardingResponse.json()
    const onboardingId = onboardingData.onboarding.id

    // Preparar datos - agregar todos los campos del formulario
    const submitData = {
      household_goals: form.value.household_goals || [],
      household_goals_other: form.value.household_goals_other || '',
      dietary_preferences: form.value.dietary_preferences || [],
      dietary_preferences_other: form.value.dietary_preferences_other || '',
      nutrition_preferences: form.value.nutrition_preferences || [],
      nutrition_preferences_other: form.value.nutrition_preferences_other || '',
      favorite_cuisines: form.value.favorite_cuisines || [],
      favorite_cuisines_other: form.value.favorite_cuisines_other || '',
      primary_clients: primaryClients.value.filter(c => c.name.trim() !== ''),
      foods_to_avoid: foodsToAvoid.value.filter(f => f.trim() !== ''),
      favorite_restaurants: restaurantFavorites.value.filter(r => r.trim() !== ''),
      start_date: form.value.start_date,
      household_address: form.value.household_address,
      number_of_adults: form.value.number_of_adults,
      number_of_kids: form.value.number_of_kids,
      kids_ages: form.value.kids_ages,
      children_preferences: form.value.children_preferences,
      status: complete ? 'completed' : 'draft',
    }

    console.log('Enviando datos:', submitData)

    // Guardar formulario
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
    console.log('CSRF Token:', csrfToken ? 'presente' : 'no encontrado')
    
    const response = await fetch(`/api/onboarding/${onboardingId}/form`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken || '',
      },
      credentials: 'include',
      body: JSON.stringify(submitData),
    })

    console.log('Response status:', response.status)
    console.log('Response headers:', {
      contentType: response.headers.get('content-type'),
      status: response.status,
    })

    if (!response.ok) {
      const message = await readApiError(response, 'Error guardando formulario')
      throw new Error(message)
    }

    const result = await response.json()

    if (complete) {
      // Completar onboarding
      const completeResponse = await fetch(`/api/onboarding/${onboardingId}/form/complete`, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || '',
        },
        credentials: 'include',
      })

      if (completeResponse.ok) {
        successMessage.value = '¡Perfil completado con éxito!'
        setTimeout(() => {
          router.push('/dashboard')
        }, 1500)
      } else {
        const message = await readApiError(completeResponse, 'Error completando onboarding')
        throw new Error(message)
      }
    } else {
      successMessage.value = '✅ Borrador guardado'
      setTimeout(() => {
        successMessage.value = null
      }, 2000)
    }
  } catch (err) {
    console.error('Error submitting form:', err)
    error.value = err.message || 'Error guardando formulario'
  } finally {
    saving.value = false
  }
}

onMounted(() => {
  loadForm()
})
</script>

<style scoped>
@keyframes spin {
  to { transform: rotate(360deg); }
}

@keyframes slideIn {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOut {
  from {
    transform: translateX(0);
    opacity: 1;
  }
  to {
    transform: translateX(400px);
    opacity: 0;
  }
}

.v-enter-active, .v-leave-active {
  transition: all 0.3s ease;
}

.v-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.v-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
