<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div>
        <x-input-label value="Código" />
        <x-text-input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" required class="block mt-1 w-full uppercase" />
    </div>

    <div>
        <x-input-label value="Tipo" />
        <select name="type" class="w-full mt-1 rounded-lg border-cream-300 shadow-sm focus:border-brand-400 focus:ring-brand-400 text-sm">
            <option value="percentage" @selected(old('type', $coupon->type ?? '') === 'percentage')>Percentual (%)</option>
            <option value="fixed" @selected(old('type', $coupon->type ?? '') === 'fixed')>Valor fixo (R$)</option>
        </select>
    </div>

    <div>
        <x-input-label value="Valor" />
        <x-text-input type="number" step="0.01" min="0.01" name="value" value="{{ old('value', $coupon->value ?? '') }}" required class="block mt-1 w-full" />
    </div>

    <div>
        <x-input-label value="Limite de uso (opcional)" />
        <x-text-input type="number" min="1" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" class="block mt-1 w-full" />
    </div>

    <div>
        <x-input-label value="Expira em (opcional)" />
        <x-text-input type="date" name="expires_at" value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" class="block mt-1 w-full" />
    </div>

    <div class="flex items-end">
        <label class="flex items-center gap-2 text-sm text-mocha-600">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? true)) class="rounded text-brand-600 focus:ring-brand-500">
            Cupom ativo
        </label>
    </div>
</div>
