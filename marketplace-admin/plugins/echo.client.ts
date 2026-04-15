import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

export default defineNuxtPlugin(() => {
  if (import.meta.server) return;

  (window as any).Pusher = Pusher;

  const echo = new Echo({
    broadcaster: 'reverb',
    key: 'e4oparfeokqzrw1dqltk',
    wsHost: 'localhost',
    wsPort: 8080,
    wssPort: 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
    authEndpoint: 'http://localhost:8000/broadcasting/auth',
    auth: {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('admin_token') || ''}`,
        Accept: 'application/json',
      },
    },
  });

  return {
    provide: {
      echo,
    },
  };
});
