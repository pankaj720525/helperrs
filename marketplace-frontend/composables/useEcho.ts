/**
 * Composable for subscribing to Laravel Echo channels.
 * Auto-cleans up subscriptions on component unmount.
 * Uses `any` cast since laravel-echo types are generic.
 */
export const useEcho = () => {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const $echo = (useNuxtApp() as any).$echo;
  const subscriptions: string[] = [];

  /** Listen on a public channel */
  const listenOn = (channel: string, event: string, callback: (data: any) => void) => {
    if (!$echo) return;
    $echo.channel(channel).listen(`.${event}`, callback);
    subscriptions.push(channel);
  };

  /** Listen on a private channel (requires auth) */
  const listenPrivate = (channel: string, event: string, callback: (data: any) => void) => {
    if (!$echo) return;
    $echo.private(channel).listen(`.${event}`, callback);
    subscriptions.push(`private-${channel}`);
  };

  /** Stop listening to a specific channel */
  const leave = (channel: string) => {
    if (!$echo) return;
    $echo.leaveChannel(channel);
  };

  // Auto-cleanup on unmount
  onUnmounted(() => {
    subscriptions.forEach(ch => {
      try { $echo?.leaveChannel(ch); } catch {}
    });
  });

  return { listenOn, listenPrivate, leave, echo: $echo };
};
