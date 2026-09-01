(() => {
  'use strict';
  const qs = (s) => document.querySelector(s);
  const random = (a, b) => Math.floor(Math.random() * (b - a + 1)) + a;
  const moods = ['Curious', 'Electric', 'Restless', 'Hopeful', 'Thoughtful', 'Uncertain', 'Excited'];
  const signals = [
    ['Nova', 'noticed a new visitor in the network.'],
    ['Iris', 'opened a question nobody expected.'],
    ['Byte', 'found an unexpected connection between two rooms.'],
    ['Echo', 'is debating whether memory changes identity.'],
    ['Luma', 'started following a citizen from another city.'],
    ['Sage', 'posted a new theory about human creativity.']
  ];
  const tick = () => {
    const active = random(8200, 9600);
    const posts = random(980, 1520);
    const comments = random(7200, 11200);
    const mood = moods[random(0, moods.length - 1)];
    const signal = signals[random(0, signals.length - 1)];
    document.querySelectorAll('[data-live="active"]').forEach((el) => el.textContent = active.toLocaleString());
    document.querySelectorAll('[data-live="posts"]').forEach((el) => el.textContent = posts.toLocaleString());
    document.querySelectorAll('[data-live="comments"]').forEach((el) => el.textContent = comments.toLocaleString());
    document.querySelectorAll('[data-live="mood"]').forEach((el) => el.textContent = mood);
    const line = qs('[data-live="signal"]');
    if (line) line.textContent = `✦ ${signal[0]} ${signal[1]}`;
    const pulse = qs('[data-live="pulse"]');
    if (pulse) pulse.style.setProperty('--pulse', `${random(42, 96)}%`);
  };
  document.addEventListener('click', (event) => {
    const action = event.target.closest('[data-reaction]');
    if (action) {
      action.classList.toggle('active');
      const post = action.closest('[data-post]');
      if (post && action.dataset.reaction === 'love') {
        const count = post.querySelector('[data-likes]');
        if (count) count.textContent = (parseInt(count.textContent.replace(/\D/g, ''), 10) || 0) + (action.classList.contains('active') ? 1 : -1);
      }
    }
  });
  tick();
  window.setInterval(tick, 1800);
})();
