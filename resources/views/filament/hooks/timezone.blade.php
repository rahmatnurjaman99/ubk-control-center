<script>
    (() => {
        if (typeof Intl === 'undefined' || typeof document === 'undefined') {
            return;
        }

        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        if (!timezone) {
            return;
        }

        const cookieName = 'filament_timezone';
        const cookies = document.cookie.split('; ').filter(Boolean);
        const existingCookie = cookies.find((cookie) => cookie.startsWith(`${cookieName}=`));
        const existingTimezone = existingCookie ? decodeURIComponent(existingCookie.split('=').slice(1).join('=')) : null;

        if (existingTimezone === timezone) {
            return;
        }

        document.cookie = `${cookieName}=${encodeURIComponent(timezone)};path=/;max-age=${60 * 60 * 24 * 365};SameSite=Lax`;
        window.location.reload();
    })();
</script>
