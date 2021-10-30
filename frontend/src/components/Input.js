import React, {Component} from 'react';

const Input = (props) => {
    const {label, error, name, onChange, type, defaultValue, className} = props
    let inputClassName = (className) ? className : 'form-control';
    if(type == 'file'){
        inputClassName += '-file';
    } 
    if(error != undefined){
        inputClassName += ' is-invalid';
    }
    
    return(
        <div className="form-group m-2">
            <label>{label}</label>
            <input className={inputClassName} name={name} onChange={onChange} type={type} defaultValue={defaultValue}/>
            <div className="invalid-feedback">{error}</div>
        </div>
    );
}
export default Input;