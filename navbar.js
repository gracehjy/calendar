
document.addEventListener('DOMContentLoaded', function() {
    // add event listener for the add event link
    document.getElementById("addeventLink").addEventListener("click", function(event) {
        event.preventDefault();

        // SOURCE: https://stackoverflow.com/questions/36631762/returning-html-with-fetch
        fetch("addevent.html")
            .then(response => response.text())
            .then(html => {
                document.body.innerHTML = html;
            })
            .catch(error => console.error("Error fetching addevent.html:", error));
    });

    // add event listener for the profile link
    document.getElementById("profileLink").addEventListener("click", function(event) {
        event.preventDefault();

        fetch("profile.php")
            .then(response => response.text())
            .then(data => {
                document.body.innerHTML = data;
            })
            .catch(error => console.error("Error fetching profile.php:", error));
    });

    // add event listener for the logout link
    document.getElementById('logout').addEventListener('click', function(event) {
        event.preventDefault();

        fetch('logout.php')
            .then(response => response.json())
            .then(data => {
                sessionStorage.setItem('loggedIn', data.loggedIn);
                window.location.href = "login.html";
            })
            .catch(error => console.error('Error logging out:', error));
    });

    // add event listener for loginLink
    document.getElementById("loginLink").addEventListener("click", function(event) {
        event.preventDefault();

        alert("clicked");
        // SOURCE: https://stackoverflow.com/questions/36631762/returning-html-with-fetch
        fetch("login.html")
            .then(response => response.text())
            .then(html => {
                document.body.innerHTML = html;
            })
            .catch(error => console.error("Error fetching login.html:", error));
    });

    // add event listener for signupLink
    document.getElementById("signupLink").addEventListener("click", function(event) {
        event.preventDefault();

        // SOURCE: https://stackoverflow.com/questions/36631762/returning-html-with-fetch
        fetch("signup.html")
            .then(response => response.text())
            .then(html => {
                document.body.innerHTML = html;
            })
            .catch(error => console.error("Error fetching signup.html:", error));
    });
});