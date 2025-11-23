import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

axios.interceptors.request.use(
    (config) => {
        config.headers["x-api-key"] = "12cdf608-c2e9-43bf-a045-00911067f9f6";
        return config;
    },
    (error) => Promise.reject(error)
);
