const App = {
    /**
     * Handles expired page tracking.
     */
    initEpiredPageTracker() {
        Livewire.hook("request", ({ fail }) => {
            fail(({ status, preventDefault }) => {
                if (status === 419) {
                    preventDefault();
                    location.reload();
                }
            });
        });
    },

    /**
     * Bootstraps all features.
     */
    init() {
        document.addEventListener("DOMContentLoaded", () => {
            this.initEpiredPageTracker();
        });
    },
};

// Start the application
App.init();
