(this.webpackJsonpfrontend=this.webpackJsonpfrontend||[]).push([[0],{52:function(e,t,n){},87:function(e,t,n){"use strict";n.r(t);var a=n(1),r=n(23),c=n.n(r),s=(n(52),function(e){e&&e instanceof Function&&n.e(3).then(n.bind(null,89)).then((function(t){var n=t.getCLS,a=t.getFID,r=t.getFCP,c=t.getLCP,s=t.getTTFB;n(e),a(e),r(e),c(e),s(e)}))}),i=n(3),o=n(9),l=n.n(o),u=function(e,t){var n=Object(a.useState)(!1),r=Object(i.a)(n,2),c=r[0],s=r[1];return Object(a.useEffect)((function(){var n,a,r=function(n,a,r){a.startsWith(t)&&n==e&&s(r)};return n=l.a.interceptors.request.use((function(e){var t=e.url,n=e.method;return r(n,t,!0),e})),a=l.a.interceptors.response.use((function(e){var t=e.config,n=t.url,a=t.method;return r(a,n,!1),e}),(function(e){var t=e.config,n=t.url,a=t.method;throw r(a,n,!1),e})),function(){l.a.interceptors.request.eject(n),l.a.interceptors.response.eject(a)}}),[t,e]),c},d=n(2),j=n.n(d),b=n(4),p=n(17),m=n(6),O=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0;return l.a.get("/api/user/".concat(e))},f=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0;return l.a.get("/api/userwiper/".concat(e,"/").concat(t))},h=function(e){var t=e.isLoggedIn,n=e.token;if(t){var a="Bearer ".concat(n);l.a.defaults.headers.Authorization=a}else delete l.a.defaults.headers.Authorization},x=function(e){return l.a.get("/api/user/".concat(e))},v=function(e){return l.a.post("/api/userwiper/findRequest",e)},g=function(e){return l.a.post("/api/user/edit",e)},N=function(e){return l.a.post("/api/userwiper/addRequest",e)},w=function(e){return l.a.post("/api/userwiper/deleteRequest",e)},k=function(e){return l.a.post("/api/userwiper/userdelete",e)},y=n(0),C=function(e){var t=e.label,n=e.error,a=e.name,r=e.onChange,c=e.type,s=e.defaultValue,i="form-control";return"file"==c&&(i+="-file"),void 0!=n&&(i+=" is-invalid"),Object(y.jsxs)("div",{className:"form-group m-2",children:[Object(y.jsx)("label",{children:t}),Object(y.jsx)("input",{className:i,name:a,onChange:r,type:c,defaultValue:s}),Object(y.jsx)("div",{className:"invalid-feedback",children:n})]})},L=n(88),S=function(e){var t=e.onClick,n=e.pendingApiCall,a=e.disabled,r=e.text,c=e.className;return Object(y.jsxs)("button",{className:c||"btn btn-primary",onClick:t,disabled:a,children:[n&&Object(y.jsx)("span",{className:"spinner-border spinner-border-sm"}),r]})},E=n(7),P="logout-success",A="login-success",U="update-success",D=function(e){return{type:A,payload:e}},I=function(e){var t=e.fullname,n=e.email,a=e.phone;return{type:U,payload:{fullname:t,email:n,phone:a}}},R=function(e){return function(){var t=Object(b.a)(j.a.mark((function t(n){var a;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,r=e,l.a.post("/api/logout",r);case 2:return a=t.sent,n({type:P}),t.abrupt("return",a);case 5:case"end":return t.stop()}var r}),t)})));return function(e){return t.apply(this,arguments)}}()},F=function(e){return function(){var t=Object(b.a)(j.a.mark((function t(n){var a,r;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,c=e,l.a.post("/api/auth",c);case 2:return a=t.sent,r=Object(m.a)(Object(m.a)({},a.data[0]),{},{role:"user"}),n(D(r)),t.abrupt("return",a);case 6:case"end":return t.stop()}var c}),t)})));return function(e){return t.apply(this,arguments)}}()},M=function(e){return function(){var t=Object(b.a)(j.a.mark((function t(n){var a;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,n=e,l.a.post("/api/user/add",n);case 2:return a=t.sent,t.abrupt("return",a);case 4:case"end":return t.stop()}var n}),t)})));return function(e){return t.apply(this,arguments)}}()},q=function(e){return function(){var t=Object(b.a)(j.a.mark((function t(n){var a,r;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,c=e,l.a.post("/api/admin/auth",c);case 2:return a=t.sent,r=Object(m.a)(Object(m.a)({},a.data.admin),{},{role:"admin",password:e.password,token:a.data.token}),n(D(r)),t.abrupt("return",a);case 6:case"end":return t.stop()}var c}),t)})));return function(e){return t.apply(this,arguments)}}()},_=function(e){return function(){var t=Object(b.a)(j.a.mark((function t(n){var a;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,r=e,l.a.post("/api/admin",r);case 2:return a=t.sent,n(q(e)),t.abrupt("return",a);case 5:case"end":return t.stop()}var r}),t)})));return function(e){return t.apply(this,arguments)}}()},z=function(e){var t,n=Object(a.useState)({username:null,fullname:null,email:null,phone:null,password:null,passwordRepeat:null}),r=Object(i.a)(n,2),c=r[0],s=r[1],o=Object(a.useState)({}),l=Object(i.a)(o,2),d=l[0],O=l[1],f=Object(E.b)(),h=function(e){var t=e.target,n=t.name,a=t.value;O((function(e){return Object(m.a)(Object(m.a)({},e),{},Object(p.a)({},n,void 0))})),s((function(e){return Object(m.a)(Object(m.a)({},e),{},Object(p.a)({},n,a))}))},x=function(){var t=Object(b.a)(j.a.mark((function t(n){var a,r,s,i,o,l,u,d;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n.preventDefault(),a=e.history,r=a.push,s=c.username,i=c.fullname,o=c.email,l=c.phone,u=c.password,d={username:s,fullname:i,email:o,phone:l,password:u},console.log(d),t.prev=6,t.next=9,f(M(d));case 9:r("/"),t.next=15;break;case 12:t.prev=12,t.t0=t.catch(6),t.t0.response.data.validationErrors&&O(t.t0.response.data.validationErrors);case 15:case"end":return t.stop()}}),t,null,[[6,12]])})));return function(e){return t.apply(this,arguments)}}(),v=Object(L.a)().t,g=d.username,N=d.fullname,w=d.email,k=d.phone,P=d.password,A=u("post","/api/user/add"),U=u("post","/api/auth"),D=A||U;return c.password!=c.passwordRepeat&&(t=v("Password mismatch")),Object(y.jsx)("div",{className:"container",children:Object(y.jsxs)("form",{children:[Object(y.jsx)("h1",{className:"text-center",children:v("Sign Up")}),Object(y.jsx)(C,{name:"username",label:v("Username"),error:g,onChange:h}),Object(y.jsx)(C,{name:"fullname",label:v("Fullname"),error:N,onChange:h}),Object(y.jsx)(C,{name:"email",label:v("Email"),error:w,onChange:h}),Object(y.jsx)(C,{name:"phone",label:v("Phone"),error:k,onChange:h}),Object(y.jsx)(C,{name:"password",label:v("Password"),error:P,onChange:h,type:"password"}),Object(y.jsx)(C,{name:"passwordRepeat",label:v("Password Repeat"),error:t,onChange:h,type:"password"}),Object(y.jsx)("div",{className:"form-group text-center",children:Object(y.jsx)(S,{onClick:x,disabled:D||void 0!=t,pendingApiCall:D,text:v("Sign Up")})})]})})},V=function(e){var t=Object(a.useState)(),n=Object(i.a)(t,2),r=n[0],c=n[1],s=Object(a.useState)(),o=Object(i.a)(s,2),l=o[0],d=o[1],p=Object(a.useState)(),m=Object(i.a)(p,2),O=m[0],f=m[1],h=Object(E.b)();Object(a.useEffect)((function(){f(void 0)}),[r,l]);var x=function(){var t=Object(b.a)(j.a.mark((function t(n){var a,c,s;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n.preventDefault(),a={username:r,password:l},c=e.history,s=c.push,f(void 0),t.prev=5,t.next=8,h(F(a));case 8:s("/"),t.next=14;break;case 11:t.prev=11,t.t0=t.catch(5),f(t.t0.response.data.message);case 14:case"end":return t.stop()}}),t,null,[[5,11]])})));return function(e){return t.apply(this,arguments)}}(),v=Object(L.a)().t,g=u("post","/api/1.0/auth"),N=r&&l;return Object(y.jsx)("div",{className:"container",children:Object(y.jsxs)("form",{children:[Object(y.jsx)("h1",{className:"text-center",children:v("Login")}),Object(y.jsx)(C,{label:v("Username"),onChange:function(e){return c(e.target.value)}}),Object(y.jsx)(C,{label:v("Password"),onChange:function(e){return d(e.target.value)},type:"password"}),O&&Object(y.jsx)("div",{className:"alert alert-danger",children:O}),Object(y.jsx)("div",{className:"form-group text-center",children:Object(y.jsx)(S,{onClick:x,disabled:!N||g,pendingApiCall:g,text:v("Login")})})]})})},B=function(e){var t,n=Object(a.useState)({username:null,password:null,passwordRepeat:null}),r=Object(i.a)(n,2),c=r[0],s=r[1],o=Object(a.useState)({}),l=Object(i.a)(o,2),d=l[0],O=l[1],f=Object(E.b)(),h=function(e){var t=e.target,n=t.name,a=t.value;O((function(e){return Object(m.a)(Object(m.a)({},e),{},Object(p.a)({},n,void 0))})),s((function(e){return Object(m.a)(Object(m.a)({},e),{},Object(p.a)({},n,a))}))},x=function(){var t=Object(b.a)(j.a.mark((function t(n){var a,r,s,i,o;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n.preventDefault(),a=e.history,r=a.push,s=c.username,i=c.password,o={username:s,password:i},t.prev=5,t.next=8,f(_(o));case 8:r("/"),t.next=14;break;case 11:t.prev=11,t.t0=t.catch(5),t.t0.response.data.validationErrors&&O(t.t0.response.data.validationErrors);case 14:case"end":return t.stop()}}),t,null,[[5,11]])})));return function(e){return t.apply(this,arguments)}}(),v=Object(L.a)().t,g=d.username,N=d.password,w=u("post","/api/1.0/admin"),k=u("post","/api/1.0/auth"),P=w||k;return c.password!=c.passwordRepeat&&(t=v("Password mismatch")),Object(y.jsx)("div",{className:"container",children:Object(y.jsxs)("form",{children:[Object(y.jsx)("h1",{className:"text-center",children:v("Sign Up")}),Object(y.jsx)(C,{name:"username",label:v("Username"),error:g,onChange:h}),Object(y.jsx)(C,{name:"password",label:v("Password"),error:N,onChange:h,type:"password"}),Object(y.jsx)(C,{name:"passwordRepeat",label:v("Password Repeat"),error:t,onChange:h,type:"password"}),Object(y.jsx)("div",{className:"form-group text-center",children:Object(y.jsx)(S,{onClick:x,disabled:P||void 0!=t,pendingApiCall:P,text:v("Sign Up")})})]})})},T=function(e){var t=Object(a.useState)(),n=Object(i.a)(t,2),r=n[0],c=n[1],s=Object(a.useState)(),o=Object(i.a)(s,2),l=o[0],d=o[1],p=Object(a.useState)(),m=Object(i.a)(p,2),O=m[0],f=m[1],h=Object(E.b)();Object(a.useEffect)((function(){f(void 0)}),[r,l]);var x=function(){var t=Object(b.a)(j.a.mark((function t(n){var a,c,s;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n.preventDefault(),a={username:r,password:l},c=e.history,s=c.push,f(void 0),t.prev=5,t.next=8,h(q(a));case 8:s("/"),t.next=14;break;case 11:t.prev=11,t.t0=t.catch(5),f(t.t0.response.data.message);case 14:case"end":return t.stop()}}),t,null,[[5,11]])})));return function(e){return t.apply(this,arguments)}}(),v=Object(L.a)().t,g=u("post","/api/1.0/admin/auth"),N=r&&l;return Object(y.jsx)("div",{className:"container",children:Object(y.jsxs)("form",{children:[Object(y.jsx)("h1",{className:"text-center",children:v("Login")}),Object(y.jsx)(C,{label:v("Username"),onChange:function(e){return c(e.target.value)}}),Object(y.jsx)(C,{label:v("Password"),onChange:function(e){return d(e.target.value)},type:"password"}),O&&Object(y.jsx)("div",{className:"alert alert-danger",children:O}),Object(y.jsx)("div",{className:"form-group text-center",children:Object(y.jsx)(S,{onClick:x,disabled:!N||g,pendingApiCall:g,text:v("Login")})})]})})},K=n(13),G=n.p+"static/media/profile.06c30927.png",H=function(e){var t=e.image,n=e.tempimage,a=G;return t&&(a="images/"+t),Object(y.jsx)("img",Object(m.a)(Object(m.a)({alt:"Profile",src:n||a},e),{},{onError:function(e){e.target.src=G}}))},J=function(e){var t=e.user,n=t.username,a=t.fullname;return Object(y.jsxs)(K.b,{to:"/user/".concat(n),className:"list-group-item list-group-item-action",children:[Object(y.jsx)(H,{className:"img-circle rounded-circle",width:"30",height:"30",alt:"".concat(n," profile")}),Object(y.jsxs)("span",{className:"pl-2",children:[a,"@",n]})]})},X=function(){return Object(y.jsx)("div",{className:"d-flex justify-content-center",children:Object(y.jsx)("div",{className:"spinner-border text-black-50",children:Object(y.jsx)("span",{className:"sr-only",children:"Loading..."})})})},Y=function(e){var t=Object(a.useState)({content:[],size:3,number:0}),n=Object(i.a)(t,2),r=n[0],c=n[1],s=Object(a.useState)(!1),o=Object(i.a)(s,2),l=o[0],d=o[1],p=u("get","/api/user/1");Object(a.useEffect)((function(){f()}),[]);var m=Object(E.c)((function(e){return{isLoggedIn:e.isLoggedIn}})),f=(m.isLoggedIn,m.admin,function(){var e=Object(b.a)(j.a.mark((function e(t){var n;return j.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return d(!1),e.prev=1,e.next=4,O(t);case 4:n=e.sent,console.log(n),c(n.data),e.next=12;break;case 9:e.prev=9,e.t0=e.catch(1),d(!0);case 12:case 13:case"end":return e.stop()}}),e,null,[[1,9]])})));return function(t){return e.apply(this,arguments)}}()),h=Object(L.a)().t,x=r.content,v=r.last,g=r.first,N=Object(y.jsxs)("div",{children:[0==g&&Object(y.jsx)("button",{className:"btn btn-sm btn-light",onClick:function(){var e=r.number-1;f(e)},children:h("Previous")}),0==v&&Object(y.jsx)("button",{className:"btn btn-sm btn-light float-right",onClick:function(){var e=r.number+1;f(e)},children:h("Next")})]});return p&&(N=Object(y.jsx)(X,{})),Object(y.jsx)("div",{className:"container",children:Object(y.jsxs)("div",{className:"card",children:[Object(y.jsx)("h3",{className:"card-header text-center",children:h("Users")}),Object(y.jsx)("div",{className:"list-group-flush",children:x.map((function(e){return Object(y.jsx)(J,{user:e},e.username)}))}),N,l&&Object(y.jsx)("div",{className:"text-center text-danger",children:h("Load Failure")})]})})},W=n(8),Q=function(e){var t=e.visible,n=e.onClickCancel,a=e.message,r=e.onClickOk,c=e.pendingApiCall,s=e.title,i=e.okButton,o=Object(L.a)().t,l="modal";return l+=t?" show d-block":" fade",Object(y.jsx)("div",{className:l,style:{backgroundColor:"#000000b0"},children:Object(y.jsx)("div",{className:"modal-dialog",children:Object(y.jsxs)("div",{className:"modal-content",children:[Object(y.jsx)("div",{className:"modal-header",children:Object(y.jsx)("h5",{className:"modal-title",children:s})}),Object(y.jsx)("div",{className:"modal-body",children:a}),Object(y.jsxs)("div",{className:"modal-footer",children:[Object(y.jsx)("button",{className:"btn btn-secondary",disabled:c,onClick:n,children:o("Cancel")}),Object(y.jsx)(S,{className:"btn btn-danger",onClick:r,pendingApiCall:c,disabled:c,text:i})]})]})})})},Z=function(e){var t=Object(a.useState)(!1),n=Object(i.a)(t,2),r=n[0],c=n[1],s=Object(a.useState)(),o=Object(i.a)(s,2),l=o[0],d=o[1],p=Object(a.useState)(),O=Object(i.a)(p,2),f=O[0],h=O[1],x=Object(a.useState)(),v=Object(i.a)(x,2),k=v[0],P=v[1],A=Object(E.c)((function(e){return{username:e.username}})),U=A.username,D=(A.role,Object(W.h)().username),R=Object(a.useState)({}),F=Object(i.a)(R,2),M=F[0],q=F[1],_=Object(a.useState)({}),z=Object(i.a)(_,2),V=(z[0],z[1]),B=Object(a.useState)(!1),T=Object(i.a)(B,2),K=T[0],G=T[1],H=Object(a.useState)({}),J=Object(i.a)(H,2),X=J[0],Y=J[1],Z=Object(a.useState)(!1),$=Object(i.a)(Z,2),ee=$[0],te=$[1],ne=Object(E.b)(),ae=Object(W.g)();Object(a.useEffect)((function(){q(e.user)}),[e.user]),Object(a.useEffect)((function(){V(e.deleteRequest)}),[e.deleteRequest]),Object(a.useEffect)((function(){G(D===U)}),[D,U]),Object(a.useEffect)((function(){Y((function(e){return Object(m.a)(Object(m.a)({},e),{},{fullname:null,email:null,phone:null})}))}),[l,f,k]);var re=M.username,ce=M.fullname,se=M.email,ie=M.phone,oe=(M.created_at,M.updated_at),le=u("delete","/api/user/".concat(re)),ue=Object(L.a)().t;Object(a.useEffect)((function(){d(r?ce:null)}),[r,ce]),Object(a.useEffect)((function(){h(r?se:null)}),[r,se]),Object(a.useEffect)((function(){P(r?ie:null)}),[r,ie]);var de=function(){var t=Object(b.a)(j.a.mark((function t(){var n,a;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n={username:re,fullname:l,email:f,phone:k,token:e.token},t.prev=1,t.next=4,g(n);case 4:a=t.sent,c(!1),q(a.data[0]),ne(I(a.data)),t.next=13;break;case 10:t.prev=10,t.t0=t.catch(1),Y(t.t0.response.data.validationErrors);case 13:case"end":return t.stop()}}),t,null,[[1,10]])})));return function(){return t.apply(this,arguments)}}(),je=function(){var t=Object(b.a)(j.a.mark((function t(){var n;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n={username:re,token:e.token},t.next=3,N(n);case 3:te(!1),ae.push("/");case 5:case"end":return t.stop()}}),t)})));return function(){return t.apply(this,arguments)}}(),be=function(){var t=Object(b.a)(j.a.mark((function t(){var n;return j.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n={username:re,token:e.token},t.next=3,w(n);case 3:ae.push("/");case 4:case"end":return t.stop()}}),t)})));return function(){return t.apply(this,arguments)}}(),pe=u("put","/api/user/"+re),me=X.fullname,Oe=X.email,fe=X.phone;return Object(y.jsxs)("div",{className:"card text-center",children:[Object(y.jsxs)("div",{className:"card text-center",children:[Object(y.jsx)("div",{className:"card-header",children:"Profile"}),Object(y.jsxs)("div",{className:"card-body",children:[!r&&Object(y.jsxs)(y.Fragment,{children:[Object(y.jsx)("h5",{className:"card-title",children:"UserName:"}),Object(y.jsx)("p",{className:"card-text",children:re}),Object(y.jsx)("h5",{className:"card-title",children:"FullName:"}),Object(y.jsx)("p",{className:"card-text",children:ce}),Object(y.jsx)("h5",{className:"card-title",children:"E-Mail:"}),Object(y.jsx)("p",{className:"card-text",children:se}),Object(y.jsx)("h5",{className:"card-title",children:"Phone:"}),Object(y.jsx)("p",{className:"card-text",children:ie})]}),r&&Object(y.jsxs)("div",{children:[Object(y.jsx)(C,{label:ue("Change Full Name"),defaultValue:ce,onChange:function(e){d(e.target.value)},error:me}),Object(y.jsx)(C,{label:ue("Change E-Mail"),defaultValue:se,onChange:function(e){h(e.target.value)},error:Oe}),Object(y.jsx)(C,{label:ue("Change Phone"),defaultValue:ie,onChange:function(e){P(e.target.value)},error:fe}),Object(y.jsxs)("div",{className:"m-2",children:[Object(y.jsx)(S,{className:"btn btn-primary d-inline-flex",onClick:de,disabled:pe,pendingApiCall:pe,text:Object(y.jsxs)(y.Fragment,{children:[Object(y.jsx)("i",{className:"material-icons",children:"save"}),ue("Save")]})}),Object(y.jsxs)("button",{className:"btn btn-light d-inline-flex ml-1",onClick:function(){return c(!1)},disabled:pe,children:[Object(y.jsx)("i",{className:"material-icons",children:"close"}),ue("Cancel")]})]})]}),K&&Object(y.jsxs)(y.Fragment,{children:[Object(y.jsx)("hr",{}),Object(y.jsxs)("button",{className:"btn btn-success d-inline-flex",onClick:function(){return c(!0)},children:[Object(y.jsx)("i",{className:"material-icons",children:"edit"}),ue("Edit")]}),Object(y.jsxs)("div",{className:"pt-2",children:[!e.deleteRequest&&Object(y.jsxs)("button",{className:"btn btn-danger d-inline-flex",onClick:function(){return te(!0)},children:[Object(y.jsx)("i",{className:"material-icons",children:"directions_run"}),ue("Delete My Account")]}),e.deleteRequest&&Object(y.jsxs)("button",{className:"btn btn-primary d-inline-flex",onClick:be,children:[Object(y.jsx)("i",{className:"material-icons",children:"directions_run"}),ue("Cancel Account Deletion")]})]})]})]}),Object(y.jsx)("div",{className:"card-footer text-muted",children:Object(y.jsxs)("h6",{title:"Last Update",children:[Object(y.jsx)("i",{className:"material-icons",children:"update"}),oe]})})]}),!e.deleteRequest&&Object(y.jsx)(Q,{visible:ee,title:ue("Delete My Account"),okButton:ue("Delete My Account"),onClickCancel:function(){te(!1)},onClickOk:je,message:ue("Are you sure to delete your account?"),pendingApiCall:le})]})},$=function(){var e=Object(a.useState)({}),t=Object(i.a)(e,2),n=t[0],r=t[1],c=Object(a.useState)(),s=Object(i.a)(c,2),o=s[0],l=s[1],d=Object(a.useState)(!1),p=Object(i.a)(d,2),m=p[0],O=p[1],f=Object(W.h)().username,h=Object(L.a)().t,g=u("get","/api/user/"+f),N=Object(E.c)((function(e){return{isLoggedIn:e.isLoggedIn,role:e.role,token:e.token}})),w=(N.isLoggedIn,N.role,N.token);return Object(a.useEffect)((function(){O(!1)}),[n]),Object(a.useEffect)((function(){(function(){var e=Object(b.a)(j.a.mark((function e(){var t,n,a;return j.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t={username:f,token:w},e.prev=1,e.next=4,x(f);case 4:return n=e.sent,r(n.data[0]),e.next=8,v(t);case 8:a=e.sent,console.log(a),l(a.data.DeleteRequest),e.next=16;break;case 13:e.prev=13,e.t0=e.catch(1),O(!0);case 16:case"end":return e.stop()}}),e,null,[[1,13]])})));return function(){return e.apply(this,arguments)}})()()}),[f]),g?Object(y.jsx)(X,{}):m?Object(y.jsx)("div",{className:"container",children:Object(y.jsxs)("div",{className:"alert alert-danger text-center",children:[Object(y.jsx)("div",{children:Object(y.jsx)("i",{className:"material-icons",style:{fontSize:"48px"},children:"error"})}),h("User not found")]})}):Object(y.jsx)("div",{className:"container",children:Object(y.jsx)(Z,{user:n,token:w,deleteRequest:o})})},ee=(n.p,function(e){var t=Object(L.a)().t,n=Object(E.c)((function(e){return{isLoggedIn:e.isLoggedIn,username:e.username,fullname:e.fullname,image:e.image,role:e.role,token:e.token}})),r=n.username,c=n.isLoggedIn,s=n.fullname,o=n.image,l=n.role,u=n.token,d=Object(a.useRef)(null),p=Object(a.useState)(!1),m=Object(i.a)(p,2),O=m[0],f=m[1];Object(a.useEffect)((function(){return document.addEventListener("click",h),function(){document.removeEventListener("click",h)}}),[c]);var h=function(e){null!=d.current&&d.current.contains(e.target)||f(!1)},x=Object(E.b)(),v=function(){var e=Object(b.a)(j.a.mark((function e(t){return j.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t.preventDefault(),x(R({token:u}));case 3:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}(),g=Object(y.jsxs)("ul",{className:"navbar-nav ms-auto",children:[Object(y.jsx)("li",{children:Object(y.jsx)(K.b,{className:"nav-link",to:"/login",children:t("Login")})}),Object(y.jsx)("li",{children:Object(y.jsx)(K.b,{className:"nav-link",to:"/signup",children:t("Sign Up")})}),Object(y.jsx)("li",{children:Object(y.jsx)(K.b,{className:"nav-link",to:"/admin/login",children:t("Admin Login")})})]});if(c){var N="dropdown-menu p-0 shadow";O&&(N+=" show"),g=Object(y.jsx)("ul",{className:"navbar-nav ms-auto",ref:d,children:Object(y.jsxs)("li",{className:"nav-item dropdown",children:[Object(y.jsxs)("div",{className:"nav-link d-flex",style:{cursor:"pointer"},onClick:function(){return f(!0)},children:[Object(y.jsx)(H,{image:o,width:"32",height:"32",className:"rounded-circle img-circle m-auto"}),Object(y.jsxs)("span",{className:"dropdown-toggle p-2",children:[s,!s&&"".concat(r,"-").concat(l)]})]}),Object(y.jsxs)("div",{className:N,children:["user"==l&&Object(y.jsxs)(K.b,{className:"dropdown-item d-flex",to:"/user/".concat(r),onClick:function(){return f(!1)},children:[Object(y.jsx)("i",{className:"material-icons text-info",children:"person"}),Object(y.jsx)("span",{className:"",children:t("My Profile")})]}),Object(y.jsxs)("span",{className:"dropdown-item d-flex",onClick:v,style:{cursor:"pointer"},children:[Object(y.jsx)("i",{className:"material-icons text-info",children:"power_settings_new"}),Object(y.jsx)("span",{className:"",children:t("Logout")})]})]})]})})}return Object(y.jsx)("div",{className:"shadow-sm bg-light mb-2",children:Object(y.jsxs)("nav",{className:"navbar navbar-light navbar-expand container",children:[Object(y.jsx)(K.b,{className:"navbar-brand d-flex",to:"/",children:Object(y.jsx)("span",{className:"m-auto p-2",children:"User&Admin"})}),g]})})}),te=function(e){var t=e.user[1],n=t.username,a=t.fullname,r=Object(L.a)().t,c=(Object(W.g)(),Object(E.c)((function(e){return{isLoggedIn:e.isLoggedIn,role:e.role,token:e.token}}))),s=(c.isLoggedIn,c.role,c.token),i=function(){var e=Object(b.a)(j.a.mark((function e(){var t;return j.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t={username:n,token:s},e.next=3,w(t);case 3:case"end":return e.stop()}}),e)})));return function(){return e.apply(this,arguments)}}(),o=function(){var e=Object(b.a)(j.a.mark((function e(){var t;return j.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t={username:n,token:s},e.next=3,k(t);case 3:case"end":return e.stop()}}),e)})));return function(){return e.apply(this,arguments)}}();return Object(y.jsxs)(K.b,{to:"/user/".concat(n),className:"list-group-item list-group-item-action",children:[Object(y.jsx)("button",{className:"btn btn-danger d-inline-flex m-1",title:r("Delete Account"),onClick:o,children:Object(y.jsx)("i",{className:"material-icons",children:"person_off"})}),Object(y.jsx)("button",{className:"btn btn-primary d-inline-flex m-1",title:r("Cancel Deletion"),onClick:i,children:Object(y.jsx)("i",{className:"material-icons",children:"cancel"})}),Object(y.jsxs)("span",{className:"pl-2 m-3",children:[a,"@",n]})]})},ne=function(e){var t=Object(a.useState)({content:[],size:3,number:0}),n=Object(i.a)(t,2),r=n[0],c=n[1],s=Object(a.useState)(!1),o=Object(i.a)(s,2),l=o[0],d=o[1],p=u("get","/api/userwiper/0/0");Object(a.useEffect)((function(){m()}),[]);Object(E.c)((function(e){return{isLoggedIn:e.isLoggedIn}})).isLoggedIn;var m=function(){var e=Object(b.a)(j.a.mark((function e(t){var n;return j.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return d(!1),e.prev=1,e.next=4,f(t,0);case 4:n=e.sent,console.log(n),c(n.data),e.next=12;break;case 9:e.prev=9,e.t0=e.catch(1),d(!0);case 12:case 13:case"end":return e.stop()}}),e,null,[[1,9]])})));return function(t){return e.apply(this,arguments)}}(),O=Object(L.a)().t,h=r.content,x=r.last,v=r.first,g=Object(y.jsxs)("div",{children:[0==v&&Object(y.jsx)("button",{className:"btn btn-sm btn-light",onClick:function(){var e=r.number-1;m(e)},children:O("Previous")}),0==x&&Object(y.jsx)("button",{className:"btn btn-sm btn-light float-right",onClick:function(){var e=r.number+1;m(e)},children:O("Next")})]});return p&&(g=Object(y.jsx)(X,{})),Object(y.jsx)("div",{className:"container",children:Object(y.jsxs)("div",{className:"card",children:[Object(y.jsx)("h3",{className:"card-header text-center",children:O("Users's Delete Requests")}),Object(y.jsx)("div",{className:"list-group-flush",children:h.map((function(e){return Object(y.jsx)(te,{user:e},e[1].username)}))}),g,l&&Object(y.jsx)("div",{className:"text-center text-danger",children:O("Load Failure")})]})})},ae=function(){var e=Object(E.c)((function(e){return{isLoggedIn:e.isLoggedIn,role:e.role}})),t=e.isLoggedIn;e.role;return Object(y.jsx)("div",{children:Object(y.jsxs)(K.a,{children:[Object(y.jsx)(ee,{}),Object(y.jsxs)(W.d,{children:[Object(y.jsx)(W.b,{exact:!0,path:"/",component:Y}),t&&Object(y.jsx)(W.b,{path:"/requests",component:ne}),!t&&Object(y.jsx)(W.b,{path:"/login",component:V}),!t&&Object(y.jsx)(W.b,{path:"/signup",component:z}),!t&&Object(y.jsx)(W.b,{path:"/admin/login",component:T}),!t&&Object(y.jsx)(W.b,{path:"/admin/signup",component:B}),Object(y.jsx)(W.b,{path:"/user/:username",component:$}),Object(y.jsx)(W.a,{to:"/"})]})]})})},re=n(31),ce=n(20);re.a.use(ce.e).init({resources:{en:{translations:{"Sign Up":"Sign Up",Login:"Login","Admin Login":"Admin Login","Password mismatch":"Password mismatch",Username:"Username",Password:"Password","Password Repeat":"Password Repeat",Logout:"Logout",Users:"Users",Next:"next > ",Previous:"< previous","Load Failure":"Load Failure","User Not Found":"User Not Found",Edit:"Edit","Change Full Name":"Change Full Name","Change E-Mail":"Change E-Mail","Change Phone":"Change Phone",Save:"Save",Cancel:"Cancel","My Profile":"My Profile","Delete My Account":"Delete My Account","Cancel Account Deletion":"Cancel Account Deletion","Are you sure to delete your account?":"Are you sure to delete your account?"}},tr:{translations:{"Sign Up":"Kay\u0131t Ol",Login:"Giri\u015f Yap","Admin Login":"Y\xf6netici Giri\u015fi","Password mismatch":"Ayn\u0131 \u015fifreyi giriniz",Username:"Kullan\u0131c\u0131 Ad\u0131","Display Name":"Tercih Edilen \u0130sim",Password:"\u015eifre","Password Repeat":"\u015eifreyi Tekrarla",Logout:"\xc7\u0131k\u0131\u015f",Users:"Kullan\u0131c\u0131lar",Next:"sonraki >",Previous:"< \xf6nceki","Load Failure":"Liste Al\u0131namad\u0131","User Not Found":"Kullan\u0131c\u0131 Bulunamad\u0131",Edit:"D\xfczenle","Change Display Name":"G\xf6r\xfcn\xfcr \u0130sminizi De\u011fi\u015ftirin",Save:"Kaydet",Cancel:"\u0130ptal","My Profile":"Profilim","Delete My Account":"Hesab\u0131m\u0131 Sil","Are you sure to delete your account?":"Hesab\u0131n\u0131z\u0131 silmek istedi\u011finize emin misiniz?"}}},fallbackLng:"en",ns:["translations"],defaultNS:"translations",keySeparator:!1,interpolation:{escapeValue:!1,formatSeparator:","},react:{wait:!0}});re.a;var se=n(27),ie=n(46),oe={isLoggedIn:!1,username:void 0,displayName:void 0,image:void 0,password:void 0},le=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:Object(m.a)({},oe),t=arguments.length>1?arguments[1]:void 0;return t.type==P?oe:t.type==A?Object(m.a)(Object(m.a)({},t.payload),{},{isLoggedIn:!0}):t.type==U?Object(m.a)(Object(m.a)({},e),t.payload):e},ue=n(47),de=new(n.n(ue).a),je=function(){var e=de.get("h-auth")||{isLoggedIn:!1,username:void 0,displayName:void 0,image:void 0,password:void 0};h(e);var t=window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__||se.b,n=Object(se.c)(le,e,t(Object(se.a)(ie.a)));return n.subscribe((function(){var e;e=n.getState(),de.set("h-auth",e),h(n.getState())})),n}();c.a.render(Object(y.jsx)(E.a,{store:je,children:Object(y.jsx)(ae,{})}),document.getElementById("root")),s()}},[[87,1,2]]]);
//# sourceMappingURL=main.4223ec7a.chunk.js.map