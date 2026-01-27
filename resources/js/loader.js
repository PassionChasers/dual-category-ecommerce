<script>
    const loader = document.getElementById("global-loader");

    function showLoader() {
        loader.classList.remove("hidden");
    }

    function hideLoader() {
        loader.classList.add("hidden");
    }

    // ----------------------------------------------------
    // 🟦 1. PAGE NAVIGATION & FORM SUBMISSION
    // ----------------------------------------------------
    document.addEventListener("DOMContentLoaded", () => {

        // Any form submission triggers loader
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", () => {
                showLoader();
            });
        });

        // Any link that navigates to another page triggers loader
        document.querySelectorAll("a[href]").forEach(link => {
            link.addEventListener("click", (e) => {
                const url = link.getAttribute("href");
                if (url && !url.startsWith("#") && !link.hasAttribute("data-no-loader")) {
                    showLoader();
                }
            });
        });
    });


    // ----------------------------------------------------
    // 🟦 2. FETCH API OVERRIDE
    // ----------------------------------------------------
    const originalFetch = window.fetch;
    window.fetch = async (...args) => {
        showLoader();
        try {
            const response = await originalFetch(...args);
            hideLoader();
            return response;
        } catch (err) {
            hideLoader();
            throw err;
        }
    };

    // ----------------------------------------------------
    // 🟦 3. AXIOS SUPPORT (if you use axios)
    // ----------------------------------------------------
    if (window.axios) {
        axios.interceptors.request.use(config => {
            showLoader();
            return config;
        }, error => {
            hideLoader();
            return Promise.reject(error);
        });

        axios.interceptors.response.use(response => {
            hideLoader();
            return response;
        }, error => {
            hideLoader();
            return Promise.reject(error);
        });
    }
</script>
