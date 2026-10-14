<div class="space-y-4">
    <div>
        <x-input-label value="Nome" />
        <x-text-input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="block mt-1 w-full" />
    </div>

    <div>
        <x-input-label value="Slug" />
        <x-text-input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}" required class="block mt-1 w-full" />
    </div>

    <div>
        <label class="flex items-center gap-2 text-sm text-mocha-600">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true)) class="rounded text-brand-600 focus:ring-brand-500">
            Categoria ativa (visível na loja)
        </label>
    </div>
</div>
