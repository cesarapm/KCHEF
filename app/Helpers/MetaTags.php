<?php

namespace App\Helpers;

class MetaTags
{
    private static array $pages = [
        // Home
        '' => [
            'title' => 'KChef Services | Personalized Private Chef Services',
            'description' => 'Personalized private chef services designed around your family, your lifestyle, and the moments that matter most. Chef Adam & Lorena bring restaurant-quality cuisine to your home.',
            'keywords' => 'private chef, personal chef, meal planning, home chef, food services, catering, family meals, chef services',
            'image' => '/images/portadas/portadahome.webp',
            'og_type' => 'website',
        ],

        // Services
        'services' => [
            'title' => 'Our Services | KChef Services',
            'description' => 'Meals made around your family. Personalized weekly private chef services, thoughtful add-ons, and options designed for your household.',
            'keywords' => 'private chef services, meal plans, weekly chef, personalized meals, family meal prep',
            'image' => '/images/portadas/portadahome.webp',
            'og_type' => 'website',
        ],

        // Meet the Team
        'meet-the-team' => [
            'title' => 'Meet Lore & Adam | KChef Services',
            'description' => 'Meet the team behind KChef Services. Chef Adam and Lore bring culinary expertise and genuine hospitality to every family they serve.',
            'keywords' => 'chef adam, lore, private chef team, hospitality, personalized chef service',
            'image' => '/images/meet/adams.webp',
            'og_type' => 'website',
        ],

        // Why families choose us
        'why-families-choose-us' => [
            'title' => 'Why Families Choose Us | KChef Services',
            'description' => 'The difference is in the details. Discover why families choose KChef Services for truly personalized meals and genuine hospitality.',
            'keywords' => 'why choose private chef, personalized chef service, family meals, hospitality, chef service details',
            'image' => '/images/portadas/portadanosotros.webp',
            'og_type' => 'website',
        ],
        
        // Prospect Registration
        'prospecto' => [
            'title' => 'Register as Prospect | KChef Services',
            'description' => 'Join our community and become a prospect for personalized private chef services. Book your consultation today.',
            'keywords' => 'register prospect, private chef services, meal planning services, consultation',
            'image' => '/images/portadas/portadanosotros.webp',
            'index' => false,
        ],
        
        // Login Page
        'login' => [
            'title' => 'Sign In | KChef Services',
            'description' => 'Sign in to your KChef Services account to manage your private chef services and consultation.',
            'keywords' => 'login, sign in, account, private chef, consultation',
            'image' => '/images/portadas/portadahome.webp',
            'index' => false,
        ],
        
        // Dashboard
        'dashboard' => [
            'title' => 'Dashboard | KChef Services',
            'description' => 'Manage your private chef services from your personal dashboard. Track your consultations and preferences.',
            'keywords' => 'dashboard, account, private chef management, consultation tracking',
            'image' => '/images/portadas/portadahome.webp',
            'index' => false,
        ],
        
        // Onboarding
        'onboarding' => [
            'title' => 'Onboarding | KChef Services',
            'description' => 'Complete your onboarding to start your personalized private chef experience. Tell us about your family and preferences.',
            'keywords' => 'onboarding, setup, private chef services, family preferences, dietary needs',
            'image' => '/images/portadas/portadanosotros.webp',
            'index' => false,
        ],
        
        // About / Nosotros
        'nosotros' => [
            'title' => 'About KChef Services | Chef Adam & Lore',
            'description' => 'Learn about KChef Services, Chef Adam\'s culinary expertise, and Lore\'s commitment to personalized hospitality.',
            'keywords' => 'about KChef Services, Chef Adam, Lore, private chef philosophy, culinary excellence, hospitality',
            'image' => '/images/portadas/portadanosotros.webp',
            'og_type' => 'website',
        ],
        
        // Contact / Consultation
        'contacto' => [
            'title' => 'Contact KChef Services | Book Your Consultation',
            'description' => 'Get in touch with KChef Services to schedule your consultation with Chef Adam and Lore. Let\'s create a service designed around your family.',
            'keywords' => 'contact, consultation, book, private chef, family services',
            'image' => '/images/portadas/portadahome.webp',
        ],
        
        // Complete Registration (dynamic route)
        'completar-registro' => [
            'title' => 'Complete Registration | KChef Services',
            'description' => 'Complete your registration to start your private chef consultation journey.',
            'keywords' => 'registration, complete profile, private chef',
            'image' => '/images/portadas/portadanosotros.webp',
            'index' => false,
        ],
        
        // Default fallback
        'default' => [
            'title' => 'KChef Services | Personalized Private Chef Services',
            'description' => 'Personalized private chef services designed around your family, your lifestyle, and the moments that matter most.',
            'keywords' => 'private chef, personal chef, meal planning, home chef, food services, catering, family meals',
            'image' => '/images/portadas/portadahome.webp',
            'og_type' => 'website',
        ],
    ];

    public static function getMeta()
    {
        $path = trim(request()->getPathInfo(), '/');
        
        // Extract main slug (without query strings)
        $slug = explode('?', $path)[0];
        
        // Try to find exact match
        if (isset(self::$pages[$slug])) {
            $meta = self::$pages[$slug];
        } else {
            // Try to match first part of the path (for dynamic routes)
            $firstPart = explode('/', $slug)[0];
            if (isset(self::$pages[$firstPart])) {
                $meta = self::$pages[$firstPart];
            } else {
                $meta = self::$pages['default'];
            }
        }

        return self::buildMetaTags($meta);
    }

    private static function buildMetaTags(array $meta): array
    {
        $baseUrl = config('app.url');
        $title = $meta['title'] ?? 'KChef Services';
        $description = $meta['description'] ?? 'Personalized private chef services.';
        $keywords = $meta['keywords'] ?? '';
        $image = $meta['image'] ?? '/images/portadas/portadahome.webp';
        $ogType = $meta['og_type'] ?? 'website';
        $noIndex = ($meta['index'] ?? true) === false ? true : false;

        // Ensure image is a full URL
        if (!str_starts_with($image, 'http')) {
            $image = $baseUrl . $image;
        }

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'image' => $image,
            'og_type' => $ogType,
            'noIndex' => $noIndex,
            'url' => request()->url(),
        ];
    }

    /**
     * Generate meta tags HTML
     */
    public static function render(): string
    {
        $meta = self::getMeta();
        
        $html = '';
        
        // Title
        $html .= '<title>' . htmlspecialchars($meta['title']) . '</title>' . "\n";
        
        // Meta Description
        $html .= '<meta name="description" content="' . htmlspecialchars($meta['description']) . '">' . "\n";
        
        // Keywords
        if (!empty($meta['keywords'])) {
            $html .= '<meta name="keywords" content="' . htmlspecialchars($meta['keywords']) . '">' . "\n";
        }
        
        // Robots
        if ($meta['noIndex']) {
            $html .= '<meta name="robots" content="noindex, follow">' . "\n";
        } else {
            $html .= '<meta name="robots" content="index, follow">' . "\n";
        }
        
        // Open Graph
        $html .= '<meta property="og:type" content="' . $meta['og_type'] . '">' . "\n";
        $html .= '<meta property="og:title" content="' . htmlspecialchars($meta['title']) . '">' . "\n";
        $html .= '<meta property="og:description" content="' . htmlspecialchars($meta['description']) . '">' . "\n";
        $html .= '<meta property="og:image" content="' . htmlspecialchars($meta['image']) . '">' . "\n";
        $html .= '<meta property="og:url" content="' . htmlspecialchars($meta['url']) . '">' . "\n";
        $html .= '<meta property="og:site_name" content="KChef Services">' . "\n";
        
        // Twitter Card
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:title" content="' . htmlspecialchars($meta['title']) . '">' . "\n";
        $html .= '<meta name="twitter:description" content="' . htmlspecialchars($meta['description']) . '">' . "\n";
        $html .= '<meta name="twitter:image" content="' . htmlspecialchars($meta['image']) . '">' . "\n";
        $html .= '<meta name="twitter:site" content="@kchefservices">' . "\n";
        
        // Canonical
        $html .= '<link rel="canonical" href="' . htmlspecialchars($meta['url']) . '">' . "\n";
        
        // Author
        $html .= '<meta name="author" content="KChef Services">' . "\n";
        
        // Viewport (responsive)
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        
        // Language
        $html .= '<meta http-equiv="Content-Language" content="en">' . "\n";
        
        return $html;
    }
}
