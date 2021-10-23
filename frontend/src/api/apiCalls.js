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

export const getDeleteRequests = (page = 0, status=0, creds) => {
    return axios.post(`/api/userwiper/${page}/${status}`, creds);
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

export const getDeleteRequest = (body) => {
    return axios.post(`/api/userwiper/findRequest`, body);
}

export const updateUser = (body) => {
    return axios.post(`/api/user/edit`, body);
};

export const sessionControl = (body) => {
    return axios.post(`/api/sessionControl`, body);
};

export const deleteUserAddRequest = (body) => {
    return axios.post(`/api/userwiper/addRequest`, body);
};

export const cancelDeleteUser = (body) => {
    return axios.post(`/api/userwiper/deleteRequest`, body);
};

export const deleteUser = (body) => {
    return axios.post(`/api/userwiper/userdelete`, body);
};

export const changeLanguage = language => {
    axios.defaults.headers['accept-language'] = language;
};
