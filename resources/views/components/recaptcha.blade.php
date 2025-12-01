{{-- Composant reCAPTCHA v3 avec design professionnel --}}
@if(config('services.recaptcha.enabled', true))
  {{-- Script reCAPTCHA --}}
  @push('scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form[data-recaptcha="true"]');

        forms.forEach(form => {
          form.addEventListener('submit', function (e) {
            e.preventDefault();

            grecaptcha.ready(function () {
              grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
                action: 'submit'
              }).then(function (token) {
                // Ajouter le token au formulaire
                let input = form.querySelector('input[name="g-recaptcha-response"]');
                if (!input) {
                  input = document.createElement('input');
                  input.type = 'hidden';
                  input.name = 'g-recaptcha-response';
                  form.appendChild(input);
                }
                input.value = token;

                // Soumettre le formulaire
                form.submit();
              });
            });
          });

          // Marquer le formulaire pour éviter la double soumission
          form.setAttribute('data-recaptcha', 'true');
        });
      });
    </script>
  @endpush

  {{-- Message d'information avec design professionnel --}}
  <div class="recaptcha-notice" style="
      background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
      border: 1px solid #86efac;
      border-radius: 8px;
      padding: 12px 16px;
      margin-top: 16px;
      margin-bottom: 16px;
      display: flex;
      align-items: start;
      gap: 12px;
    ">
    {{-- Icône de bouclier --}}
    <svg style="
        width: 20px;
        height: 20px;
        color: #16a34a;
        flex-shrink: 0;
        margin-top: 2px;
      " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
    </svg>

    {{-- Texte --}}
    <div style="
        font-size: 13px;
        line-height: 1.6;
        color: #166534;
        flex: 1;
      ">
      <div style="font-weight: 600; margin-bottom: 4px;">
        🛡️ Protection anti-spam activée
      </div>
      <div style="font-weight: 400; color: #15803d;">
        Ce site est protégé par reCAPTCHA et les
        <a href="https://policies.google.com/privacy" target="_blank" style="
            color: #16a34a;
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s;
          " onmouseover="this.style.color='#15803d'" onmouseout="this.style.color='#16a34a'">
          Règles de confidentialité
        </a>
        et
        <a href="https://policies.google.com/terms" target="_blank" style="
            color: #16a34a;
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s;
          " onmouseover="this.style.color='#15803d'" onmouseout="this.style.color='#16a34a'">
          Conditions d'utilisation
        </a>
        de Google s'appliquent.
      </div>
    </div>
  </div>
@endif