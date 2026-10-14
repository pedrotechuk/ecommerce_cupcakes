@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-mocha-200 bg-white text-mocha-900 placeholder-mocha-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm transition duration-150 ease-in-out disabled:opacity-60 disabled:cursor-not-allowed']) !!}>
