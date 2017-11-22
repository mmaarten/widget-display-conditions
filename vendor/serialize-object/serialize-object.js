// Combination of jQuery.deparam and jQuery.serializeObject by Ben Alman.
/*!
* jQuery BBQ: Back Button & Query Library - v1.2.1 - 2/17/2010
* http://benalman.com/projects/jquery-bbq-plugin/
*
* Copyright (c) 2010 "Cowboy" Ben Alman
* Dual licensed under the MIT and GPL licenses.
* http://benalman.com/about/license/
*/
/*!
* jQuery serializeObject - v0.2 - 1/20/2010
* http://benalman.com/projects/jquery-misc-plugins/
*
* Copyright (c) 2010 "Cowboy" Ben Alman
* Dual licensed under the MIT and GPL licenses.
* http://benalman.com/about/license/
*/
!function(e){e.fn.serializeObject=function(i){var t={},n={true:!0,false:!1,null:null};return e.each(this.serializeArray(),function(l,a){var r=a.name,s=a.value,u=t,f=0,c=r.split("]["),o=c.length-1;if(/\[/.test(c[0])&&/\]$/.test(c[o])?(c[o]=c[o].replace(/\]$/,""),o=(c=c.shift().split("[").concat(c)).length-1):o=0,i&&(s=s&&!isNaN(s)?+s:"undefined"===s?void 0:void 0!==n[s]?n[s]:s),o)for(;f<=o;f++)u=u[r=""===c[f]?u.length:c[f]]=f<o?u[r]||(c[f+1]&&isNaN(c[f+1])?{}:[]):s;else e.isArray(t[r])?t[r].push(s):void 0!==t[r]?t[r]=[t[r],s]:t[r]=s}),t}}(jQuery);
