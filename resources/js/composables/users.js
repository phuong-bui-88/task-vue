import { ref } from "vue";
import axios from "axios";
import response_status from "../consts/response_status.js";
import api from "./api.js";

export default function useUsers() {
    const userErrors = ref({});
    const signup = async (data) => {
        try {
            let result = await axios.post("/api/users", data);
        } catch (error) {
            if (response_status.ERROR_STATUS.includes(error.response.status)) {
                userErrors.value = error.response.data;
            }
            return false;
        }

        return true;
    };

    const login = async (data) => {
        let result = {};

        try {
            result = await axios.post("/api/user/login", data);
        } catch (error) {
            if (response_status.ERROR_STATUS.includes(error.response.status)) {
                userErrors.value = error.response.data;
            }
            return false;
        }

        return result;
    };

    const logout = async (data) => {
        try {
            let result = await api.get("/user/logout");
        } catch (error) {
            if (response_status.ERROR_STATUS.includes(error.response.status)) {
                userErrors.value = error.response.data;
            }
            return false;
        }
        return true;
    };

    const forgotPassword = async (data) => {
        try {
            let result = await axios.post("/api/user/forgot-password", data);
        } catch (error) {
            if (response_status.ERROR_STATUS.includes(error.response.status)) {
                userErrors.value = error.response.data;
            }
            return false;
        }

        return true;
    };

    const resetPassword = async (data) => {
        try {
            let result = await axios.post("/api/user/reset-password", data);
        } catch (error) {
            if (response_status.ERROR_STATUS.includes(error.response.status)) {
                userErrors.value = error.response.data;
            }
            return false;
        }

        return true;
    };

    const googleAuth = async () => {
        try {
            let result = await axios.get("/auth/google/callback");
        } catch (error) {
            if (response_status.ERROR_STATUS.includes(error.response.status)) {
                userErrors.value = error.response.data;
            }
            return false;
        }

        return result;
    };

    return {
        signup,
        login,
        logout,
        forgotPassword,
        resetPassword,
        googleAuth,
        userErrors
    };
}
