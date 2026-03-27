(function() {
  "use strict";

  let deferredPrompt = null;

  function notify(ready) {
    window.dispatchEvent(new CustomEvent("formaibly:pwa-install-availability", {
      detail: { canInstall: ready }
    }));
  }

  window.addEventListener("beforeinstallprompt", function(event) {
    event.preventDefault();
    deferredPrompt = event;
    notify(true);
  });

  window.addEventListener("appinstalled", function() {
    deferredPrompt = null;
    notify(false);
  });

  window.FormaiblyPWAInstall = {
    isReady: function() {
      return Boolean(deferredPrompt);
    },
    prompt: async function() {
      if (!deferredPrompt) {
        return { outcome: "unavailable" };
      }

      deferredPrompt.prompt();
      const choice = await deferredPrompt.userChoice;
      if (choice && choice.outcome === "accepted") {
        deferredPrompt = null;
        notify(false);
      }
      return choice || { outcome: "unknown" };
    }
  };
})();
