    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
        function alert(type, msg, position = 'body'){
            let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
            let element = document.createElement('div');
            element.innerHTML = `
                <div class="alert ${bs_class} alert-dismissible fade show custom-alert" role="alert">
                    <strong class="me-3">${msg}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            if(position == 'body'){
                document.body.append(element);
            } else {
                document.getElementById(position).appendChild(element);
            }

            document.body.append(element);
            setTimeout(remAlert, 2000);
        }
        function remAlert(){
            document.getElementsByClassName('alert')[0].remove();
            setTimeout(remAlert, 3000);
        }

        function remAlert() {
            document.getElementsByClassName('alert')[0].remove();
        }

        function setActive() {
            let navbar = document.getElementById('dashboard-menu');
            let a_tags = navbar.getElementsByTagName('a');

            for(i=0; i<a_tags.length;i++){
                let file = a_tags[i].href.split('/').pop();
                let file_name = file.split('.')[0];

                if(document.location.href.indexOf(file_name) >= 0){
                    a_tags[i].classList.add('active');
                }
            }
        }
        setActive();

    </script>

    