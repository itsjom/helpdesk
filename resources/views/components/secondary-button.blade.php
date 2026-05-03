<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-secondary rounded-none']) }}>
    {{ $slot }}
</button>
