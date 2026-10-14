@props(['name' => 'rating', 'value' => 5])

<div x-data="{ rating: {{ (int) $value }} }" class="inline-flex items-center gap-1" role="radiogroup" aria-label="Sua nota">
    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
        <button type="button"
                @click="rating = star"
                role="radio"
                :aria-checked="rating === star"
                class="text-2xl leading-none focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 rounded transition-colors"
                :class="star <= rating ? 'text-amber-500' : 'text-mocha-200 hover:text-amber-300'"
        >★</button>
    </template>
    <input type="hidden" name="{{ $name }}" :value="rating">
</div>
