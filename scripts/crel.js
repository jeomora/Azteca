!function(e,n){"object"==typeof exports?module.exports=n():"function"==typeof define&&define.amd?define(n):e.crel=n()}(this,function(){function e(){var o,a=arguments,p=a[0],m=a[1],x=2,v=a.length,b=e[f];if(p=e[c](p)?p:d.createElement(p),1===v)return p;if((!l(m,t)||e[u](m)||s(m))&&(--x,m=null),v-x===1&&l(a[x],"string")&&void 0!==p[r])p[r]=a[x];else for(;v>x;++x)if(o=a[x],null!=o)if(s(o))for(var g=0;g<o.length;++g)y(p,o[g]);else y(p,o);for(var h in m)if(b[h]){var N=b[h];typeof N===n?N(p,m[h]):p[i](N,m[h])}else p[i](h,m[h]);return p}var n="function",t="object",o="nodeType",r="textContent",i="setAttribute",f="attrMap",u="isNode",c="isElement",d=typeof document===t?document:{},l=function(e,n){return typeof e===n},a=typeof Node===n?function(e){return e instanceof Node}:function(e){return e&&l(e,t)&&o in e&&l(e.ownerDocument,t)},p=function(n){return e[u](n)&&1===n[o]},s=function(e){return e instanceof Array},y=function(n,t){e[u](t)||(t=d.createTextNode(t)),n.appendChild(t)};return e[f]={},e[c]=p,e[u]=a,"undefined"!=typeof Proxy&&(e.proxy=new Proxy(e,{get:function(n,t){return!(t in e)&&(e[t]=e.bind(null,t)),e[t]}})),e});
