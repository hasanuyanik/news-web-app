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

export const getUsers = (page = 1, body) => {
    return axios.post(`/api/user/${page}`, body);
}

export const getCategoryFollowers = (page = 1, body) => {
    return axios.post(`/api/category/followers/${page}`, body);
}

export const getRoleUsers = (page = 1, body) => {
    return axios.post(`/api/user/role/${page}`, body);
}

export const getCategories = (page = 1) => {
    return axios.get(`/api/category/${page}`);
}

export const getCategoryUserList = (body, page = 1) => {
    return axios.post(`/api/category/assign/list/${body.categoryUrl}/${page}`, body);
}

export const getUserCategoryList = (body, page = 1) => {
    return axios.post(`/api/user/category/list/${page}`, body);
}

export const getUserNewsList = (body, page = 1) => {
    return axios.post(`/api/user/news/${page}`, body);
}

export const getUserCategoryNewsList = (body, page = 1) => {
    return axios.post(`/api/user/category/news/${page}`, body);
}

export const getRelation = (body) => {
    return axios.post(`/api/category/assign/relation`, body);
}

export const assignCategory = (body) => {
    return axios.post(`/api/category/userassign`, body);
}

export const followCategory = (body) => {
    return axios.post(`/api/category/follow`, body);
}

export const followCategoryControl = (body) => {
    return axios.post(`/api/category/follow/control`, body);
}

export const createCategory = body => {
    return axios.post(`/api/category/add`, body);
}

export const editCategory = body => {
    return axios.post(`/api/category/edit`, body);
}

export const createNews = body => {
    return axios.post(`/api/news/add`, body);
}

export const editNews = body => {
    return axios.post(`/api/news/edit`, body);
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

export const getCategory = categoryName => {
    return axios.get(`/api/category/show/${categoryName}`);
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

export const deleteCategory = (body) => {
    return axios.post(`/api/category/delete`, body);
};

export const deleteNews = (body) => {
    return axios.post(`/api/news/delete`, body);
};

export const changeLanguage = language => {
    axios.defaults.headers['accept-language'] = language;
};
