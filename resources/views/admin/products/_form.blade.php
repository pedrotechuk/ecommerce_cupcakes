<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div>
        <x-input-label value="Nome" />
        <x-text-input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="block mt-1 w-full" />
    </div>

    <div>
        <x-input-label value="Slug" />
        <x-text-input type="text" name="slug" value="{{ old('slug', $product->slug ?? '') }}" required class="block mt-1 w-full" />
    </div>

    <div>
        <x-input-label value="Categoria" />
        <select name="category_id" required class="w-full mt-1 rounded-lg border-cream-300 shadow-sm focus:border-brand-400 focus:ring-brand-400 text-sm">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? null) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label value="Preço (R$)" />
        <x-text-input type="number" step="0.01" min="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required class="block mt-1 w-full" />
    </div>

    <div>
        <x-input-label value="Estoque" />
        <x-text-input type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required class="block mt-1 w-full" />
    </div>

    <div x-data="{ preview: null }">
        <x-input-label value="Imagem" />
        <input type="file" name="image" accept="image/*"
               x-on:change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = e => preview = e.target.result; reader.readAsDataURL(file); }"
               class="w-full mt-1 text-sm text-mocha-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:font-medium">

        <template x-if="preview">
            <img :src="preview" class="mt-2 h-16 rounded-lg object-cover">
        </template>

        @isset($product)
            @if ($product->image)
                <img x-show="!preview" src="{{ $product->image_url }}" class="mt-2 h-16 rounded-lg object-cover">
            @endif
        @endisset
    </div>

    <div class="sm:col-span-2">
        <x-input-label value="Descrição" />
        <textarea name="description" rows="3"
                  class="w-full mt-1 rounded-lg border-cream-300 shadow-sm focus:border-brand-400 focus:ring-brand-400 text-sm">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div class="sm:col-span-2">
        <label class="flex items-center gap-2 text-sm text-mocha-600">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true)) class="rounded text-brand-600 focus:ring-brand-500">
            Produto ativo (visível na loja)
        </label>
    </div>
</div>
