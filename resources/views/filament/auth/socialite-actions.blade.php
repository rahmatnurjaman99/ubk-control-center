<div class="mt-8 flex flex-col items-center gap-4 text-center">
    <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.2em] text-white/70">
        <span class="h-px w-12 bg-white/30"></span>
        <span>{{ __('Continue with') }}</span>
        <span class="h-px w-12 bg-white/30"></span>
    </div>

    <a
        href="{{ route('auth.google.redirect') }}"
        class="inline-flex w-full items-center justify-center gap-3 rounded-full border border-white/30 bg-white/10 px-5 py-3 text-sm font-semibold uppercase tracking-[0.3em] text-white transition hover:bg-white/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/60"
    >
        <svg class="h-5 w-5" viewBox="0 0 48 48" aria-hidden="true" focusable="false">
            <path fill="#EA4335" d="M24 9.5c3.54 0 6 1.54 7.38 2.84l5.4-5.26C33.74 3.54 29.32 1.5 24 1.5 14.91 1.5 7.09 7.22 3.92 15.16l6.92 5.38C12.11 14.5 17.57 9.5 24 9.5z" />
            <path fill="#4285F4" d="M46.5 24.5c0-1.64-.15-3.2-.43-4.71H24v8.92h12.65c-.54 2.78-2.19 5.14-4.66 6.73l7.35 5.7C43.97 36.61 46.5 31 46.5 24.5z" />
            <path fill="#FBBC05" d="M10.84 28.47a14.5 14.5 0 0 1 0-9l-6.92-5.38a23.94 23.94 0 0 0 0 19.76l6.92-5.38z" />
            <path fill="#34A853" d="M24 46.5c6.48 0 11.94-2.13 15.92-5.81l-7.35-5.7C30.6 36.49 27.51 37.5 24 37.5c-6.43 0-11.89-5-13.16-11.03L3.92 32.9C7.09 40.78 14.91 46.5 24 46.5z" />
        </svg>
        <span>Google</span>
    </a>

    <p class="text-xs text-white/60">
        {{ __('Single sign-on is powered by Google Workspace for UBK Control Center.') }}
    </p>
</div>
