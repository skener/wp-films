((page) => {
        page.onload = async () => {
            try {
                var urlFilms = 'http://localhost/wordpress/wp-json/wp/v2/film';
                const http = new XMLHttpRequest();
                http.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        let result = JSON.parse(http.responseText);
                        result.forEach((film) => {
                            console.log(film.title.rendered);
                        })
                    }
                }
                http.open("GET", urlFilms, true);
                http.send();
            } catch (e) {
                console.log(e);
            }
        }
    }
)(window);