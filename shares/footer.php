<div
    class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn"
    data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-8     col-md-6">
                <h4 class="text-white mb-3">Contact</h4>
                <p class="mb-2"><i
                        class="fa fa-map-marker-alt me-3"></i>Phân khu
                    đào tạo E1, Khu Công Nghệ cao TP.HCM, Phường Hiệp
                    Phú, TP. Thủ Đức, TP.HCM</p>
                <p class="mb-2"><i
                        class="fa fa-phone-alt me-3"></i>+(028) 7105
                    6686</p>
                <p class="mb-2"><i
                        class="fa fa-envelope me-3"></i>daotao@hutech.edu.vn</p>
                <div class="d-flex pt-2">
                    <a class="btn btn-outline-light btn-social"
                        href="#"><i
                            class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-social"
                        href="#"><i
                            class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social"
                        href="#"><i
                            class="fab fa-youtube"></i></a>
                    <a class="btn btn-outline-light btn-social"
                        href="#"><i
                            class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h4 class="text-white mb-3">Company</h4>
                <a class="btn btn-link" href="about.html">About Us</a>
                <a class="btn btn-link" href="contact.html">Contact
                    Us</a>
                <a class="btn btn-link" href="#">Privacy Policy</a>
                <a class="btn btn-link" href="#">Terms & Condition</a>
                <a class="btn btn-link" href="#">FAQs & Help</a>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script>
    // Set the active class on the current page in the navbar

    function alert(type, msg, position = 'body') {
        let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
        let element = document.createElement('div');
        element.innerHTML = `
            <div class="alert ${bs_class} alert-dismissible fade show custom-alert" role="alert">
                <strong class="me-3">${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            `;
        if (position == 'body') {
            document.body.append(element);
        } else {
            document.getElementById(position).appendChild(element);
        }
        setTimeout(remAlert, 2000);
    }

    function remAlert() {
        document.getElementsByClassName('custom-alert')[0].remove();
    }

    function setActive() {
        let navbar = document.getElementById('dashboard-menu');
        let a_tags = navbar.getElementsByTagName('a');

        for (i = 0; i < a_tags.length; i++) {
            let file = a_tags[i].href.split('/').pop();
            let file_name = file.split('.')[0];

            if (document.location.href.indexOf(file) != -1) {
                a_tags[i].classList.add('active');
            }
        }
    }

    let register_form = document.getElementById('registerform');

    register_form.addEventListener('submit', function(e) {
        e.preventDefault();
        let data = new FormData();

        data.append('name', register_form.elements['name'].value);
        data.append('email', register_form.elements['email'].value);
        data.append('phonenum', register_form.elements['phonenum'].value);
        data.append('address', register_form.elements['address'].value);
        data.append('pincode', register_form.elements['pincode'].value);
        data.append('dob', register_form.elements['dob'].value);
        data.append('pass', register_form.elements['pass'].value);
        data.append('cpass', register_form.elements['cpass'].value);
        data.append('register', '');

        var Model = document.getElementById('registerModal');
        var modal = bootstrap.Modal.getInstance(Model);
        modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open('POST', "ajax/login_regester.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (this.responseText == 'pass_mismatch') {
                alert('error', 'Password Mismatch!');
            } else if (this.responseText == 'email_already') {
                alert('error', 'Email Already registered');
            } else if (this.responseText == 'phonenum_already') {
                alert('error', 'Phone Number Already Exists');
            } else if (this.responseText == 'mail_failed') {
                alert('error', 'Email Sending Failed');
            } else if (this.responseText == 1) {
                alert('success', 'Registration Successful. Confirmation link sent to email!');
                register_form.reset();
            }
        }
        console.log(data);
        xhr.send(data);
    });

    let login_form = document.getElementById('login_form');

    login_form.addEventListener('submit', function(e) {
        e.preventDefault();
        let data = new FormData();

        data.append('email_mob', login_form.elements['email_mob'].value);
        data.append('pass', login_form.elements['pass'].value);
        data.append('login', '');


        var Model = document.getElementById('loginModal');
        var modal = bootstrap.Modal.getInstance(Model);
        modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open('POST', "ajax/login_regester.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (this.responseText == 'inv_email_mob') {
                alert('error', 'Invalid Email or Mobile Number!');
            } else if (this.responseText == 'not_verified') {
                alert('error', 'Email is not verified');
            } else if (this.responseText == 'inactive') {
                alert('error', 'Account Suspended! Please contact admin');
            } else if (this.responseText == 'invalid_pass') {
                alert('error', 'Incorrect Password');
            } else {
                window.location = window.location.pathname;
            }
        }

        xhr.send(data);
    });

    let forgot_form = document.getElementById('forgot_form');

    forgot_form.addEventListener('submit', function(e) {
        e.preventDefault();
        let data = new FormData();

        data.append('email', forgot_form.elements['email'].value);
        data.append('forgot_pass', '');


        var Model = document.getElementById('forgotModal');
        var modal = bootstrap.Modal.getInstance(Model);
        modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open('POST', "ajax/login_regester.php", true);

        xhr.onload = function() {
            if (this.responseText == 'inv_email') {
                alert('error', 'Invalid Email !');
            } else if (this.responseText == 'not_verified') {
                alert('error', 'Email is not verified! Please contact Admin');
            } else if (this.responseText == 'inactive') {
                alert('error', 'Account Suspended! Please contact admin');
            } else if (this.responseText == 'mail_failed') {
                alert('error', 'Cannot send email. Sever down!');
            } else if (this.responseText == 'mail_failed') {
                alert('error', 'Account recover failed. Sever down!');
            } else {
                alert('success', 'Reset link sent to email!');
                forgot_form.reset();
            }
        }

        xhr.send(data);
    });




    setActive();
</script>