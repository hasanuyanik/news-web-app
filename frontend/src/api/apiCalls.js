import axios from "axios";

export const signup = body => {
    return axios.post(`/api/user/add`, body);
};
export const login = creds =>{
    return axios.post('/api/auth', creds);
};
export const signupAdmin = (body) => {
    return axios.post('/api/admin', body);
};
export const loginAdmin = creds =>{
    return axios.post('/api/admin/auth', creds);
};
export const logout = creds =>{
    return axios.post('/api/logout', creds);
};

export const getUsers = (page = 0) => {
    return axios.get(`/api/user/${page}`);
}

export const setAuthorizationHeader = ({ isLoggedIn, token }) => {
    if(isLoggedIn){
        const authorizationHeaderValue = `Bearer ${token}`;
        axios.defaults.headers['Authorization'] = authorizationHeaderValue;
    }else{
        delete axios.defaults.headers['Authorization'];
    }
}

export const getUser = username => {
    return axios.get(`/api/user/${username}`);
};
export const updateUser = (username, body) => {
    return axios.put(`/api/user/${username}`, body);
};
export const deleteUser = username => {
    return axios.delete(`/api/user/${username}`);
}

export const changeLanguage = language => {
    axios.defaults.headers['accept-language'] = language;
}
