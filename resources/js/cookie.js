const cookie = {
    getCookie(name) {
        // Split the document.cookie string into an array of individual cookies
        let cookies = document.cookie.split('; ');

        // Iterate over the cookies to find the one with the specified name
        for (let i = 0; i < cookies.length; i++) {
            let cookie = cookies[i].split('=');
            let cookieName = decodeURIComponent(cookie[0]);

            // If the cookie name matches, return its value
            if (cookieName === name) {
                return decodeURIComponent(cookie[1]);
            }
        }
    
        // If the cookie with the specified name is not found, return null
        return null;
    },
    setCookie(name, value, hoursToExpire) {
        let expirationDate = new Date();
        expirationDate.setTime(expirationDate.getTime() + (hoursToExpire * 60 * 60 * 1000));

        let cookieValue = encodeURIComponent(name) + '=' + encodeURIComponent(value) + '; expires=' + expirationDate.toUTCString() + '; path=/';

        document.cookie = cookieValue;
    },
    unsetCookie(name) {
        document.cookie = encodeURIComponent(name) + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }
}

window.cookie = cookie;