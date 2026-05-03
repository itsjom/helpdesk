<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary rounded-none']) }}>
    {{ $slot }}
</button>
