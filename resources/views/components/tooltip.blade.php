@props([
    'message' => '',
    'position' => 'top', // top, bottom, left, right
    'trigger' => 'hover', // hover, click
])

<div x-data="{ 
    tooltipVisible: false,
    message: '{{ $message }}',
    position: '{{ $position }}',
    getPositionStyles() {
        const styles = {
            'top': 'left: 50%; bottom: 100%; transform: translateX(-50%) translateY(-10px);',
            'bottom': 'left: 50%; top: 100%; transform: translateX(-50%) translateY(10px);',
            'left': 'right: 100%; top: 50%; transform: translateY(-50%) translateX(-10px);',
            'right': 'left: 100%; top: 50%; transform: translateY(-50%) translateX(10px);'
        };
        return styles[this.position] || styles['top'];
    },
    getArrowStyles() {
        const styles = {
            'top': 'left: 50%; top: 100%; margin-left: -4px; margin-top: -4px;',
            'bottom': 'left: 50%; bottom: 100%; margin-left: -4px; margin-top: -4px;',
            'left': 'right: 0; top: 50%; margin-top: -4px; margin-right: -4px;',
            'right': 'left: 0; top: 50%; margin-top: -4px; margin-left: -4px;'
        };
        return styles[this.position] || styles['top'];
    }
}"
    {{ $attributes->merge(['class' => 'relative inline-block']) }}
    @if($trigger === 'hover')
        @mouseenter="tooltipVisible = true"
        @mouseleave="tooltipVisible = false"
    @elseif($trigger === 'click')
        @click.stop="tooltipVisible = !tooltipVisible"
        @click.outside="tooltipVisible = false"
    @endif
>
    {{ $slot }}
    
    <template x-teleport="body">
        <div 
            x-show="tooltipVisible" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute bg-gray-800 text-white text-xs rounded p-2 z-50"
            :style="getPositionStyles()"
            role="tooltip"
        >
            <div x-html="$slots.tooltipContent ? $slots.tooltipContent() : message"></div>
            <div 
                class="arrow absolute w-2 h-2 bg-gray-800 transform rotate-45" 
                :style="getArrowStyles()"
            ></div>
        </div>
    </template>
</div>