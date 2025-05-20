@props([
    'message' => 'Espera un minuto, en un momento podrás seguir con tu fiesta 🤘',
    'secondaryMessage' => 'Estamos tardando más de lo normal, solo será un momento.',
    'delay' => 10,
])

<div class="fixed inset-0 bg-gradient-to-br from-blue-300 via-purple-400 to-purple-500 flex flex-col items-center justify-center z-50"
     x-data="{
         message: @js($message),
         secondaryMessage: @js($secondaryMessage),
         delay: {{ $delay * 1000 }}
     }"
     x-init="setTimeout(() => message = secondaryMessage, delay)">
    
    <div class="relative transform hover:scale-105 transition-all duration-300 svg-floating">
        {!! file_get_contents(public_path('storage/icons/djponte-logo.svg')) !!}
    </div>

    <div class="text-center max-w-md mb-8">
        <p class="text-white text-lg font-light letter-spacing-wide transition-all duration-500 ease-in-out transform"
           x-text="message"
           :key="message">
        </p>
    </div>

    <div class="loading-dots-container">
        <div class="loading-dots">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
            <div class="dot dot-4"></div>
        </div>
    </div>

    <style>
        .svg-floating {
            animation: float 3s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        .letter-spacing-wide {
            letter-spacing: 1px;
        }

        .loading-dots-container {
            position: relative;
            width: 80px;
            height: 80px;
        }

        .loading-dots {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .dot {
            position: absolute;
            top: 33px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.8);
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }

        .dot-1 { left: 8px; animation: dots1 0.6s infinite; }
        .dot-2 { left: 8px; animation: dots2 0.6s infinite; }
        .dot-3 { left: 32px; animation: dots2 0.6s infinite; }
        .dot-4 { left: 56px; animation: dots3 0.6s infinite; }

        @keyframes dots1 {
            0% { transform: scale(0); }
            100% { transform: scale(1); }
        }

        @keyframes dots3 {
            0% { transform: scale(1); }
            100% { transform: scale(0); }
        }

        @keyframes dots2 {
            0% { transform: translate(0, 0); }
            100% { transform: translate(24px, 0); }
        }

        @media (max-width: 768px) {
            .svg-floating svg {
                max-width: 80vw;
                height: auto;
            }
        }
    </style>
</div>
