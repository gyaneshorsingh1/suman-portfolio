const btn = document.getElementById('button');
const form = document.getElementById('form');

// Elements inside .my-3
const my3Section = document.querySelector('.my-3');
const loadingEl = document.querySelector('.loading');
const errorEl = document.querySelector('.error-message');
const sentEl = document.querySelector('.sent-message');

// Hide .my-3 initially
my3Section.style.display = 'none';

form.addEventListener('submit', function(event) {
  event.preventDefault();

  // Show status section & loading
  my3Section.style.display = 'block';
  loadingEl.style.display = 'block';
  errorEl.style.display = 'none';
  sentEl.style.display = 'none';

  // Disable button & change text
  btn.disabled = true;
  btn.innerHTML = 'Sending... <span class="button-arrow">→</span>';

  const serviceID = 'service_4w8apcg';
  const templateID = 'template_e961s7m';

  emailjs.sendForm(serviceID, templateID, this)
    .then(() => {
      loadingEl.style.display = 'none';
      sentEl.style.display = 'block'; // success
      btn.disabled = false;
      btn.innerHTML = 'Send Message <span class="button-arrow">→</span>';
    })
    .catch((err) => {
      loadingEl.style.display = 'none';
      errorEl.innerText = 'Failed to send message. Please try again.';
      errorEl.style.display = 'block';
      btn.disabled = false;
      btn.innerHTML = 'Send Message <span class="button-arrow">→</span>';
      console.error(err);
    });
});
