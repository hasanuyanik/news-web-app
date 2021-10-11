import { createStore, applyMiddleware, compose } from 'redux';
import thunk from 'redux-thunk';
import authReducer from './authReducer';
import SecureLS from 'secure-ls';
import { setAuthorizationHeader } from '../api/apiCalls';

const secureLs = new SecureLS();

const getStateFromStorage = () => {
    const hAuth = secureLs.get('h-auth');
    let stateInLocalStorage = {
        isLoggedIn: false,
        username: undefined,
        displayName: undefined,
        image: undefined,
        password: undefined
    };

    if(hAuth){
       return hAuth;
    }
    return stateInLocalStorage;
}

const updateStateInStorage = newState => {
    secureLs.set('h-auth', newState);
}

const configureStore = () => {
    const initialState = getStateFromStorage();
    setAuthorizationHeader(initialState);
    const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;
    // window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__()
    const store = createStore(authReducer, initialState, composeEnhancers(applyMiddleware(thunk)));
    store.subscribe(() => {
        updateStateInStorage(store.getState());
        setAuthorizationHeader(store.getState());
    });
    return store;
}

export default configureStore;