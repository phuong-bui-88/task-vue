import axios from "axios";
import Base from "../consts/base.js";

const instance = axios.create({
    baseURL: "/api"
});

instance.interceptors.request.use((config) => {
    const token = localStorage.getItem(Base.TOKEN);

    if (token) {
        config.headers["Authorization"] = `Bearer ${token}`;
    }

    return config;
});

export default instance;
