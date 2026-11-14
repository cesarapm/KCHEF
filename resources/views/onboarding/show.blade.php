@extends('layouts.app')

@section('content')
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
                🍽️ Perfil de Cocina - Onboarding #{{ $onboarding->id }}
            </h1>
            <p style="
                margin: 0;
                font-size: 16px;
                opacity: 0.95;
                font-weight: 300;
            ">
                Información del formulario completado
            </p>
        </div>

        <!-- CONTENT -->
        <div style="padding: 40px; max-height: 70vh; overflow-y: auto;">
            @if($form)
                <div style="display: flex; flex-direction: column; gap: 30px;">

                    <!-- ESTADO DEL FORMULARIO -->
                    <div style="
                        background: #dcfce7;
                        border: 2px solid #86efac;
                        border-radius: 12px;
                        padding: 20px;
                        text-align: center;
                    ">
                        <h2 style="color: #15803d; margin: 0; font-size: 18px;">
                            ✅ Formulario Completado
                        </h2>
                        <p style="margin: 5px 0 0 0; color: #166534; font-size: 14px;">
                            Completado el {{ $form->completed_at?->format('d/m/Y H:i') ?? 'N/A' }}
                        </p>
                    </div>

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
                        <div style="margin-bottom: 20px; border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9;">
                            <h3 style="margin: 0 0 15px 0; color: #333; font-size: 16px;">👥 Cliente(s) Principal(es)</h3>
                            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                @php
                                    $clients = is_string($form->primary_clients) 
                                        ? json_decode($form->primary_clients, true) 
                                        : (array)$form->primary_clients;
                                @endphp
                                @foreach($clients ?? [] as $client)
                                    <span style="
                                        background: #fecaca;
                                        color: #7f1d1d;
                                        padding: 8px 16px;
                                        border-radius: 20px;
                                        font-size: 14px;
                                        font-weight: 500;
                                    ">
                                        {{ $client }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- INFORMACIÓN DEMOGRÁFICA -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <!-- Dirección -->
                            <div style="border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9;">
                                <label style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                                    📍 Dirección
                                </label>
                                <div style="font-size: 16px; color: #333; font-weight: 500;">
                                    {{ $form->household_address ?? 'N/A' }}
                                </div>
                            </div>

                            <!-- Fecha de Inicio -->
                            <div style="border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9;">
                                <label style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                                    📅 Fecha de Inicio
                                </label>
                                <div style="font-size: 16px; color: #333; font-weight: 500;">
                                    {{ $form->start_date ? \Carbon\Carbon::parse($form->start_date)->format('d/m/Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <!-- COMPOSICIÓN DEL HOGAR -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                            <div style="border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9; text-align: center;">
                                <label style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; text-transform: uppercase;">
                                    👨 Adultos
                                </label>
                                <div style="font-size: 28px; color: #dc2626; font-weight: 700;">
                                    {{ $form->number_of_adults }}
                                </div>
                            </div>

                            <div style="border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9; text-align: center;">
                                <label style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; text-transform: uppercase;">
                                    👧 Niños
                                </label>
                                <div style="font-size: 28px; color: #dc2626; font-weight: 700;">
                                    {{ $form->number_of_kids }}
                                </div>
                            </div>

                            <div style="border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9;">
                                <label style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; text-transform: uppercase;">
                                    🎂 Edades
                                </label>
                                <div style="font-size: 14px; color: #333; font-weight: 500;">
                                    {{ $form->kids_ages ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: OBJETIVOS -->
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

                        <div style="border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9;">
                            @php
                                $goals = is_string($form->household_goals) 
                                    ? json_decode($form->household_goals, true) 
                                    : (array)$form->household_goals;
                            @endphp
                            
                            @if(!empty($goals))
                                <div style="display: flex; flex-direction: column; gap: 12px;">
                                    @foreach($goals as $goal)
                                        <div style="
                                            background: white;
                                            border-left: 4px solid #dc2626;
                                            padding: 12px 16px;
                                            border-radius: 6px;
                                            display: flex;
                                            align-items: center;
                                            gap: 12px;
                                        ">
                                            <span style="color: #dc2626; font-size: 18px;">✓</span>
                                            <span style="color: #333;">{{ $goal }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p style="color: #999; font-style: italic;">No especificado</p>
                            @endif

                            @if($form->household_goals_other)
                                <div style="
                                    background: white;
                                    border-left: 4px solid #f59e0b;
                                    padding: 12px 16px;
                                    border-radius: 6px;
                                    margin-top: 12px;
                                    display: flex;
                                    align-items: center;
                                    gap: 12px;
                                ">
                                    <span style="color: #f59e0b; font-size: 18px;">📝</span>
                                    <span style="color: #333;">{{ $form->household_goals_other }}</span>
                                </div>
                            @endif
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
                            🍽️ Preferencias Dietéticas
                        </h2>

                        <div style="border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9;">
                            @php
                                $dietary = is_string($form->dietary_preferences) 
                                    ? json_decode($form->dietary_preferences, true) 
                                    : (array)$form->dietary_preferences;
                            @endphp
                            
                            @if(!empty($dietary))
                                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                                    @foreach($dietary as $pref)
                                        <span style="
                                            background: #fecaca;
                                            color: #7f1d1d;
                                            padding: 8px 16px;
                                            border-radius: 20px;
                                            font-size: 14px;
                                            font-weight: 500;
                                        ">
                                            {{ $pref }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p style="color: #999; font-style: italic;">No especificado</p>
                            @endif

                            @if($form->dietary_preferences_other)
                                <div style="
                                    background: white;
                                    border-left: 4px solid #f59e0b;
                                    padding: 12px 16px;
                                    border-radius: 6px;
                                    margin-top: 12px;
                                ">
                                    <strong style="color: #f59e0b;">Otro:</strong> {{ $form->dietary_preferences_other }}
                                </div>
                            @endif
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
                            🥗 Preferencias Nutricionales
                        </h2>

                        <div style="border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9;">
                            @php
                                $nutrition = is_string($form->nutrition_preferences) 
                                    ? json_decode($form->nutrition_preferences, true) 
                                    : (array)$form->nutrition_preferences;
                            @endphp
                            
                            @if(!empty($nutrition))
                                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                                    @foreach($nutrition as $pref)
                                        <span style="
                                            background: #dcfce7;
                                            color: #15803d;
                                            padding: 8px 16px;
                                            border-radius: 20px;
                                            font-size: 14px;
                                            font-weight: 500;
                                        ">
                                            {{ $pref }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p style="color: #999; font-style: italic;">No especificado</p>
                            @endif

                            @if($form->nutrition_preferences_other)
                                <div style="
                                    background: white;
                                    border-left: 4px solid #f59e0b;
                                    padding: 12px 16px;
                                    border-radius: 6px;
                                    margin-top: 12px;
                                ">
                                    <strong style="color: #f59e0b;">Otro:</strong> {{ $form->nutrition_preferences_other }}
                                </div>
                            @endif
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
                            ⛔ Alimentos a Evitar
                        </h2>

                        <div style="border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9;">
                            @php
                                $avoid = is_string($form->foods_to_avoid) 
                                    ? json_decode($form->foods_to_avoid, true) 
                                    : (array)$form->foods_to_avoid;
                            @endphp
                            
                            @if(!empty($avoid))
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    @foreach($avoid as $food)
                                        <span style="
                                            background: #fee2e2;
                                            color: #7f1d1d;
                                            padding: 8px 16px;
                                            border-radius: 20px;
                                            font-size: 14px;
                                            font-weight: 500;
                                            border: 2px solid #fecaca;
                                        ">
                                            ❌ {{ $food }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p style="color: #999; font-style: italic;">No especificado</p>
                            @endif
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
                            🌍 Cuisinas Favoritas
                        </h2>

                        <div style="border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9;">
                            @php
                                $cuisines = is_string($form->favorite_cuisines) 
                                    ? json_decode($form->favorite_cuisines, true) 
                                    : (array)$form->favorite_cuisines;
                            @endphp
                            
                            @if(!empty($cuisines))
                                <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                                    @foreach($cuisines as $cuisine)
                                        <span style="
                                            background: #fef08a;
                                            color: #713f12;
                                            padding: 8px 16px;
                                            border-radius: 20px;
                                            font-size: 14px;
                                            font-weight: 500;
                                        ">
                                            🍲 {{ $cuisine }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p style="color: #999; font-style: italic;">No especificado</p>
                            @endif

                            @if($form->favorite_cuisines_other)
                                <div style="
                                    background: white;
                                    border-left: 4px solid #f59e0b;
                                    padding: 12px 16px;
                                    border-radius: 6px;
                                    margin-top: 12px;
                                ">
                                    <strong style="color: #f59e0b;">Otro:</strong> {{ $form->favorite_cuisines_other }}
                                </div>
                            @endif
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
                            🏪 Restaurantes Favoritos
                        </h2>

                        <div style="border: 2px solid #fee2e2; border-radius: 12px; padding: 20px; background: #fafaf9;">
                            @php
                                $restaurants = is_string($form->favorite_restaurants) 
                                    ? json_decode($form->favorite_restaurants, true) 
                                    : (array)$form->favorite_restaurants;
                            @endphp
                            
                            @if(!empty($restaurants))
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    @foreach($restaurants as $restaurant)
                                        <span style="
                                            background: #dbeafe;
                                            color: #1e40af;
                                            padding: 8px 16px;
                                            border-radius: 20px;
                                            font-size: 14px;
                                            font-weight: 500;
                                        ">
                                            🏢 {{ $restaurant }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p style="color: #999; font-style: italic;">No especificado</p>
                            @endif
                        </div>
                    </div>

                    <!-- BOTONES DE ACCIÓN -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 30px; border-top: 2px solid #fee2e2; padding-top: 30px;">
                        <a href="javascript:history.back()" style="
                            display: block;
                            background: #f3f4f6;
                            color: #333;
                            padding: 12px 24px;
                            border-radius: 8px;
                            text-decoration: none;
                            text-align: center;
                            font-weight: 600;
                            border: 2px solid #e5e7eb;
                            transition: all 0.3s ease;
                            cursor: pointer;
                        " onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                            ← Volver
                        </a>
                        
                        <a href="javascript:window.print()" style="
                            display: block;
                            background: #dc2626;
                            color: white;
                            padding: 12px 24px;
                            border-radius: 8px;
                            text-decoration: none;
                            text-align: center;
                            font-weight: 600;
                            border: none;
                            transition: all 0.3s ease;
                            cursor: pointer;
                        " onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                            🖨️ Imprimir
                        </a>
                    </div>

                </div>
            @else
                <div style="
                    background: #fee2e2;
                    border: 2px solid #fecaca;
                    border-radius: 12px;
                    padding: 30px;
                    text-align: center;
                    color: #991b1b;
                ">
                    <h3 style="margin: 0 0 10px 0; font-size: 18px;">
                        ⚠️ Formulario no encontrado
                    </h3>
                    <p style="margin: 0; font-size: 14px;">
                        Este onboarding aún no tiene un formulario completado.
                    </p>
                </div>
            @endif
        </div>

        <!-- FOOTER -->
        <div style="
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 20px;
            text-align: center;
            color: #999;
            font-size: 12px;
        ">
            <p style="margin: 0;">
                Onboarding #{{ $onboarding->id }} | 
                Prospect: {{ $onboarding->prospect->email ?? 'N/A' }} |
                {{ $form?->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}
            </p>
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @media print {
        body {
            background: white;
        }
        div[style*="background: linear-gradient(135deg"] {
            background: white !important;
        }
    }
</style>
@endsection
